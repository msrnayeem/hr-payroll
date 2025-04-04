<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

use App\Exports\AttendancesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
