<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    protected $fillable = ['name', 'type'];

    public function salaryCards()
    {
        return $this->belongsToMany(SalaryCard::class, 'salary_card_components')
            ->withPivot('amount')
            ->withTimestamps();
    }
}
