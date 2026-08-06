<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Travel;
use App\Models\SuratDinas;
use Illuminate\Http\Request;

class CekSpdController extends Controller
{
    /**
     * Display the public search portal.
     */
    public function index()
    {
        return view('cek-spd.index');
    }

    /**
     * Search employee SPD records by NIP (Strictly View-Only).
     */
    public function search(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|max:50',
        ], [
            'nip.required' => 'Silakan masukkan NIP pegawai.',
        ]);

        $nip = trim($request->nip);
        $employee = Employee::where('nip', $nip)->first();

        if (!$employee) {
            return back()->withInput()->with('error', "Pegawai dengan NIP '{$nip}' tidak ditemukan dalam sistem.");
        }

        // Fetch travels associated with this employee
        $travels = Travel::with(['employees', 'suratDinas'])
            ->whereHas('employees', function ($q) use ($employee) {
                $q->where('employees.id', $employee->id);
            })
            ->orderByDesc('start_date')
            ->get();

        // Fetch surat dinas associated with this employee
        $suratDinasList = SuratDinas::with(['employees', 'travel'])
            ->whereHas('employees', function ($q) use ($employee) {
                $q->where('employees.id', $employee->id);
            })
            ->orderByDesc('tanggal_surat')
            ->get();

        // Statistics summary
        $stats = [
            'total_spd' => $travels->count(),
            'pending' => $travels->where('status', 'pending')->count(),
            'paid' => $travels->where('status', 'paid')->count(),
            'total_amount' => $travels->sum('total_amount'),
        ];

        return view('cek-spd.index', compact('employee', 'travels', 'suratDinasList', 'stats', 'nip'));
    }
}
