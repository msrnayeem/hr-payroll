<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'attendance_date',
        'employee_id',
        'shift_start',
        'entry_time',
        'is_late',
        'shift_end',
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

    public function getShiftTimeAttribute()
    {
        return Carbon::parse($this->shift_start)->format('g:i A') . ' - ' .
            Carbon::parse($this->shift_end)->format('g:i A');
    }

}
