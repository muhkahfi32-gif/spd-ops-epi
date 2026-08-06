<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SuratDinas extends Model
{
    use HasFactory;

    protected $table = 'surat_dinas';

    protected $fillable = [
        'nomor_surat', 'tanggal_surat', 'perihal',
        'travel_id', 'employee_id', 'tujuan',
        'tanggal_berangkat', 'tanggal_kembali',
        'status', 'keterangan',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_berangkat' => 'date',
        'tanggal_kembali' => 'date',
    ];

    // ─── Relationships ─────────────────────────────────────

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_surat_dinas', 'surat_dinas_id', 'employee_id')
                    ->withTimestamps();
    }

    // Single employee fallback (for legacy single-employee data)
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }

    public function travels()
    {
        return $this->hasMany(Travel::class, 'surat_dinas_id');
    }

    // ─── Accessors ──────────────────────────────────────────

    public function getDurasiAttribute()
    {
        if (!$this->tanggal_berangkat || !$this->tanggal_kembali) {
            return 0;
        }
        return $this->tanggal_berangkat->diffInDays($this->tanggal_kembali) + 1;
    }

    public function getTotalAmountSpdAttribute()
    {
        if ($this->travels->count() > 0) {
            return $this->travels->sum(function ($t) {
                return $t->total_amount;
            });
        }
        return $this->travel ? $this->travel->total_amount : 0;
    }

    public function getEmployeeNamesAttribute()
    {
        if ($this->employees->count() > 0) {
            return $this->employees->pluck('name')->implode(', ');
        }
        
        // Try getting names from travels if relation empty
        if ($this->travels->count() > 0) {
            $names = [];
            foreach ($this->travels as $t) {
                foreach ($t->employees as $e) {
                    $names[$e->id] = $e->name;
                }
            }
            if (!empty($names)) {
                return implode(', ', array_values($names));
            }
        }

        return $this->employee->name ?? '-';
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'draft'  => '<span class="badge-draft"><i class="ri-draft-line"></i> Draft</span>',
            'aktif'  => '<span class="badge-aktif"><i class="ri-checkbox-circle-line"></i> Aktif</span>',
            'selesai' => '<span class="badge-selesai"><i class="ri-check-double-line"></i> Selesai</span>',
            default  => '<span class="badge-draft">-</span>',
        };
    }

    // ─── Scopes ─────────────────────────────────────────────

    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeByYear($query, $year)
    {
        if ($year) {
            return $query->whereYear('tanggal_surat', $year);
        }
        return $query;
    }

    public function scopeByMonth($query, $month)
    {
        if ($month) {
            return $query->whereMonth('tanggal_surat', $month);
        }
        return $query;
    }
}
