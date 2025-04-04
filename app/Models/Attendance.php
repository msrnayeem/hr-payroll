<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'attendance_date',
        'employee_id',
        'shift_start',
        'late_entry_consider',
        'entry_time',
        'is_late',
        'shift_end',
        'early_out_consider',
        'exit_time',
        'is_early',
        'is_manual',
        'manual_by',
    ];

    /**
     * Get the employee for this attendance.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Get the user who manually added or edited this attendance.
     */
    public function manualBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manual_by');
    }
}
