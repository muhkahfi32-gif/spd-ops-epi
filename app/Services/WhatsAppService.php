<?php

namespace App\Services;

use App\Models\Travel;
use App\Models\Employee;
use App\Models\ReminderLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $apiUrl;
    protected ?string $token;

    public function __construct()
    {
        $this->apiUrl = config('services.wa_gateway.url', 'https://api.fonnte.com/send');
        $this->token = config('services.wa_gateway.token');
    }

    /**
     * Send a WhatsApp message to a phone number.
     *
     * @return array{success: bool, response: mixed}
     */
    public function send(string $phone, string $message): array
    {
        if (empty($this->token)) {
            Log::info("[MOCK WA] Token belum dikonfigurasi. Ke: {$phone}\nPesan:\n{$message}");
            return ['success' => true, 'response' => ['mock' => true, 'message' => 'Token not configured, logged locally']];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info("WA terkirim ke {$phone}");
                return ['success' => true, 'response' => $response->json()];
            }

            Log::error("WA gagal ke {$phone}: " . $response->body());
            return ['success' => false, 'response' => $response->json()];
        } catch (\Exception $e) {
            Log::error("WA exception ke {$phone}: " . $e->getMessage());
            return ['success' => false, 'response' => ['error' => $e->getMessage()]];
        }
    }

    /**
     * Build the reminder message template for a travel + employee.
     */
    public function buildReminderMessage(Travel $travel, Employee $employee, int $days = 7): string
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

    /**
     * Send reminder for a specific travel and log the result.
     *
     * @return int Number of messages sent
     */
    public function sendTravelReminder(Travel $travel, int $days = 7): int
    {
        $travel->loadMissing('employees');
        $sentCount = 0;

        foreach ($travel->employees as $employee) {
            if (empty($employee->phone)) {
                continue;
            }

            $message = $this->buildReminderMessage($travel, $employee, $days);
            $result = $this->send($employee->phone, $message);

            // Log the reminder
            ReminderLog::create([
                'travel_id' => $travel->id,
                'employee_id' => $employee->id,
                'phone' => $employee->phone,
                'message' => $message,
                'status' => $result['success'] ? 'sent' : 'failed',
                'response_data' => $result['response'],
                'sent_at' => Carbon::now(),
            ]);

            if ($result['success']) {
                $sentCount++;
            }
        }

        if ($sentCount > 0) {
            $travel->update(['last_reminded_at' => Carbon::now()]);
        }

        return $sentCount;
    }
}
