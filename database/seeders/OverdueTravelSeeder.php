<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Travel;
use App\Models\Employee;
use Carbon\Carbon;

class OverdueTravelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Cari atau buat pegawai untuk uji coba reminder H+7
        $employee = Employee::firstOrCreate(
            ['email' => 'abdhillah.saputra@iconpln.co.id'],
            [
                'name' => 'Abdhillah Aji Saputra',
                'nip' => '198001012001011001',
                'position' => 'Staff Ops',
                'email_korporat' => 'abdhillah.saputra@iconpln.co.id',
                'phone' => '081330109721',
                'aplikasi' => 'EAM Distribusi',
                'is_active' => true,
            ]
        );

        // 2. Buat data Perjalanan Dinas 1 (Kepulangan H-8, Belum Dibayar -> H+7 Overdue)
        $travel1 = Travel::create([
            'destination' => 'Jakarta - Rapat Koordinasi PLN UIW Jatim',
            'start_date' => Carbon::today()->subDays(15),
            'end_date' => Carbon::today()->subDays(8), // Kepulangan 8 hari lalu (H+8)
            'amount' => 1750000.00,
            'description' => 'Perjalanan dinas koordinasi proyek EAM Distribusi di Kantor Pusat',
            'status' => 'pending',
            'is_accumulated' => false,
            'aplikasi' => 'EAM Distribusi',
            'last_reminded_at' => null,
        ]);

        $travel1->employees()->syncWithoutDetaching([$employee->id]);

        // 3. Buat data Perjalanan Dinas 2 (Kepulangan H-7, Belum Dibayar -> H+7 Overdue)
        $travel2 = Travel::create([
            'destination' => 'Surabaya - Inspek Lapangan & Pemeliharaan',
            'start_date' => Carbon::today()->subDays(11),
            'end_date' => Carbon::today()->subDays(7), // Kepulangan 7 hari lalu (H+7)
            'amount' => 2400000.00,
            'description' => 'Pendampingan teknis pemeliharaan sistem di UID Jatim',
            'status' => 'pending',
            'is_accumulated' => false,
            'aplikasi' => 'MAPP',
            'last_reminded_at' => null,
        ]);

        $travel2->employees()->syncWithoutDetaching([$employee->id]);

        $this->command->info("Seeder OverdueTravelSeeder berhasil dijalankan.");
        $this->command->info("Dua data perjalanan dinas H+7 pending payment telah ditambahkan untuk {$employee->name} ({$employee->phone}).");
    }
}
