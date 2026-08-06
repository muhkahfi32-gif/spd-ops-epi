<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use App\Models\Employee;
use App\Models\SuratDinas;
use App\Models\ReminderLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year');
        $month = $request->get('month');
        
        $displayYear = $year ? (int)$year : (int)date('Y');
        $displayMonth = $month ? (int)$month : null;
        
        // ========================================
        // DATA DENGAN FILTER PERIODE (Tahun & Bulan)
        // ========================================
        $query = Travel::with('employees');
        if ($year) {
            $query->whereYear('start_date', $year);
        }
        if ($month) {
            $query->whereMonth('start_date', $month);
        }
        $filteredTravels = $query->get();
        
        // ========================================
        // SEMUA DATA UNTUK PERBANDINGAN TAHUNAN
        // ========================================
        $allTravels = Travel::with('employees')->get();
        
        // ========================================
        // STATISTIK UTAMA (Synced with Filter)
        // ========================================
        $totalTrips = $allTravels->count();
        $filteredCount = $filteredTravels->count();
        $totalEmployees = Employee::where('is_active', true)->count();
        $filteredNominal = $filteredTravels->sum(fn($t) => $t->total_amount);
        $completionRate = $totalTrips > 0 ? round(($filteredCount / $totalTrips) * 100) : 0;
        
        // Overdue count
        $overdueCount = Travel::pendingPaymentOverdue(7)->count();
        
        // ========================================
        // GRAFIK 1: DATA BULANAN (12 Bulan untuk Display Year)
        // ========================================
        $displayYearTravels = Travel::with('employees')
            ->whereYear('start_date', $displayYear)
            ->get();
        
        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyNominals = [];
        $monthlyTripCounts = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthTravels = $displayYearTravels->filter(fn($t) => $t->start_date && (int)$t->start_date->format('m') === $i);
            $monthlyNominals[] = (float) $monthTravels->sum(fn($t) => $t->total_amount);
            $monthlyTripCounts[] = $monthTravels->count();
        }
        
        // ========================================
        // GRAFIK 1 (EXTENDED): DATA HARIAN (Jika Bulan Dipilih)
        // ========================================
        $dailyLabels = [];
        $dailyNominals = [];
        $dailyTripCounts = [];
        $selectedMonthName = null;

        if ($displayMonth) {
            $carbonMonth = Carbon::createFromDate($displayYear, $displayMonth, 1);
            $daysInMonth = $carbonMonth->daysInMonth;
            $monthNamesFull = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $selectedMonthName = $monthNamesFull[$displayMonth - 1] ?? 'Bulan ' . $displayMonth;

            $monthTravels = $displayYearTravels->filter(fn($t) => $t->start_date && (int)$t->start_date->format('m') === $displayMonth);

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dailyLabels[] = 'Tgl ' . $d;
                $dayTravels = $monthTravels->filter(fn($t) => (int)$t->start_date->format('d') === $d);
                $dailyNominals[] = (float) $dayTravels->sum(fn($t) => $t->total_amount);
                $dailyTripCounts[] = $dayTravels->count();
            }
        }

        // ========================================
        // GRAFIK 1 (EXTENDED): DATA TAHUNAN (Perbandingan Multi-Tahun)
        // ========================================
        $yearlyLabels = [2024, 2025, 2026];
        $yearlyNominals = [];
        $yearlyTripCounts = [];

        foreach ($yearlyLabels as $y) {
            $yTravels = $allTravels->filter(fn($t) => $t->start_date && (int)$t->start_date->format('Y') === $y);
            $yearlyNominals[] = (float) $yTravels->sum(fn($t) => $t->total_amount);
            $yearlyTripCounts[] = $yTravels->count();
        }
        
        // ========================================
        // GRAFIK 2: STATUS PIE CHART (Mengikuti Filter Tahun & Bulan)
        // ========================================
        $statusPaid = $filteredTravels->where('status', 'paid')->count();
        $statusPending = $filteredTravels->where('status', '!=', 'paid')->count();
        $statusOverdue = $filteredTravels->filter(fn($t) => $t->status !== 'paid' && $t->end_date && Carbon::parse($t->end_date)->diffInDays(Carbon::today(), false) >= 7)->count();
        
        // ========================================
        // QUICK SUMMARY: Surat Dinas & Reminder
        // ========================================
        $recentSurat = SuratDinas::with('employee')
            ->orderByDesc('tanggal_surat')
            ->limit(5)
            ->get();
        $totalSurat = SuratDinas::count();
        $reminderSentToday = ReminderLog::today()->sent()->count();
        
        return view('dashboard.index', compact(
            'totalTrips', 'filteredCount', 'totalEmployees', 'filteredNominal',
            'completionRate', 'overdueCount',
            'monthLabels', 'monthlyNominals', 'monthlyTripCounts',
            'dailyLabels', 'dailyNominals', 'dailyTripCounts', 'selectedMonthName',
            'yearlyLabels', 'yearlyNominals', 'yearlyTripCounts',
            'statusPaid', 'statusPending', 'statusOverdue',
            'recentSurat', 'totalSurat', 'reminderSentToday',
            'year', 'month', 'displayYear', 'displayMonth'
        ));
    }
}