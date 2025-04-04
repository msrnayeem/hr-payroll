<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InOutRecord extends Model
{
    protected $fillable = [
        'sn',
        'table',
        'stamp',
        'zk_device_id',
        'timestamp',
        'status1',
        'status2',
        'status3',
        'status4',
        'status5',
    ];
}
