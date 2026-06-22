<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_balance_id', 'leave_request_id', 'type', 'amount', 'description'
    ];

    public function balance(): BelongsTo
    {
        return $this->belongsTo(LeaveBalance::class, 'leave_balance_id');
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(LeaveRequest::class, 'leave_request_id');
    }
}
