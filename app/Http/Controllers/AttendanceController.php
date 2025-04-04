<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

use App\Exports\AttendancesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\AttendanceRequest;
use App\Models\Shift;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::query();

        // Filter by employee_id
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by date
        if ($request->filled('date')) {
            $date = $request->date;
            $query->whereDate('attendance_date', $date);
        }

        // Fetch attendances
        $attendances = $query->get();

        // Fetch all employees for the filter dropdown
        $employees = User::get();

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

        // Return the view with attendances and employee data
        return view('attendances.index', compact('attendances', 'employees'));
    }


    public function attendanceRequest()
    {

        if (auth()->user()->can('attendance_request_decision')) {
            $attendanceRequests = AttendanceRequest::with(['employee', 'decidedBy'])->get();
            $users = User::all();
        } else {
            $attendanceRequests = AttendanceRequest::with(['employee', 'decidedBy'])->where('employee_id', auth()->user()->id)->get();
            $users = User::where('id', auth()->user()->id)->get();
        }

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

                // Use the relationship to access shift directly
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
                $shiftEnd = Carbon::parse($shift->end_time);

                // Grace periods
                $lateMinutesAllowed = Carbon::parse($shift->late_time)->hour * 60 + Carbon::parse($shift->late_time)->minute;
                $earlyMinutesAllowed = Carbon::parse($shift->early_time)->hour * 60 + Carbon::parse($shift->early_time)->minute;

                $isLate = $entry->gt($shiftStart->copy()->addMinutes($lateMinutesAllowed));
                $isEarly = $exit->lt($shiftEnd->copy()->subMinutes($earlyMinutesAllowed));

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
