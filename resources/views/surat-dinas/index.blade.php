@extends('layouts.app')

@section('page-title', 'Rekap Surat Dinas')
@section('content')

<div class="fade-in-up">
    <!-- Filter & Action Bar -->
    <div class="filter-bar">
        <div class="filter-group">
            <i class="ri-filter-3-line" style="color: var(--primary-500); font-size: 18px;"></i>
            <span>Filter:</span>
            <select id="yearFilter" class="filter-select" onchange="autoFilter()">
                <option value="">Semua Tahun</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ ($year ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <select id="monthFilter" class="filter-select" onchange="autoFilter()">
                <option value="">Semua Bulan</option>
                @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $idx => $m)
                    <option value="{{ $idx+1 }}" {{ ($month ?? '') == ($idx+1) ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
            <select id="statusFilter" class="filter-select" onchange="autoFilter()">
                <option value="">Semua Status</option>
                <option value="draft" {{ ($status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="aktif" {{ ($status ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="selesai" {{ ($status ?? '') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <input type="text" id="searchInput" class="filter-input" placeholder="Cari no. surat, pegawai, tujuan..." value="{{ $search ?? '' }}" onkeyup="if(event.key==='Enter') autoFilter()">
            <button type="button" onclick="autoFilter()" class="btn-outline" style="padding: 8px 14px;"><i class="ri-search-line"></i> Cari</button>
            <button type="button" onclick="resetFilter()" class="filter-reset">Reset</button>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('surat-dinas.export', ['year' => $year ?? date('Y')]) }}" class="btn-outline">
                <i class="ri-file-download-line"></i> Export CSV
            </a>
            <button type="button" onclick="openCreateModal()" class="btn-primary-grad">
                <i class="ri-add-line"></i> Tambah Surat Dinas
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="stats-grid">
        <div class="stat-card indigo">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--primary-50); color: var(--primary-600);"><i class="ri-file-paper-2-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                        <div class="stat-label">Total Surat Dinas</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--primary-100); color: var(--primary-800);">Total</span>
            </div>
        </div>

        <div class="stat-card cyan">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--cyan-50); color: var(--cyan-500);"><i class="ri-checkbox-circle-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['aktif'] ?? 0 }}</div>
                        <div class="stat-label">Surat Dinas Aktif</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: #cffafe; color: #0e7490;">Aktif</span>
            </div>
        </div>

        <div class="stat-card emerald">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--emerald-50); color: var(--emerald-500);"><i class="ri-check-double-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['selesai'] ?? 0 }}</div>
                        <div class="stat-label">Surat Dinas Selesai</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--emerald-100); color: var(--emerald-700);">Selesai</span>
            </div>
        </div>

        <div class="stat-card amber">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--amber-50); color: var(--amber-500);"><i class="ri-draft-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['draft'] ?? 0 }}</div>
                        <div class="stat-label">Surat Dinas Draft</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--amber-100); color: var(--amber-700);">Draft</span>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3><i class="ri-file-paper-2-fill" style="color: var(--primary-500);"></i> Daftar Rekap Surat Dinas</h3>
                <p>Kelola data surat dinas perjalanan operasional</p>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($suratList->count() > 0)
            <div class="table-container" style="border: none; border-radius: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. Surat & Tanggal</th>
                            <th>Perihal & Tujuan</th>
                            <th>Rekap SPD Terkait</th>
                            <th>Periode & Durasi</th>
                            <th>Status</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suratList as $surat)
                        @php
                            $linkedTravels = $surat->travels;
                            $travelCount = $linkedTravels->count();
                            $totalSpdAmount = $surat->total_amount_spd;
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: var(--slate-900);">{{ $surat->nomor_surat }}</div>
                                <div style="font-size: 11px; color: var(--slate-500);">
                                    <i class="ri-calendar-line"></i> {{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d M Y') : '-' }}
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--slate-900);">{{ $surat->perihal }}</div>
                                <div style="font-size: 11.5px; color: var(--slate-500);"><i class="ri-map-pin-line"></i> {{ $surat->tujuan }}</div>
                            </td>
                            <td>
                                @if($travelCount > 0)
                                    <span style="font-size: 11px; font-weight: 700; background: #dcfce7; color: #166534; padding: 3px 10px; border-radius: 12px; display: inline-block;">
                                        <i class="ri-file-list-3-line"></i> {{ $travelCount }} Record SPD
                                    </span>
                                    <div style="font-size: 11.5px; font-weight: 700; color: #047857; margin-top: 3px;">
                                        Rp {{ number_format($totalSpdAmount, 0, ',', '.') }}
                                    </div>
                                @else
                                    <span style="font-size: 10.5px; color: #94a3b8;">Belum ada SPD</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-size: 11.5px; font-weight: 600; color: var(--slate-700);">
                                    {{ $surat->tanggal_berangkat ? $surat->tanggal_berangkat->format('d M Y') : '-' }} s/d {{ $surat->tanggal_kembali ? $surat->tanggal_kembali->format('d M Y') : '-' }}
                                </div>
                                <span style="font-size: 11px; font-weight: 700; color: var(--primary-600);">({{ $surat->durasi }} hari)</span>
                            </td>
                            <td>{!! $surat->status_badge !!}</td>
                            <td style="text-align: right;">
                                <div style="display: flex; justify-content: flex-end; gap: 6px;">
                                    <button type="button" onclick="openSuratDetail({{ $surat->id }})" class="btn-outline" style="padding: 5px 9px; font-size: 11.5px; background: #f0fdf4; color: #15803d; border-color: #bbf7d0;">
                                        <i class="ri-eye-line"></i> Detail SPD
                                    </button>
                                    <button type="button" onclick="editSurat({{ $surat->id }})" class="btn-outline" style="padding: 5px 9px; font-size: 11.5px;">
                                        <i class="ri-edit-line"></i> Edit
                                    </button>
                                    <button type="button" onclick="deleteSurat({{ $surat->id }})" class="btn-danger" style="padding: 5px 9px; font-size: 11.5px;">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-container">
                {{ $suratList->appends(request()->query())->links() }}
            </div>
            @else
            <div class="empty-state">
                <i class="ri-file-paper-2-line"></i>
                <p>Belum ada data surat dinas</p>
                <button type="button" onclick="openCreateModal()" class="btn-primary-grad" style="margin-top: 12px;">
                    <i class="ri-add-line"></i> Tambah Surat Dinas Pertama
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Create / Edit Surat Dinas -->
<div id="suratModal" class="modal-overlay" onclick="if(event.target===this) closeModal()">
    <div class="modal-content wide">
        <div class="modal-header">
            <h3 id="modalTitle">Tambah Surat Dinas Baru</h3>
            <button type="button" onclick="closeModal()" class="modal-close">&times;</button>
        </div>
        <form id="suratForm" onsubmit="submitSurat(event)">
            <input type="hidden" id="suratId">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nomor Surat *</label>
                        <input type="text" id="nomor_surat" class="form-input" placeholder="Misal: SPD/001/VIII/2026" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Surat *</label>
                        <input type="date" id="tanggal_surat" class="form-input" required>
                    </div>
                </div>

                <!-- Section Pegawai Ditunjuk: Step 1 Filter App -> Step 2 Select Employees -->
                <div class="form-group" style="background: var(--slate-50); padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
                        <label class="form-label" style="margin-bottom: 0;">
                            <i class="ri-user-follow-line" style="color: var(--primary-600);"></i> Pegawai Ditunjuk *
                            <span id="selectedEmpCountBadge" style="font-size: 11px; font-weight: 700; color: var(--primary-700); background: var(--primary-100); padding: 3px 10px; border-radius: 12px; margin-left: 6px;">(0 Terpilih)</span>
                        </label>

                        <!-- Action Buttons: Pilih Semua & Bersihkan -->
                        <div style="display: flex; gap: 6px;">
                            <button type="button" onclick="selectAllEmployees()" class="btn-outline" style="padding: 4px 10px; font-size: 11.5px; background: #fff;">
                                <i class="ri-checkbox-multiple-line" style="color: var(--primary-600);"></i> Pilih Semua
                            </button>
                            <button type="button" onclick="clearAllEmployees()" class="btn-outline" style="padding: 4px 10px; font-size: 11.5px; color: var(--rose-600); border-color: #fecdd3; background: #fff;">
                                <i class="ri-close-circle-line"></i> Bersihkan
                            </button>
                        </div>
                    </div>

                    <!-- Step 1: Dropdown Pilih Nama Aplikasi -->
                    <div style="margin-bottom: 10px;">
                        <select id="appFilterSelect" class="form-select" onchange="filterEmployeesByApp(this.value)" style="font-weight: 700; background: #fff; border-color: var(--primary-400); color: var(--slate-900);">
                            <option value="ALL">-- Pilih Aplikasi (Tampilkan Semua Pegawai) --</option>
                            @foreach($employeesByAplikasi as $appName => $empList)
                                <option value="{{ $appName }}">📱 Aplikasi: {{ $appName }} ({{ count($empList) }} Pegawai)</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Step 2: Employee List Cards Container -->
                    <div id="employeeListContainer" style="max-height: 220px; overflow-y: auto; border: 1px solid var(--slate-300); border-radius: var(--radius-sm); padding: 10px; background: #fff;">
                        @foreach($employeesByAplikasi as $appName => $empList)
                            <div class="app-group-section app-sec-{{ Str::slug($appName) }}" data-appname="{{ strtolower($appName) }}" style="margin-bottom: 12px;">
                                <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: var(--primary-700); background: var(--primary-50); padding: 4px 10px; border-radius: 6px; margin-bottom: 6px; display: flex; align-items: center; justify-content: space-between; border-left: 3px solid var(--primary-600);">
                                    <span><i class="ri-apps-line"></i> {{ $appName }}</span>
                                    <span style="font-size: 10px; opacity: 0.85; font-weight: 600;">{{ count($empList) }} Pegawai</span>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px; padding-left: 4px;">
                                    @foreach($empList as $emp)
                                        <label class="emp-card-item" data-appname="{{ strtolower($appName) }}" style="display: flex; align-items: center; gap: 8px; padding: 6px 8px; border-radius: 6px; cursor: pointer; background: var(--slate-50); border: 1px solid var(--slate-200); font-size: 12.5px; transition: all 0.15s;" onmouseover="this.style.borderColor='var(--primary-400)'" onmouseout="this.style.borderColor='var(--slate-200)'">
                                            <input type="checkbox" name="employee_ids" value="{{ $emp->id }}" class="emp-checkbox emp-cb-{{ $emp->id }}" onchange="syncEmpCheckbox({{ $emp->id }}, this.checked)" style="width: 15px; height: 15px; accent-color: var(--primary-600); cursor: pointer;">
                                            <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                <div style="font-weight: 700; color: var(--slate-900);">{{ $emp->name }}</div>
                                                <div style="font-size: 10.5px; color: var(--slate-500);">NIP: {{ $emp->nip ?? '-' }}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div style="font-size: 11.5px; color: var(--slate-500); margin-top: 6px;"><i class="ri-information-line"></i> Pilih nama aplikasi di atas untuk memfilter daftar pegawai, lalu gunakan tombol <b>Pilih Semua</b> atau <b>Bersihkan</b>.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Perihal Surat *</label>
                    <input type="text" id="perihal" class="form-input" placeholder="Perihal tugas dinas..." required>
                </div>

                <div class="form-group">
                    <label class="form-label">Tujuan Dinas *</label>
                    <input type="text" id="tujuan" class="form-input" placeholder="Kota / Lokasi tujuan..." required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Berangkat *</label>
                        <input type="date" id="tanggal_berangkat" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Kembali *</label>
                        <input type="date" id="tanggal_kembali" class="form-input" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Status Surat *</label>
                        <select id="status" class="form-select" required>
                            <option value="draft">Draft</option>
                            <option value="aktif">Aktif</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Link ke Perjalanan Dinas (Opsional)</label>
                        <select id="travel_id" class="form-select">
                            <option value="">-- Tanpa Perjalanan Dinas --</option>
                            @foreach($travels as $t)
                                <option value="{{ $t->id }}">#{{ $t->id }} - {{ $t->destination }} ({{ $t->start_date ? $t->start_date->format('d M Y') : '' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan / Catatan</label>
                    <textarea id="keterangan" class="form-textarea" placeholder="Catatan tambahan..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal()" class="btn-outline">Batal</button>
                <button type="submit" class="btn-primary-grad"><i class="ri-save-line"></i> Simpan Surat Dinas</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Filter daftar pegawai berdasarkan aplikasi yang dipilih di dropdown
        window.filterEmployeesByApp = function(selectedApp) {
            const sections = document.querySelectorAll('.app-group-section');
            sections.forEach(sec => {
                const appName = sec.getAttribute('data-appname') || '';
                if (selectedApp === 'ALL' || appName === selectedApp.toLowerCase()) {
                    sec.style.display = 'block';
                } else {
                    sec.style.display = 'none';
                }
            });
        };

        // Sync centang untuk pegawai yang memegang multiple aplikasi
        window.syncEmpCheckbox = function(empId, isChecked) {
            document.querySelectorAll(`.emp-cb-${empId}`).forEach(cb => cb.checked = isChecked);
            window.updateEmpSelectedCount();
        };

        // Tombol PILIH SEMUA (hanya memilih pegawai yang saat ini TAMPIL/TERFILTER)
        window.selectAllEmployees = function() {
            document.querySelectorAll('.app-group-section').forEach(sec => {
                if (sec.style.display !== 'none') {
                    sec.querySelectorAll('.emp-checkbox').forEach(cb => {
                        cb.checked = true;
                        // Sync multiple occurrences
                        const empId = cb.value;
                        document.querySelectorAll(`.emp-cb-${empId}`).forEach(c => c.checked = true);
                    });
                }
            });
            window.updateEmpSelectedCount();
        };

        // Tombol BERSIHKAN (uncheck semua pegawai)
        window.clearAllEmployees = function() {
            document.querySelectorAll('.emp-checkbox').forEach(cb => cb.checked = false);
            window.updateEmpSelectedCount();
        };

        // Hitung total pegawai unik yang terpilih
        window.updateEmpSelectedCount = function() {
            const checkedCbs = document.querySelectorAll('.emp-checkbox:checked');
            const uniqueEmpIds = new Set();
            checkedCbs.forEach(cb => uniqueEmpIds.add(cb.value));
            document.getElementById('selectedEmpCountBadge').innerText = `(${uniqueEmpIds.size} Terpilih)`;
        };

        window.autoFilter = function() {
            const year = document.getElementById('yearFilter').value;
            const month = document.getElementById('monthFilter').value;
            const status = document.getElementById('statusFilter').value;
            const search = document.getElementById('searchInput').value;

            let params = new URLSearchParams();
            if (year) params.append('year', year);
            if (month) params.append('month', month);
            if (status) params.append('status', status);
            if (search) params.append('search', search);

            window.location.href = `/surat-dinas?${params.toString()}`;
        };

        window.resetFilter = function() {
            window.location.href = '/surat-dinas';
        };

        window.openCreateModal = function() {
            document.getElementById('suratId').value = '';
            document.getElementById('suratForm').reset();
            document.getElementById('modalTitle').innerText = 'Tambah Surat Dinas Baru';
            
            // Reset filter dropdown & checkboxes
            document.getElementById('appFilterSelect').value = 'ALL';
            window.filterEmployeesByApp('ALL');
            window.clearAllEmployees();

            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal_surat').value = today;
            document.getElementById('tanggal_berangkat').value = today;
            document.getElementById('tanggal_kembali').value = today;
            
            document.getElementById('suratModal').classList.add('show');
        };

        window.closeModal = function() {
            document.getElementById('suratModal').classList.remove('show');
        };

        window.editSurat = function(id) {
            fetch(`/surat-dinas/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('suratId').value = data.id;
                    document.getElementById('nomor_surat').value = data.nomor_surat || '';
                    document.getElementById('tanggal_surat').value = data.tanggal_surat ? data.tanggal_surat.split('T')[0] : '';
                    document.getElementById('perihal').value = data.perihal || '';
                    document.getElementById('tujuan').value = data.tujuan || '';
                    document.getElementById('tanggal_berangkat').value = data.tanggal_berangkat ? data.tanggal_berangkat.split('T')[0] : '';
                    document.getElementById('tanggal_kembali').value = data.tanggal_kembali ? data.tanggal_kembali.split('T')[0] : '';
                    document.getElementById('status').value = data.status || 'draft';
                    document.getElementById('travel_id').value = data.travel_id || '';
                    document.getElementById('keterangan').value = data.keterangan || '';

                    // Reset filter dropdown
                    document.getElementById('appFilterSelect').value = 'ALL';
                    window.filterEmployeesByApp('ALL');

                    // Check selected employee checkboxes
                    const selectedEmpIds = (data.employee_ids || []).map(String);
                    document.querySelectorAll('.emp-checkbox').forEach(cb => {
                        cb.checked = selectedEmpIds.includes(String(cb.value));
                    });
                    window.updateEmpSelectedCount();

                    document.getElementById('modalTitle').innerText = 'Edit Surat Dinas';
                    document.getElementById('suratModal').classList.add('show');
                })
                .catch(err => {
                    if (window.showToast) window.showToast('Gagal memuat data surat dinas', 'error');
                });
        };

        window.submitSurat = function(e) {
            e.preventDefault();
            const id = document.getElementById('suratId').value;
            const isEdit = !!id;
            const url = isEdit ? `/surat-dinas/${id}` : '/surat-dinas';
            const method = isEdit ? 'PUT' : 'POST';

            // Collect unique checked employee IDs
            const checkedCbs = document.querySelectorAll('.emp-checkbox:checked');
            const uniqueEmpIds = new Set();
            checkedCbs.forEach(cb => uniqueEmpIds.add(parseInt(cb.value)));
            const selectedEmpIds = Array.from(uniqueEmpIds);

            if (selectedEmpIds.length === 0) {
                if (window.showToast) window.showToast('Pilih minimal satu pegawai ditunjuk!', 'error');
                return;
            }

            const payload = {
                nomor_surat: document.getElementById('nomor_surat').value,
                tanggal_surat: document.getElementById('tanggal_surat').value,
                employee_ids: selectedEmpIds,
                perihal: document.getElementById('perihal').value,
                tujuan: document.getElementById('tujuan').value,
                tanggal_berangkat: document.getElementById('tanggal_berangkat').value,
                tanggal_kembali: document.getElementById('tanggal_kembali').value,
                status: document.getElementById('status').value,
                travel_id: document.getElementById('travel_id').value || null,
                keterangan: document.getElementById('keterangan').value,
            };

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (window.showToast) window.showToast(data.message, 'success');
                    window.closeModal();
                    setTimeout(() => location.reload(), 1000);
                } else if (data.errors) {
                    const firstError = Object.values(data.errors)[0][0];
                    if (window.showToast) window.showToast(firstError, 'error');
                }
            })
            .catch(err => {
                if (window.showToast) window.showToast('Terjadi kesalahan sistem saat menyimpan.', 'error');
            });
        };

        window.deleteSurat = function(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus surat dinas ini?')) return;

            fetch(`/surat-dinas/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (window.showToast) window.showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    if (window.showToast) window.showToast(data.message || 'Gagal menghapus.', 'error');
                }
            })
            .catch(err => {
                if (window.showToast) window.showToast('Terjadi kesalahan sistem saat menghapus.', 'error');
            });
        };

        window.openSuratDetail = function(id) {
            fetch(`/surat-dinas/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('detailNomorSurat').innerText = data.nomor_surat || '-';
                    document.getElementById('detailPerihal').innerText = data.perihal || '-';
                    document.getElementById('detailTujuan').innerText = data.tujuan || '-';
                    document.getElementById('detailPeriode').innerText = `${data.tanggal_berangkat || '-'} s/d ${data.tanggal_kembali || '-'}`;
                    document.getElementById('detailStatus').innerText = (data.status || 'draft').toUpperCase();

                    // Render Pegawai Ditunjuk
                    const pegContainer = document.getElementById('detailPegawaiContainer');
                    const empIds = data.employee_ids || [];
                    if (empIds.length > 0 && window.allEmployeesMap) {
                        let html = '';
                        empIds.forEach(empId => {
                            const emp = window.allEmployeesMap[empId];
                            if (emp) {
                                html += `<span style="font-size: 11px; background: #e0e7ff; color: #3730a3; padding: 4px 10px; border-radius: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">👥 ${emp.name} <small style="opacity: 0.75;">(${emp.nip || '-'})</small></span> `;
                            }
                        });
                        pegContainer.innerHTML = html || '<span style="font-size: 12px; color: #94a3b8;">-</span>';
                    } else {
                        pegContainer.innerHTML = '<span style="font-size: 12px; color: #94a3b8;">Daftar pegawai ditunjuk dapat dilihat pada record SPD terkait.</span>';
                    }

                    document.getElementById('suratDetailModal').classList.add('show');
                });
        };

        window.closeDetailModal = function() {
            document.getElementById('suratDetailModal').classList.remove('show');
        };
    });
</script>

<!-- Pass All Employees JS Map for Modal Rendering -->
<script>
    window.allEmployeesMap = {
        @foreach($employees as $emp)
            {{ $emp->id }}: { name: "{{ addslashes($emp->name) }}", nip: "{{ $emp->nip }}" },
        @endforeach
    };
</script>

<!-- Modal Detail Breakdown SPD per Surat Dinas -->
<div id="suratDetailModal" class="modal-overlay" onclick="if(event.target===this) closeDetailModal()">
    <div class="modal-content wide" style="max-width: 750px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #3730a3 0%, #4f46e5 100%); color: white;">
            <h3 style="color: white; font-size: 16px;"><i class="ri-file-search-line"></i> Detail Rekap Surat Dinas</h3>
            <button type="button" onclick="closeDetailModal()" class="modal-close" style="color: white;">&times;</button>
        </div>
        <div class="modal-body" style="padding: 20px;">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 12px; margin-bottom: 16px;">
                <div style="font-size: 15px; font-weight: 800; color: #1e293b;" id="detailNomorSurat">Nomor Surat</div>
                <div style="font-size: 13px; color: #475569; margin-top: 4px;" id="detailPerihal">Perihal</div>
                <div style="display: flex; gap: 16px; font-size: 12px; color: #64748b; margin-top: 10px; flex-wrap: wrap;">
                    <span>📍 Tujuan: <strong id="detailTujuan" style="color: #0f172a;">-</strong></span>
                    <span>📅 Periode: <strong id="detailPeriode" style="color: #0f172a;">-</strong></span>
                    <span>🏷️ Status: <strong id="detailStatus" style="color: #4f46e5;">-</strong></span>
                </div>
            </div>

            <!-- Pegawai Ditunjuk Section -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 16px;">
                <div style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 8px;">
                    👥 Daftar Pegawai Ditunjuk:
                </div>
                <div id="detailPegawaiContainer" style="display: flex; flex-wrap: wrap; gap: 6px;">
                    <span style="font-size: 12px; color: #94a3b8;">Memuat data...</span>
                </div>
            </div>

            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 12px; font-size: 12px; color: #1e40af; line-height: 1.5;">
                <i class="ri-information-line"></i> Data Perjalanan Dinas (SPD) diinput melalui menu <strong>Perjalanan Dinas</strong> dengan memilih atau menginput Nomor Surat Dinas ini. Semua record SPD akan otomatis tersambung dan dihitung pada modul rekapitulasi ini.
            </div>
        </div>
        <div class="modal-footer" style="padding: 12px 20px; text-align: right; background: #f8fafc; border-top: 1px solid #e2e8f0;">
            <a href="{{ route('travels.index') }}" class="btn-primary-grad" style="font-size: 12px; padding: 7px 16px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <i class="ri-add-line"></i> Kelola Perjalanan Dinas (SPD)
            </a>
            <button type="button" onclick="closeDetailModal()" class="btn-outline" style="font-size: 12px; padding: 7px 16px; margin-left: 8px;">Tutup</button>
        </div>
    </div>
</div>
@endsection
