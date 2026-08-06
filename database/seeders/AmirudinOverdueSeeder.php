<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Travel;
use App\Models\Employee;
use App\Models\SuratDinas;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class AmirudinOverdueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat / Ambil Pegawai Amirudin Rizal Divianto (Mas Amir)
        $employee = Employee::firstOrCreate(
            ['name' => 'Amirudin Rizal Divianto'],
            [
                'nip' => '198008082001011008',
                'position' => 'Senior Specialist Ops & Sistem',
                'email' => 'amirudin.divianto@iconpln.co.id',
                'email_korporat' => 'amirudin.divianto@iconpln.co.id',
                'phone' => '082113132160',
                'aplikasi' => 'EAM Distribusi, MAXICO',
                'is_active' => true,
            ]
        );

        // Pastikan no hp selalu terupdate ke 082113132160
        $employee->update([
            'phone' => '082113132160',
            'position' => 'Senior Specialist Ops & Sistem',
        ]);

        // 2. Buat Rekap Surat Dinas terkait Mas Amir
        $surat = SuratDinas::create([
            'nomor_surat' => 'SPD/088/EPI-OPS/VIII/2026',
            'tanggal_surat' => Carbon::today()->subDays(18),
            'perihal' => 'Surat Tugas Pendampingan Implementasi Sistem EAM Distribusi & MAXICO',
            'employee_id' => $employee->id,
            'tujuan' => 'Semarang - UP3 Semarang & UP2D Jateng',
            'tanggal_berangkat' => Carbon::today()->subDays(15),
            'tanggal_kembali' => Carbon::today()->subDays(8),
            'status' => 'aktif',
            'keterangan' => 'Tagihan SPD belum dibayar (H+8 Overdue)',
        ]);
        $surat->employees()->syncWithoutDetaching([$employee->id]);

        // 3. Buat Data Perjalanan Dinas 1 (H+8 Overdue Belum Bayar)
        $travel1 = Travel::create([
            'surat_dinas_id' => $surat->id,
            'destination' => 'Semarang - UP3 Semarang & UP2D Jateng',
            'start_date' => Carbon::today()->subDays(15),
            'end_date' => Carbon::today()->subDays(8), // H+8 Overdue
            'amount' => 2400000.00,
            'description' => 'Pendampingan implementasi EAM Distribusi dan integrasi sistem MAXICO di unit Jateng',
            'status' => 'pending',
            'is_accumulated' => false,
            'aplikasi' => 'EAM Distribusi',
            'last_reminded_at' => null,
        ]);
        $travel1->employees()->syncWithoutDetaching([$employee->id]);

        // 4. Buat Data Perjalanan Dinas 2 (H+5 Tagihan Pending)
        $travel2 = Travel::create([
            'surat_dinas_id' => $surat->id,
            'destination' => 'Surakarta - Audit Lapangan Aset Kelistrikan',
            'start_date' => Carbon::today()->subDays(10),
            'end_date' => Carbon::today()->subDays(5),
            'amount' => 1800000.00,
            'description' => 'Pemeriksaan fisik dan pemutakhiran data aset EAM Distribusi',
            'status' => 'pending',
            'is_accumulated' => false,
            'aplikasi' => 'MAXICO',
            'last_reminded_at' => null,
        ]);
        $travel2->employees()->syncWithoutDetaching([$employee->id]);

        // Update travel_id di Surat Dinas
        $surat->update(['travel_id' => $travel1->id]);

        // 5. Kirim & Log Pesan WA Reminder untuk Mas Amir
        $waService = app(WhatsAppService::class);
        $sentCount = $waService->sendTravelReminder($travel1, 8);

        $this->command->info("✅ Seeder AmirudinOverdueSeeder berhasil dijalankan.");
        $this->command->info("👤 Pegawai: Amirudin Rizal Divianto ({$employee->phone})");
        $this->command->info("📄 Surat Dinas: {$surat->nomor_surat}");
        $this->command->info("📲 Message WhatsApp Reminder berhasil diproses ({$sentCount} pesan terkirim/tercatat).");
    }
}
