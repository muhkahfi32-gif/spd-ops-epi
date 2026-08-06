<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Cek Status SPD - SPD OPS EPI & KIT</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .portal-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            padding: 2.5rem 1rem;
            text-align: center;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.15);
        }
        .portal-container {
            max-width: 1000px;
            margin: -2rem auto 3rem auto;
            padding: 0 1rem;
            width: 100%;
        }
        .search-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-pending { background-color: #fef3c7; color: #d97706; }
        .badge-overdue { background-color: #fee2e2; color: #dc2626; }
        .badge-paid { background-color: #dcfce7; color: #15803d; }
        .btn-print {
            background-color: #0284c7;
            color: #ffffff;
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            font-size: 0.813rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-print:hover {
            background-color: #0369a1;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.3);
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="portal-header">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex justify-center items-center gap-3 mb-2">
                <i class="ri-search-eye-line text-3xl text-sky-400"></i>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Portal Lacak Status SPD</h1>
            </div>
            <p class="text-slate-300 text-sm sm:text-base max-w-xl mx-auto">
                Cek status pertanggungjawaban & pencairan Perjalanan Dinas (SPD) secara mandiri hanya dengan memasukkan NIP Pegawai.
            </p>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="portal-container">
        
        <!-- Form Search NIP -->
        <div class="search-card mb-6">
            <form action="{{ route('cek-spd.search') }}" method="GET" class="space-y-4">
                <label for="nip" class="block text-sm font-semibold text-slate-700">
                    Masukkan NIP (Nomor Induk Pegawai):
                </label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="ri-id-card-line text-lg"></i>
                        </div>
                        <input type="text" name="nip" id="nip" value="{{ old('nip', $nip ?? '') }}" 
                            placeholder="Contoh: 198206302001011030" required
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                    </div>
                    <button type="submit" 
                        class="px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl shadow-lg shadow-sky-600/20 transition flex items-center justify-center gap-2">
                        <i class="ri-search-line"></i>
                        <span>Cek Status SPD</span>
                    </button>
                </div>
            </form>

            @if(session('error'))
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm flex items-center gap-2">
                    <i class="ri-error-warning-line text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        @if(isset($employee))
            <!-- Employee Profile Summary -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm mb-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-sky-100 text-sky-700 rounded-full flex items-center justify-center font-bold text-xl">
                            {{ strtoupper(substr($employee->name, 0, 2)) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ $employee->name }}</h2>
                            <p class="text-sm text-slate-500 font-mono">NIP: {{ $employee->nip }} &bull; {{ $employee->position }}</p>
                            <p class="text-xs text-sky-600 font-medium mt-0.5"><i class="ri-apps-line"></i> Layanan: {{ $employee->aplikasi ?? 'Umum' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold border border-emerald-200">
                            <i class="ri-checkbox-circle-line"></i> Pegawai Aktif
                        </span>
                    </div>
                </div>

                <!-- Quick Stats Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4">
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <p class="text-xs font-medium text-slate-500">Total SPD</p>
                        <p class="text-lg font-bold text-slate-900 mt-0.5">{{ $stats['total_spd'] }} Records</p>
                    </div>
                    <div class="bg-amber-50 p-3.5 rounded-xl border border-amber-100">
                        <p class="text-xs font-medium text-amber-700">Belum Dibayar</p>
                        <p class="text-lg font-bold text-amber-800 mt-0.5">{{ $stats['pending'] }} SPD</p>
                    </div>
                    <div class="bg-emerald-50 p-3.5 rounded-xl border border-emerald-100">
                        <p class="text-xs font-medium text-emerald-700">Sudah Lunas</p>
                        <p class="text-lg font-bold text-emerald-800 mt-0.5">{{ $stats['paid'] }} SPD</p>
                    </div>
                    <div class="bg-sky-50 p-3.5 rounded-xl border border-sky-100">
                        <p class="text-xs font-medium text-sky-700">Total Nominal SPD</p>
                        <p class="text-lg font-bold text-sky-900 mt-0.5">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- List Perjalanan Dinas -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <div class="p-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <i class="ri-flight-takeoff-line text-sky-600"></i>
                        <span>Daftar Perjalanan Dinas (SPD)</span>
                    </h3>
                    <span class="text-xs font-semibold bg-slate-200 text-slate-700 px-2.5 py-1 rounded-full">
                        View-Only Mode
                    </span>
                </div>

                @if($travels->isEmpty())
                    <div class="p-8 text-center text-slate-500">
                        <i class="ri-inbox-archive-line text-4xl text-slate-300 block mb-2"></i>
                        <p class="font-medium">Belum ada riwayat perjalanan dinas untuk NIP ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-700">
                            <thead class="bg-slate-100 text-slate-600 uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="p-4">Tanggal & Tujuan</th>
                                    <th class="p-4">Aplikasi & Deskripsi</th>
                                    <th class="p-4">Nominal</th>
                                    <th class="p-4">Status Pencairan</th>
                                    <th class="p-4 text-center">Aksi Dokumen</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-normal">
                                @foreach($travels as $travel)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="p-4">
                                            <div class="font-semibold text-slate-900">{{ $travel->destination }}</div>
                                            <div class="text-xs text-slate-500 mt-0.5">
                                                <i class="ri-calendar-line"></i> 
                                                {{ $travel->start_date ? $travel->start_date->format('d M Y') : '-' }} s/d {{ $travel->end_date ? $travel->end_date->format('d M Y') : '-' }}
                                                ({{ $travel->duration }} Hari)
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="inline-block px-2.5 py-0.5 bg-slate-100 text-slate-800 rounded font-semibold text-xs mb-1">
                                                {{ $travel->aplikasi ?? 'Umum' }}
                                            </span>
                                            <p class="text-xs text-slate-600 line-clamp-1">{{ $travel->description }}</p>
                                        </td>
                                        <td class="p-4 font-bold text-slate-900">
                                            Rp {{ number_format($travel->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4">
                                            @if($travel->status === 'paid')
                                                <span class="status-badge badge-paid">
                                                    <i class="ri-checkbox-circle-line"></i> Lunas / Dibayar
                                                </span>
                                            @elseif($travel->is_overdue)
                                                <span class="status-badge badge-overdue">
                                                    <i class="ri-alarm-warning-line"></i> Overdue (+{{ $travel->overdue_days }} Hari)
                                                </span>
                                            @else
                                                <span class="status-badge badge-pending">
                                                    <i class="ri-time-line"></i> Menunggu Pencairan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-center">
                                            <a href="{{ route('travels.print', $travel) }}" target="_blank" class="btn-print">
                                                <i class="ri-printer-line"></i>
                                                <span>Cetak SPD</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- List Surat Dinas -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <i class="ri-file-text-line text-emerald-600"></i>
                        <span>Rekap Surat Dinas / Surat Tugas</span>
                    </h3>
                </div>

                @if($suratDinasList->isEmpty())
                    <div class="p-8 text-center text-slate-500">
                        <p class="font-medium">Belum ada riwayat Surat Dinas untuk NIP ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-700">
                            <thead class="bg-slate-100 text-slate-600 uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="p-4">Nomor & Tanggal Surat</th>
                                    <th class="p-4">Perihal & Tujuan</th>
                                    <th class="p-4">Tanggal Pelaksanaan</th>
                                    <th class="p-4 text-center">Status Surat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($suratDinasList as $surat)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="p-4">
                                            <div class="font-bold text-slate-900 font-mono">{{ $surat->nomor_surat }}</div>
                                            <div class="text-xs text-slate-500 mt-0.5">
                                                Tanggal Surat: {{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d M Y') : '-' }}
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="font-semibold text-slate-900">{{ $surat->perihal }}</div>
                                            <div class="text-xs text-slate-500 mt-0.5"><i class="ri-map-pin-line"></i> {{ $surat->tujuan }}</div>
                                        </td>
                                        <td class="p-4 text-xs font-medium text-slate-700">
                                            {{ $surat->tanggal_berangkat ? $surat->tanggal_berangkat->format('d M Y') : '-' }} s/d {{ $surat->tanggal_kembali ? $surat->tanggal_kembali->format('d M Y') : '-' }}
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                                {{ $surat->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($surat->status === 'aktif' ? 'bg-sky-100 text-sky-800' : 'bg-slate-100 text-slate-700') }}">
                                                {{ $surat->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        @endif

        <div class="mt-8 text-center text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} Sistem Informasi SPD OPS EPI & KIT &bull; Khusus Portal Cek Status Pegawai Mandiri</p>
            @auth
                <p class="mt-1"><a href="{{ route('dashboard') }}" class="text-sky-600 hover:underline">Kembali ke Dashboard Admin</a></p>
            @endauth
        </div>

    </main>

</body>
</html>
