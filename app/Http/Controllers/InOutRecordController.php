<?php

namespace App\Http\Controllers;

use App\Models\InOutRecord;
use Illuminate\Http\Request;
use App\Exports\InOutRecordsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class InOutRecordController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all InOutRecords
        $query = InOutRecord::query();

        // Filter by date (optional, if you want to filter records based on date)
        if ($request->filled('date')) {
            $date = $request->date;
            $query->whereDate('timestamp', $date);
        }

        // Fetch InOutRecords
        $inOutRecords = $query->get();

        // Export logic (same as in the attendance index method)
        if ($request->has('export')) {
            $export = true;

            if ($request->export === 'pdf') {
                $pdf = PDF::loadView('in_out_records.table', compact('inOutRecords', 'export'));
                return $request->has('download') ? $pdf->download('inout-records-list.pdf') : $pdf->stream('inout-records-list.pdf');
            }

            if ($request->export === 'excel') {
                return Excel::download(new InOutRecordsExport($inOutRecords), 'inout-records-list.xlsx');
            }
        }

        // Return the view with InOutRecords
        return view('in_out_records.index', [
            'inOutRecords' => $inOutRecords,
        ]);
    }
}
