<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    protected $fillable = ['user_id', 'leave_category_id', 'from_date', 'to_date', 'total_days', 'reason', 'status', 'supporting_document', 'decision_maker_id'];

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leaveCategory()
    {
        return $this->belongsTo(LeaveCategory::class);
    }

    public function decisionMaker()
    {
        return $this->belongsTo(User::class, 'decision_maker_id');
    }
}
