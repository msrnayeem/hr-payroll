<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryCardHistory extends Model
{
    public $timestamps = false;

    protected $fillable = ['salary_card_id', 'employee_id', 'action', 'old_values', 'new_values', 'changed_at', 'changed_by'];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_at' => 'datetime',
    ];

    public function salaryCard()
    {
        return $this->belongsTo(SalaryCard::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
