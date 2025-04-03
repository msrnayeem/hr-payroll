<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryCard extends Model
{
    protected $fillable = ['basic_salary', 'net_salary', 'total_deductions', 'total_earnings', 'employee_id', 'created_by', 'updated_by'];


    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function components()
    {
        return $this->belongsToMany(SalaryComponent::class, 'salary_card_components')
            ->withPivot('amount', 'calculation_type', 'original_value')
            ->withTimestamps();
    }

    public function calculateTotals()
    {
        $earnings = $this->components->where('type', 'earning')->sum('pivot.amount');
        $deductions = $this->components->where('type', 'deduction')->sum('pivot.amount');
        $this->total_earnings = $earnings;
        $this->total_deductions = $deductions;
        $this->net_salary = $this->basic_salary + $earnings - $deductions;
        $this->save();
    }

    public function histories()
    {
        return $this->hasMany(SalaryCardHistory::class);
    }
}
