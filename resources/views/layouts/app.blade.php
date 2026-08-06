<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPD OPS EPI & KIT - Sistem Manajemen Perjalanan Dinas</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* ═══════════════════════════════════════════════════════════════
           SPD OPS EPI - Professional Design System v2.0
           ═══════════════════════════════════════════════════════════════ */
        
        :root {
            /* Primary Palette */
            --primary-50: #eef2ff;
            --primary-100: #e0e7ff;
            --primary-200: #c7d2fe;
            --primary-400: #818cf8;
            --primary-500: #6366f1;
            --primary-600: #4f46e5;
            --primary-700: #4338ca;
            --primary-800: #3730a3;
            
            /* Accent */
            --accent-400: #a78bfa;
            --accent-500: #8b5cf6;
            --accent-600: #7c3aed;
            
            /* Semantic */
            --emerald-50: #ecfdf5;
            --emerald-100: #dcfce7;
            --emerald-500: #10b981;
            --emerald-600: #059669;
            --emerald-700: #15803d;
            
            --amber-50: #fffbeb;
            --amber-100: #fef3c7;
            --amber-500: #f59e0b;
            --amber-700: #b45309;
            
            --rose-50: #fff1f2;
            --rose-100: #ffe4e6;
            --rose-500: #f43f5e;
            --rose-600: #e11d48;
            --rose-700: #be123c;
            
            --cyan-50: #ecfeff;
            --cyan-500: #06b6d4;
            
            /* Neutrals */
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
            --slate-950: #020617;
            
            /* WA Green */
            --wa-green: #25d366;
            --wa-dark: #128c7e;
            
            /* Layout */
            --sidebar-w: 240px;
            --topbar-h: 60px;
            --radius-sm: 8px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 16px;
            
            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 14px rgba(0,0,0,0.04);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.06);
            --shadow-xl: 0 16px 36px rgba(0,0,0,0.08);
        }

        /* ─── Reset & Base ───────────────────────────── */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, sans-serif;
            background: var(--slate-50);
            color: var(--slate-800);
            font-size: 13px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ─── App Layout ─────────────────────────────── */
        .app { display: flex; min-height: 100vh; }

        /* ─── Sidebar ────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            background: linear-gradient(180deg, var(--slate-900) 0%, var(--slate-950) 100%);
            color: var(--slate-100);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.12);
            transition: all 0.3s ease;
        }
        .sidebar::-webkit-scrollbar { width: 0; }

        /* Sidebar Header */
        .sidebar-header {
            padding: 16px 16px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(139, 92, 246, 0.08) 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-logo {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--accent-500) 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 18px;
            box-shadow: 0 3px 12px rgba(99, 102, 241, 0.35);
            flex-shrink: 0;
        }
        .sidebar-header h1 { font-size: 14.5px; font-weight: 800; letter-spacing: -0.3px; color: #fff; line-height: 1.2; }
        .sidebar-header p { font-size: 10px; color: var(--slate-400); font-weight: 500; margin-top: 1px; }

        /* Navigation */
        .nav-section-title {
            font-size: 9.5px; font-weight: 700; letter-spacing: 1px;
            color: var(--slate-500); padding: 16px 16px 6px;
            text-transform: uppercase;
        }
        .nav-item {
            padding: 8px 12px; margin: 2px 10px;
            display: flex; align-items: center; justify-content: space-between;
            border-radius: var(--radius-md); color: var(--slate-400);
            cursor: pointer; font-size: 12.5px; font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .nav-item:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            transform: translateX(3px);
        }
        .nav-item.active {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-500) 100%);
            color: #fff; font-weight: 600;
            box-shadow: 0 3px 12px rgba(79, 70, 229, 0.3);
            transform: none;
        }
        .nav-left { display: flex; align-items: center; gap: 10px; }
        .nav-left i { font-size: 16px; width: 18px; text-align: center; }
        .nav-badge {
            background: rgba(255, 255, 255, 0.12);
            color: #fff; padding: 2px 7px;
            border-radius: 12px; font-size: 10.5px; font-weight: 700;
            min-width: 20px; text-align: center;
        }
        .nav-badge.danger {
            background: var(--rose-500);
            animation: pulse-badge 2s infinite;
        }
        .nav-badge.wa-green {
            background: var(--wa-green);
        }
        @keyframes pulse-badge {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.08); }
        }

        /* Sidebar Divider */
        .sidebar-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
            margin: 6px 16px;
        }

        /* Logout */
        .logout-btn {
            margin-top: auto;
            padding: 12px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            color: var(--rose-500);
            display: flex; align-items: center; gap: 10px;
            cursor: pointer; font-size: 12.5px; font-weight: 600;
            transition: all 0.2s; border: none; background: transparent; width: 100%; text-align: left;
            font-family: inherit;
        }
        .logout-btn:hover { background: rgba(239, 68, 68, 0.08); }

        /* Sidebar Version */
        .sidebar-version {
            padding: 10px 16px;
            font-size: 10px; color: var(--slate-600); font-weight: 500;
            text-align: center; border-top: 1px solid rgba(255,255,255,0.04);
        }

        /* ─── Main Content ───────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            padding: 20px 28px;
            min-height: 100vh;
        }

        /* ─── Top Bar ────────────────────────────────── */
        .top-bar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 12px 20px;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow: var(--shadow-sm);
        }
        .breadcrumb { font-size: 11.5px; color: var(--slate-500); font-weight: 500; }
        .breadcrumb .current { color: var(--slate-900); font-weight: 700; }
        .page-title { font-size: 18px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.4px; margin-top: 1px; }
        .top-bar-right { display: flex; align-items: center; gap: 12px; }
        .wa-status-indicator {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 12px; background: var(--emerald-50);
            color: var(--emerald-700); border-radius: 16px;
            font-size: 11.5px; font-weight: 600;
            border: 1px solid var(--emerald-100);
        }
        .wa-status-indicator i { font-size: 14px; color: var(--wa-green); }
        .wa-status-indicator .dot {
            width: 6px; height: 6px; background: var(--wa-green);
            border-radius: 50%; animation: dot-pulse 1.5s infinite;
        }
        @keyframes dot-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        .date-badge {
            font-size: 13px; font-weight: 600; color: var(--slate-500);
            display: flex; align-items: center; gap: 6px;
        }

        /* ─── Card System ────────────────────────────── */
        .card {
            background: #fff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--slate-200);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }
        .card:hover { box-shadow: var(--shadow-md); }
        .card-header {
            padding: 14px 18px;
            border-bottom: 1px solid var(--slate-100);
            display: flex; justify-content: space-between; align-items: center;
        }
        .card-header h3 { font-size: 14px; font-weight: 700; color: var(--slate-900); display: flex; align-items: center; gap: 6px; }
        .card-header p { font-size: 11px; color: var(--slate-500); margin-top: 1px; }
        .card-body { padding: 18px; }

        /* ─── Stats Cards ────────────────────────────── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
        .stat-card {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 16px 18px;
            border: 1px solid var(--slate-200);
            position: relative; overflow: hidden;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-card::after {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 3px;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }
        .stat-card.indigo::after { background: linear-gradient(90deg, var(--primary-500), var(--accent-500)); }
        .stat-card.emerald::after { background: linear-gradient(90deg, var(--emerald-500), #34d399); }
        .stat-card.amber::after { background: linear-gradient(90deg, var(--amber-500), #fbbf24); }
        .stat-card.rose::after { background: linear-gradient(90deg, var(--rose-500), #fb7185); }
        .stat-card.cyan::after { background: linear-gradient(90deg, var(--cyan-500), #22d3ee); }
        .stat-card-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .stat-card-left { display: flex; align-items: center; gap: 12px; }
        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 19px; flex-shrink: 0;
        }
        .stat-tag {
            font-size: 10px; font-weight: 700;
            padding: 3px 9px; border-radius: 12px; flex-shrink: 0;
        }
        .stat-value { font-size: 20px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.4px; line-height: 1.2; margin: 0; }
        .stat-label { font-size: 11.5px; font-weight: 600; color: var(--slate-500); line-height: 1.2; margin-top: 2px; }

        /* ─── Table System ───────────────────────────── */
        .table-container {
            background: #fff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--slate-200);
            overflow: hidden;
        }
        .data-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .data-table th {
            background: var(--slate-50); padding: 10px 14px;
            text-align: left; font-weight: 700; color: var(--slate-600);
            border-bottom: 1px solid var(--slate-200);
            text-transform: uppercase; font-size: 10.5px; letter-spacing: 0.4px;
        }
        .data-table td {
            padding: 9px 14px;
            border-bottom: 1px solid var(--slate-100);
            color: var(--slate-700); vertical-align: middle;
        }
        .data-table tr:hover td { background: var(--slate-50); }
        .data-table tr:last-child td { border-bottom: none; }

        /* ─── Badges ─────────────────────────────────── */
        .badge-paid { background: var(--emerald-100); color: var(--emerald-700); padding: 4px 10px; border-radius: 14px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
        .badge-pending { background: var(--amber-100); color: var(--amber-700); padding: 4px 10px; border-radius: 14px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
        .badge-overdue { background: var(--rose-100); color: var(--rose-600); padding: 4px 10px; border-radius: 14px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; border: 1px solid #fecdd3; }
        .badge-draft { background: var(--slate-100); color: var(--slate-600); padding: 4px 10px; border-radius: 14px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
        .badge-aktif { background: #dbeafe; color: #1d4ed8; padding: 4px 10px; border-radius: 14px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
        .badge-selesai { background: var(--emerald-100); color: var(--emerald-700); padding: 4px 10px; border-radius: 14px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
        .badge-sent { background: var(--emerald-100); color: var(--emerald-700); padding: 3px 9px; border-radius: 14px; font-size: 10.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
        .badge-failed { background: var(--rose-100); color: var(--rose-600); padding: 3px 9px; border-radius: 14px; font-size: 10.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }

        /* ─── Buttons ────────────────────────────────── */
        .btn-primary-grad {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-500) 100%);
            color: #fff; padding: 8px 16px;
            border-radius: var(--radius-md); font-weight: 600;
            border: none; cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
            box-shadow: 0 2px 10px rgba(79, 70, 229, 0.25);
            transition: all 0.2s; font-family: inherit; font-size: 12px;
        }
        .btn-primary-grad:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35); }

        .btn-wa {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 6px 14px;
            background: linear-gradient(135deg, var(--wa-green) 0%, var(--wa-dark) 100%);
            color: #fff; border-radius: var(--radius-sm);
            font-size: 11.5px; font-weight: 600;
            border: none; cursor: pointer;
            box-shadow: 0 2px 8px rgba(37, 211, 102, 0.25);
            transition: all 0.2s; font-family: inherit;
        }
        .btn-wa:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37, 211, 102, 0.35); }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--slate-200);
            padding: 6px 14px; border-radius: var(--radius-sm);
            cursor: pointer; font-size: 11.5px; font-weight: 600;
            color: var(--slate-600); transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 5px;
            font-family: inherit;
        }
        .btn-outline:hover { background: var(--slate-50); border-color: var(--primary-500); color: var(--primary-600); }

        .btn-danger {
            background: var(--rose-500); color: #fff;
            padding: 6px 14px; border-radius: var(--radius-sm);
            border: none; cursor: pointer; font-size: 11.5px; font-weight: 600;
            font-family: inherit; transition: all 0.2s;
        }
        .btn-danger:hover { background: var(--rose-600); }

        /* ─── Filter Bar ─────────────────────────────── */
        .filter-bar {
            background: #fff; padding: 10px 16px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--slate-200);
            margin-bottom: 18px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px;
        }
        .filter-group {
            display: flex; align-items: center; gap: 10px;
            font-size: 12.5px; font-weight: 600; color: var(--slate-600);
        }
        .filter-select {
            padding: 6px 12px; border: 1px solid var(--slate-300);
            border-radius: var(--radius-sm); font-size: 12px;
            outline: none; background: var(--slate-50);
            font-weight: 500; font-family: inherit;
            transition: border-color 0.2s;
        }
        .filter-select:focus { border-color: var(--primary-500); }
        .filter-input {
            padding: 6px 12px; border: 1px solid var(--slate-300);
            border-radius: var(--radius-sm); font-size: 12px;
            outline: none; background: var(--slate-50);
            font-weight: 500; font-family: inherit;
            min-width: 200px;
        }
        .filter-input:focus { border-color: var(--primary-500); }
        .filter-reset {
            padding: 6px 14px; background: var(--slate-100);
            color: var(--slate-500); border: none;
            border-radius: var(--radius-sm); font-size: 12px;
            font-weight: 600; cursor: pointer; font-family: inherit;
        }
        .filter-reset:hover { background: var(--slate-200); }

        /* ─── Grid Layouts ───────────────────────────── */
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }

        /* ─── Modal ───────────────────────────────────── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            z-index: 1000; display: none;
            align-items: center; justify-content: center;
        }
        .modal-overlay.show { display: flex; }
        .modal-content {
            background: #fff;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            width: 100%; max-width: 580px;
            max-height: 88vh; overflow-y: auto;
            margin: 16px;
            animation: modal-in 0.25s ease-out;
        }
        .modal-content.wide { max-width: 760px; }
        @keyframes modal-in {
            from { opacity: 0; transform: scale(0.96) translateY(8px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-header {
            padding: 14px 20px; border-bottom: 1px solid var(--slate-100);
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; background: #fff; z-index: 1;
        }
        .modal-header h3 { font-size: 15px; font-weight: 700; color: var(--slate-900); }
        .modal-close {
            width: 28px; height: 28px; border-radius: 50%;
            border: none; background: var(--slate-100);
            cursor: pointer; font-size: 16px;
            display: flex; align-items: center; justify-content: center;
            color: var(--slate-500); transition: all 0.2s;
        }
        .modal-close:hover { background: var(--slate-200); color: var(--slate-700); }
        .modal-body { padding: 18px 20px; }
        .modal-footer {
            padding: 12px 20px; border-top: 1px solid var(--slate-100);
            display: flex; justify-content: flex-end; gap: 10px;
        }

        /* ─── Form Elements ──────────────────────────── */
        .form-group { margin-bottom: 12px; }
        .form-label {
            display: block; font-size: 12px; font-weight: 600;
            color: var(--slate-700); margin-bottom: 4px;
        }
        .form-input {
            width: 100%; padding: 7px 11px;
            border: 1px solid var(--slate-300);
            border-radius: var(--radius-sm); font-size: 12px;
            font-family: inherit; outline: none;
            transition: border-color 0.2s;
        }
        .form-input:focus { border-color: var(--primary-500); box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1); }
        .form-select {
            width: 100%; padding: 7px 11px;
            border: 1px solid var(--slate-300);
            border-radius: var(--radius-sm); font-size: 12px;
            font-family: inherit; outline: none; background: #fff;
        }
        .form-textarea {
            width: 100%; padding: 7px 11px;
            border: 1px solid var(--slate-300);
            border-radius: var(--radius-sm); font-size: 12px;
            font-family: inherit; outline: none; resize: vertical;
            min-height: 70px;
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        /* ─── Toast ───────────────────────────────────── */
        #toastContainer {
            position: fixed; bottom: 24px; right: 24px;
            z-index: 9999; display: flex;
            flex-direction: column; gap: 10px;
        }
        .toast-card {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 14px 20px;
            box-shadow: var(--shadow-lg);
            display: flex; align-items: center; gap: 12px;
            min-width: 320px;
            border-left: 4px solid var(--emerald-500);
            animation: slideInRight 0.3s ease-out;
        }
        .toast-card.error { border-left-color: var(--rose-500); }
        .toast-card.info { border-left-color: var(--primary-500); }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* ─── Pagination ─────────────────────────────── */
        .pagination-container {
            display: flex; justify-content: center; align-items: center;
            gap: 6px; padding: 20px; flex-wrap: wrap;
        }
        .pagination-container nav { display: contents; }
        .pagination-container .relative { display: contents; }
        .pagination-container a, .pagination-container span {
            padding: 8px 14px; border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 600;
            border: 1px solid var(--slate-200);
            color: var(--slate-600); text-decoration: none;
            transition: all 0.2s;
        }
        .pagination-container a:hover { background: var(--primary-50); border-color: var(--primary-300); color: var(--primary-600); }
        .pagination-container span[aria-current="page"] span {
            background: var(--primary-600) !important; color: #fff !important;
            border-color: var(--primary-600) !important;
        }

        /* ─── Animations ─────────────────────────────── */
        .fade-in-up {
            animation: fadeInUp 0.4s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ─── Empty State ────────────────────────────── */
        .empty-state {
            padding: 48px; text-align: center; color: var(--slate-400);
        }
        .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }
        .empty-state p { font-size: 14px; font-weight: 500; }

        /* ─── Avatar ─────────────────────────────────── */
        .avatar-circle {
            width: 32px; height: 32px;
            background: var(--primary-100);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: var(--primary-700);
            flex-shrink: 0;
        }

        /* ─── Responsive ─────────────────────────────── */
        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .grid-2 { grid-template-columns: 1fr; }
        }
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr; }
            .top-bar { flex-direction: column; align-items: flex-start; gap: 12px; }
            .filter-bar { flex-direction: column; align-items: stretch; }
            .form-row { grid-template-columns: 1fr; }
            .grid-3 { grid-template-columns: 1fr; }
        }
    </style>

    @stack('styles')
    @yield('styles')
