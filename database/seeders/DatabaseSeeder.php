<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========================================
        // CREATE ADMIN USER
        // ========================================
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@spd.com',
            'password' => Hash::make('password123'),
        ]);
        
        // ========================================
        // CREATE 38 EMPLOYEES (sesuai data yang diberikan)
        // ========================================
        $employees = [
            [
                'name' => 'Abdhillah Aji Saputra',
                'nip' => '198001012001011001',
                'position' => 'Staff',
                'email' => 'abdhillah.saputra@iconpln.co.id',
                'email_korporat' => 'abdhillah.saputra@iconpln.co.id',
                'phone' => '081330109721',
                'aplikasi' => 'EAM Distribusi, MAPP',
                'is_active' => true
            ],
            [
                'name' => 'Abdul Rosyi',
                'nip' => '198002022001011002',
                'position' => 'Staff',
                'email' => 'abdul.rosyi@iconpln.co.id',
                'email_korporat' => 'abdul.rosyi@iconpln.co.id',
                'phone' => '08990877111',
                'aplikasi' => 'EAM Distribusi, GIS Korporat',
                'is_active' => true
            ],
            [
                'name' => 'Achmad Umar Faruq',
                'nip' => '198003032001011003',
                'position' => 'Staff',
                'email' => 'achmad.umar@iconpln.co.id',
                'email_korporat' => 'achmad.umar@iconpln.co.id',
                'phone' => '085697500682',
                'aplikasi' => 'BBO',
                'is_active' => true
            ],
            [
                'name' => 'Adi Mulya Abadi',
                'nip' => '198004042001011004',
                'position' => 'Staff',
                'email' => 'adi.abadi@iconpln.co.id',
                'email_korporat' => 'adi.abadi@iconpln.co.id',
                'phone' => '089652059974',
                'aplikasi' => 'EAM Distribusi, Climate Click',
                'is_active' => true
            ],
            [
                'name' => 'Adi Rahadiansyah',
                'nip' => '198005052001011005',
                'position' => 'Staff',
                'email' => 'adi.rahadian@iconpln.co.id',
                'email_korporat' => 'adi.rahadian@iconpln.co.id',
                'phone' => '081572139962',
                'aplikasi' => 'BBO, GIS Korporat',
                'is_active' => true
            ],
            [
                'name' => 'Agung Bayu Wibowo',
                'nip' => '198006062001011006',
                'position' => 'Staff',
                'email' => 'agung.wibowo@iconpln.co.id',
                'email_korporat' => 'agung.wibowo@iconpln.co.id',
                'phone' => '081380250309',
                'aplikasi' => 'BBO',
                'is_active' => true
            ],
            [
                'name' => 'Alvi Nur Amalia',
                'nip' => '198007072001011007',
                'position' => 'Staff',
                'email' => 'alvi.amalia@iconpln.co.id',
                'email_korporat' => 'alvi.amalia@iconpln.co.id',
                'phone' => '083869606985',
                'aplikasi' => 'SCADA',
                'is_active' => true
            ],
            [
                'name' => 'Amirudin Rizal Divianto',
                'nip' => '198008082001011008',
                'position' => 'Staff',
                'email' => 'amirudin.divianto@iconpln.co.id',
                'email_korporat' => 'amirudin.divianto@iconpln.co.id',
                'phone' => '082113132160',
                'aplikasi' => 'EAM Distribusi, MAXICO',
                'is_active' => true
            ],
            [
                'name' => 'Andru Baskara Putra',
                'nip' => '198009092001011009',
                'position' => 'Staff',
                'email' => 'andru.baskara@iconpln.co.id',
                'email_korporat' => 'andru.baskara@iconpln.co.id',
                'phone' => '085173420102',
                'aplikasi' => 'EAM Distribusi',
                'is_active' => true
            ],
            [
                'name' => 'Andry Mulyawan',
                'nip' => '198010102001011010',
                'position' => 'Staff',
                'email' => 'andry.mulyawan@iconpln.co.id',
                'email_korporat' => 'andry.mulyawan@iconpln.co.id',
                'phone' => '081212329331',
                'aplikasi' => 'EAM Pembangkit, BBO, AVMS, Power Inspect',
                'is_active' => true
            ],
            [
                'name' => 'Annisa Eka Mediza',
                'nip' => '198011112001011011',
                'position' => 'Staff',
                'email' => 'annisa.mediza@iconpln.co.id',
                'email_korporat' => 'annisa.mediza@iconpln.co.id',
                'phone' => '089695272398',
                'aplikasi' => 'GIS Korporat, Maxico, EPI Maxico PLN',
                'is_active' => true
            ],
            [
                'name' => 'Arifulloh',
                'nip' => '198012122001011012',
                'position' => 'Staff',
                'email' => 'arifulloh.bambang@iconpln.co.id',
                'email_korporat' => 'arifulloh.bambang@iconpln.co.id',
                'phone' => '0895630441364',
                'aplikasi' => 'BBO',
                'is_active' => true
            ],
            [
                'name' => 'Ario Nugroho',
                'nip' => '198101132001011013',
                'position' => 'Staff',
                'email' => 'ario.nugroho@plnicon.co.id',
                'email_korporat' => 'ario.nugroho@plnicon.co.id',
                'phone' => '082148738071',
                'aplikasi' => 'MAPP, GIS Korporat',
                'is_active' => true
            ],
            [
                'name' => 'Dedy Djunaedi',
                'nip' => '198102142001011014',
                'position' => 'Staff',
                'email' => 'dedi.junaedi@iconpln.co.id',
                'email_korporat' => 'dedi.junaedi@iconpln.co.id',
                'phone' => '081321240960',
                'aplikasi' => 'BBO',
                'is_active' => true
            ],
            [
                'name' => 'Defika Ayu Christanti',
                'nip' => '198103152001011015',
                'position' => 'Staff',
                'email' => 'defika.christanti@iconpln.co.id',
                'email_korporat' => 'defika.christanti@iconpln.co.id',
                'phone' => '081282009224',
                'aplikasi' => 'EAM Pembangkit, EAM Distribusi, Smarter',
                'is_active' => true
            ],
            [
                'name' => 'Diah Nur Yunita',
                'nip' => '198104162001011016',
                'position' => 'Staff',
                'email' => 'diah.yunita@iconpln.co.id',
                'email_korporat' => 'diah.yunita@iconpln.co.id',
                'phone' => '081326492612',
                'aplikasi' => 'EAM Pembangkit, BBO, MAPP',
                'is_active' => true
            ],
            [
                'name' => 'Dwi Sulistiyowati',
                'nip' => '198105172001011017',
                'position' => 'Staff',
                'email' => 'dwi.sulistyowati@iconpln.co.id',
                'email_korporat' => 'dwi.sulistyowati@iconpln.co.id',
                'phone' => '081213545514',
                'aplikasi' => 'EAM Distribusi',
                'is_active' => true
            ],
            [
                'name' => 'Dwiky Putra Hardiawan',
                'nip' => '198106182001011018',
                'position' => 'Staff',
                'email' => 'dwiky.hardiawan@iconpln.co.id',
                'email_korporat' => 'dwiky.hardiawan@iconpln.co.id',
                'phone' => '085700840786',
                'aplikasi' => 'EAM Distribusi, GIS Korporat',
                'is_active' => true
            ],
            [
                'name' => 'Eko Priyono',
                'nip' => '198107192001011019',
                'position' => 'Staff',
                'email' => 'eko.priyono@iconpln.co.id',
                'email_korporat' => 'eko.priyono@iconpln.co.id',
                'phone' => '087848218423',
                'aplikasi' => 'EAM Distribusi, MAPP',
                'is_active' => true
            ],
            [
                'name' => 'Elsa Erianti',
                'nip' => '198108202001011020',
                'position' => 'Staff',
                'email' => 'elsa.erianti@iconpln.co.id',
                'email_korporat' => 'elsa.erianti@iconpln.co.id',
                'phone' => '082111414854',
                'aplikasi' => 'GBMO, BAg',
                'is_active' => true
            ],
            [
                'name' => 'Fathan Zuffar Alghifary',
                'nip' => '198109212001011021',
                'position' => 'Staff',
                'email' => 'fathan.zuffar@iconpln.co.id',
                'email_korporat' => 'fathan.zuffar@iconpln.co.id',
                'phone' => '081280557600',
                'aplikasi' => 'Smarter',
                'is_active' => true
            ],
            [
                'name' => 'Julia Mega Krismon',
                'nip' => '198110222001011022',
                'position' => 'Staff',
                'email' => 'julia.krismon@iconpln.co.id',
                'email_korporat' => 'julia.krismon@iconpln.co.id',
                'phone' => '08161923610',
                'aplikasi' => 'BBO, E-Bid Doc',
                'is_active' => true
            ],
            [
                'name' => 'Khairunnas',
                'nip' => '198111232001011023',
                'position' => 'Staff',
                'email' => 'khairunnas.hasanuddin@iconpln.co.id',
                'email_korporat' => 'khairunnas.hasanuddin@iconpln.co.id',
                'phone' => '085270278229',
                'aplikasi' => 'MAPP',
                'is_active' => true
            ],
            [
                'name' => 'Kurniawan Budianto',
                'nip' => '198112242001011024',
                'position' => 'Staff',
                'email' => 'kurniawan.budianto@iconpln.co.id',
                'email_korporat' => 'kurniawan.budianto@iconpln.co.id',
                'phone' => '089626671455',
                'aplikasi' => 'BBO',
                'is_active' => true
            ],
            [
                'name' => 'Lintang Choirunesa',
                'nip' => '198201252001011025',
                'position' => 'Staff',
                'email' => 'lintang.choirunesa@iconpln.co.id',
                'email_korporat' => 'lintang.choirunesa@iconpln.co.id',
                'phone' => '081329492393',
                'aplikasi' => 'Gaspro',
                'is_active' => true
            ],
            [
                'name' => 'Mochammad Nouval Saputra',
                'nip' => '198202262001011026',
                'position' => 'Staff',
                'email' => 'mochammad.nouval@iconpln.co.id',
                'email_korporat' => 'mochammad.nouval@iconpln.co.id',
                'phone' => '0895396001362',
                'aplikasi' => 'BBO',
                'is_active' => true
            ],
            [
                'name' => 'Mohammad Riyan Al Farisi',
                'nip' => '198203272001011027',
                'position' => 'Staff',
                'email' => 'muhammad.farizi@iconpln.co.id',
                'email_korporat' => 'muhammad.farizi@iconpln.co.id',
                'phone' => '08978223886',
                'aplikasi' => 'GBMO, EAM Distribusi, Valiant, BBO',
                'is_active' => true
            ],
            [
                'name' => 'Muhammad Ainul Yaqien',
                'nip' => '198204282001011028',
                'position' => 'Staff',
                'email' => 'muhammad.yaqien@iconpln.co.id',
                'email_korporat' => 'muhammad.yaqien@iconpln.co.id',
                'phone' => '085779044979',
                'aplikasi' => 'EAM Pembangkit, BBO, Smarter',
                'is_active' => true
            ],
            [
                'name' => 'Muhammad Ekki Hartono',
                'nip' => '198205292001011029',
                'position' => 'Staff',
                'email' => 'muhammad.hartono@iconpln.co.id',
                'email_korporat' => 'muhammad.hartono@iconpln.co.id',
                'phone' => '08885378568',
                'aplikasi' => 'MAPP, AVMS',
                'is_active' => true
            ],
            [
                'name' => 'Muhammad Kahfi',
                'nip' => '198206302001011030',
                'position' => 'Staff',
                'email' => 'muhammad.kahfi@iconpln.co.id',
                'email_korporat' => 'muhammad.kahfi@iconpln.co.id',
                'phone' => '082111318263',
                'aplikasi' => 'EAM Distribusi, AVMS',
                'is_active' => true
            ],
            [
                'name' => 'Muhammad Radifa Putra Suwari',
                'nip' => '198207312001011031',
                'position' => 'Staff',
                'email' => 'muhammad.suwari@iconpln.co.id',
                'email_korporat' => 'muhammad.suwari@iconpln.co.id',
                'phone' => '081388434924',
                'aplikasi' => 'Smarter',
                'is_active' => true
            ],
            [
                'name' => 'Novemi Tobi Fahrudin',
                'nip' => '198208012001011032',
                'position' => 'Staff',
                'email' => 'novemi.fahrudin@iconpln.co.id',
                'email_korporat' => 'novemi.fahrudin@iconpln.co.id',
                'phone' => '087782919155',
                'aplikasi' => 'MAPP, E-Bid Doc, Maxico',
                'is_active' => true
            ],
            [
                'name' => 'Rachmawati Dzakiy Malikah',
                'nip' => '198209022001011033',
                'position' => 'Staff',
                'email' => 'rachmawati.malikah@iconpln.co.id',
                'email_korporat' => 'rachmawati.malikah@iconpln.co.id',
                'phone' => '08111159822',
                'aplikasi' => 'EAM Distribusi, MAXICO, Gaspro',
                'is_active' => true
            ],
            [
                'name' => 'Rifki Rahman Syaiful',
                'nip' => '198210032001011034',
                'position' => 'Staff',
                'email' => 'rifki.syaiful@iconpln.co.id',
                'email_korporat' => 'rifki.syaiful@iconpln.co.id',
                'phone' => '0811172071',
                'aplikasi' => 'Gaspro',
                'is_active' => true
            ],
            [
                'name' => 'Riska Fitriani',
                'nip' => '198211042001011035',
                'position' => 'Staff',
                'email' => 'riska.fitriani@iconpln.co.id',
                'email_korporat' => 'riska.fitriani@iconpln.co.id',
                'phone' => '081703533144',
                'aplikasi' => 'BBO',
                'is_active' => true
            ],
            [
                'name' => 'Sri Devi Nurhayati',
                'nip' => '198212052001011036',
                'position' => 'Staff',
                'email' => 'sri.nurhayati@iconpln.co.id',
                'email_korporat' => 'sri.nurhayati@iconpln.co.id',
                'phone' => '085747041193',
                'aplikasi' => 'BBO, GBMO',
                'is_active' => true
            ],
            [
                'name' => 'Viderawi Purnama Ade',
                'nip' => '198301062001011037',
                'position' => 'Staff',
                'email' => 'vide.purnama@iconpln.co.id',
                'email_korporat' => 'vide.purnama@iconpln.co.id',
                'phone' => '081380179974',
                'aplikasi' => 'EAM Pembangkit, BBO, Climate Click',
                'is_active' => true
            ],
            [
                'name' => 'Vita Sovia Hadianti',
                'nip' => '198302072001011038',
                'position' => 'Staff',
                'email' => 'vita.sovia@iconpln.co.id',
                'email_korporat' => 'vita.sovia@iconpln.co.id',
                'phone' => '085642504373',
                'aplikasi' => 'EAM Pembangkit, BAg',
                'is_active' => true
            ],
        ];
        
        // Insert all employees
        foreach ($employees as $emp) {
            Employee::create($emp);
        }
        
        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📊 Admin User: admin@spd.com / password123');
        $this->command->info('📊 Total Employees: ' . count($employees));
    }
}