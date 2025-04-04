<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;

class EmployeeSelect extends Component
{
    public $allEmployee;

    public function __construct(bool $allEmployee = false)
    {
        $this->allEmployee = $allEmployee;
    }

    public function render()
    {
        if ($this->allEmployee) {
            $employees = User::all();
        } else {
            $employees = User::where('status', true)->get();
        }

        return view('components.employee-select', [
            'employees' => $employees,
        ]);
    }
}
