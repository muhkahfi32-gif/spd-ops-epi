@extends('layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 flex items-center gap-2.5">
                <i class="ri-calendar-event-line text-sky-600"></i>
                <span>Kalender Perjalanan Dinas</span>
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Visualisasi jadwal penugasan dan perjalanan dinas pegawai per minggu / bulan secara interaktif.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('cek-spd.index') }}" target="_blank" 
                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition flex items-center gap-2">
                <i class="ri-external-link-line"></i>
                <span>Portal Cek NIP (Public)</span>
            </a>
            @auth
                <a href="{{ route('travels.index') }}" 
                    class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl text-sm shadow-md shadow-sky-600/20 transition flex items-center gap-2">
                    <i class="ri-add-line"></i>
                    <span>Kelola Perjalanan Dinas</span>
                </a>
            @endauth
        </div>
    </div>

    <!-- Status Color Legend Card -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-700">
        <span class="text-slate-500 font-bold uppercase tracking-wider">Keterangan Warna:</span>
        <div class="flex items-center gap-1.5">
            <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 inline-block"></span>
            <span>Lunas / Selesai</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3.5 h-3.5 rounded-full bg-amber-500 inline-block"></span>
            <span>Menunggu Pencairan</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3.5 h-3.5 rounded-full bg-red-500 inline-block"></span>
            <span>Overdue (Menunggak)</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3.5 h-3.5 rounded-full bg-sky-600 inline-block"></span>
            <span>Perjalanan Aktif</span>
        </div>
    </div>

    <!-- Calendar Container Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div id="calendar" class="min-h-[650px]"></div>
    </div>

</div>

<!-- FullCalendar Library CDN -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                list: 'Daftar'
            },
            locale: 'id',
            firstDay: 1, // Start on Monday
            events: '{{ route("calendar.events") }}',
            eventClick: function(info) {
                var props = info.event.extendedProps;
                var detailsHtml = `
                    <div class="space-y-3 text-sm text-slate-700">
                        <div>
                            <span class="text-xs text-slate-400 font-semibold uppercase block">Pegawai Bertugas:</span>
                            <span class="font-bold text-slate-900 text-base">${props.employees}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 font-semibold uppercase block">Tujuan & Aplikasi:</span>
                            <span class="font-semibold text-slate-800">${props.destination}</span>
                            <span class="inline-block ml-2 px-2 py-0.5 bg-slate-100 rounded text-xs text-slate-700 font-bold">${props.aplikasi}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 font-semibold uppercase block">Nominal SPD:</span>
                            <span class="font-bold text-slate-900">${props.amount}</span>
                            <span class="ml-2 font-bold ${props.status === 'Lunas' ? 'text-emerald-600' : (props.status === 'Overdue' ? 'text-red-600' : 'text-amber-600')}">(${props.status})</span>
                        </div>
                    </div>
                `;
                
                // Trigger quick alert or open print URL
                if (props.print_url) {
                    window.open(props.print_url, '_blank');
                }
            }
        });
        calendar.render();
    });
</script>
@endsection
