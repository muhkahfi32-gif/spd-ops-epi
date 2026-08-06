<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use App\Models\ReminderLog;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReminderController extends Controller
{
    protected WhatsAppService $waService;

    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    /**
     * Display the WA Reminder Center page.
     */
    public function index(Request $request)
    {
        $filterStatus = $request->get('filter', 'all');

        // Overdue travels (H+7 belum bayar)
        $overdueTravels = Travel::with('employees')
            ->pendingPaymentOverdue(7)
            ->get();

        // Reminder history logs
        $logsQuery = ReminderLog::with(['travel', 'employee'])
            ->orderByDesc('sent_at');

        if ($filterStatus === 'sent') {
            $logsQuery->sent();
        } elseif ($filterStatus === 'failed') {
            $logsQuery->failed();
        }

        $reminderLogs = $logsQuery->paginate(15);

        // Statistics
        $stats = [
            'total_overdue' => $overdueTravels->count(),
            'sent_today' => ReminderLog::today()->sent()->count(),
            'failed_today' => ReminderLog::today()->failed()->count(),
            'total_sent' => ReminderLog::sent()->count(),
            'total_failed' => ReminderLog::failed()->count(),
        ];

        return view('reminders.index', compact('overdueTravels', 'reminderLogs', 'stats', 'filterStatus'));
    }

    /**
     * Send WA reminder for a specific travel.
     */
    public function sendSingle(Travel $travel)
    {
        $sentCount = $this->waService->sendTravelReminder($travel);

        return response()->json([
            'success' => $sentCount > 0,
            'message' => $sentCount > 0
                ? "Reminder WhatsApp berhasil dikirim ke {$sentCount} pegawai!"
                : 'Gagal mengirim reminder. Pastikan pegawai memiliki nomor telepon.',
        ]);
    }

    /**
     * Send all H+7 WA reminders.
     */
    public function sendAll()
    {
        $overdueTravels = Travel::with('employees')
            ->pendingPaymentOverdue(7)
            ->get();

        $totalSent = 0;
        foreach ($overdueTravels as $travel) {
            $totalSent += $this->waService->sendTravelReminder($travel);
        }

        return response()->json([
            'success' => true,
            'message' => "Proses selesai. {$totalSent} pesan reminder berhasil dikirim.",
        ]);
    }

    /**
     * Get reminder history as JSON (for AJAX).
     */
    public function history(Request $request)
    {
        $logs = ReminderLog::with(['travel', 'employee'])
            ->orderByDesc('sent_at')
            ->limit(50)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'employee_name' => $log->employee->name ?? '-',
                    'phone' => $log->phone,
                    'destination' => $log->travel->destination ?? '-',
                    'status' => $log->status,
                    'sent_at' => $log->sent_at ? $log->sent_at->format('d M Y H:i') : '-',
                ];
            });

        return response()->json(['success' => true, 'data' => $logs]);
    }
}