</head>
<body>
    <div class="app">
        <!-- ═══════════ SIDEBAR ═══════════ -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="ri-plane-fill"></i>
                </div>
                <div>
                    <h1>SPD OPS EPI</h1>
                    <p>Sistem Perjalanan Dinas</p>
                </div>
            </div>
            
            @php
                $travelCountNav = \App\Models\Travel::count();
                $employeeCountNav = \App\Models\Employee::where('is_active', true)->count();
                $overdueCountNav = \App\Models\Travel::pendingPaymentOverdue(7)->count();
                $suratCountNav = \App\Models\SuratDinas::count();
            @endphp
            
            <div class="nav-section-title">Menu Utama</div>
            
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <div class="nav-left"><i class="ri-dashboard-3-line"></i><span>Dashboard</span></div>
            </a>
            
            <a href="{{ route('travels.index') }}" class="nav-item {{ request()->routeIs('travels.*') ? 'active' : '' }}">
                <div class="nav-left"><i class="ri-route-line"></i><span>Perjalanan Dinas</span></div>
                <span class="nav-badge">{{ $travelCountNav }}</span>
            </a>
            
            <a href="{{ route('surat-dinas.index') }}" class="nav-item {{ request()->routeIs('surat-dinas.*') ? 'active' : '' }}">
                <div class="nav-left"><i class="ri-file-paper-2-line"></i><span>Rekap Surat Dinas</span></div>
                @if($suratCountNav > 0)
                <span class="nav-badge">{{ $suratCountNav }}</span>
                @endif
            </a>

            <a href="{{ route('calendar.index') }}" class="nav-item {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                <div class="nav-left"><i class="ri-calendar-event-line"></i><span>Kalender Aktivitas</span></div>
            </a>

            <a href="{{ route('cek-spd.index') }}" target="_blank" class="nav-item">
                <div class="nav-left"><i class="ri-search-eye-line" style="color: #38bdf8;"></i><span>Portal Cek NIP (Public)</span></div>
            </a>

            <div class="sidebar-divider"></div>
            <div class="nav-section-title">Notifikasi & Monitoring</div>
            
            <a href="{{ route('reminders.index') }}" class="nav-item {{ request()->routeIs('reminders.*') ? 'active' : '' }}" @if($overdueCountNav > 0) style="border: 1px solid rgba(239, 68, 68, 0.15); background: rgba(239, 68, 68, 0.06);" @endif>
                <div class="nav-left"><i class="ri-whatsapp-line" style="color: {{ $overdueCountNav > 0 ? '#25d366' : 'inherit' }};"></i><span>WA Reminder Center</span></div>
                @if($overdueCountNav > 0)
                <span class="nav-badge danger">{{ $overdueCountNav }}</span>
                @endif
            </a>

            <div class="sidebar-divider"></div>
            <div class="nav-section-title">Data & Laporan</div>
            
            <a href="{{ route('employees.index') }}" class="nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <div class="nav-left"><i class="ri-user-star-line"></i><span>Data Pegawai</span></div>
                <span class="nav-badge">{{ $employeeCountNav }}</span>
            </a>
            
            <a href="{{ route('reports') }}" class="nav-item {{ request()->routeIs('reports*') ? 'active' : '' }}">
                <div class="nav-left"><i class="ri-file-chart-line"></i><span>Laporan & Rekap</span></div>
            </a>
            
            <form method="POST" action="{{ route('logout') }}" style="margin-top: auto;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="ri-logout-box-r-line"></i> Keluar (Logout)
                </button>
            </form>
            
            <div class="sidebar-version">SPD OPS EPI v2.0 — PLN EPI {{ date('Y') }}</div>
        </aside>
        
        <!-- ═══════════ MAIN CONTENT ═══════════ -->
        <main class="main-content" id="mainContent">
            <!-- Top Bar -->
            <div class="top-bar">
                <div>
                    <div class="breadcrumb"><span>SPD Ops EPI</span> <i class="ri-arrow-right-s-line"></i> <span class="current">@yield('page-title', isset($header) ? 'Profile' : 'Dashboard')</span></div>
                    <h1 class="page-title">@yield('page-title', isset($header) ? 'Profile' : 'Dashboard Overview')</h1>
                </div>
                
                <div class="top-bar-right">
                    <div class="wa-status-indicator">
                        <i class="ri-whatsapp-fill"></i>
                        <div class="dot"></div>
                        <span>WA Gateway Aktif</span>
                    </div>
                    <div class="date-badge">
                        <i class="ri-calendar-event-line"></i> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </div>
                </div>
            </div>
            
            @if(session('success'))
                <div style="margin-bottom: 16px; padding: 14px 20px; background: var(--emerald-50); border-left: 4px solid var(--emerald-500); border-radius: var(--radius-md); color: var(--emerald-700); font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <i class="ri-checkbox-circle-fill" style="font-size: 18px;"></i> {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div style="margin-bottom: 16px; padding: 14px 20px; background: var(--rose-50); border-left: 4px solid var(--rose-500); border-radius: var(--radius-md); color: var(--rose-700); font-size: 13px; font-weight: 600;">
                    @foreach($errors->all() as $error)
                        <div><i class="ri-error-warning-fill" style="margin-right: 4px;"></i> {{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>

    <!-- Toast Notifications Container -->
    <div id="toastContainer"></div>
    
    <script>
        function showToast(msg, type='success') {
            let container = document.getElementById('toastContainer');
            let toast = document.createElement('div');
            toast.className = `toast-card ${type}`;
            let icon = type === 'success' ? 'checkbox-circle-fill' : type === 'error' ? 'error-warning-fill' : 'information-fill';
            let color = type === 'success' ? 'var(--emerald-500)' : type === 'error' ? 'var(--rose-500)' : 'var(--primary-500)';
            toast.innerHTML = `
                <i class="ri-${icon}" style="font-size: 20px; color: ${color};"></i>
                <span style="font-size: 13px; font-weight: 600; color: var(--slate-800);">${msg}</span>
            `;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }
        window.showToast = showToast;
        
        // Mobile sidebar toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }
    </script>
    
    @stack('scripts')
    @yield('scripts')
</body>
</html>