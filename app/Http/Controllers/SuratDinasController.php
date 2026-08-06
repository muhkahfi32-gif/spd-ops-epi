<?php

namespace App\Http\Controllers;

use App\Models\SuratDinas;
use App\Models\Employee;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuratDinasController extends Controller
{
    /**
     * Display the Rekap Surat Dinas page.
     */
    public function index(Request $request)
    {
        $year = $request->get('year');
        $month = $request->get('month');
        $status = $request->get('status');
        $search = $request->get('search');

        $query = SuratDinas::with(['employees', 'employee', 'travel', 'travels.employees'])
            ->byYear($year)
            ->byMonth($month)
            ->byStatus($status)
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nomor_surat', 'like', "%{$search}%")
                        ->orWhere('perihal', 'like', "%{$search}%")
                        ->orWhere('tujuan', 'like', "%{$search}%")
                        ->orWhereHas('employees', function ($empQ) use ($search) {
                            $empQ->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('employee', function ($empQ) use ($search) {
                            $empQ->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('tanggal_surat');

        $suratList = $query->paginate(15);

        // Statistics
        $statsBase = SuratDinas::query();
        if ($year) $statsBase->byYear($year);
        if ($month) $statsBase->byMonth($month);

        $stats = [
            'total' => (clone $statsBase)->count(),
            'draft' => (clone $statsBase)->byStatus('draft')->count(),
            'aktif' => (clone $statsBase)->byStatus('aktif')->count(),
            'selesai' => (clone $statsBase)->byStatus('selesai')->count(),
        ];

        $employees = Employee::where('is_active', true)->orderBy('name')->get();
        $travels = Travel::orderByDesc('start_date')->limit(100)->get();

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

        $years = SuratDinas::select(DB::raw('DISTINCT YEAR(tanggal_surat) as year'))
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('surat-dinas.index', compact(
            'suratList', 'stats', 'employees', 'employeesByAplikasi', 'travels',
            'year', 'month', 'status', 'search', 'years'
        ));
    }

    /**
     * Store a new surat dinas.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_surat' => 'required|string|max:100|unique:surat_dinas',
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|string|max:255',
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'exists:employees,id',
            'travel_id' => 'nullable|exists:travels,id',
            'tujuan' => 'required|string|max:255',
            'tanggal_berangkat' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_berangkat',
            'status' => 'required|in:draft,aktif,selesai',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $surat = SuratDinas::create([
                'nomor_surat' => $request->nomor_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'perihal' => $request->perihal,
                'employee_id' => $request->employee_ids[0] ?? null, // Primary employee fallback
                'travel_id' => $request->travel_id,
                'tujuan' => $request->tujuan,
                'tanggal_berangkat' => $request->tanggal_berangkat,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
            ]);

            // Sync multi-employees
            $surat->employees()->sync($request->employee_ids);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Surat dinas berhasil ditambahkan',
                'data' => $surat->load(['employees', 'travel']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
        }
    }

    /**
     * Get surat dinas data for editing.
     */
    public function edit(SuratDinas $surat_dina)
    {
        $surat_dina->load(['employees', 'employee', 'travel']);

        // Collect employee IDs (from relation or fallback)
        $employeeIds = $surat_dina->employees->pluck('id')->toArray();
        if (empty($employeeIds) && $surat_dina->employee_id) {
            $employeeIds = [$surat_dina->employee_id];
        }

        return response()->json([
            'id' => $surat_dina->id,
            'nomor_surat' => $surat_dina->nomor_surat,
            'tanggal_surat' => $surat_dina->tanggal_surat ? $surat_dina->tanggal_surat->format('Y-m-d') : null,
            'perihal' => $surat_dina->perihal,
            'employee_ids' => $employeeIds,
            'travel_id' => $surat_dina->travel_id,
            'tujuan' => $surat_dina->tujuan,
            'tanggal_berangkat' => $surat_dina->tanggal_berangkat ? $surat_dina->tanggal_berangkat->format('Y-m-d') : null,
            'tanggal_kembali' => $surat_dina->tanggal_kembali ? $surat_dina->tanggal_kembali->format('Y-m-d') : null,
            'status' => $surat_dina->status,
            'keterangan' => $surat_dina->keterangan,
        ]);
    }

    /**
     * Update surat dinas.
     */
    public function update(Request $request, SuratDinas $surat_dina)
    {
        $validator = Validator::make($request->all(), [
            'nomor_surat' => 'required|string|max:100|unique:surat_dinas,nomor_surat,' . $surat_dina->id,
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|string|max:255',
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'exists:employees,id',
            'travel_id' => 'nullable|exists:travels,id',
            'tujuan' => 'required|string|max:255',
            'tanggal_berangkat' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_berangkat',
            'status' => 'required|in:draft,aktif,selesai',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $surat_dina->update([
                'nomor_surat' => $request->nomor_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'perihal' => $request->perihal,
                'employee_id' => $request->employee_ids[0] ?? null,
                'travel_id' => $request->travel_id,
                'tujuan' => $request->tujuan,
                'tanggal_berangkat' => $request->tanggal_berangkat,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
            ]);

            // Sync multi-employees
            $surat_dina->employees()->sync($request->employee_ids);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Surat dinas berhasil diupdate',
                'data' => $surat_dina->load(['employees', 'travel']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
        }
    }

    /**
     * Delete surat dinas.
     */
    public function destroy(SuratDinas $surat_dina)
    {
        DB::beginTransaction();
        try {
            $surat_dina->employees()->detach();
            $surat_dina->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Surat dinas berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
        }
    }

    /**
     * Export surat dinas (print-friendly CSV).
     */
    public function export(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $suratList = SuratDinas::with(['employees', 'employee', 'travel'])
            ->byYear($year)
            ->orderBy('tanggal_surat')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=rekap-surat-dinas-{$year}.csv",
        ];

        $callback = function () use ($suratList) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nomor Surat', 'Tanggal', 'Perihal', 'Pegawai Ditunjuk', 'Tujuan', 'Berangkat', 'Kembali', 'Durasi', 'Status', 'Keterangan']);

            foreach ($suratList as $idx => $surat) {
                fputcsv($file, [
                    $idx + 1,
                    $surat->nomor_surat,
                    $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-',
                    $surat->perihal,
                    $surat->employee_names,
                    $surat->tujuan,
                    $surat->tanggal_berangkat ? $surat->tanggal_berangkat->format('d/m/Y') : '-',
                    $surat->tanggal_kembali ? $surat->tanggal_kembali->format('d/m/Y') : '-',
                    $surat->durasi . ' hari',
                    ucfirst($surat->status),
                    $surat->keterangan ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
