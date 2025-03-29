<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveCategory extends Model
{
    protected $fillable = ['name', 'max_days', 'requires_approval'];

    //cast requires_approval to boolean
    protected $casts = [
        'requires_approval' => 'boolean',
    ];
}
