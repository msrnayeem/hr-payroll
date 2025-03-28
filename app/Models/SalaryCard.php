<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryCard extends Model
{
    protected $fillable = ['basic_salary', 'net_salary', 'total_deductions', 'total_earnings'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'salary_card_id');
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
}
