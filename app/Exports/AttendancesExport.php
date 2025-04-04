<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendancesExport implements FromCollection, WithHeadings
{
    protected $attendances;

    public function __construct(Collection $attendances)
    {
        $this->attendances = $attendances;
    }

    public function collection()
    {
        return $this->attendances->map(function ($a) {
            return [
                'ID' => $a->id,
                'Employee Name' => $a->employee->name ?? 'N/A',
                'Date' => $a->attendance_date,
                'Shift Start' => $a->shift_start,
                'Shift End' => $a->shift_end,
                'Entry Time' => $a->entry_time,
                'Is Late' => $a->is_late ? 'Yes' : 'No',
                'Exit Time' => $a->exit_time,
                'Is Early' => $a->is_early ? 'Yes' : 'No',
                'Manual Entry' => $a->is_manual ? 'Yes' : 'No',
                'Edited By' => $a->manualBy->name ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Employee Name',
            'Date',
            'Shift Start',
            'Shift End',
            'Entry Time',
            'Is Late',
            'Exit Time',
            'Is Early',
            'Manual Entry',
            'Edited By',
        ];
    }
}
