@extends('layouts.app')

@section('page-title', 'Data Pegawai')
@section('content')

<style>
    /* Custom Styles for Employees Page */
    .stat-card-employee {
        background: white;
        border-radius: 20px;
        padding: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 1px solid #e8ecf1;
    }
    
    .stat-card-employee::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #4f46e5 0%, #7c3aed 100%);
    }
    
    .stat-card-employee:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .btn-add-employee {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-add-employee:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
    }
    
    .table-employee-premium {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 900px;
    }
    
    .table-employee-premium th {
        background: #f8fafc;
        padding: 14px 12px;
        text-align: left;
        font-weight: 600;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .table-employee-premium td {
        padding: 14px 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .table-employee-premium tr:hover td {
        background: #faf5ff;
    }
    
    .aplikasi-badge-premium {
        background: linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%);
        color: #4f46e5;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        display: inline-block;
        margin: 2px;
        white-space: nowrap;
    }
    
    .status-badge-active {
        background: #dcfce7;
        color: #166534;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .status-badge-inactive {
        background: #fee2e2;
        color: #991b1b;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .action-btn-employee {
        padding: 6px;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        margin: 0 3px;
        transition: all 0.2s;
    }
    
    .action-btn-employee.edit {
        color: #4f46e5;
    }
    .action-btn-employee.edit:hover {
        background: #e0e7ff;
    }
    .action-btn-employee.delete {
        color: #ef4444;
    }
    .action-btn-employee.delete:hover {
        background: #fee2e2;
    }
    
    .modal-employee {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
        z-index: 2000;
    }
    
    .modal-employee.active {
        display: flex;
    }
    
    .modal-content-employee {
        background: white;
        border-radius: 28px;
        max-width: 550px;
        width: 90%;
        max-height: 85vh;
        overflow-y: auto;
        animation: modalSlideIn 0.3s ease;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .modal-header-employee {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        background: white;
        border-radius: 28px 28px 0 0;
    }
    
    .modal-header-employee h3 {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #94a3b8;
        transition: color 0.2s;
    }
    
    .modal-close:hover {
        color: #ef4444;
    }
    
    .modal-body-employee {
        padding: 24px;
    }
    
    .form-group-employee {
        margin-bottom: 20px;
    }
    
    .form-label-employee {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
        display: block;
    }
    
    .form-input-employee {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        font-size: 14px;
        transition: all 0.2s;
    }
    
    .form-input-employee:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    .form-grid-employee {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    
    .btn-primary-employee {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 14px;
        font-weight: 600;
        cursor: pointer;
        flex: 1;
        transition: all 0.2s;
    }
    
    .btn-primary-employee:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    
    .btn-secondary-employee {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 12px 20px;
        border-radius: 14px;
        font-weight: 600;
        cursor: pointer;
        flex: 1;
    }
    
    .btn-secondary-employee:hover {
        background: #e2e8f0;
    }
    
    .info-text {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 4px;
    }
    
    .search-box-employee {
        background: white;
        border-radius: 16px;
        padding: 4px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .search-box-employee input {
        flex: 1;
        border: none;
        padding: 12px 16px;
        font-size: 13px;
        outline: none;
        background: transparent;
    }
    
    .search-box-employee i {
        padding-left: 16px;
        color: #94a3b8;
    }
</style>

<div class="fade-in-up">
    <!-- Filter & Action Bar -->
    <div class="filter-bar">
        <div class="filter-group">
            <i class="ri-search-line" style="color: var(--primary-500); font-size: 18px;"></i>
            <span>Pencarian:</span>
            <input type="text" id="searchEmployee" class="filter-input" placeholder="Cari nama pegawai, NIP, email, aplikasi..." style="min-width: 280px;">
        </div>

        <div style="display: flex; gap: 10px;">
            <button onclick="openEmployeeModal()" class="btn-primary-grad">
                <i class="ri-add-line"></i> Tambah Pegawai Baru
            </button>
        </div>
    </div>
    
    <!-- Stats Cards Grid -->
    <div class="stats-grid">
        <div class="stat-card indigo">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--primary-50); color: var(--primary-600);"><i class="ri-group-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $statistics['total'] ?? 0 }}</div>
                        <div class="stat-label">Total Pegawai</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--primary-100); color: var(--primary-800);">Total</span>
            </div>
        </div>

        <div class="stat-card emerald">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--emerald-50); color: var(--emerald-500);"><i class="ri-user-star-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $statistics['active'] ?? 0 }}</div>
                        <div class="stat-label">Pegawai Aktif</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--emerald-100); color: var(--emerald-700);">Aktif</span>
            </div>
        </div>

        <div class="stat-card rose">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--rose-50); color: var(--rose-600);"><i class="ri-user-unfollow-line"></i></div>
                    <div>
                        <div class="stat-value" style="color: var(--rose-600);">{{ $statistics['inactive'] ?? 0 }}</div>
                        <div class="stat-label">Pegawai Nonaktif</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: var(--rose-100); color: var(--rose-600);">Nonaktif</span>
            </div>
        </div>

        <div class="stat-card cyan">
            <div class="stat-card-row">
                <div class="stat-card-left">
                    <div class="stat-icon" style="background: var(--cyan-50); color: var(--cyan-500);"><i class="ri-apps-line"></i></div>
                    <div>
                        <div class="stat-value">{{ $allAplikasi ?? 0 }}</div>
                        <div class="stat-label">Layanan Aplikasi</div>
                    </div>
                </div>
                <span class="stat-tag" style="background: #cffafe; color: #0e7490;">Layanan</span>
            </div>
        </div>
    </div>
    
    <!-- Employee Table Container -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3><i class="ri-group-fill" style="color: var(--primary-500);"></i> Daftar Data Pegawai</h3>
                <p>Kelola data pegawai, NIP, email, dan aplikasi yang dikelola</p>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container" style="border: none; border-radius: 0;">
                <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Pegawai</th>
                    <th>Email Korporat</th>
                    <th>Layanan Aplikasi</th>
                    <th>No. Telepon</th>
                    <th>Status</th>
                    <th style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="employeeTableBody">
                @forelse(($employees ?? []) as $index => $emp)
                <tr>
                    <td>{{ $index + 1 + (($employees->currentPage() ?? 1) - 1) * ($employees->perPage() ?? 10) }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #e0e7ff, #ede9fe); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <span style="font-weight: 700; color: #4f46e5;">{{ substr($emp->name, 0, 1) }}</span>
                            </div>
                            <strong style="color: #1e293b;">{{ $emp->name }}</strong>
                        </div>
                    </td>
                    <td style="font-size: 12px; color: #4f46e5;">{{ $emp->email_korporat ?: $emp->email }}</td>
                    <td>
                        @php
                            $aplikasiList = $emp->aplikasi ? explode(',', $emp->aplikasi) : [];
                        @endphp
                        @foreach($aplikasiList as $app)
                            <span class="aplikasi-badge-premium">{{ trim($app) }}</span>
                        @endforeach
                        @if(empty($aplikasiList))
                            <span style="color: #94a3b8; font-size: 11px;">-</span>
                        @endif
                    </td>
                    <td>{{ $emp->phone ?: '-' }}</td>
                    <td>
                        <span class="{{ $emp->is_active ? 'status-badge-active' : 'status-badge-inactive' }}">
                            <i class="ri-{{ $emp->is_active ? 'checkbox-circle' : 'close-circle' }}-line"></i>
                            {{ $emp->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <span class="action-btn-employee edit" onclick="editEmployee({{ $emp->id }})" title="Edit">
                            <i class="ri-edit-line"></i>
                        </span>
                        <span class="action-btn-employee delete" onclick="deleteEmployee({{ $emp->id }})" title="Hapus">
                            <i class="ri-delete-bin-line"></i>
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 60px; text-align: center; color: #94a3b8;">
                        <i class="ri-user-line" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
                        Belum ada data pegawai
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(isset($employees) && method_exists($employees, 'links'))
    <div style="margin-top: 20px;">
        {{ $employees->links() }}
    </div>
    @endif
</div>

<!-- Modal Tambah/Edit Pegawai -->
<div id="employeeModal" class="modal-employee">
    <div class="modal-content-employee">
        <div class="modal-header-employee">
            <h3 id="modalTitle">Tambah Pegawai</h3>
            <button onclick="closeEmployeeModal()" class="modal-close">&times;</button>
        </div>
        <form id="employeeForm" class="modal-body-employee">
            @csrf
            <input type="hidden" name="_method" id="methodField" value="POST">
            <input type="hidden" name="id" id="employeeId">
            
            <div class="form-group-employee">
                <label class="form-label-employee">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name" id="emp_name" required class="form-input-employee" placeholder="Masukkan nama lengkap">
            </div>
            
            <div class="form-grid-employee">
                <div class="form-group-employee">
                    <label class="form-label-employee">NIP</label>
                    <input type="text" name="nip" id="emp_nip" class="form-input-employee" placeholder="Nomor Induk Pegawai">
                </div>
                <div class="form-group-employee">
                    <label class="form-label-employee">Jabatan</label>
                    <input type="text" name="position" id="emp_position" class="form-input-employee" placeholder="Jabatan">
                </div>
            </div>
            
            <div class="form-grid-employee">
                <div class="form-group-employee">
                    <label class="form-label-employee">Email <span style="color: #ef4444;">*</span></label>
                    <input type="email" name="email" id="emp_email" required class="form-input-employee" placeholder="email@example.com">
                </div>
                <div class="form-group-employee">
                    <label class="form-label-employee">Email Korporat</label>
                    <input type="email" name="email_korporat" id="emp_email_korporat" class="form-input-employee" placeholder="email@iconpln.co.id">
                </div>
            </div>
            
            <div class="form-group-employee">
                <label class="form-label-employee">Layanan Aplikasi</label>
                <input type="text" name="aplikasi" id="emp_aplikasi" class="form-input-employee" placeholder="Contoh: EAM Distribusi, BBO, MAPP">
                <div class="info-text">Pisahkan dengan koma jika lebih dari satu aplikasi</div>
            </div>
            
            <div class="form-grid-employee">
                <div class="form-group-employee">
                    <label class="form-label-employee">No. Telepon</label>
                    <input type="text" name="phone" id="emp_phone" class="form-input-employee" placeholder="Nomor telepon">
                </div>
                <div class="form-group-employee">
                    <label class="form-label-employee" style="display: block; margin-bottom: 8px;">Status</label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" id="emp_is_active" value="1" checked style="width: 18px; height: 18px;">
                        <span style="font-size: 13px;">Aktif</span>
                    </label>
                </div>
            </div>
            
            <div style="display: flex; gap: 16px; margin-top: 24px;">
                <button type="button" onclick="closeEmployeeModal()" class="btn-secondary-employee">Batal</button>
                <button type="submit" class="btn-primary-employee">
                    <i class="ri-save-line"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Open Modal
    function openEmployeeModal() {
        document.getElementById('employeeModal').classList.add('active');
        document.getElementById('employeeForm').reset();
        document.getElementById('employeeForm').action = "{{ route('employees.store') }}";
        document.getElementById('methodField').value = "POST";
        document.getElementById('modalTitle').innerText = "Tambah Pegawai";
        document.getElementById('emp_is_active').checked = true;
    }
    
    // Close Modal
    function closeEmployeeModal() {
        document.getElementById('employeeModal').classList.remove('active');
    }
    
    // Edit Employee
    function editEmployee(id) {
        fetch(`/employees/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('employeeId').value = data.id;
                document.getElementById('emp_name').value = data.name;
                document.getElementById('emp_nip').value = data.nip || '';
                document.getElementById('emp_position').value = data.position || '';
                document.getElementById('emp_email').value = data.email;
                document.getElementById('emp_email_korporat').value = data.email_korporat || '';
                document.getElementById('emp_aplikasi').value = data.aplikasi || '';
                document.getElementById('emp_phone').value = data.phone || '';
                document.getElementById('emp_is_active').checked = data.is_active == 1;
                
                document.getElementById('employeeForm').action = `/employees/${id}`;
                document.getElementById('methodField').value = "PUT";
                document.getElementById('modalTitle').innerText = "Edit Pegawai";
                openEmployeeModal();
            });
    }
    
    // Delete Employee
    function deleteEmployee(id) {
        if (confirm('Apakah Anda yakin ingin menghapus pegawai ini?')) {
            fetch(`/employees/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            });
        }
    }
    
    // Submit Form with AJAX
    document.getElementById('employeeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const method = document.getElementById('methodField').value;
        const id = document.getElementById('employeeId').value;
        let url = form.action;
        
        if (method === 'PUT') {
            url = `/employees/${id}`;
            formData.append('_method', 'PUT');
        }
        
        // Disable submit button
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Menyimpan...';
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
    
    // Search Functionality
    document.getElementById('searchEmployee').addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#employeeTableBody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(search) ? '' : 'none';
        });
    });
    
    // Close modal when clicking outside
    document.getElementById('employeeModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEmployeeModal();
        }
    });
</script>

<style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
</style>
@endsection