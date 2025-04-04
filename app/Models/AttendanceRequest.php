<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRequest extends Model
{

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'status',
        'reason',
        'entry_time',
        'exit_time',
        'decided_by',
    ];

    /**
     * Get the employee that owns the request.
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Get the HR user (decided by) who approved or rejected the request.
     */
    public function decidedBy()
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    /**
     * HR can approve or reject attendance requests.
     */
    public function approve($userId)
    {
        $this->update([
            'status' => 'approved',
            'decided_by' => $userId,
        ]);
    }

    public function reject($userId)
    {
        $this->update([
            'status' => 'rejected',
            'decided_by' => $userId,
        ]);
    }

    /**
     * Scope to get all pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
