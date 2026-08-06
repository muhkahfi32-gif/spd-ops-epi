@extends('layouts.app')

@section('page-title', 'Perjalanan Dinas')
@section('content')

<style>
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --primary-light: #e0e7ff;
        --success: #10b981;
        --success-light: #dcfce7;
        --warning: #f59e0b;
        --warning-light: #fef3c7;
        --danger: #ef4444;
        --danger-light: #fee2e2;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-500: #64748b;
        --gray-700: #334155;
        --gray-900: #0f172a;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .fade-in { animation: fadeIn 0.3s ease-out; }
    .spin { animation: spin 1s linear infinite; display: inline-block; }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(79,70,229,0.3); }
    .btn-secondary {
        background: var(--gray-100);
        color: var(--gray-700);
        border: 1px solid var(--gray-200);
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 12px;
        cursor: pointer;
    }
    .btn-secondary:hover { background: var(--gray-200); }
    .btn-outline {
        background: transparent;
        border: 1px solid var(--gray-200);
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-outline:hover { background: var(--gray-50); border-color: var(--primary); }
    
    .table-container {
        background: white;
        border-radius: 14px;
        border: 1px solid var(--gray-200);
        overflow-x: auto;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        min-width: 1200px;
    }
    .data-table th {
        background: var(--gray-50);
        padding: 10px 14px;
        text-align: left;
        font-weight: 700;
        color: var(--gray-500);
        border-bottom: 1px solid var(--gray-200);
        text-transform: uppercase;
        font-size: 10.5px;
        letter-spacing: 0.4px;
    }
    .data-table td {
        padding: 9px 14px;
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
        color: var(--gray-700);
    }
    .data-table tr:hover td { background: rgba(79, 70, 229, 0.03); }
    
    .employee-tag {
        background: var(--primary-light);
        color: var(--primary);
        padding: 3px 10px;
        border-radius: 14px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        margin: 1px;
    }
    .aplikasi-tag {
        background: var(--success-light);
        color: #166534;
        padding: 3px 8px;
        border-radius: 14px;
        font-size: 10.5px;
        font-weight: 600;
        display: inline-block;
        margin: 1px;
    }
    
    .badge-paid {
        background: var(--success-light);
        color: #166534;
        padding: 4px 10px;
        border-radius: 14px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-pending {
        background: var(--warning-light);
        color: #92400e;
        padding: 4px 10px;
        border-radius: 14px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-surat {
        background: var(--primary-light);
        color: var(--primary-dark);
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 10.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        border: 1px solid rgba(79, 70, 229, 0.2);
    }
    
    .action-btn {
        padding: 4px;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        margin: 0 1px;
        transition: all 0.2s;
    }
    .action-btn.edit { color: var(--primary); }
    .action-btn.edit:hover { background: var(--primary-light); }
    .action-btn.delete { color: var(--danger); }
    .action-btn.delete:hover { background: var(--danger-light); }
    
    .filter-bar {
        background: white;
        border-radius: 14px;
        padding: 10px 16px;
        margin-bottom: 18px;
        border: 1px solid var(--gray-200);
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    .search-input {
        flex: 1;
        position: relative;
    }
    .search-input i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-500);
        font-size: 14px;
    }
    .search-input input {
        width: 100%;
        padding: 7px 12px 7px 36px;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        font-size: 12px;
        outline: none;
    }
    .search-input input:focus { border-color: var(--primary); box-shadow: 0 0 0 2px rgba(79,70,229,0.1); }
    .year-select {
        padding: 7px 12px;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        background: white;
        cursor: pointer;
        font-size: 12px;
    }
    
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
        z-index: 2000;
    }
    .modal-overlay.active { display: flex; }
    .modal-container {
        background: white;
        border-radius: 16px;
        max-width: 920px;
        width: 95vw;
        max-height: 88vh;
        overflow-y: auto;
        overflow-x: hidden !important;
        animation: fadeIn 0.25s ease-out;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.12);
    }
    .modal-header {
        padding: 14px 20px;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
    }
    .modal-header h3 { font-size: 16px; font-weight: 700; color: var(--gray-900); }
    .modal-close {
        background: var(--gray-100);
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-size: 16px;
        cursor: pointer;
        color: var(--gray-500);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .modal-close:hover { background: var(--danger-light); color: var(--danger); }
    .modal-body { padding: 18px 20px; overflow-x: hidden !important; }
    
    .form-group { margin-bottom: 12px; min-width: 0; }
    .form-label { font-size: 12px; font-weight: 600; margin-bottom: 4px; display: block; color: var(--gray-700); }
    .form-input {
        width: 100%;
        box-sizing: border-box;
        padding: 7px 11px;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        font-size: 12px;
        outline: none;
        transition: all 0.2s;
        min-width: 0;
    }
    .form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 2px rgba(79,70,229,0.1); }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; min-width: 0; }
    
    .modal-grid-2col {
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 18px;
        min-width: 0;
    }
    
    /* Aplikasi Grid */
    .aplikasi-grid {
        border: 1px solid var(--gray-200);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 16px;
        background: white;
    }
    .aplikasi-search {
        padding: 10px 12px;
        background: white;
        border-bottom: 1px solid var(--gray-200);
    }
    .aplikasi-search input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--gray-200);
        border-radius: 10px;
        font-size: 12px;
        outline: none;
    }
    .aplikasi-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 12px;
        max-height: 170px;
        overflow-y: auto;
    }
    .aplikasi-chip {
        background: var(--gray-100);
        color: var(--gray-700);
        border: 1px solid var(--gray-200);
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .aplikasi-chip:hover {
        background: var(--primary-light);
        border-color: var(--primary);
    }
    .aplikasi-chip.active {
        background: linear-gradient(135deg, var(--primary), #7c3aed);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    
    .employee-list-container {
        border: 1px solid var(--gray-200);
        border-radius: 16px;
        overflow: hidden;
        margin-top: 8px;
    }
    .employee-search {
        padding: 10px 12px;
        background: white;
        border-bottom: 1px solid var(--gray-200);
    }
    .employee-search input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--gray-200);
        border-radius: 10px;
        font-size: 12px;
        outline: none;
    }
    .employee-items {
        max-height: 230px;
        overflow-y: auto;
        padding: 8px;
    }
    .employee-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 12px;
        cursor: pointer;
        border-radius: 12px;
        transition: all 0.2s;
    }
    .employee-item:hover { background: var(--primary-light); }
    .employee-item input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary);
    }
    .employee-item label {
        flex: 1;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        color: var(--gray-900);
    }
    .employee-item small {
        font-size: 10px;
        color: var(--gray-500);
        display: block;
    }
    
    @media (max-width: 768px) {
        .modal-container { max-width: 95%; }
        .form-grid { grid-template-columns: 1fr; }
        .stat-value { font-size: 24px; }
        .filter-bar { flex-direction: column; }
        .search-input { width: 100%; }
        .year-select { width: 100%; }
    }
