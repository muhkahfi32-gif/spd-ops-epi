# SPD OPS EPI & KIT - Sistem Manajemen Perjalanan Dinas

Sistem Informasi Manajemen Perjalanan Dinas (SPD) berbasis web untuk pendataan, pengelolaan, monitoring rencana dan realisasi perjalanan dinas per pegawai dan per layanan aplikasi, rekapitulasi Surat Dinas, serta Pusat Pengingat WhatsApp (WA Reminder Center) secara dinamis, akurat, dan terstruktur.

---

## 🌐 Live Demo & Deployment

- **Website Live**: [https://spd-ops-epi.onrender.com](https://spd-ops-epi.onrender.com)
- **Akun Administrator**:
  - **Email**: `admin@spd.com`
  - **Password**: `password123`
- **Deployment Platform**: Render.com (Docker Container + Nginx + PHP 8.2 + SSL HTTPS)

---

## 🚀 Fitur Utama

- **Pendataan 1 Orang 1 Layanan Aplikasi**:
  - Setiap record perjalanan dinas merepresentasikan **1 Pegawai** dan **1 Layanan Aplikasi** secara spesifik.
  - Pengisian batch untuk beberapa pegawai sekaligus pada 1 aplikasi akan otomatis menghasilkan record perjalanan terpisah secara proporsional.

- **Form Modal 2-Kolom Modern (960px)**:
  - Antarmuka form yang luas dan lapang (2-Column Wide Layout).
  - **Pilih Layanan Aplikasi**: Pencarian cepat dan filter chip interaktif.
  - **Pilih Pegawai (Bulk Select)**: Tombol *Pilih Semua / Batal Pilih* untuk mempercepat input data batch.
  - **Dynamic Summary Card**: Kalkulasi live durasi hari, nominal SPD, dan preview jumlah record yang dibuat.

- **📜 Rekapitulasi & Manajemen Surat Dinas**:
  - Pendataan Surat Dinas / Surat Tugas secara terstruktur (Nomor Surat, Perihal, Tujuan, Pegawai Bertugas, Tanggal Berangkat & Kembali).
  - Terhubung langsung dengan record perjalanan dinas terkait.
  - **Fasilitas Ekspor Data**: Ekspor rekapitulasi Surat Dinas ke format CSV/Excel.

- **📲 WA Reminder Center (Pusat Pengingat WhatsApp)**:
  - Monitoring otomatis tagihan / perjalanan dinas *overdue* (menunggak pencairan/pelaporan) atau mendekati tenggat.
  - **Kirim Pengingat Tunggal & Massal (Send All)**: Pengiriman pesan pengingat WhatsApp ke pegawai secara instan via API Fonnte.
  - **Histori & Audit Log**: Pencatatan riwayat pengiriman reminder (`reminder_logs`) lengkap dengan status, tanggal kirim, dan nomor penerima.

- **🖨️ Cetak Dokumen SPD (Printable Layout)**:
  - Fitur cetak (*print preview*) dokumen Surat Perjalanan Dinas secara langsung dari sistem untuk pertanggungjawaban fisik.

- **Kalkulasi & Akumulasi Nominal SPD**:
  - Kalkulasi otomatis nominal berbasis durasi hari (Rp 30.000 / hari).
  - Event listener real-time pada tanggal mulai & selesai.
  - Management status pencairan & toggle akumulasi biaya perjalanan.

- **📊 Dashboard Monitoring & Grafik Interaktif**:
  - **Monthly Bar Chart**: Grafik batang nominal perjalanan dinas per bulan.
  - **Yearly Line Chart**: Tren pertumbuhan nominal antar tahun.
  - **Top Pegawai Performers**: Ranking pegawai berdasarkan intensitas perjalanan dinas.
  - Stat cards: Total Perjalanan, Pegawai Aktif, Realisasi Anggaran, dan Total Hari.

- **Manajemen Pegawai & Aplikasi**:
  - Pendataan 38+ pegawai aktif, NIP, Jabatan, Email Korporat, No. WhatsApp/HP, dan pemetaan Layanan Aplikasi (EAM Distribusi, Maximo, BBO, MAPP, GIS Korporat, SCADA, Smarter, dll).

- **Laporan & Ekspor Data**:
  - Filter tahunan/bulanan dengan breakdown detail dan ekspor data perjalanan dinas (`/travels/export`) & Surat Dinas (`/surat-dinas/export`).
  - Fitur Hapus Massal (*Bulk Delete*) untuk pengelolaan data secara efisien.

---

## 🛠️ Teknologi & Stack

- **Framework Backend**: Laravel 11.x (PHP 8.2+)
- **Database**: MySQL / SQLite (Database-Agnostic Querying)
- **Containerization & Web Server**: Docker, Nginx, PHP-FPM
- **Frontend / UI**: Laravel Blade, Vanilla CSS (Design Tokens & Utility Classes), Remixicon 4.2
- **Visualisasi Data**: Chart.js 4.x
- **Pengujian**: PHPUnit / Laravel Test Suite (29 test cases / 77 assertions)

---

## 📥 Panduan Instalasi Lokal

1. **Clone & Masuk ke Direktori Project**:
   ```bash
   git clone https://github.com/muhkahfi32-gif/spd-ops-epi.git
   cd spd-ops-epi
   ```

2. **Install Dependensi Composer**:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment (`.env`)**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi Database & Seed Data**:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Seed Data Overdue Testing**:
   ```bash
   php artisan db:seed --class=KahfiElsaOverdueSeeder
   ```

6. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 🔑 Akun Default (Seeder)

- **Email**: `admin@spd.com`
- **Password**: `password123`
- **Total Data Pegawai Awal**: 38 Pegawai Aktif

---

## 🗄️ Skema Database Utama

- **`employees`**: Data pegawai (`id`, `name`, `nip`, `position`, `email`, `email_korporat`, `phone`, `aplikasi`, `is_active`).
- **`travels`**: Dokumen perjalanan dinas (`id`, `aplikasi`, `start_date`, `end_date`, `payment_date`, `amount`, `destination`, `description`, `status`, `is_accumulated`, `last_reminded_at`).
- **`employee_travel`**: Pivot table relasi pegawai dan perjalanan dinas (`employee_id`, `travel_id`).
- **`surat_dinas`**: Rekapitulasi dokumen Surat Dinas / Surat Tugas (`id`, `nomor_surat`, `tanggal_surat`, `perihal`, `tujuan`, `tanggal_berangkat`, `tanggal_kembali`, `status`, `keterangan`).
- **`employee_surat_dinas`**: Pivot table relasi pegawai dan Surat Dinas (`employee_id`, `surat_dinas_id`).
- **`reminder_logs`**: Log pengiriman WhatsApp Reminder (`id`, `travel_id`, `recipient_phone`, `message`, `status`, `sent_at`).

---

## 📄 Lisensi

Sistem Perjalanan Dinas SPD OPS EPI & KIT dikembangkan untuk kebutuhan internal manajemen perjalanan dinas.
