<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Travel extends Model
{
    use HasFactory;

    protected $table = 'travels';

    protected $fillable = [
        'surat_dinas_id', 'start_date', 'end_date', 'payment_date', 
        'amount', 'destination', 'description', 'status', 'is_accumulated', 'aplikasi', 'last_reminded_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'is_accumulated' => 'boolean',
        'last_reminded_at' => 'datetime',
    ];

    public function suratDinas()
    {
        return $this->belongsTo(SuratDinas::class, 'surat_dinas_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_travel', 'travel_id', 'employee_id')
                    ->withTimestamps();
    }

    public function getDurationAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
    
    // Get total amount with multiplier (jumlah pegawai)
    public function getTotalAmountAttribute()
    {
        $employeeCount = $this->employees->count();
        if ($employeeCount === 0) {
            return (float) $this->amount;
        }
        return $this->amount * $employeeCount;
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getEmployeeNamesAttribute()
    {
        return $this->employees->pluck('name')->implode(', ');
    }
    
    // Get aplikasi (prioritize travel's own aplikasi field)
    public function getAplikasiListAttribute()
    {
        if (!empty($this->aplikasi)) {
            return [$this->aplikasi];
        }

        $aplikasiList = [];
        foreach ($this->employees as $employee) {
            if ($employee->aplikasi) {
                $apps = explode(',', $employee->aplikasi);
                foreach ($apps as $app) {
                    $aplikasiList[] = trim($app);
                }
            }
        }
        return array_unique($aplikasiList);
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->status == 'paid' && $this->is_accumulated) {
            return '<span class="badge-paid"><i class="ri-checkbox-circle-line"></i> Paid (Accumulated)</span>';
        } elseif ($this->status == 'paid' && !$this->is_accumulated) {
            return '<span class="badge-paid"><i class="ri-time-line"></i> Paid (Pending Acc)</span>';
        } else {
            return '<span class="badge-pending"><i class="ri-time-line"></i> Pending</span>';
        }
    }

    /**
     * Scope query to find travels that have ended >= $days days ago and remain unpaid.
     */
    public function scopePendingPaymentOverdue($query, int $days = 7)
    {
        $targetDate = Carbon::today()->subDays($days);

        return $query->where('status', '!=', 'paid')
            ->whereDate('end_date', '<=', $targetDate)
            ->where(function ($q) {
                $q->whereNull('last_reminded_at')
                  ->orWhereDate('last_reminded_at', '<', Carbon::today());
            });
    }
}