</style>

<div class="fade-in-up">
    <!-- Filter & Action Bar -->
    <div class="filter-bar">
        <div class="filter-group">
            <i class="ri-filter-3-line" style="color: var(--primary-500); font-size: 18px;"></i>
            <span>Filter:</span>
            <select id="yearFilter" class="filter-select" onchange="autoFilter()">
                <option value="">Semua Tahun</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                @endforeach
            </select>
            <input type="text" id="searchInput" class="filter-input" placeholder="Cari nama pegawai, aplikasi, atau tujuan..." value="{{ $search }}" onkeyup="if(event.key==='Enter') autoFilter()">
            <button type="button" onclick="autoFilter()" class="btn-outline" style="padding: 6px 14px;"><i class="ri-search-line"></i> Cari</button>
            <button type="button" onclick="resetFilter()" class="filter-reset">Reset</button>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="openAddModal()" class="btn-primary-grad">
                <i class="ri-add-line"></i> Tambah Perjalanan Dinas
            </button>
        </div>
    </div>
    
    <!-- Stats Cards Grid -->
    <div class="stats-grid">
        <div class="stat-card indigo">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--primary-50); color: var(--primary-600);"><i class="ri-file-copy-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $statistics['total_data'] }}</div>
                        <div class="stat-label">Total Perjalanan</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--primary-100); color: var(--primary-800);">Total</span>
            </div>
        </div>

        <div class="stat-card cyan">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--cyan-50); color: var(--cyan-500);"><i class="ri-user-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $statistics['total_employees'] }}</div>
                        <div class="stat-label">Pegawai Aktif</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: #cffafe; color: #0e7490;">Aktif</span>
            </div>
        </div>

        <div class="stat-card amber">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--amber-50); color: var(--amber-500);"><i class="ri-money-dollar-circle-line"></i></div>
                    <div>
                        <div class="stat-value" style="font-size: 16px;">Rp {{ number_format($statistics['total_nominal'], 0, ',', '.') }}</div>
                        <div class="stat-label">Total Nominal</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--amber-100); color: var(--amber-700);">Nominal</span>
            </div>
        </div>

        <div class="stat-card emerald">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--emerald-50); color: var(--emerald-500);"><i class="ri-calendar-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $statistics['total_days'] }} Hari</div>
                        <div class="stat-label">Total Hari</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--emerald-100); color: var(--emerald-700);">Durasi</span>
            </div>
        </div>
    </div>
    
    <!-- Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 45px;">No</th>
                    <th style="width: 220px;">Pegawai & Aplikasi</th>
                    <th style="width: 220px;">Surat Dinas & Tujuan</th>
                    <th style="width: 180px;">Periode & Durasi</th>
                    <th style="width: 130px;">Nominal SPD</th>
                    <th style="width: 110px;">Status</th>
                    <th style="width: 150px; white-space: nowrap; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($travels as $index => $travel)
                @php
                    $aplikasiList = $travel->aplikasi_list ?? [];
                @endphp
                <tr>
                    <td>{{ $index + 1 + ($travels->currentPage() - 1) * $travels->perPage() }}</td>
                    <td>
                        <div style="font-weight: 700; color: var(--gray-900);">
                            @foreach($travel->employees as $emp)
                                <span class="employee-tag" title="{{ $emp->aplikasi ?? '' }}">{{ $emp->name }}</span>
                            @endforeach
                        </div>
                        <div style="margin-top: 3px;">
                            @foreach($aplikasiList as $app)
                                <span class="aplikasi-tag" style="font-size: 10px; padding: 2px 7px;">📱 {{ $app }}</span>
                            @endforeach
                            @if(empty($aplikasiList)) <span style="color: #94a3b8; font-size: 11px;">-</span> @endif
                        </div>
                    </td>
                    <td>
                        @if($travel->suratDinas)
                            <div style="margin-bottom: 2px;">
                                <span class="badge-surat" title="{{ $travel->suratDinas->perihal }}"><i class="ri-file-paper-2-line"></i> {{ $travel->suratDinas->nomor_surat }}</span>
                            </div>
                        @endif
                        <div style="font-size: 12px; font-weight: 600; color: var(--gray-800);">
                            <i class="ri-map-pin-line" style="color: var(--primary);"></i> {{ $travel->destination }}
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 11.5px; font-weight: 600; color: var(--gray-800);">
                            {{ $travel->start_date->format('d M Y') }} s/d {{ $travel->end_date->format('d M Y') }}
                        </div>
                        <div style="font-size: 11px; font-weight: 700; color: var(--primary); margin-top: 2px;">
                            ✨ {{ $travel->duration }} Hari
                        </div>
                    </td>
                    <td>
                        <strong style="font-size: 13px; color: var(--gray-900);">Rp {{ number_format($travel->amount, 0, ',', '.') }}</strong>
                    </td>
                    <td>
                        @if($travel->status == 'paid')
                            <button type="button" onclick="toggleTravelStatus({{ $travel->id }}, 'pending')" class="badge-paid" style="border:none; cursor:pointer;" title="Klik untuk ubah ke Pending">
                                <i class="ri-checkbox-circle-line"></i> Lunas
                            </button>
                        @else
                            @php
                                $isOverdueWA = $travel->end_date && \Carbon\Carbon::parse($travel->end_date)->diffInDays(\Carbon\Carbon::today(), false) >= 7;
                            @endphp
                            @if($isOverdueWA)
                                <button type="button" onclick="toggleTravelStatus({{ $travel->id }}, 'paid')" class="badge-overdue" style="border:none; cursor:pointer;" title="Klik untuk tandai LUNAS">
                                    <i class="ri-whatsapp-line"></i> H+7 Overdue
                                </button>
                            @else
                                <button type="button" onclick="toggleTravelStatus({{ $travel->id }}, 'paid')" class="badge-pending" style="border:none; cursor:pointer;" title="Klik untuk tandai LUNAS">
                                    <i class="ri-time-line"></i> Pending
                                </button>
                            @endif
                        @endif
                    </td>
                    <td style="white-space: nowrap; text-align: right;">
                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 6px; white-space: nowrap;">
                            <button type="button" onclick="openTravelDetail({{ $travel->id }})" class="btn-outline" style="padding: 5px 9px; font-size: 11.5px; background: #f0fdf4; color: #15803d; border-color: #bbf7d0;">
                                <i class="ri-eye-line"></i> Detail
                            </button>
                            <button type="button" onclick="editTravel({{ $travel->id }})" class="btn-outline" style="padding: 5px 9px; font-size: 11.5px;">
                                <i class="ri-edit-line"></i> Edit
                            </button>
                            <button type="button" onclick="deleteTravel({{ $travel->id }})" class="btn-danger" style="padding: 5px 9px; font-size: 11.5px;">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="padding: 60px; text-align: center; color: var(--gray-500);">
                        <i class="ri-inbox-line" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
                        Belum ada data perjalanan dinas
                        <br>
                        <button onclick="openAddModal()" class="btn-primary" style="margin-top: 16px; padding: 8px 20px; font-size: 13px;">
                            <i class="ri-add-line"></i> Buat Perjalanan Dinas Pertama
                        </button>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(isset($travels) && method_exists($travels, 'links'))
        <div style="margin-top: 20px;">{{ $travels->appends(request()->query())->links() }}</div>
    @endif
