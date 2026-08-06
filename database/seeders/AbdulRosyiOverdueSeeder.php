<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Travel;
use App\Models\Employee;
use App\Models\SuratDinas;
use Carbon\Carbon;

class AbdulRosyiOverdueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat / Ambil Pegawai Abdul Rosyi
        $employee = Employee::firstOrCreate(
            ['name' => 'Abdul Rosyi'],
            [
                'nip' => '198503152010011002',
                'position' => 'Senior Specialist Ops',
                'email' => 'abdul.rosyi@plnepi.co.id',
                'email_korporat' => 'abdul.rosyi@plnepi.co.id',
                'phone' => '081234567890',
                'aplikasi' => 'EAM Distribusi, Maximo',
                'is_active' => true,
            ]
        );

        // 2. Buat Data Perjalanan Dinas 1 (H+9 Overdue Belum Bayar)
        $travel1 = Travel::create([
            'destination' => 'Bandung - Monitoring Integrasi System EAM & Maximo',
            'start_date' => Carbon::today()->subDays(16),
            'end_date' => Carbon::today()->subDays(9), // H+9 Overdue
            'amount' => 2850000.00,
            'description' => 'Supervisi integrasi sistem EAM & Maximo pada unit pelaksana PLN UP3 Bandung',
            'status' => 'pending',
            'is_accumulated' => false,
            'aplikasi' => 'EAM Distribusi',
            'last_reminded_at' => null,
        ]);
        $travel1->employees()->syncWithoutDetaching([$employee->id]);

        // 3. Buat Data Perjalanan Dinas 2 (H+7 Overdue Belum Bayar)
        $travel2 = Travel::create([
            'destination' => 'Denpasar - Pendampingan Audit K3 & Operasional',
            'start_date' => Carbon::today()->subDays(12),
            'end_date' => Carbon::today()->subDays(7), // H+7 Overdue
            'amount' => 3200000.00,
            'description' => 'Pelaksanaan verifikasi berkas dan pendampingan inspeksi K3 lapangan',
            'status' => 'pending',
            'is_accumulated' => false,
            'aplikasi' => 'Maximo',
            'last_reminded_at' => null,
        ]);
        $travel2->employees()->syncWithoutDetaching([$employee->id]);

        // 4. Buat Rekap Surat Dinas terkait Abdul Rosyi
        $surat = SuratDinas::create([
            'nomor_surat' => 'SPD/012/EPI-OPS/VIII/2026',
            'tanggal_surat' => Carbon::today()->subDays(17),
            'perihal' => 'Surat Tugas Monitoring Integrasi System EAM & Maximo',
            'employee_id' => $employee->id,
            'travel_id' => $travel1->id,
            'tujuan' => 'Bandung - UP3 Bandung',
            'tanggal_berangkat' => Carbon::today()->subDays(16),
            'tanggal_kembali' => Carbon::today()->subDays(9),
            'status' => 'aktif',
            'keterangan' => 'Tagihan SPD belum dibayar (H+9 Overdue)',
        ]);
        $surat->employees()->syncWithoutDetaching([$employee->id]);

        $this->command->info("Seeder AbdulRosyiOverdueSeeder berhasil dijalankan.");
        $this->command->info("Data pegawai Abdul Rosyi dan 2 tagihan SPD belum bayar (H+9 & H+7 overdue) telah ditambahkan.");
    }
}
