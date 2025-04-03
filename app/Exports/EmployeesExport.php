<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::with('shift')->get()->map(function ($employee) {
            return [
                'ID' => $employee->id,
                'Name' => $employee->name,
                'Email' => $employee->email,
                'Shift' => $employee->shift ? $employee->shift->name : 'No Shift',
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Shift'];
    }
}
