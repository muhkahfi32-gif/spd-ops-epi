<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Travel;
use App\Models\Employee;
use App\Models\SuratDinas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class KahfiElsaOverdueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Bersihkan Data Perjalanan, Surat Dinas, & Log Reminder Terlebih Dahulu
        Schema::disableForeignKeyConstraints();
        DB::table('reminder_logs')->truncate();
        DB::table('employee_surat_dinas')->truncate();
        DB::table('surat_dinas')->truncate();
        DB::table('employee_travel')->truncate();
        DB::table('travels')->truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Ambil / Buat Pegawai Muhammad Kahfi & Elsa Erianti
        $kahfi = Employee::firstOrCreate(
            ['name' => 'Muhammad Kahfi'],
            [
                'nip' => '198206302001011030',
                'position' => 'Senior Specialist Ops EAM',
                'email' => 'muhammad.kahfi@iconpln.co.id',
                'email_korporat' => 'muhammad.kahfi@iconpln.co.id',
                'phone' => '082111318263',
                'aplikasi' => 'EAM Distribusi, AVMS',
                'is_active' => true,
            ]
        );

        $elsa = Employee::firstOrCreate(
            ['name' => 'Elsa Erianti'],
            [
                'nip' => '198108202001011020',
                'position' => 'Specialist GBMO & BAg',
                'email' => 'elsa.erianti@iconpln.co.id',
                'email_korporat' => 'elsa.erianti@iconpln.co.id',
                'phone' => '082111414854',
                'aplikasi' => 'GBMO, BAg',
                'is_active' => true,
            ]
        );

        // 3. Buat Data Perjalanan Dinas & Surat Dinas untuk Muhammad Kahfi (H+8 Overdue Pending)
        $suratKahfi = SuratDinas::create([
            'nomor_surat' => 'SPD/001/EPI-OPS/VIII/2026',
            'tanggal_surat' => Carbon::today()->subDays(18),
            'perihal' => 'Surat Tugas Maintenance EAM Distribusi & AVMS Unit Pelaksana',
            'employee_id' => $kahfi->id,
            'tujuan' => 'Bandung - UP3 Bandung & UP2D Jabar',
            'tanggal_berangkat' => Carbon::today()->subDays(15),
            'tanggal_kembali' => Carbon::today()->subDays(8),
            'status' => 'aktif',
            'keterangan' => 'Tagihan SPD belum dibayar (H+8 Overdue)',
        ]);
        $suratKahfi->employees()->syncWithoutDetaching([$kahfi->id]);

        $travelKahfi = Travel::create([
            'surat_dinas_id' => $suratKahfi->id,
            'destination' => 'Bandung - UP3 Bandung & UP2D Jabar',
            'start_date' => Carbon::today()->subDays(15),
            'end_date' => Carbon::today()->subDays(8), // H+8 Overdue
            'amount' => 2700000.00,
            'description' => 'Supervisi integrasi EAM Distribusi & pemeliharaan sistem AVMS',
            'status' => 'pending',
            'is_accumulated' => false,
            'aplikasi' => 'EAM Distribusi',
            'last_reminded_at' => null,
        ]);
        $travelKahfi->employees()->syncWithoutDetaching([$kahfi->id]);
        $suratKahfi->update(['travel_id' => $travelKahfi->id]);

        // 4. Buat Data Perjalanan Dinas & Surat Dinas untuk Elsa Erianti (H+7 Overdue Pending)
        $suratElsa = SuratDinas::create([
            'nomor_surat' => 'SPD/002/EPI-OPS/VIII/2026',
            'tanggal_surat' => Carbon::today()->subDays(16),
            'perihal' => 'Surat Tugas Audit Lapangan Sistem GBMO & BAg',
            'employee_id' => $elsa->id,
            'tujuan' => 'Semarang - Unit Pelaksana PLN Semarang',
            'tanggal_berangkat' => Carbon::today()->subDays(14),
            'tanggal_kembali' => Carbon::today()->subDays(7),
            'status' => 'aktif',
            'keterangan' => 'Tagihan SPD belum dibayar (H+7 Overdue)',
        ]);
        $suratElsa->employees()->syncWithoutDetaching([$elsa->id]);

        $travelElsa = Travel::create([
            'surat_dinas_id' => $suratElsa->id,
            'destination' => 'Semarang - Unit Pelaksana PLN Semarang',
            'start_date' => Carbon::today()->subDays(14),
            'end_date' => Carbon::today()->subDays(7), // H+7 Overdue
            'amount' => 2400000.00,
            'description' => 'Pelaksanaan verifikasi berkas audit operasional GBMO & BAg',
            'status' => 'pending',
            'is_accumulated' => false,
            'aplikasi' => 'GBMO',
            'last_reminded_at' => null,
        ]);
        $travelElsa->employees()->syncWithoutDetaching([$elsa->id]);
        $suratElsa->update(['travel_id' => $travelElsa->id]);

        $this->command->info("✅ Seeder KahfiElsaOverdueSeeder berhasil dijalankan.");
        $this->command->info("Data Perjalanan Dinas berhasil dibersihkan & diisi khusus untuk 2 Pegawai:");
        $this->command->info("1. Muhammad Kahfi ({$kahfi->phone}) - H+8 Overdue Pending");
        $this->command->info("2. Elsa Erianti ({$elsa->phone}) - H+7 Overdue Pending");
        $this->command->info("Pesan WA Reminder BELUM dikirim otomatis agar dapat diuji coba tombol action-nya.");
    }
}
