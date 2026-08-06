<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use App\Models\Employee;
use App\Models\SuratDinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TravelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');
        
        $travels = Travel::with(['employees', 'suratDinas'])
            ->when($search, function($query, $search) {
                return $query->whereHas('employees', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('destination', 'like', "%{$search}%")
                  ->orWhereHas('suratDinas', function($sq) use ($search) {
                      $sq->where('nomor_surat', 'like', "%{$search}%");
                  });
            })
            ->when($year, function($query, $year) {
                return $query->whereYear('start_date', $year);
            })
            ->when($month, function($query, $month) {
                return $query->whereMonth('start_date', $month);
            })
            ->orderBy('start_date', 'desc')
            ->paginate(10);
        
        $employees = Employee::where('is_active', true)->orderBy('name')->get();
        $suratDinasList = SuratDinas::orderByDesc('tanggal_surat')->get();

        // Group employees by application
        $employeesByAplikasi = [];
        foreach ($employees as $emp) {
            if (!empty($emp->aplikasi)) {
                $apps = array_map('trim', explode(',', $emp->aplikasi));
                foreach ($apps as $app) {
                    if (!empty($app)) {
                        $employeesByAplikasi[$app][] = $emp;
                    }
                }
            } else {
                $employeesByAplikasi['Lainnya / Umum'][] = $emp;
            }
        }
        ksort($employeesByAplikasi);

        $years = Travel::whereNotNull('start_date')
            ->get()
            ->map(fn($t) => (int) $t->start_date->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }
        
        // Get all travels for statistics (with same year & month filter)
        $statsQuery = Travel::with('employees');
        if ($year) {
            $statsQuery->whereYear('start_date', $year);
        }
        if ($month) {
            $statsQuery->whereMonth('start_date', $month);
        }
        $allTravelsForStats = $statsQuery->get();
        
        $totalData = $allTravelsForStats->count();
        $totalEmployees = Employee::where('is_active', true)->count();
        
        // Total nominal with multiplier (jumlah pegawai)
        $totalNominal = 0;
        $totalDays = 0;
        foreach ($allTravelsForStats as $travel) {
            $totalNominal += $travel->total_amount;
            $totalDays += $travel->duration;
        }
        
        $statistics = [
            'total_data' => $totalData,
            'total_employees' => $totalEmployees,
            'total_nominal' => $totalNominal,
            'total_days' => $totalDays,
        ];
        
        return view('travels.index', compact('travels', 'employees', 'employeesByAplikasi', 'suratDinasList', 'search', 'year', 'month', 'years', 'statistics'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aplikasi' => 'nullable|string|max:255',
            'surat_dinas_id' => 'nullable|exists:surat_dinas,id',
            'nomor_surat' => 'nullable|string|max:100',
            'perihal_surat' => 'nullable|string|max:255',
            'tanggal_surat' => 'nullable|date',
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'payment_date' => 'nullable|date',
            'amount' => 'required|numeric|min:0',
            'destination' => 'required|string|max:255',
            'status' => 'required|in:pending,paid',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        DB::beginTransaction();
        try {
            $suratDinasId = $request->surat_dinas_id;

            // Self-create Surat Dinas if nomor_surat is entered directly
            if (empty($suratDinasId) && !empty($request->nomor_surat)) {
                $surat = SuratDinas::firstOrCreate(
                    ['nomor_surat' => trim($request->nomor_surat)],
                    [
                        'tanggal_surat' => $request->tanggal_surat ?? $request->start_date,
                        'perihal' => $request->perihal_surat ?? ('Tugas ' . $request->destination),
                        'employee_id' => $request->employee_ids[0] ?? null,
                        'tujuan' => $request->destination,
                        'tanggal_berangkat' => $request->start_date,
                        'tanggal_kembali' => $request->end_date,
                        'status' => 'aktif',
                    ]
                );
                $suratDinasId = $surat->id;
            }

            if ($suratDinasId) {
                $suratObj = SuratDinas::find($suratDinasId);
                if ($suratObj) {
                    $suratObj->employees()->syncWithoutDetaching($request->employee_ids);
                }
            }

            $createdTravels = [];
            
            foreach ($request->employee_ids as $empId) {
                $travel = Travel::create([
                    'surat_dinas_id' => $suratDinasId,
                    'aplikasi' => $request->aplikasi,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'payment_date' => $request->payment_date,
                    'amount' => $request->amount,
                    'destination' => $request->destination,
                    'description' => $request->description,
                    'status' => $request->status,
                    'is_accumulated' => $request->status == 'paid' ? true : false,
                ]);
                
                $travel->employees()->attach([$empId]);
                $createdTravels[] = $travel->load(['employees', 'suratDinas']);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => count($createdTravels) > 1 
                    ? count($createdTravels) . ' Perjalanan dinas berhasil ditambahkan'
                    : 'Perjalanan dinas berhasil ditambahkan',
                'data' => $createdTravels[0] ?? null
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Travel store error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['errors' => ['system' => ['Terjadi kesalahan sistem saat menyimpan data.']]], 500);
        }
    }
    
    public function edit(Travel $travel)
    {
        $travel->load(['employees', 'suratDinas']);

        return response()->json([
            'id' => $travel->id,
            'surat_dinas_id' => $travel->surat_dinas_id,
            'nomor_surat' => $travel->suratDinas->nomor_surat ?? null,
            'perihal_surat' => $travel->suratDinas->perihal ?? null,
            'tanggal_surat' => $travel->suratDinas && $travel->suratDinas->tanggal_surat ? $travel->suratDinas->tanggal_surat->format('d M Y') : null,
            'aplikasi' => $travel->aplikasi,
            'aplikasi_list' => $travel->aplikasi_list,
            'employee_ids' => $travel->employees->pluck('id'),
            'employee_names' => $travel->employee_names,
            'employees_detail' => $travel->employees->map(function($e) {
                return [
                    'id' => $e->id,
                    'name' => $e->name,
                    'nip' => $e->nip,
                    'email' => $e->email,
                    'phone' => $e->phone,
                    'aplikasi' => $e->aplikasi
                ];
            }),
            'start_date' => $travel->start_date->format('Y-m-d'),
            'start_date_formatted' => $travel->start_date->format('d M Y'),
            'end_date' => $travel->end_date->format('Y-m-d'),
            'end_date_formatted' => $travel->end_date->format('d M Y'),
            'duration' => $travel->duration,
            'payment_date' => $travel->payment_date ? $travel->payment_date->format('Y-m-d') : null,
            'payment_date_formatted' => $travel->payment_date ? $travel->payment_date->format('d M Y') : '-',
            'amount' => $travel->amount,
            'formatted_amount' => $travel->formatted_amount,
            'destination' => $travel->destination,
            'description' => $travel->description,
            'status' => $travel->status,
            'is_accumulated' => $travel->is_accumulated,
            'last_reminded_at' => $travel->last_reminded_at ? $travel->last_reminded_at->format('d M Y H:i') : null,
        ]);
    }
    
    public function update(Request $request, Travel $travel)
    {
        $validator = Validator::make($request->all(), [
            'aplikasi' => 'nullable|string|max:255',
            'surat_dinas_id' => 'nullable|exists:surat_dinas,id',
            'nomor_surat' => 'nullable|string|max:100',
            'perihal_surat' => 'nullable|string|max:255',
            'tanggal_surat' => 'nullable|date',
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'payment_date' => 'nullable|date',
            'amount' => 'required|numeric|min:0',
            'destination' => 'required|string|max:255',
            'status' => 'required|in:pending,paid',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        DB::beginTransaction();
        try {
            $suratDinasId = $request->surat_dinas_id;

            if (empty($suratDinasId) && !empty($request->nomor_surat)) {
                $surat = SuratDinas::firstOrCreate(
                    ['nomor_surat' => trim($request->nomor_surat)],
                    [
                        'tanggal_surat' => $request->tanggal_surat ?? $request->start_date,
                        'perihal' => $request->perihal_surat ?? ('Tugas ' . $request->destination),
                        'employee_id' => $request->employee_ids[0] ?? null,
                        'tujuan' => $request->destination,
                        'tanggal_berangkat' => $request->start_date,
                        'tanggal_kembali' => $request->end_date,
                        'status' => 'aktif',
                    ]
                );
                $suratDinasId = $surat->id;
            }

            if ($suratDinasId) {
                $suratObj = SuratDinas::find($suratDinasId);
                if ($suratObj) {
                    $suratObj->employees()->syncWithoutDetaching($request->employee_ids);
                }
            }

            $travel->update([
                'surat_dinas_id' => $suratDinasId,
                'aplikasi' => $request->aplikasi,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'destination' => $request->destination,
                'description' => $request->description,
                'status' => $request->status,
                'is_accumulated' => $request->status == 'paid' ? true : false,
            ]);
            
            $travel->employees()->sync($request->employee_ids);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Perjalanan dinas berhasil diupdate',
                'data' => $travel->load(['employees', 'suratDinas'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Travel update error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['errors' => ['system' => ['Terjadi kesalahan sistem saat mengupdate data.']]], 500);
        }
    }
    
    public function destroy(Travel $travel)
    {
        DB::beginTransaction();
        try {
            $travel->employees()->detach();
            $travel->delete();
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Perjalanan dinas berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Travel destroy error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['errors' => ['system' => ['Terjadi kesalahan sistem saat menghapus data.']]], 500);
        }
    }
    
    public function getEmployeesByAplikasi(Request $request)
    {
        $aplikasi = $request->get('aplikasi');
        
        $employees = Employee::where('is_active', true)
            ->where('aplikasi', 'LIKE', '%' . $aplikasi . '%')
            ->orderBy('name')
            ->get(['id', 'name', 'aplikasi']);
        
        return response()->json($employees);
    }
    
    // Update status to accumulate or not
    public function updateAccumulation(Request $request, Travel $travel)
    {
        $travel->update(['is_accumulated' => $request->is_accumulated]);
        
        return response()->json([
            'success' => true,
            'message' => 'Status akumulasi berhasil diupdate'
        ]);
    }

    /**
     * Quick toggle status between pending and paid
     */
    public function toggleStatus(Request $request, Travel $travel)
    {
        $targetStatus = $request->get('status');
        if (empty($targetStatus)) {
            $targetStatus = $travel->status === 'paid' ? 'pending' : 'paid';
        }

        $paymentDate = $targetStatus === 'paid' ? ($travel->payment_date ?? \Carbon\Carbon::now()) : null;

        $travel->update([
            'status' => $targetStatus,
            'payment_date' => $paymentDate,
            'is_accumulated' => $targetStatus === 'paid' ? true : false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah menjadi ' . strtoupper($targetStatus),
            'status' => $targetStatus
        ]);
    }

    /**
     * Send WhatsApp reminder for a specific travel record
     */
    public function sendWaReminder(Travel $travel)
    {
        $travel->load('employees');
        if ($travel->employees->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data pegawai pada perjalanan dinas ini.'], 422);
        }

        $sentCount = 0;
        $waUrl = config('services.wa_gateway.url', 'https://api.fonnte.com/send');
        $waToken = config('services.wa_gateway.token');

        foreach ($travel->employees as $employee) {
            if (empty($employee->phone)) {
                continue;
            }

            $startDate = $travel->start_date ? \Carbon\Carbon::parse($travel->start_date)->translatedFormat('d F Y') : '-';
            $endDate = $travel->end_date ? \Carbon\Carbon::parse($travel->end_date)->translatedFormat('d F Y') : '-';

            $message = "*REMINDER PEMBAYARAN PERJALANAN DINAS*\n\n";
            $message .= "Yth. Bapak/Ibu *{$employee->name}*,\n\n";
            $message .= "Memberitahukan bahwa terdapat tagihan perjalanan dinas yang belum terselesaikan:\n\n";
            $message .= "📍 *Tujuan:* {$travel->destination}\n";
            $message .= "📅 *Tanggal Perjalanan:* {$startDate} s/d {$endDate}\n";
            $message .= "💰 *Total Nominal:* {$travel->formatted_amount}\n";
            $message .= "📌 *Status Pembayaran:* PENDING / BELUM DIBAYAR\n\n";
            if (!empty($travel->description)) {
                $message .= "📝 *Keterangan:* {$travel->description}\n\n";
            }
            $message .= "Mohon dapat segera menindaklanjuti proses pembayaran / verifikasi berkas SPD.\n\n";
            $message .= "_Pesan ini dikirimkan secara otomatis oleh Sistem SPD Ops EPI._";

            if (!empty($waToken)) {
                try {
                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'Authorization' => $waToken,
                    ])->post($waUrl, [
                        'target' => $employee->phone,
                        'message' => $message,
                        'countryCode' => '62',
                    ]);

                    if ($response->successful()) {
                        $sentCount++;
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("WA Send Error: " . $e->getMessage());
                }
            } else {
                \Illuminate\Support\Facades\Log::info("[MOCK WA] Ke {$employee->phone}:\n{$message}");
                $sentCount++;
            }
        }

        $travel->update(['last_reminded_at' => \Carbon\Carbon::now()]);

        return response()->json([
            'success' => true,
            'message' => "Reminder WhatsApp berhasil dikirim ke {$sentCount} pegawai!"
        ]);
    }

    /**
     * Send all H+7 WA reminders
     */
    public function sendAllWaReminders()
    {
        \Illuminate\Support\Facades\Artisan::call('travel:send-reminder');
        return response()->json([
            'success' => true,
            'message' => 'Proses pengiriman seluruh reminder WhatsApp H+7 selesai dieksekusi.'
        ]);
    }
}