<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Display the FullCalendar view.
     */
    public function index()
    {
        return view('calendar.index');
    }

    /**
     * Get travels formatted for FullCalendar events (JSON API).
     */
    public function events(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $query = Travel::with('employees');

        if ($start && $end) {
            $query->where(function($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end]);
            });
        }

        $travels = $query->get();

        $events = $travels->map(function ($travel) {
            $employeeNames = $travel->employees->pluck('name')->implode(', ');
            $title = ($employeeNames ?: 'Pegawai') . ' (' . ($travel->aplikasi ?? 'SPD') . ')';

            // Determine status color
            $color = '#0284c7'; // default sky-600
            if ($travel->status === 'paid') {
                $color = '#10b981'; // emerald-500
            } elseif ($travel->is_overdue) {
                $color = '#ef4444'; // red-500
            } elseif ($travel->status === 'pending') {
                $color = '#f59e0b'; // amber-500
            }

            return [
                'id' => $travel->id,
                'title' => $title,
                'start' => $travel->start_date ? $travel->start_date->format('Y-m-d') : null,
                // FullCalendar end date is exclusive, add 1 day so last day renders properly
                'end' => $travel->end_date ? $travel->end_date->copy()->addDay()->format('Y-m-d') : null,
                'color' => $color,
                'extendedProps' => [
                    'destination' => $travel->destination,
                    'employees' => $employeeNames,
                    'aplikasi' => $travel->aplikasi,
                    'amount' => 'Rp ' . number_format($travel->total_amount, 0, ',', '.'),
                    'status' => $travel->status === 'paid' ? 'Lunas' : ($travel->is_overdue ? 'Overdue' : 'Pending'),
                    'print_url' => route('travels.print', $travel),
                ],
            ];
        });

        return response()->json($events);
    }
}
