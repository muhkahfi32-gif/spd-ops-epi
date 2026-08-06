@extends('layouts.app')

@section('page-title', 'Dashboard Overview')
@section('content')

<div class="fade-in-up">
    <!-- Filter Bar -->
    <div class="filter-bar" style="background: white; border: 1px solid var(--gray-200); padding: 14px 20px; border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <div class="filter-group" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 6px; font-weight: 700; color: var(--gray-800); font-size: 13px;">
                <i class="ri-filter-3-line" style="color: var(--primary); font-size: 18px;"></i>
                <span>Filter Periode Monitoring:</span>
            </div>
            <select id="yearFilter" class="filter-select" onchange="autoFilter()" style="padding: 7px 14px; border-radius: 10px; border: 1px solid var(--gray-300); font-size: 13px; font-weight: 600;">
                <option value="">Semua Tahun</option>
                @for($i = 2024; $i <= 2026; $i++)
                    <option value="{{ $i }}" {{ ($year ?? '') == $i ? 'selected' : '' }}>Tahun {{ $i }}</option>
                @endfor
            </select>
            <select id="monthFilter" class="filter-select" onchange="autoFilter()" style="padding: 7px 14px; border-radius: 10px; border: 1px solid var(--gray-300); font-size: 13px; font-weight: 600;">
                <option value="">Semua Bulan (12 Bulan)</option>
                @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $idx => $m)
                    <option value="{{ $idx+1 }}" {{ ($month ?? '') == ($idx+1) ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
            @if($year || $month)
                <button onclick="resetFilter()" class="filter-reset" style="padding: 7px 14px; border-radius: 10px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-size: 12px; font-weight: 700; cursor: pointer;">
                    <i class="ri-refresh-line"></i> Reset Filter
                </button>
            @endif
        </div>
    </div>
    
    <!-- 4 Stats Cards -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="stat-card indigo" style="background: white; padding: 18px; border-radius: 16px; border: 1px solid var(--gray-200); box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <div class="stat-card-row" style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div class="stat-card-left" style="display: flex; gap: 12px; align-items: center;">
                    <div class="stat-icon" style="width: 44px; height: 44px; border-radius: 12px; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ri-file-copy-line"></i></div>
                    <div>
                        <div class="stat-value" style="font-size: 22px; font-weight: 800; color: #1e293b;">{{ $filteredCount ?? 0 }} <span style="font-size: 12px; color: #94a3b8; font-weight: 600;">/ {{ $totalTrips ?? 0 }} Total</span></div>
                        <div class="stat-label" style="font-size: 12px; color: #64748b; margin-top: 2px;">Perjalanan Dinas</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: #e0e7ff; color: #3730a3; padding: 3px 8px; border-radius: 8px; font-size: 11px; font-weight: 700;">{{ $completionRate ?? 0 }}% Share</span>
            </div>
        </div>
        
        <div class="stat-card emerald" style="background: white; padding: 18px; border-radius: 16px; border: 1px solid var(--gray-200); box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <div class="stat-card-row" style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div class="stat-card-left" style="display: flex; gap: 12px; align-items: center;">
                    <div class="stat-icon" style="width: 44px; height: 44px; border-radius: 12px; background: #dcfce7; color: #166534; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ri-user-star-line"></i></div>
                    <div>
                        <div class="stat-value" style="font-size: 22px; font-weight: 800; color: #1e293b;">{{ $totalEmployees ?? 0 }}</div>
                        <div class="stat-label" style="font-size: 12px; color: #64748b; margin-top: 2px;">Pegawai Aktif Sistem</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: #dcfce7; color: #166534; padding: 3px 8px; border-radius: 8px; font-size: 11px; font-weight: 700;">Aktif</span>
            </div>
        </div>
        
        <div class="stat-card amber" style="background: white; padding: 18px; border-radius: 16px; border: 1px solid var(--gray-200); box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <div class="stat-card-row" style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div class="stat-card-left" style="display: flex; gap: 12px; align-items: center;">
                    <div class="stat-icon" style="width: 44px; height: 44px; border-radius: 12px; background: #fef3c7; color: #92400e; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ri-money-dollar-circle-line"></i></div>
                    <div>
                        <div class="stat-value" style="font-size: 19px; font-weight: 800; color: #1e293b;">Rp {{ number_format($filteredNominal ?? 0, 0, ',', '.') }}</div>
                        <div class="stat-label" style="font-size: 12px; color: #64748b; margin-top: 2px;">Total Anggaran Dinas</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 8px; font-size: 11px; font-weight: 700;">Realisasi</span>
            </div>
        </div>

        <a href="{{ route('reminders.index') }}" style="text-decoration: none; color: inherit;">
            <div class="stat-card rose" style="background: white; padding: 18px; border-radius: 16px; border: 1px solid var(--gray-200); box-shadow: 0 4px 12px rgba(0,0,0,0.03); cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="stat-card-row" style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div class="stat-card-left" style="display: flex; gap: 12px; align-items: center;">
                        <div class="stat-icon" style="width: 44px; height: 44px; border-radius: 12px; background: #ffe4e6; color: #e11d48; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ri-whatsapp-line"></i></div>
                        <div>
                            <div class="stat-value" style="font-size: 22px; font-weight: 800; color: #e11d48;">{{ $overdueCount ?? 0 }} <span style="font-size: 11px; font-weight: 600; color: #94a3b8;">Belum Bayar</span></div>
                            <div class="stat-label" style="font-size: 12px; color: #64748b; margin-top: 2px;">WA Reminder Center →</div>
                        </div>
                    </div>
                    <span class="stat-tag" style="background: #ffe4e6; color: #e11d48; padding: 3px 8px; border-radius: 8px; font-size: 11px; font-weight: 700;">H+7</span>
                </div>
            </div>
        </a>
    </div>

    <!-- Charts Section -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 28px;">
        <!-- Bar Chart: Dynamic Monthly / Daily / Yearly Breakdown -->
        <div class="card" style="background: white; border-radius: 16px; border: 1px solid var(--gray-200); padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--gray-200);">
                <div>
                    <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <i class="ri-bar-chart-2-fill" style="color: var(--primary);"></i> 
                        <span id="chartMainTitle">Rekap Monitoring Anggaran SPD</span>
                    </h3>
                    <p style="font-size: 12px; color: #64748b; margin: 3px 0 0 0;" id="chartSubTitle">
                        @if($selectedMonthName)
                            Realisasi per hari di bulan {{ $selectedMonthName }} {{ $displayYear }}
                        @else
                            Realisasi anggaran perjalanan dinas per bulan tahun {{ $displayYear }}
                        @endif
                    </p>
                </div>

                <!-- Dynamic Mode Switcher Segmented Control -->
                <div style="display: flex; background: #f1f5f9; padding: 3px; border-radius: 10px; gap: 2px;">
                    @if($displayMonth)
                    <button type="button" id="btnModeDaily" onclick="switchChartMode('daily')" class="chart-tab-btn active" style="padding: 5px 12px; border-radius: 8px; border: none; font-size: 11.5px; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                        📅 Harian ({{ $selectedMonthName }})
                    </button>
                    @endif
                    <button type="button" id="btnModeMonthly" onclick="switchChartMode('monthly')" class="chart-tab-btn {{ !$displayMonth ? 'active' : '' }}" style="padding: 5px 12px; border-radius: 8px; border: none; font-size: 11.5px; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                        📊 Bulanan (12 Bulan)
                    </button>
                    <button type="button" id="btnModeYearly" onclick="switchChartMode('yearly')" class="chart-tab-btn" style="padding: 5px 12px; border-radius: 8px; border: none; font-size: 11.5px; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                        📆 Tahunan (Multi-Year)
                    </button>
                </div>
            </div>

            <div class="card-body" style="position: relative; height: 310px;">
                <canvas id="mainDynamicChart"></canvas>
            </div>
        </div>

        <!-- Pie Chart: Status Pembayaran -->
        <div class="card" style="background: white; border-radius: 16px; border: 1px solid var(--gray-200); padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <div class="card-header" style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--gray-200);">
                <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="ri-pie-chart-fill" style="color: var(--accent-500);"></i> Status Pembayaran
                </h3>
                <p style="font-size: 12px; color: #64748b; margin: 3px 0 0 0;">
                    @if($selectedMonthName)
                        Status paid vs pending ({{ $selectedMonthName }} {{ $displayYear }})
                    @else
                        Status paid vs pending (Tahun {{ $displayYear }})
                    @endif
                </p>
            </div>
            <div class="card-body" style="display: flex; justify-content: center; align-items: center; height: 260px;">
                <canvas id="statusPieChart" style="max-height: 250px; max-width: 250px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Recent Surat Dinas + Reminder Summary -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <!-- Recent Surat Dinas -->
        <div class="card" style="background: white; border-radius: 16px; border: 1px solid var(--gray-200); padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div>
                    <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <i class="ri-file-paper-2-line" style="color: #0284c7;"></i> Surat Dinas Terbaru
                    </h3>
                    <p style="font-size: 12px; color: #64748b; margin: 3px 0 0 0;">Total: {{ $totalSurat ?? 0 }} surat terdaftar</p>
                </div>
                <a href="{{ route('surat-dinas.index') }}" class="btn-outline" style="font-size: 12px; padding: 5px 12px; text-decoration: none;">
                    <i class="ri-arrow-right-line"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body" style="padding: 0;">
                @if(($recentSurat ?? collect())->count() > 0)
                <table class="data-table" style="width: 100%; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: left;">
                            <th style="padding: 10px 12px;">No. Surat</th>
                            <th style="padding: 10px 12px;">Pegawai</th>
                            <th style="padding: 10px 12px;">Tujuan</th>
                            <th style="padding: 10px 12px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSurat as $surat)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px 12px; font-weight: 700; color: #0f172a;">{{ $surat->nomor_surat }}</td>
                            <td style="padding: 10px 12px; color: #334155;">{{ $surat->employee->name ?? '-' }}</td>
                            <td style="padding: 10px 12px; color: #64748b;">{{ $surat->tujuan }}</td>
                            <td style="padding: 10px 12px;">{!! $surat->status_badge !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div style="text-align: center; padding: 30px; color: #94a3b8;">
                    <i class="ri-file-paper-2-line" style="font-size: 32px;"></i>
                    <p style="margin-top: 8px; font-size: 13px;">Belum ada data surat dinas</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Reminder Summary -->
        <div class="card" style="background: white; border-radius: 16px; border: 1px solid var(--gray-200); padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div>
                    <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <i class="ri-notification-3-line" style="color: #166534;"></i> Aktivitas WA Reminder Center
                    </h3>
                    <p style="font-size: 12px; color: #64748b; margin: 3px 0 0 0;">Ringkasan monitoring pengiriman tagihan</p>
                </div>
                <a href="{{ route('reminders.index') }}" class="btn-outline" style="font-size: 12px; padding: 5px 12px; text-decoration: none;">
                    <i class="ri-arrow-right-line"></i> Buka Center
                </a>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                    <div style="text-align: center; padding: 18px 10px; background: #ffe4e6; border-radius: 12px;">
                        <div style="font-size: 26px; font-weight: 800; color: #e11d48;">{{ $overdueCount ?? 0 }}</div>
                        <div style="font-size: 11px; font-weight: 700; color: #be123c; margin-top: 4px;">Pending Overdue (H+7)</div>
                    </div>
                    <div style="text-align: center; padding: 18px 10px; background: #dcfce7; border-radius: 12px;">
                        <div style="font-size: 26px; font-weight: 800; color: #166534;">{{ $reminderSentToday ?? 0 }}</div>
                        <div style="font-size: 11px; font-weight: 700; color: #15803d; margin-top: 4px;">Terkirim Hari Ini</div>
                    </div>
                    <div style="text-align: center; padding: 18px 10px; background: #e0e7ff; border-radius: 12px;">
                        <div style="font-size: 26px; font-weight: 800; color: #3730a3;">{{ $statusPaid ?? 0 }}</div>
                        <div style="font-size: 11px; font-weight: 700; color: #4338ca; margin-top: 4px;">Sudah Lunas (Paid)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .chart-tab-btn {
        background: transparent;
        color: #64748b;
    }
    .chart-tab-btn.active {
        background: white;
        color: #4f46e5;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
</style>

@push('scripts')
<script>
    function autoFilter() {
        const year = document.getElementById('yearFilter').value;
        const month = document.getElementById('monthFilter').value;
        let url = '{{ route("dashboard") }}?';
        if (year) url += 'year=' + year + '&';
        if (month) url += 'month=' + month + '&';
        window.location.href = url.replace(/&$/, '');
    }

    function resetFilter() {
        window.location.href = '{{ route("dashboard") }}';
    }

    // Chart Data Sets
    const chartDataDaily = {
        labels: {!! json_encode($dailyLabels ?? []) !!},
        nominals: {!! json_encode($dailyNominals ?? []) !!},
        title: "Nominal Per Hari ({{ $selectedMonthName ?? '' }} {{ $displayYear }})",
        subtitle: "Realisasi anggaran per tanggal di bulan {{ $selectedMonthName ?? '' }} {{ $displayYear }}"
    };

    const chartDataMonthly = {
        labels: {!! json_encode($monthLabels ?? []) !!},
        nominals: {!! json_encode($monthlyNominals ?? []) !!},
        title: "Nominal Per Bulan (Tahun {{ $displayYear }})",
        subtitle: "Realisasi anggaran perjalanan dinas 12 bulan tahun {{ $displayYear }}"
    };

    const chartDataYearly = {
        labels: {!! json_encode($yearlyLabels ?? []) !!},
        nominals: {!! json_encode($yearlyNominals ?? []) !!},
        title: "Perbandingan Nominal Pertahun",
        subtitle: "Tren realisasi anggaran perjalanan dinas antar tahun"
    };

    let mainChartInstance = null;
    let currentMode = "{{ $displayMonth ? 'daily' : 'monthly' }}";

    function renderMainChart(dataset) {
        const ctx = document.getElementById('mainDynamicChart').getContext('2d');
        
        if (mainChartInstance) {
            mainChartInstance.destroy();
        }

        document.getElementById('chartMainTitle').innerText = dataset.title;
        document.getElementById('chartSubTitle').innerText = dataset.subtitle;

        // Custom Gradient Colors based on values
        let gradients = dataset.nominals.map((val) => {
            let g = ctx.createLinearGradient(0, 0, 0, 300);
            if (val > 0) {
                g.addColorStop(0, '#4f46e5');
                g.addColorStop(1, '#7c3aed');
            } else {
                g.addColorStop(0, '#cbd5e1');
                g.addColorStop(1, '#e2e8f0');
            }
            return g;
        });

        mainChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dataset.labels,
                datasets: [{
                    label: 'Total Nominal (Rp)',
                    data: dataset.nominals,
                    backgroundColor: gradients,
                    borderRadius: 8,
                    barPercentage: dataset.labels.length > 20 ? 0.8 : 0.55
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(ctx) {
                                return 'Nominal: Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw);
                            }
                        }
                    },
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(val) {
                                if (val >= 1000000) return (val / 1000000).toFixed(1) + ' Jt';
                                if (val >= 1000) return (val / 1000).toFixed(0) + ' Rb';
                                return val;
                            },
                            font: { family: 'Plus Jakarta Sans', size: 11 }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    function switchChartMode(mode) {
        currentMode = mode;
        document.querySelectorAll('.chart-tab-btn').forEach(btn => btn.classList.remove('active'));
        
        if (mode === 'daily') {
            document.getElementById('btnModeDaily')?.classList.add('active');
            renderMainChart(chartDataDaily);
        } else if (mode === 'monthly') {
            document.getElementById('btnModeMonthly')?.classList.add('active');
            renderMainChart(chartDataMonthly);
        } else if (mode === 'yearly') {
            document.getElementById('btnModeYearly')?.classList.add('active');
            renderMainChart(chartDataYearly);
        }
    }

    // Initialize Main Chart
    document.addEventListener('DOMContentLoaded', function() {
        if (currentMode === 'daily' && chartDataDaily.labels.length > 0) {
            renderMainChart(chartDataDaily);
        } else {
            renderMainChart(chartDataMonthly);
        }

        // Initialize Status Pie Chart
        const pieCtx = document.getElementById('statusPieChart').getContext('2d');
        const paidCount = {{ $statusPaid ?? 0 }};
        const pendingCount = {{ $statusPending ?? 0 }};

        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Paid (Lunas)', 'Pending / Overdue'],
                datasets: [{
                    data: [paidCount, pendingCount],
                    backgroundColor: ['#10b981', '#f59e0b'],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }, usePointStyle: true }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush
@endsection