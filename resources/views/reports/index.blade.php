@extends('layouts.app')

@section('page-title', 'Laporan & Rekapan')
@section('content')

<div class="fade-in-up">
    <!-- Filter & Action Bar -->
    <div class="filter-bar">
        <div class="filter-group">
            <i class="ri-filter-3-line" style="color: var(--primary-500); font-size: 18px;"></i>
            <span>Filter Periode:</span>
            <select id="yearFilter" class="filter-select" onchange="autoFilter()">
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                @endforeach
            </select>
            <button onclick="resetFilter()" class="filter-reset">Reset Filter</button>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="stats-grid">
        <div class="stat-card indigo">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--primary-50); color: var(--primary-600);"><i class="ri-file-list-3-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $statistics['total_trips'] }}</div>
                        <div class="stat-label">Total Perjalanan</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--primary-100); color: var(--primary-800);">Total</span>
            </div>
        </div>

        <div class="stat-card emerald">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--emerald-50); color: var(--emerald-500);"><i class="ri-money-dollar-circle-line"></i></div>
                    <div>
                        <div class="stat-value" style="font-size: 16px;">Rp {{ number_format($statistics['total_nominal'], 0, ',', '.') }}</div>
                        <div class="stat-label">Total Anggaran</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--emerald-100); color: var(--emerald-700);">Realisasi</span>
            </div>
        </div>

        <div class="stat-card cyan">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--cyan-50); color: var(--cyan-500);"><i class="ri-calendar-event-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $statistics['total_months'] }} Bulan</div>
                        <div class="stat-label">Bulan Terdata</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: #cffafe; color: #0e7490;">Bulan</span>
            </div>
        </div>

        <div class="stat-card amber">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--amber-50); color: var(--amber-500);"><i class="ri-time-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $statistics['total_days'] }} Hari</div>
                        <div class="stat-label">Total Durasi</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--amber-100); color: var(--amber-700);">Hari</span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8">
            <a href="{{ route('dashboard') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Dashboard</a>
            <a href="{{ route('travels.index') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Data Perjalanan Dinas</a>
            <a href="{{ route('reports') }}" class="py-4 px-1 border-b-2 border-blue-500 text-blue-600 font-medium">Data Rekapan</a>
        </nav>
    </div>

    <!-- Monthly Report Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        <h3 class="text-lg font-semibold p-6 pb-0">Rekapan Per Bulan</h3>
        <div class="overflow-x-auto p-6 pt-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Perjalanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pegawai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Hari</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Nominal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($monthlyReports as $report)
                        @if($report['total_trips'] > 0)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $report['period'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $report['total_trips'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $report['total_employees'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $report['total_days'] }} hari</td>
                                <td class="px-6 py-4 text-sm font-semibold text-green-600">Rp {{ number_format($report['total_nominal'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <button onclick="showDetail({{ $report['month_num'] }})" class="text-blue-600 hover:text-blue-800">
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full mx-4 max-h-[80vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between">
            <h3 id="detailTitle" class="text-xl font-bold">Detail Perjalanan</h3>
            <button onclick="closeDetail()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <div id="detailContent" class="p-6"></div>
    </div>
</div>

<script>
document.getElementById('yearFilter').addEventListener('change', function() {
    window.location.href = `{{ route('reports') }}?year=${this.value}`;
});

function resetFilter() {
    window.location.href = '{{ route('reports') }}';
}

function showDetail(month) {
    const year = document.getElementById('yearFilter').value;
    fetch(`/reports/monthly?year=${year}&month=${month}`)
        .then(response => response.json())
        .then(data => {
            let html = '<div class="space-y-4">';
            data.data.forEach(travel => {
                html += `
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between">
                            <div>
                                <p class="font-semibold">${travel.employee.name}</p>
                                <p class="text-sm text-gray-600">${travel.destination}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">Rp ${new Intl.NumberFormat('id-ID').format(travel.amount)}</p>
                                <p class="text-sm text-gray-500">${travel.duration} hari</p>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">
                            ${travel.start_date} s/d ${travel.end_date}
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            document.getElementById('detailContent').innerHTML = html;
            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailModal').classList.add('flex');
        });
}

function closeDetail() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
}
</script>
@endsection