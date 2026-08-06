<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'nip', 'position', 'aplikasi', 'email', 'email_korporat', 'phone', 'is_active'
    ];

    public function travels()
    {
        return $this->belongsToMany(Travel::class, 'employee_travel', 'employee_id', 'travel_id')
                    ->withTimestamps();
    }

    public function getTotalTravelsAttribute()
    {
        return $this->travels()->count();
    }

    public function getTotalAmountAttribute()
    {
        return $this->travels()->sum('amount');
    }

    public function getTotalDaysAttribute()
    {
        return $this->travels->sum(function($travel) {
            return $travel->duration;
        });
    }
    
    public function getAplikasiListAttribute()
    {
        return explode(',', $this->aplikasi);
    }
    
    // Scope untuk filter berdasarkan aplikasi
    public function scopeByAplikasi($query, $aplikasi)
    {
        if ($aplikasi) {
            return $query->where('aplikasi', 'LIKE', '%' . $aplikasi . '%');
        }
        return $query;
    }
}