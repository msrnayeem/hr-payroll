<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

use App\Exports\AttendancesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\AttendanceRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        Log::info($request->all());

        // Query for attendances, eager loading employee and manualBy relationships
        $query = Attendance::with('employee', 'manualBy');

        // Filter by employee_id if provided
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by date range if provided
        if ($request->filled('date')) {
            $dateRange = $request->date;
            // Split the date range into start and end dates
            $dates = explode(' - ', $dateRange);

            if (count($dates) === 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay(); // Ensure it's the start of the day
                $endDate = Carbon::parse($dates[1])->endOfDay(); // Ensure it's the end of the day

                $query->whereBetween('attendance_date', [$startDate, $endDate]);
            }
        }

        // Fetch attendances
        $attendances = $query->get();

        // Export logic (unchanged)
        if ($request->has('export')) {
            $export = true;

            if ($request->export === 'pdf') {
                $pdf = PDF::loadView('attendances.table', compact('attendances', 'export'));
                return $request->has('download') ? $pdf->download('attendance-list.pdf') : $pdf->stream('attendance-list.pdf');
            }

            if ($request->export === 'excel') {
                return Excel::download(new AttendancesExport($attendances), 'attendance-list.xlsx');
            }
        }

        // Return the view with only attendances data
        return view('attendances.index', compact('attendances'));
    }


    public function attendanceRequest(Request $request)
    {
        // Check for user permission
        if (auth()->user()->can('attendance_request_decision')) {
            $attendanceRequests = AttendanceRequest::with(['employee', 'decidedBy']);
            $users = User::all();
        } else {
            $attendanceRequests = AttendanceRequest::with(['employee', 'decidedBy'])
                ->where('employee_id', auth()->user()->id);
            $users = User::where('id', auth()->user()->id)->get();
        }

        // Apply filters
        if ($request->filled('employee_id')) {
            $attendanceRequests->where('employee_id', $request->employee_id);
        }

        if ($request->filled('date')) {
            $dateRange = $request->date;
            $dates = explode(' - ', $dateRange);

            if (count($dates) === 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate = Carbon::parse($dates[1])->endOfDay();

                $attendanceRequests->whereBetween('attendance_date', [$startDate, $endDate]);
            }
        }

        // Fetch attendance requests
        $attendanceRequests = $attendanceRequests->get();

        // Return the view with attendanceRequests and users data
        return view('attendances.requests', compact('attendanceRequests', 'users'));
    }


    public function attendanceRequestStore(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'attendance_date' => 'required|date',
            'entry_time' => 'required|date_format:H:i',
            'exit_time' => 'required|date_format:H:i|after:entry_time',
            'reason' => 'nullable|string|max:1000',
        ]);

        $attendanceRequest = AttendanceRequest::create([
            'employee_id' => $validated['employee_id'],
            'attendance_date' => $validated['attendance_date'],
            'entry_time' => $validated['entry_time'],
            'exit_time' => $validated['exit_time'],
            'reason' => $validated['reason'],
            'status' => 'pending',
            'decided_by' => null, // Will be set when approved/rejected
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance request created successfully',
            'data' => $attendanceRequest
        ], 201);
    }

    public function attendanceRequestEdit(int $id)
    {
        $attendanceRequest = AttendanceRequest::find($id);

        if ($attendanceRequest->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot edit a non-pending attendance request'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'attendance_date' => $attendanceRequest->attendance_date,
                'entry_time' => $attendanceRequest->entry_time,
                'exit_time' => $attendanceRequest->exit_time,
                'reason' => $attendanceRequest->reason,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function attendanceRequestUpdate(Request $request, int $id)
    {
        $attendanceRequest = AttendanceRequest::findOrFail($id);

        if ($request->has('status')) {

            $attendanceRequest->update([
                'status' => $request->status,
                'decided_by' => auth()->user()->id,
            ]);

            if ($request->status === 'approved') {
                $employee = $attendanceRequest->employee;

                $shift = $employee->shift;

                if (!$shift) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Shift not assigned to employee',
                    ], 400);
                }

                $entry = Carbon::parse($attendanceRequest->entry_time);
                $exit = Carbon::parse($attendanceRequest->exit_time);
                $shiftStart = Carbon::parse($shift->start_time);
                $lateTime = Carbon::parse($shift->late_time);
                $shiftEnd = Carbon::parse($shift->end_time);
                $earlyTime = Carbon::parse($shift->early_time);

                $isLate = $lateTime->greaterThan($shiftStart);
                $isEarly = $earlyTime->lessThan($shiftEnd);

                Attendance::create([
                    'attendance_date'  => $attendanceRequest->attendance_date,
                    'employee_id'      => $attendanceRequest->employee_id,
                    'shift_start'      => $shift->start_time,
                    'entry_time'       => $attendanceRequest->entry_time,
                    'is_late'          => $isLate,
                    'shift_end'        => $shift->end_time,
                    'exit_time'        => $attendanceRequest->exit_time,
                    'is_early'         => $isEarly,
                    'is_manual'        => true,
                    'manual_by'        => auth()->user()->id,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Attendance request {$request->status} successfully"
            ]);
        } else {
            // This is an edit request
            if ($attendanceRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot edit a non-pending attendance request'
                ], 403);
            }

            $attendanceRequest->update([
                'attendance_date' => $request->attendance_date,
                'entry_time' => $request->entry_time,
                'exit_time' => $request->exit_time,
                'reason' => $request->reason,
            ]);

            return response()->json([
                'success' => true,
                'message' => $attendanceRequest,
            ]);
        }
    }
}
