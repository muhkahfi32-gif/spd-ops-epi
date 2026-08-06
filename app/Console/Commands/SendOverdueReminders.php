<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Travel;
use App\Models\ReminderLog;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SendOverdueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spd:send-reminders {--force : Abaikan jeda hari pengiriman terakhir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis mengirimkan pengingat WA Fonnte untuk perjalanan dinas overdue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Memulai pemrosesan WA Reminder Otomatis...');

        // Fetch overdue travels (>7 days past end_date and status pending)
        $travels = Travel::with('employees')
            ->where('status', 'pending')
            ->whereDate('end_date', '<=', Carbon::today()->subDays(7))
            ->get();

        if ($travels->isEmpty()) {
            $this->info('✅ Tidak ada perjalanan dinas overdue yang perlu dikirimkan pengingat.');
            return 0;
        }

        $sentCount = 0;

        foreach ($travels as $travel) {
            foreach ($travel->employees as $employee) {
                if (empty($employee->phone)) {
                    $this->warn("⚠️  Pegawai {$employee->name} tidak memiliki nomor telepon.");
                    continue;
                }

                $message = "Halo Bpk/Ibu *{$employee->name}*,\n\n"
                    . "Pengingat otomatis Sistem SPD Ops EPI:\n"
                    . "Perjalanan Dinas Anda ke *{$travel->destination}* ({$travel->aplikasi}) tanggal "
                    . ($travel->start_date ? $travel->start_date->format('d/m/Y') : '') . " s/d "
                    . ($travel->end_date ? $travel->end_date->format('d/m/Y') : '')
                    . " telah melewati tenggat (*Overdue +{$travel->overdue_days} Hari*).\n\n"
                    . "Mohon segera melakukan kelengkapan berkas & klaim pencairan SPD sebesar *Rp "
                    . number_format($travel->total_amount, 0, ',', '.') . "*.\n\n"
                    . "Cek status SPD Anda di: " . route('cek-spd.index') . "?nip=" . $employee->nip . "\n\n"
                    . "Terima Kasih.\n*Tim Operasional SPD EPI*";

                $token = env('WA_GATEWAY_TOKEN', 'XC7DbDVjasfVUSRDiBXi');
                $gatewayUrl = env('WA_GATEWAY_URL', 'https://api.fonnte.com/send');

                try {
                    $response = Http::withHeaders([
                        'Authorization' => $token,
                    ])->post($gatewayUrl, [
                        'target' => $employee->phone,
                        'message' => $message,
                    ]);

                    $success = $response->successful();
                    $status = $success ? 'sent' : 'failed';

                    ReminderLog::create([
                        'travel_id' => $travel->id,
                        'employee_id' => $employee->id,
                        'phone' => $employee->phone,
                        'message' => $message,
                        'status' => $status,
                        'sent_at' => now(),
                    ]);

                    if ($success) {
                        $travel->update(['last_reminded_at' => now()]);
                        $sentCount++;
                        $this->info("✅ Reminder terkirim ke {$employee->name} ({$employee->phone})");
                    } else {
                        $this->error("❌ Gagal mengirim ke {$employee->name}: " . $response->body());
                    }
                } catch (\Exception $e) {
                    $this->error("❌ Error koneksi ke Fonnte API: " . $e->getMessage());
                }
            }
        }

        $this->info("🎉 Pemrosesan selesai. Total WA Reminder terkirim: {$sentCount}");
        return 0;
    }
}
