<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        
        // Ambil seluruh data perjalanan 1 tahun sekaligus (1 single query dengan eager loading)
        $allTravels = Travel::with('employees')
            ->whereYear('start_date', $year)
            ->get();
        
        // Rekapan per Bulan
        $monthlyReports = [];
        for ($i = 1; $i <= 12; $i++) {
            $travels = $allTravels->filter(fn($t) => $t->start_date && (int)$t->start_date->format('m') === $i)->values();
            
            // Count unique employees from travels (many-to-many)
            $uniqueEmployeeIds = collect();
            foreach ($travels as $travel) {
                foreach ($travel->employees as $employee) {
                    $uniqueEmployeeIds->push($employee->id);
                }
            }
            
            $monthlyReports[] = [
                'period' => Carbon::create()->month($i)->format('F') . ' ' . $year,
                'month_num' => $i,
                'total_trips' => $travels->count(),
                'total_employees' => $uniqueEmployeeIds->unique()->count(),
                'total_days' => $travels->sum(fn($t) => $t->duration),
                'total_nominal' => $travels->sum(fn($t) => $t->total_amount),
                'travels' => $travels,
            ];
        }
        
        // Statistik Keseluruhan
        $allUniqueEmployeeIds = collect();
        foreach ($allTravels as $travel) {
            foreach ($travel->employees as $employee) {
                $allUniqueEmployeeIds->push($employee->id);
            }
        }
        
        $statistics = [
            'total_trips' => $allTravels->count(),
            'total_nominal' => $allTravels->sum(fn($t) => $t->total_amount),
            'total_months' => $allTravels->groupBy(function($t) {
                return $t->start_date->format('Y-m');
            })->count(),
            'total_employees' => $allUniqueEmployeeIds->unique()->count(),
            'total_days' => $allTravels->sum(fn($t) => $t->duration),
        ];
        
        $years = Travel::select(DB::raw('DISTINCT YEAR(start_date) as year'))->orderBy('year', 'desc')->pluck('year');
        
        return view('reports.index', compact('monthlyReports', 'statistics', 'year', 'years'));
    }
    
    public function monthly(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        
        $travels = Travel::with('employees')
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->get();
        
        // Format data for response
        $formattedTravels = $travels->map(function($travel) {
            return [
                'id' => $travel->id,
                'employee_names' => $travel->employees->pluck('name')->implode(', '),
                'destination' => $travel->destination,
                'start_date' => $travel->start_date->format('d M Y'),
                'end_date' => $travel->end_date->format('d M Y'),
                'duration' => $travel->duration,
                'amount' => $travel->total_amount,
                'formatted_amount' => 'Rp ' . number_format($travel->total_amount, 0, ',', '.'),
                'status' => $travel->status,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $formattedTravels,
            'summary' => [
                'total_trips' => $travels->count(),
                'total_nominal' => $travels->sum(fn($t) => $t->total_amount),
                'total_days' => $travels->sum(fn($t) => $t->duration),
            ]
        ]);
    }
}