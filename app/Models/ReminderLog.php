<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'travel_id', 'employee_id', 'phone', 'message',
        'status', 'response_data', 'sent_at',
    ];

    protected $casts = [
        'response_data' => 'array',
        'sent_at' => 'datetime',
    ];

    // ─── Relationships ─────────────────────────────────────

    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // ─── Scopes ─────────────────────────────────────────────

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('sent_at', today());
    }
}
