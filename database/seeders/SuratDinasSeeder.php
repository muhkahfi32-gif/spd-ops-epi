<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratDinas;
use App\Models\Employee;
use App\Models\Travel;
use Carbon\Carbon;

class SuratDinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::where('is_active', true)->get();
        if ($employees->isEmpty()) return;

        $travels = Travel::all();

        $sampleSurat = [
            [
                'nomor_surat' => 'SPD/001/EPI-OPS/VIII/2026',
                'tanggal_surat' => Carbon::now()->subDays(15),
                'perihal' => 'Surat Tugas Rapat Koordinasi EAM Distribusi PLN UIW Jatim',
                'tujuan' => 'Surabaya - Kantor PLN UIW Jatim',
                'tanggal_berangkat' => Carbon::now()->subDays(10),
                'tanggal_kembali' => Carbon::now()->subDays(8),
                'status' => 'selesai',
                'keterangan' => 'Koordinasi penyusunan roadmap EAM 2026',
            ],
            [
                'nomor_surat' => 'SPD/002/EPI-OPS/VIII/2026',
                'tanggal_surat' => Carbon::now()->subDays(5),
                'perihal' => 'Surat Tugas Pendampingan Audit K3 & Operasional Ketenagakerjaan',
                'tujuan' => 'Semarang - UP3 Semarang',
                'tanggal_berangkat' => Carbon::now()->addDays(2),
                'tanggal_kembali' => Carbon::now()->addDays(5),
                'status' => 'aktif',
                'keterangan' => 'Pendampingan tim auditor eksternal',
            ],
            [
                'nomor_surat' => 'SPD/003/EPI-OPS/VIII/2026',
                'tanggal_surat' => Carbon::now()->subDays(2),
                'perihal' => 'Draft Surat Tugas Monitoring Integrasi System Maximo & SAP',
                'tujuan' => 'Jakarta - Kantor Pusat PLN EPI',
                'tanggal_berangkat' => Carbon::now()->addDays(10),
                'tanggal_kembali' => Carbon::now()->addDays(14),
                'status' => 'draft',
                'keterangan' => 'Menunggu persetujuan VP Operasional',
            ],
        ];

        foreach ($sampleSurat as $data) {
            $travel = $travels->first();
            $assignedEmps = $employees->random(min(rand(1, 3), $employees->count()));

            $surat = SuratDinas::updateOrCreate(
                ['nomor_surat' => $data['nomor_surat']],
                array_merge($data, [
                    'employee_id' => $assignedEmps->first()->id,
                    'travel_id' => $travel ? $travel->id : null,
                ])
            );

            $surat->employees()->sync($assignedEmps->pluck('id'));
        }
    }
}