</div>

<!-- Modal Add/Edit Travel -->
<div id="travelModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="modalTitle">Tambah Perjalanan</h3>
            <button onclick="closeModal()" class="modal-close">&times;</button>
        </div>
        <form id="travelForm" class="modal-body">
            @csrf
            <input type="hidden" name="_method" id="methodField" value="POST">
            <input type="hidden" name="id" id="travelId">
            <input type="hidden" name="aplikasi" id="aplikasiInput">
            
            <div class="modal-grid-2col">
                <!-- LEFT COLUMN: Layanan Aplikasi & Pegawai Ditunjuk -->
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="background: var(--gray-50); padding: 18px; border-radius: 18px; border: 1px solid var(--gray-200);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
                            <label class="form-label" style="margin: 0; font-size: 14px; color: var(--gray-900); font-weight: 700;">
                                👥 Pegawai Ditunjuk
                                <span id="empCountBadge" style="font-size: 11px; font-weight: 700; color: var(--primary); background: var(--primary-light); padding: 3px 10px; border-radius: 12px; margin-left: 6px;">(0 Terpilih)</span>
                            </label>
                            
                            <!-- Action Buttons: Pilih Semua & Bersihkan -->
                            <div style="display: flex; gap: 6px;">
                                <button type="button" onclick="selectAllTravelEmployees()" class="btn-outline" style="padding: 4px 10px; font-size: 11.5px; background: white; font-weight: 600;">
                                    <i class="ri-checkbox-multiple-line" style="color: var(--primary);"></i> Pilih Semua
                                </button>
                                <button type="button" onclick="clearAllTravelEmployees()" class="btn-outline" style="padding: 4px 10px; font-size: 11.5px; color: var(--danger); border-color: var(--danger-light); background: white; font-weight: 600;">
                                    <i class="ri-close-circle-line"></i> Bersihkan
                                </button>
                            </div>
                        </div>

                        <!-- Step 1: Dropdown Pilih Nama Aplikasi -->
                        <div style="margin-bottom: 10px;">
                            <select id="travelAppFilterSelect" class="form-input" onchange="filterTravelEmployeesByApp(this.value)" style="font-weight: 700; background: white; border-color: var(--primary); color: var(--gray-900);">
                                <option value="ALL">-- Pilih Aplikasi (Tampilkan Semua Pegawai) --</option>
                                @foreach($employeesByAplikasi as $appName => $empList)
                                    <option value="{{ $appName }}">📱 Aplikasi: {{ $appName }} ({{ count($empList) }} Pegawai)</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Step 2: Employee List Container Grouped by Application -->
                        <div id="travelEmployeeContainer" style="max-height: 280px; overflow-y: auto; border: 1px solid var(--gray-200); border-radius: 14px; padding: 10px; background: white;">
                            @foreach($employeesByAplikasi as $appName => $empList)
                                <div class="travel-app-group-section" data-appname="{{ strtolower($appName) }}" style="margin-bottom: 12px;">
                                    <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: var(--primary); background: var(--primary-light); padding: 5px 10px; border-radius: 6px; margin-bottom: 6px; display: flex; align-items: center; justify-content: space-between; border-left: 3px solid var(--primary);">
                                        <span><i class="ri-apps-line"></i> Aplikasi: {{ $appName }}</span>
                                        <span style="font-size: 10.5px; opacity: 0.85; font-weight: 600;">{{ count($empList) }} Pegawai</span>
                                    </div>
                                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px; padding-left: 4px;">
                                        @foreach($empList as $emp)
                                            <label style="display: flex; align-items: center; gap: 8px; padding: 6px 8px; border-radius: 6px; cursor: pointer; background: var(--gray-50); border: 1px solid var(--gray-200); font-size: 12.5px; transition: all 0.15s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--gray-200)'">
                                                <input type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" class="travel-emp-checkbox travel-emp-cb-{{ $emp->id }}" onchange="syncTravelEmpCheckbox({{ $emp->id }}, this.checked)" style="width: 15px; height: 15px; accent-color: var(--primary); cursor: pointer;">
                                                <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    <div style="font-weight: 700; color: var(--gray-900);">{{ $emp->name }}</div>
                                                    <div style="font-size: 10.5px; color: var(--gray-500);">NIP: {{ $emp->nip ?? '-' }}</div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div style="font-size: 11.5px; color: var(--gray-500); margin-top: 6px;"><i class="ri-information-line"></i> Pilih aplikasi untuk menyaring daftar pegawai, gunakan <b>Pilih Semua</b> atau <b>Bersihkan</b>.</div>
                    </div>
                </div>
                
                <!-- RIGHT COLUMN: Detail Perjalanan & Dynamic Nominal Card -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="background: var(--gray-50); padding: 20px; border-radius: 18px; border: 1px solid var(--gray-200); display: flex; flex-direction: column; gap: 16px;">
                        <h4 style="font-size: 15px; font-weight: 700; color: var(--gray-900); margin: 0; border-bottom: 1px solid var(--gray-200); padding-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
                            <span>📝 Detail Perjalanan Dinas</span>
                            <span id="durationBadge" style="font-size: 11px; background: var(--primary-light); color: var(--primary); padding: 4px 12px; border-radius: 20px; font-weight: 700;">✨ 1 Hari</span>
                        </h4>

                        <!-- Surat Dinas Selection -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                                <span>📜 Nomor Surat Dinas / Surat Tugas</span>
                                <span style="font-size: 11px; font-weight: 400; color: var(--gray-500);">(Pilih atau buat baru)</span>
                            </label>
                            <select name="surat_dinas_id" id="surat_dinas_id" class="form-input" onchange="toggleNewSuratDinasFields(this.value)">
                                <option value="">-- Tanpa Surat Dinas / Buat Surat Dinas Baru --</option>
                                @foreach($suratDinasList as $sd)
                                    <option value="{{ $sd->id }}" data-perihal="{{ $sd->perihal }}">{{ $sd->nomor_surat }} ({{ Str::limit($sd->perihal, 32) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Fields for New Surat Dinas -->
                        <div id="newSuratDinasContainer" style="display: none; background: #e0e7ff; padding: 12px 14px; border-radius: 12px; border: 1px solid #c7d2fe;">
                            <div style="font-size: 11.5px; font-weight: 700; color: #3730a3; margin-bottom: 8px; display: flex; align-items: center; gap: 4px;">
                                <i class="ri-add-circle-line"></i> Input Data Surat Dinas Baru
                            </div>
                            <div class="form-group" style="margin-bottom: 8px;">
                                <input type="text" name="nomor_surat" id="nomor_surat" class="form-input" placeholder="Ketik Nomor Surat Dinas (e.g. SPD/001/EPI-OPS/2026)">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <input type="text" name="perihal_surat" id="perihal_surat" class="form-input" placeholder="Perihal / Judul Surat Tugas">
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">📅 Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" class="form-input" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">📅 Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date" class="form-input" required>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">💳 Tanggal Pembayaran</label>
                                <input type="date" name="payment_date" id="payment_date" class="form-input">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">🏷️ Status</label>
                                <select name="status" id="status" class="form-input">
                                    <option value="pending">⏳ Pending</option>
                                    <option value="paid">✅ Paid</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">📍 Tujuan Perjalanan</label>
                            <input type="text" name="destination" id="destination" class="form-input" placeholder="Contoh: Bandung / SCBD Tower / Kantor Unit" required>
                        </div>
                        
                        <!-- Calculated Nominal Card -->
                        <div style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); padding: 16px 18px; border-radius: 16px; color: white; box-shadow: 0 8px 20px rgba(79, 70, 229, 0.25);">
                            <div style="font-size: 11.5px; opacity: 0.9; margin-bottom: 4px; font-weight: 500;">💰 Nominal SPD Per Orang / Per Trip</div>
                            <div style="display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap; gap: 4px;">
                                <span id="nominalDisplay" style="font-size: 24px; font-weight: 800;">Rp 30.000</span>
                                <span style="font-size: 11.5px; font-weight: 500; opacity: 0.9;">(Rp 30.000 / hari)</span>
                            </div>
                            <input type="hidden" name="amount" id="amount" value="30000">
                            <div id="batchInfoText" style="font-size: 11px; margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.2); opacity: 0.95; line-height: 1.4;">
                                ℹ️ Silakan pilih layanan aplikasi & pegawai.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Action Buttons -->
            <div style="display: flex; gap: 12px; margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--gray-200);">
                <button type="button" onclick="closeModal()" class="btn-secondary" style="flex: 1; padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 12px;">Batal</button>
                <button type="submit" class="btn-primary" style="flex: 2; padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 12px; justify-content: center;" id="btnSubmit">Simpan Perjalanan</button>
            </div>
        </form>
    </div>
</div>

<script>
    let selectedEmployees = [];
    let currentAplikasi = null;
    
    // Auto filter when year changes
    function autoFilter() {
        const year = document.getElementById('yearFilter').value;
        const search = document.getElementById('searchInput').value;
        let url = '/travels?';
        if (year) url += `year=${year}&`;
        if (search) url += `search=${search}`;
        window.location.href = url;
    }
    
    function resetFilter() {
        window.location.href = '/travels';
    }
    
    document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') autoFilter();
    });
    
    // Search aplikasi
    document.getElementById('searchAplikasi')?.addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        const chips = document.querySelectorAll('.aplikasi-chip');
        chips.forEach(chip => {
            const text = chip.innerText.toLowerCase();
            chip.style.display = text.includes(search) ? 'inline-block' : 'none';
        });
    });
    
    // Filter daftar pegawai di modal perjalanan berdasarkan aplikasi
    window.filterTravelEmployeesByApp = function(selectedApp) {
        const sections = document.querySelectorAll('.travel-app-group-section');
        sections.forEach(sec => {
            const appName = sec.getAttribute('data-appname') || '';
            if (selectedApp === 'ALL' || appName === selectedApp.toLowerCase()) {
                sec.style.display = 'block';
            } else {
                sec.style.display = 'none';
            }
        });
        document.getElementById('aplikasiInput').value = (selectedApp === 'ALL') ? '' : selectedApp;
        calculateTotalNominal();
    };

    // Sync centang untuk pegawai yang ada di beberapa grup aplikasi
    window.syncTravelEmpCheckbox = function(empId, isChecked) {
        document.querySelectorAll(`.travel-emp-cb-${empId}`).forEach(cb => cb.checked = isChecked);
        calculateTotalNominal();
    };

    // Tombol PILIH SEMUA (hanya memilih pegawai yang saat ini TAMPIL/TERFILTER)
    window.selectAllTravelEmployees = function() {
        document.querySelectorAll('.travel-app-group-section').forEach(sec => {
            if (sec.style.display !== 'none') {
                sec.querySelectorAll('.travel-emp-checkbox').forEach(cb => {
                    cb.checked = true;
                    const empId = cb.value;
                    document.querySelectorAll(`.travel-emp-cb-${empId}`).forEach(c => c.checked = true);
                });
            }
        });
        calculateTotalNominal();
    };

    // Tombol BERSIHKAN (uncheck semua pegawai)
    window.clearAllTravelEmployees = function() {
        document.querySelectorAll('.travel-emp-checkbox').forEach(cb => cb.checked = false);
        calculateTotalNominal();
    };

    // Format Rupiah helper
    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    // Calculate nominal berdasarkan durasi hari (Rp 30.000 per hari) & jumlah pegawai
    function calculateTotalNominal() {
        const startDateVal = document.getElementById('start_date').value;
        const endDateVal = document.getElementById('end_date').value;
        let diffDays = 1;

        if (startDateVal && endDateVal) {
            const start = new Date(startDateVal);
            const end = new Date(endDateVal);
            
            if (end >= start) {
                const diffTime = Math.abs(end - start);
                diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            }
        }
        
        // Count unique checked employee IDs
        const checkedCbs = document.querySelectorAll('.travel-emp-checkbox:checked');
        const uniqueEmpIds = new Set();
        checkedCbs.forEach(cb => uniqueEmpIds.add(cb.value));
        selectedEmployees = Array.from(uniqueEmpIds);

        const dailyRate = 30000;
        const totalNominal = diffDays * dailyRate;
        
        // Update DOM Elements
        document.getElementById('amount').value = totalNominal;
        const nominalDisplay = document.getElementById('nominalDisplay');
        if (nominalDisplay) nominalDisplay.innerText = formatRupiah(totalNominal);
        
        const durationBadge = document.getElementById('durationBadge');
        if (durationBadge) durationBadge.innerText = `✨ ${diffDays} Hari`;

        const empCountBadge = document.getElementById('empCountBadge');
        if (empCountBadge) empCountBadge.innerText = `(${uniqueEmpIds.size} Terpilih)`;

        const batchInfoText = document.getElementById('batchInfoText');
        if (batchInfoText) {
            const count = uniqueEmpIds.size;
            const selectedApp = document.getElementById('travelAppFilterSelect') ? document.getElementById('travelAppFilterSelect').value : 'ALL';
            const appText = selectedApp && selectedApp !== 'ALL' ? selectedApp : 'Layanan Aplikasi';
            if (count > 0) {
                batchInfoText.innerHTML = `✅ Akan membuat <strong>${count} data SPD terpisah</strong> untuk <strong>${count} pegawai</strong> (${appText}). Nominal per SPD: <strong>${formatRupiah(totalNominal)}</strong>.`;
            } else {
                batchInfoText.innerHTML = `ℹ️ Silakan pilih aplikasi & pegawai untuk melanjutkan.`;
            }
        }
    }

    document.getElementById('start_date')?.addEventListener('change', calculateTotalNominal);
    document.getElementById('end_date')?.addEventListener('change', calculateTotalNominal);

    window.toggleNewSuratDinasFields = function(val) {
        const container = document.getElementById('newSuratDinasContainer');
        if (val === '') {
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
            document.getElementById('nomor_surat').value = '';
            document.getElementById('perihal_surat').value = '';
        }
    };

    // Open Add Modal
    function openAddModal() {
        document.getElementById('travelForm').reset();
        document.getElementById('travelForm').action = "{{ route('travels.store') }}";
        document.getElementById('methodField').value = "POST";
        document.getElementById('modalTitle').innerText = "Tambah Perjalanan";
        document.getElementById('travelId').value = '';
        document.getElementById('aplikasiInput').value = '';
        document.getElementById('surat_dinas_id').value = '';
        document.getElementById('nomor_surat').value = '';
        document.getElementById('perihal_surat').value = '';
        document.getElementById('destination').value = '';
        document.getElementById('status').value = 'pending';
        toggleNewSuratDinasFields('');

        const today = new Date().toISOString().split('T')[0];
        document.getElementById('start_date').value = today;
        document.getElementById('end_date').value = today;

        if (document.getElementById('travelAppFilterSelect')) {
            document.getElementById('travelAppFilterSelect').value = 'ALL';
            window.filterTravelEmployeesByApp('ALL');
        }
        window.clearAllTravelEmployees();

        calculateTotalNominal();
        document.getElementById('travelModal').classList.add('active');
    }
    
    function closeModal() {
        document.getElementById('travelModal').classList.remove('active');
    }
    
    // Edit Travel
    function editTravel(id) {
        fetch(`/travels/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('travelId').value = data.id;
                document.getElementById('aplikasiInput').value = data.aplikasi || '';
                document.getElementById('surat_dinas_id').value = data.surat_dinas_id || '';
                toggleNewSuratDinasFields(data.surat_dinas_id || '');
                document.getElementById('start_date').value = data.start_date;
                document.getElementById('end_date').value = data.end_date;
                document.getElementById('payment_date').value = data.payment_date || '';
                document.getElementById('destination').value = data.destination;
                document.getElementById('status').value = data.status;
                document.getElementById('travelForm').action = `/travels/${id}`;
                document.getElementById('methodField').value = "PUT";
                document.getElementById('modalTitle').innerText = "Edit Perjalanan";

                if (document.getElementById('travelAppFilterSelect')) {
                    document.getElementById('travelAppFilterSelect').value = 'ALL';
                    window.filterTravelEmployeesByApp('ALL');
                }

                // Check checkboxes matching employee IDs
                const selectedEmpIds = (data.employee_ids || []).map(String);
                document.querySelectorAll('.travel-emp-checkbox').forEach(cb => {
                    cb.checked = selectedEmpIds.includes(String(cb.value));
                });
                calculateTotalNominal();

                document.getElementById('travelModal').classList.add('active');
            });
    }
    
    // Delete Travel
    function deleteTravel(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            fetch(`/travels/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      window.location.reload();
                  } else {
                      alert(data.message || 'Gagal menghapus data');
                  }
              });
        }
    }
    
    // Submit form
    document.getElementById('travelForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (selectedEmployees.length === 0) {
            alert('Pilih minimal satu pegawai!');
            return;
        }
        
        if (!document.getElementById('amount').value || document.getElementById('amount').value == 0) {
            document.getElementById('amount').value = 30000;
        }
        
        const form = this;
        const formData = new FormData(form);
        const method = document.getElementById('methodField').value;
        const id = document.getElementById('travelId').value;
        let url = form.action;
        
        // Clear existing employee_ids and add new ones
        formData.delete('employee_ids[]');
        selectedEmployees.forEach(empId => formData.append('employee_ids[]', empId));
        
        if (method === 'PUT') {
            url = `/travels/${id}`;
            formData.append('_method', 'PUT');
        }
        
        const submitBtn = document.getElementById('btnSubmit');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="ri-loader-4-line spin"></i> Menyimpan...';
        submitBtn.disabled = true;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else if (data.errors) {
                let errorMsg = '';
                for (let key in data.errors) {
                    errorMsg += data.errors[key].join('\n') + '\n';
                }
                alert(errorMsg);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
    
    // Search employee in modal
    document.getElementById('searchEmployeeModal')?.addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        document.querySelectorAll('#employeeList .employee-item').forEach(item => {
            const name = item.querySelector('label strong')?.innerText.toLowerCase() || '';
            item.style.display = name.includes(search) ? 'flex' : 'none';
        });
    });
    
    // Initialize aplikasi buttons
    document.querySelectorAll('.aplikasi-chip').forEach(btn => {
        btn.addEventListener('click', function() { handleAplikasiClick(this); });
    });
    
    // Close modal on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
    
    // Send single WA Reminder AJAX
    function sendSingleWaReminder(travelId) {
        if (typeof showToast === 'function') showToast('Mengirim reminder WhatsApp...', 'info');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/travels/${travelId}/send-wa-reminder`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (typeof showToast === 'function') showToast(data.message, 'success');
                else alert(data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                if (typeof showToast === 'function') showToast(data.message || 'Gagal mengirim pesan.', 'error');
                else alert(data.message || 'Gagal mengirim pesan.');
            }
        })
        .catch(err => {
            if (typeof showToast === 'function') showToast('Terjadi kesalahan koneksi.', 'error');
            else alert('Terjadi kesalahan koneksi.');
        });
    }

    // Toggle Travel Status (Pending <-> Paid)
    function toggleTravelStatus(id, targetStatus) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (typeof showToast === 'function') showToast('Mengubah status pembayaran...', 'info');

        fetch(`/travels/${id}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: targetStatus })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (typeof showToast === 'function') showToast(data.message, 'success');
                else alert(data.message);
                setTimeout(() => location.reload(), 600);
            } else {
                if (typeof showToast === 'function') showToast('Gagal mengupdate status', 'error');
                else alert('Gagal mengupdate status');
            }
        })
        .catch(err => {
            if (typeof showToast === 'function') showToast('Terjadi kesalahan koneksi.', 'error');
            else alert('Terjadi kesalahan koneksi.');
        });
    }

    // Open Travel Detail Modal
    let currentDetailTravelId = null;
    function openTravelDetail(id) {
        currentDetailTravelId = id;
        fetch(`/travels/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('detPegawaiNames').innerText = data.employee_names || '-';
                document.getElementById('detNomorSurat').innerText = data.nomor_surat ? `${data.nomor_surat} (${data.perihal_surat || ''})` : 'Tanpa Surat Dinas';
                document.getElementById('detAplikasi').innerText = data.aplikasi || 'Lainnya / Umum';
                document.getElementById('detTujuan').innerText = data.destination || '-';
                document.getElementById('detPeriode').innerText = `${data.start_date_formatted} s/d ${data.end_date_formatted} (${data.duration} hari)`;
                document.getElementById('detNominal').innerText = data.formatted_amount || `Rp ${data.amount}`;
                document.getElementById('detStatus').innerText = (data.status || 'pending').toUpperCase();
                document.getElementById('detStatusBadge').className = data.status === 'paid' ? 'badge-paid' : 'badge-pending';
                document.getElementById('detTglBayar').innerText = data.payment_date_formatted || '-';
                document.getElementById('detDescription').innerText = data.description || 'Tidak ada keterangan tambahan.';
                document.getElementById('detLastReminded').innerText = data.last_reminded_at ? `Dikirim pada ${data.last_reminded_at}` : 'Belum pernah dikirim WA';

                document.getElementById('btnCetakDetailSpd').href = `/travels/${data.id}/print`;
                document.getElementById('travelDetailModal').classList.add('active');
            })
            .catch(err => {
                alert('Gagal memuat detail data perjalanan.');
            });
    }

    function closeTravelDetailModal() {
        document.getElementById('travelDetailModal').classList.remove('active');
    }

    function triggerWaFromDetail() {
        if (currentDetailTravelId) {
            sendSingleWaReminder(currentDetailTravelId);
        }
    }

    function triggerEditFromDetail() {
        if (currentDetailTravelId) {
            closeTravelDetailModal();
            editTravel(currentDetailTravelId);
        }
    }
</script>

<!-- Modal Detail SPD Pop-Up -->
<div id="travelDetailModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 680px; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div class="modal-header" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; padding: 18px 24px;">
            <h3 style="color: white; font-size: 16px; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="ri-file-search-line"></i> Detail Perjalanan Dinas (SPD)
            </h3>
            <button onclick="closeTravelDetailModal()" class="modal-close" style="color: white; font-size: 24px; background: none; border: none; cursor: pointer;">&times;</button>
        </div>
        <div class="modal-body" style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
            
            <!-- Summary Header Box -->
            <div style="background: var(--gray-50); padding: 16px; border-radius: 14px; border: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div>
                    <div style="font-size: 11px; font-weight: 700; color: var(--gray-500); text-transform: uppercase;">Nominal Tarif SPD</div>
                    <div style="font-size: 22px; font-weight: 800; color: var(--primary);" id="detNominal">Rp 0</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 11px; font-weight: 700; color: var(--gray-500); text-transform: uppercase; margin-bottom: 4px;">Status Pembayaran</div>
                    <span id="detStatusBadge" class="badge-pending">
                        <i class="ri-time-line"></i> <span id="detStatus">PENDING</span>
                    </span>
                </div>
            </div>

            <!-- Detail Grid Info -->
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px;">
                <div style="background: white; border: 1px solid var(--gray-200); padding: 12px; border-radius: 12px;">
                    <div style="font-size: 11px; font-weight: 700; color: var(--gray-500);"><i class="ri-user-line" style="color: var(--primary);"></i> Pegawai Ditunjuk</div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--gray-900); margin-top: 4px;" id="detPegawaiNames">-</div>
                </div>

                <div style="background: white; border: 1px solid var(--gray-200); padding: 12px; border-radius: 12px;">
                    <div style="font-size: 11px; font-weight: 700; color: var(--gray-500);"><i class="ri-apps-line" style="color: var(--primary);"></i> Layanan Aplikasi</div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--gray-900); margin-top: 4px;" id="detAplikasi">-</div>
                </div>

                <div style="background: white; border: 1px solid var(--gray-200); padding: 12px; border-radius: 12px; grid-column: span 2;">
                    <div style="font-size: 11px; font-weight: 700; color: var(--gray-500);"><i class="ri-file-paper-2-line" style="color: var(--primary);"></i> Surat Dinas Terkait</div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--gray-900); margin-top: 4px;" id="detNomorSurat">-</div>
                </div>

                <div style="background: white; border: 1px solid var(--gray-200); padding: 12px; border-radius: 12px;">
                    <div style="font-size: 11px; font-weight: 700; color: var(--gray-500);"><i class="ri-map-pin-line" style="color: var(--primary);"></i> Tujuan Perjalanan</div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--gray-900); margin-top: 4px;" id="detTujuan">-</div>
                </div>

                <div style="background: white; border: 1px solid var(--gray-200); padding: 12px; border-radius: 12px;">
                    <div style="font-size: 11px; font-weight: 700; color: var(--gray-500);"><i class="ri-calendar-line" style="color: var(--primary);"></i> Periode & Durasi</div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--gray-900); margin-top: 4px;" id="detPeriode">-</div>
                </div>

                <div style="background: white; border: 1px solid var(--gray-200); padding: 12px; border-radius: 12px;">
                    <div style="font-size: 11px; font-weight: 700; color: var(--gray-500);"><i class="ri-bank-card-line" style="color: var(--primary);"></i> Tanggal Bayar</div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--gray-900); margin-top: 4px;" id="detTglBayar">-</div>
                </div>

                <div style="background: white; border: 1px solid var(--gray-200); padding: 12px; border-radius: 12px;">
                    <div style="font-size: 11px; font-weight: 700; color: var(--gray-500);"><i class="ri-whatsapp-line" style="color: #166534;"></i> Status WA Reminder</div>
                    <div style="font-size: 12px; font-weight: 600; color: var(--gray-700); margin-top: 4px;" id="detLastReminded">-</div>
                </div>

                <div style="background: white; border: 1px solid var(--gray-200); padding: 12px; border-radius: 12px; grid-column: span 2;">
                    <div style="font-size: 11px; font-weight: 700; color: var(--gray-500);"><i class="ri-chat-3-line" style="color: var(--primary);"></i> Keterangan / Deskripsi</div>
                    <div style="font-size: 12.5px; color: var(--gray-700); margin-top: 4px; line-height: 1.5;" id="detDescription">-</div>
                </div>
            </div>

        </div>
        <div class="modal-footer" style="padding: 14px 24px; background: var(--gray-50); border-top: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; gap: 8px;">
                <a id="btnCetakDetailSpd" target="_blank" class="btn-outline" style="font-size: 12px; padding: 7px 14px; background: white; font-weight: 600; text-decoration: none;">
                    <i class="ri-printer-line"></i> Cetak SPD
                </a>
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="button" onclick="triggerEditFromDetail()" class="btn-outline" style="font-size: 12px; padding: 7px 14px; background: white; font-weight: 600;">
                    <i class="ri-edit-line"></i> Edit
                </button>
                <button type="button" onclick="closeTravelDetailModal()" class="btn-secondary" style="font-size: 12px; padding: 7px 16px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-overlay.active { display: flex !important; }
    .employee-item { transition: background 0.2s; }
    .employee-item:hover { background: var(--gray-50); }
    .aplikasi-chip { transition: all 0.2s; }
</style>
@endsection