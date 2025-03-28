<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryCard extends Model
{
    protected $fillable = [
        'basic_salary',
        'net_salary',
        'total_deduction',
        'total_earn',
    ];
}
