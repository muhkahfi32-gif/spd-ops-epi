@extends('layouts.app')

@section('page-title', 'WA Reminder Center')
@section('content')

<div class="fade-in-up">
    <!-- Header Banner -->
    <div style="background: linear-gradient(135deg, #128c7e 0%, #25d366 100%); border-radius: var(--radius-xl); padding: 24px 28px; color: white; margin-bottom: 24px; box-shadow: 0 8px 24px rgba(37, 211, 102, 0.2); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 52px; height: 52px; background: rgba(255,255,255,0.2); backdrop-filter: blur(8px); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 28px;">
                <i class="ri-whatsapp-fill"></i>
            </div>
            <div>
                <h2 style="font-size: 20px; font-weight: 800; letter-spacing: -0.3px;">WhatsApp Reminder Center H+7</h2>
                <p style="font-size: 13px; opacity: 0.9; margin-top: 2px;">Monitoring dan eksekusi pengiriman reminder tagihan perjalanan dinas yang belum dibayar</p>
            </div>
        </div>
        @if(($stats['total_overdue'] ?? 0) > 0)
        <button onclick="sendAllWaReminders()" class="btn-primary-grad" style="background: #fff; color: #128c7e; font-weight: 700; box-shadow: 0 4px 14px rgba(0,0,0,0.15);">
            <i class="ri-send-plane-fill" style="color: #25d366;"></i> Kirim Semua Reminder ({{ $stats['total_overdue'] }})
        </button>
        @endif
    </div>

    <!-- Stats Row -->
    <div class="stats-grid">
        <div class="stat-card rose">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--rose-50); color: var(--rose-600);"><i class="ri-time-line"></i></div>
                    <div>
                        <div class="stat-value" style="color: var(--rose-600);">{{ $stats['total_overdue'] ?? 0 }}</div>
                        <div class="stat-label">H+7 Belum Dibayar</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--rose-100); color: var(--rose-600);">Perlu WA</span>
            </div>
        </div>

        <div class="stat-card emerald">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--emerald-50); color: var(--emerald-500);"><i class="ri-checkbox-circle-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['sent_today'] ?? 0 }}</div>
                        <div class="stat-label">Terkirim Hari Ini</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--emerald-100); color: var(--emerald-700);">Hari Ini</span>
            </div>
        </div>

        <div class="stat-card indigo">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--primary-50); color: var(--primary-600);"><i class="ri-history-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_sent'] ?? 0 }}</div>
                        <div class="stat-label">Total Pesan Terkirim</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--primary-100); color: var(--primary-800);">Log WA</span>
            </div>
        </div>

        <div class="stat-card amber">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--amber-50); color: var(--amber-500);"><i class="ri-error-warning-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total_failed'] ?? 0 }}</div>
                        <div class="stat-label">Total Gagal Kirim</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--amber-100); color: var(--amber-700);">Gagal</span>
            </div>
        </div>
    </div>

    <!-- Section 1: Overdue Travels Table -->
    <div class="card" style="margin-bottom: 28px;">
        <div class="card-header" style="background: var(--rose-50); border-bottom-color: #fecdd3;">
            <div>
                <h3 style="color: var(--rose-700);"><i class="ri-alarm-warning-fill" style="color: var(--rose-600);"></i> Tagihan H+7 Membutuhkan Reminder ({{ $overdueTravels->count() }})</h3>
                <p style="color: var(--rose-600);">Daftar perjalanan dinas yang telah melampaui 7 hari dari tanggal kepulangan dan belum lunas</p>
            </div>
            @if($overdueTravels->count() > 0)
            <button onclick="sendAllWaReminders()" class="btn-wa">
                <i class="ri-send-plane-fill"></i> Kirim Semua WA
            </button>
            @endif
        </div>
        <div class="card-body" style="padding: 0;">
            @if($overdueTravels->count() > 0)
            <div class="table-container" style="border: none; border-radius: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Pegawai</th>
                            <th>Tujuan & Deskripsi</th>
                            <th>Tgl Kepulangan</th>
                            <th>Nominal</th>
                            <th>Status Reminder</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overdueTravels as $travel)
                        <tr>
                            <td>
                                @foreach($travel->employees as $emp)
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                    <div class="avatar-circle">{{ substr($emp->name, 0, 1) }}</div>
                                    <div>
                                        <div style="font-weight: 700; color: var(--slate-900);">{{ $emp->name }}</div>
                                        <div style="font-size: 11px; color: var(--slate-500);"><i class="ri-whatsapp-line" style="color: var(--wa-green);"></i> {{ $emp->phone ?? 'Tidak ada HP' }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--slate-900);">{{ $travel->destination }}</div>
                                <div style="font-size: 11.5px; color: var(--slate-500);">{{ Str::limit($travel->description, 45) }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--rose-600);">
                                    {{ $travel->end_date ? \Carbon\Carbon::parse($travel->end_date)->translatedFormat('d M Y') : '-' }}
                                </div>
                                <span class="badge-overdue" style="font-size: 10.5px; padding: 2px 8px;">
                                    H+{{ $travel->end_date ? \Carbon\Carbon::parse($travel->end_date)->diffInDays(\Carbon\Carbon::today()) : 7 }} Overdue
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 800; color: var(--slate-900);">{{ $travel->formatted_amount }}</div>
                            </td>
                            <td>
                                @if($travel->last_reminded_at)
                                <span style="font-size: 11px; color: var(--emerald-600); font-weight: 600;">
                                    <i class="ri-checkbox-circle-fill"></i> Terakhir {{ \Carbon\Carbon::parse($travel->last_reminded_at)->diffForHumans() }}
                                </span>
                                @else
                                <span style="font-size: 11px; color: var(--rose-600); font-weight: 600;">
                                    <i class="ri-error-warning-fill"></i> Belum Pernah
                                </span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <button onclick="sendSingleWaReminder({{ $travel->id }})" class="btn-wa" style="padding: 6px 12px; font-size: 11.5px;">
                                    <i class="ri-whatsapp-fill"></i> Kirim WA
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="ri-checkbox-circle-line" style="color: var(--emerald-500);"></i>
                <p style="font-weight: 700; color: var(--slate-700);">Semua Tagihan Aman!</p>
                <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">Tidak ada tagihan perjalanan dinas H+7 yang tertunggak saat ini.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Section 2: History Logs Table -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3><i class="ri-history-line" style="color: var(--primary-500);"></i> Riwayat Pengiriman WA Reminder</h3>
                <p>Log histori pengiriman pesan WhatsApp ke pegawai</p>
            </div>
            <!-- Filter Log Status -->
            <div class="filter-group">
                <select id="logFilter" class="filter-select" onchange="filterLogs(this.value)">
                    <option value="all" {{ ($filterStatus ?? 'all') == 'all' ? 'selected' : '' }}>Semua Log</option>
                    <option value="sent" {{ ($filterStatus ?? '') == 'sent' ? 'selected' : '' }}>Terkirim</option>
                    <option value="failed" {{ ($filterStatus ?? '') == 'failed' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($reminderLogs->count() > 0)
            <div class="table-container" style="border: none; border-radius: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Waktu Kirim</th>
                            <th>Pegawai</th>
                            <th>No. WhatsApp</th>
                            <th>Tujuan Perjalanan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reminderLogs as $log)
                        <tr>
                            <td style="font-weight: 600; color: var(--slate-600);">
                                {{ $log->sent_at ? \Carbon\Carbon::parse($log->sent_at)->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td style="font-weight: 700; color: var(--slate-900);">
                                {{ $log->employee->name ?? '-' }}
                            </td>
                            <td>
                                <span style="font-family: monospace; font-size: 12px; color: var(--slate-600);">
                                    {{ $log->phone }}
                                </span>
                            </td>
                            <td>{{ $log->travel->destination ?? '-' }}</td>
                            <td>
                                @if($log->status === 'sent')
                                <span class="badge-sent"><i class="ri-checkbox-circle-fill"></i> Terkirim</span>
                                @else
                                <span class="badge-failed"><i class="ri-close-circle-fill"></i> Gagal</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-container">
                {{ $reminderLogs->appends(['filter' => $filterStatus])->links() }}
            </div>
            @else
            <div class="empty-state">
                <i class="ri-inbox-archive-line"></i>
                <p>Belum ada riwayat pengiriman reminder</p>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function sendSingleWaReminder(travelId) {
        showToast('Mengirim reminder WhatsApp...', 'info');
        fetch(`/reminders/send/${travelId}`, {
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
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(data.message || 'Gagal mengirim pesan.', 'error');
            }
        })
        .catch(err => {
            showToast('Terjadi kesalahan koneksi.', 'error');
        });
    }

    function sendAllWaReminders() {
        if (!confirm('Apakah Anda yakin ingin mengirim reminder WhatsApp H+7 ke seluruh pegawai terkait?')) return;
        showToast('Memproses pengiriman seluruh WA reminder...', 'info');
        fetch('/reminders/send-all', {
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
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(data.message || 'Gagal mengirim pesan.', 'error');
            }
        })
        .catch(err => {
            showToast('Terjadi kesalahan koneksi.', 'error');
        });
    }

    function filterLogs(status) {
        window.location.href = `/reminders?filter=${status}`;
    }
</script>
@endpush
@endsection
