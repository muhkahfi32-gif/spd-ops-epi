<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Travel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendTravelPaymentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'travel:send-reminder {--dry-run : Simulasi pengiriman tanpa menembak API WA Gateway} {--days=7 : Jumlah hari tenggat waktu setelah kepulangan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim reminder WhatsApp H+7 (atau H+N) untuk perjalanan dinas yang belum dibayar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $isDryRun = (bool) $this->option('dry-run');

        $this->info("Menjalankan pengecekan reminder perjalanan dinas belum bayar (H+{$days})...");

        // Ambil data perjalanan yang belum dibayar & melebihi batas H+N
        $travels = Travel::with('employees')
            ->pendingPaymentOverdue($days)
            ->get();

        if ($travels->isEmpty()) {
            $this->info('Tidak ada tagihan perjalanan dinas yang memerlukan reminder hari ini.');
            return 0;
        }

        $sentCount = 0;
        $waUrl = config('services.wa_gateway.url', 'https://api.fonnte.com/send');
        $waToken = config('services.wa_gateway.token');

        foreach ($travels as $travel) {
            if ($travel->employees->isEmpty()) {
                $this->warn("Travel ID #{$travel->id} ({$travel->destination}) tidak memiliki data pegawai.");
                continue;
            }

            foreach ($travel->employees as $employee) {
                if (empty($employee->phone)) {
                    $this->warn("Pegawai {$employee->name} tidak memiliki nomor telepon (Travel ID #{$travel->id}).");
                    continue;
                }

                $message = $this->buildMessage($travel, $employee, $days);

                if ($isDryRun) {
                    $this->line("<fg=yellow>[DRY-RUN]</fg=yellow> Mengirim ke <fg=cyan>{$employee->name} ({$employee->phone})</fg=cyan>:");
                    $this->line("----------------------------------------");
                    $this->line($message);
                    $this->line("----------------------------------------");
                    $sentCount++;
                    continue;
                }

                // Kirim via WA Gateway jika Token terpasang
                if (!empty($waToken)) {
                    try {
                        $response = Http::withHeaders([
                            'Authorization' => $waToken,
                        ])->post($waUrl, [
                            'target' => $employee->phone,
                            'message' => $message,
                            'countryCode' => '62',
                        ]);

                        if ($response->successful()) {
                            $travel->update(['last_reminded_at' => Carbon::now()]);
                            $sentCount++;
                            Log::info("WA Reminder terkirim ke {$employee->name} ({$employee->phone}) - Travel ID: {$travel->id}");
                            $this->info("✓ Terkirim ke {$employee->name} ({$employee->phone})");
                        } else {
                            Log::error("Gagal mengirim WA ke {$employee->phone}: " . $response->body());
                            $this->error("✗ Gagal mengirim WA ke {$employee->name}: " . $response->body());
                        }
                    } catch (\Exception $e) {
                        Log::error("Exception saat mengirim WA ke {$employee->phone}: " . $e->getMessage());
                        $this->error("✗ Error kirim ke {$employee->name}: " . $e->getMessage());
                    }
                } else {
                    // Jika token belum terkonfigurasi di .env, log pesan secara lokal
                    Log::info("[MOCK WA SEND] Token WA_GATEWAY_TOKEN belum diisi di .env.\nKe: {$employee->phone}\nPesan:\n{$message}");
                    $this->line("<fg=blue>[MOCK LOGGED]</fg=blue> Ke <fg=cyan>{$employee->name} ({$employee->phone})</fg=cyan>. (Isi WA_GATEWAY_TOKEN di .env untuk kirim sungguhan)");
                    $travel->update(['last_reminded_at' => Carbon::now()]);
                    $sentCount++;
                }
            }
        }

        $this->info("Selesai. Total {$sentCount} pesan reminder berhasil diproses.");
        return 0;
    }

    /**
     * Formulasi teks pesan WhatsApp
     */
    private function buildMessage(Travel $travel, $employee, int $days): string
    {
        $startDate = $travel->start_date ? Carbon::parse($travel->start_date)->translatedFormat('d F Y') : '-';
        $endDate = $travel->end_date ? Carbon::parse($travel->end_date)->translatedFormat('d F Y') : '-';
        $amount = $travel->formatted_amount;

        $msg = "*REMINDER PEMBAYARAN PERJALANAN DINAS*\n\n";
        $msg .= "Yth. Bapak/Ibu *{$employee->name}*,\n\n";
        $msg .= "Memberitahukan bahwa terdapat tagihan perjalanan dinas yang belum terselesaikan (H+{$days} setelah tanggal kepulangan):\n\n";
        $msg .= "📍 *Tujuan:* {$travel->destination}\n";
        $msg .= "📅 *Tanggal Perjalanan:* {$startDate} s/d {$endDate}\n";
        $msg .= "💰 *Total Nominal:* {$amount}\n";
        $msg .= "📌 *Status Pembayaran:* PENDING / BELUM DIBAYAR\n\n";
        if (!empty($travel->description)) {
            $msg .= "📝 *Keterangan:* {$travel->description}\n\n";
        }
        $msg .= "Mohon dapat segera menindaklanjuti proses pembayaran / verifikasi berkas SPD.\n\n";
        $msg .= "_Pesan ini dikirimkan secara otomatis oleh Sistem SPD Ops EPI._";

        return $msg;
    }
}
