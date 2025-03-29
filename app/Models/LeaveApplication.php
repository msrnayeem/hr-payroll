<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    protected $fillable = ['user_id', 'leave_category_id', 'from_date', 'to_date', 'total_days', 'reason', 'status', 'supporting_document'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveCategory()
    {
        return $this->belongsTo(LeaveCategory::class);
    }
}
