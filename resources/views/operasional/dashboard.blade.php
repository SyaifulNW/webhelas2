@extends('layouts.masteradmin')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --premium-blue: #4e73df;
        --premium-success: #1cc88a;
        --premium-warning: #f6c23e;
        --premium-dark: #2c3e50;
        --glass-bg: rgba(255, 255, 255, 0.9);
    }

    .dashboard-container {
        padding: 2rem;
        background: #f8f9fc;
        min-height: 100vh;
    }

    .welcome-card {
        background: linear-gradient(135deg, var(--premium-blue) 0%, #224abe 100%);
        color: white;
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 10px 30px rgba(78, 115, 223, 0.2);
        position: relative;
        overflow: hidden;
    }

    .welcome-card::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .stat-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .nav-pills-premium {
        background: #fff;
        padding: 0.5rem;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        display: inline-flex;
        margin-bottom: 2rem;
    }

    .nav-pills-premium .nav-link {
        border-radius: 50px;
        padding: 0.8rem 2rem;
        color: #5a5c69;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .nav-pills-premium .nav-link.active {
        background: var(--premium-blue);
        color: white;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
    }

    .table-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-premium thead {
        background: #f8f9fc;
        color: #4e73df;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
    }

    .table-premium th {
        padding: 1.25rem !important;
        border: none !important;
    }

    .table-premium td {
        padding: 1.25rem !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f3f9 !important;
    }

    .badge-premium {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
    }

    .performance-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border: 10px solid #f8f9fc;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .performance-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--premium-blue);
    }

    .filter-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .bg-soft-success {
        background-color: rgba(28, 200, 138, 0.1);
    }
    
    /* Premium Colored Tabs Styling (Horizontal - Attached) */
    .premium-tab {
        border-radius: 0px !important; /* Square for joined look */
        padding: 12px 25px !important;
        font-size: 1rem !important;
        font-weight: 700 !important;
        background: #f1f3f9;
        color: #4e73df;
        border: none !important;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-right: 2px !important; /* Tiny gap for border-left visibility */
        justify-content: center;
        min-width: 160px;
        height: 100%;
        border-left: 4px solid #ddd !important; /* Default border */
    }

    /* Round ends of the whole tab bar */
    .nav-item:first-child .premium-tab { border-top-left-radius: 10px !important; border-left: none !important; }
    .nav-item:last-child .premium-tab { border-top-right-radius: 10px !important; }

    .premium-tab i {
        font-size: 1.2rem;
    }

    /* Active State & Specific Colors - "Melekat" Fixed */
    .premium-tab.active {
        color: white !important;
        border-bottom: none !important;
        transform: none !important; /* Don't float */
        box-shadow: none !important;
        margin-bottom: -1px !important; /* Touch the line below */
        z-index: 10;
    }

    /* Menjadikan warna dasar semua tab solid dan tegas */
    .premium-tab.color-primary   { background: #4e73df !important; border-left-color: #2e59d9 !important; color: white !important; }
    .premium-tab.color-danger    { background: #e74a3b !important; border-left-color: #be2617 !important; color: white !important; }
    .premium-tab.color-warning   { background: #f6c23e !important; border-left-color: #df9c00 !important; color: #111 !important; }
    .premium-tab.color-info      { background: #6610f2 !important; border-left-color: #520dc2 !important; color: white !important; }
    .premium-tab.color-success   { background: #1cc88a !important; border-left-color: #13855c !important; color: white !important; }
    .premium-tab.color-dark      { background: #2c3e50 !important; border-left-color: #1a252f !important; color: white !important; }

    /* Active State: Sedikit lebih terang dan menonjol */
    .premium-tab.active {
        filter: brightness(1.1) !important;
        box-shadow: 0 -3px 8px rgba(0,0,0,0.1) !important;
    }
    
    /* Warna teks khusus untuk active tab agar tetap terbaca */
    .premium-tab.active.color-warning { color: #111 !important; }

    /* Inactive State: Sedikit lebih gelap agar membedakan dengan active */
    .premium-tab:not(.active) { 
        filter: brightness(0.85) contrast(1.1);
        opacity: 0.95;
    }

    .premium-tab:hover:not(.active) {
        filter: brightness(0.95);
        transform: translateY(-2px);
    }

    .premium-divider-container {
        padding: 0;
        margin-top: -1px; /* Overlap with tabs */
        margin-bottom: 2rem;
        z-index: 2;
    }
    
    /* Divider Styling */
    .premium-divider-container {
        padding: 0 2rem;
        margin-bottom: 2.5rem;
    }
    .premium-divider {
        height: 2px;
        background: #e3e6f0;
        border-radius: 10px;
        position: relative;
    }
    .divider-indicator {
        position: absolute;
        height: 4px;
        background: #4e73df;
        border-radius: 10px;
        top: -1px;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        box-shadow: 0 2px 8px rgba(78, 115, 223, 0.4);
        width: 100px;
        left: 0;
    }

    /* Color-specific indicators (optional, matching the active tab color) */
    .bg-primary-indicator { background: #4e73df !important; box-shadow: 0 2px 8px rgba(78, 115, 223, 0.4); }
    .bg-danger-indicator  { background: #e74a3b !important; box-shadow: 0 2px 8px rgba(231, 74, 59, 0.4); }
    .bg-warning-indicator { background: #f6c23e !important; box-shadow: 0 2px 8px rgba(246, 194, 62, 0.4); }
    .bg-info-indicator    { background: #36b9cc !important; box-shadow: 0 2px 8px rgba(54, 185, 204, 0.4); }
    .bg-success-indicator { background: #1cc88a !important; box-shadow: 0 2px 8px rgba(28, 200, 138, 0.4); }
    .bg-dark-indicator    { background: #2c3e50 !important; box-shadow: 0 2px 8px rgba(44, 62, 80, 0.4); }

    .op-card {
        border-radius: 15px !important;
        border: none !important;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
    }
    .op-card-title {
        font-size: 1.25rem !important;
        font-weight: 800 !important;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #ffffff;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    }
    .op-card-text {
        color: rgba(255,255,255,0.9) !important;
        font-size: 0.9rem;
        margin-top: 10px;
    }
    .op-card-icon {
        opacity: 0.4;
        filter: drop-shadow(0 0 5px rgba(255,255,255,0.3));
    }

    /* Monitoring Perbaikan Table Styling */
    .monitoring-header {
        background-color: #f28d8d !important;
        color: #000;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 12px !important;
        border: 2px solid #000;
    }
    .table-monitoring {
        border: 2px solid #000;
    }
    .table-monitoring th, .table-monitoring td {
        border: 1px solid #000 !important;
        vertical-align: middle;
    }
    .table-monitoring thead th {
        background-color: #4e73df !important; /* Blue shading */
        color: #fff !important; /* White font */
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        border: 1px solid #2e59d9 !important;
    }
    .table-monitoring .form-control-inline {
        background: transparent;
        border: none;
        padding: 4px 8px;
        width: 100%;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .table-monitoring .form-control-inline:focus {
        background: #fff;
        border: 1px solid var(--premium-blue);
        box-shadow: 0 0 5px rgba(78, 115, 223, 0.2);
    }
    /* Dropdown colors */
    .status-dropdown {
        font-weight: 700;
        border-radius: 20px !important;
        padding: 4px 15px !important;
        font-size: 0.8rem;
        border: 1px solid rgba(0,0,0,0.2) !important; /* Borders made clear */
        appearance: auto !important; /* Show arrow */
        text-align: center;
        width: auto !important;
        min-width: 140px;
        cursor: pointer;
    }
    .bg-status-cari-vendor { background-color: #f6c23e !important; color: #fff !important; }
    .bg-status-pending     { background-color: #858796 !important; color: #fff !important; }
    .bg-status-on-progress { background-color: #36b9cc !important; color: #fff !important; }
    .bg-status-selesai     { background-color: #1cc88a !important; color: #fff !important; }
    
    .saving-indicator {
        display: none;
        font-size: 0.7rem;
        color: #1cc88a;
        margin-left: 5px;
    }


</style>

<div class="dashboard-container">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="h3 text-gray-800 font-weight-bold">Dashboard Operasional</h1>
            <p class="text-muted">Selamat datang kembali, {{ $csName }}!</p>
        </div>
        <div class="col-md-6 text-right">
            <!-- Filter removed -->
        </div>
    </div>


    <!-- Navigation Tabs (Main) -->
    <ul class="nav nav-tabs border-0 mt-2 mb-0 d-flex flex-wrap gap-0 justify-content-center px-4" id="opMainTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold premium-tab color-primary" id="inventaris-tab" data-toggle="tab" data-target="#inventaris" type="button" role="tab">
                <i class="fas fa-building"></i> <span>Inventaris Kantor</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold premium-tab color-warning" id="kebutuhan-mbc-tab" data-toggle="tab" data-target="#kebutuhan-mbc" type="button" role="tab">
                <i class="fas fa-chalkboard-teacher"></i> <span>Monitoring Perbaikan</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold premium-tab color-success" id="kebutuhan-m1t-tab" data-toggle="tab" data-target="#kebutuhan-m1t" type="button" role="tab">
                <i class="fas fa-user-graduate"></i> <span>Pengadaan Barang</span>
            </button>
        </li>
    </ul>

    <!-- Premium Divider with Dynamic Indicator -->
    <div class="premium-divider-container">
        <div class="premium-divider">
            <div id="tab-indicator" class="divider-indicator"></div>
        </div>
    </div>

    <div class="tab-content" id="opMainTabsContent">
        <!-- Tab 2: Inventaris -->
        <div class="tab-pane fade show active" id="inventaris" role="tabpanel">
            <div class="row justify-content-center mt-4">
                <div class="col-xl-6 col-md-8">
                    <div class="card op-card shadow border-0" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="fas fa-building fa-4x text-white op-card-icon"></i>
                            </div>
                            <h3 class="op-card-title text-center mb-3">Modul Inventaris Kantor</h3>
                            <p class="op-card-text text-center" style="font-size: 1.1rem;">Manajemen aset, perbaikan fasilitas, dan stok perlengkapan kantor. (Sedang dalam pengembangan)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 3: Monitoring Perbaikan -->
        <div class="tab-pane fade" id="kebutuhan-mbc" role="tabpanel">
            <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header monitoring-header text-center">
                    LIST MONITORING KERUSAKAN FASILITAS GEDUNG
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="font-weight-bold mb-0 text-dark"><i class="fas fa-tools mr-2 text-warning"></i> Data Monitoring</h5>
                        <button class="btn btn-primary btn-sm shadow-sm" style="border-radius: 8px;" id="btnTambahPerbaikanInline">
                            <i class="fas fa-plus mr-1"></i> Tambah Data
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-monitoring mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">NO.</th>
                                    <th>FASILITAS</th>
                                    <th>KERUSAKAN</th>
                                    <th class="text-center" style="width: 250px;">TIMELINE</th>
                                    <th class="text-center">PROGRESS</th>
                                    <th>RENCANA</th>
                                    <th class="text-right" style="width: 160px;">BUDGET</th>
                                    <th class="text-center" style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="monitoring-table-body">
                                @php $totalBudget = 0; @endphp
                                @forelse($monitoringPerbaikan as $key => $item)
                                    @php $totalBudget += (float)$item->budget; @endphp
                                    <tr data-id="{{ $item->id }}">
                                        <td class="text-center no-col">{{ $key + 1 }}</td>
                                        <td>
                                            <input type="text" class="form-control-inline font-weight-bold live-edit" data-field="fasilitas" value="{{ $item->fasilitas }}">
                                        </td>
                                        <td>
                                            <textarea class="form-control-inline live-edit" data-field="kerusakan" rows="1">{{ $item->kerusakan }}</textarea>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <input type="date" class="form-control-inline text-center live-edit" data-field="tanggal_mulai" value="{{ $item->tanggal_mulai }}" style="width: 130px;">
                                                <span class="mx-1 text-muted">-</span>
                                                <input type="date" class="form-control-inline text-center live-edit" data-field="tanggal_selesai" value="{{ $item->tanggal_selesai }}" style="width: 130px;">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusClass = '';
                                                if($item->progress == 'Cari Vendor') $statusClass = 'bg-status-cari-vendor';
                                                elseif($item->progress == 'Pending') $statusClass = 'bg-status-pending';
                                                elseif($item->progress == 'On Progress') $statusClass = 'bg-status-on-progress';
                                                elseif($item->progress == 'Selesai') $statusClass = 'bg-status-selesai';
                                            @endphp
                                            <select class="form-control-inline live-edit status-dropdown {{ $statusClass }}" data-field="progress">
                                                <option value="Cari Vendor" {{ $item->progress == 'Cari Vendor' ? 'selected' : '' }} class="bg-status-cari-vendor">Cari Vendor</option>
                                                <option value="Pending" {{ $item->progress == 'Pending' ? 'selected' : '' }} class="bg-status-pending">Pending</option>
                                                <option value="On Progress" {{ $item->progress == 'On Progress' ? 'selected' : '' }} class="bg-status-on-progress">On Progress</option>
                                                <option value="Selesai" {{ $item->progress == 'Selesai' ? 'selected' : '' }} class="bg-status-selesai">Selesai</option>
                                            </select>
                                        </td>
                                        <td>
                                            <textarea class="form-control-inline live-edit" data-field="rencana" rows="1">{{ $item->rencana }}</textarea>
                                        </td>
                                        <td class="text-right">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <span class="mr-1 text-success font-weight-bold">Rp</span>
                                                <input type="number" class="form-control-inline text-right text-success font-weight-bold live-edit budget-input" data-field="budget" value="{{ $item->budget }}">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-link text-danger p-0 delete-btn" data-id="{{ $item->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="empty-row">
                                        <td colspan="8" class="text-center py-5 text-muted">Belum ada data monitoring perbaikan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f8f9fc;">
                                    <td colspan="6" class="text-right font-weight-bold">TOTAL ESTIMASI BUDGET</td>
                                    <td class="text-right font-weight-bold text-danger" style="font-size: 1.1rem;">
                                        Rp <span id="grand-total-budget">{{ number_format($totalBudget, 0, ',', '.') }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 4: Pengadaan Barang -->
        <div class="tab-pane fade" id="kebutuhan-m1t" role="tabpanel">
            <div class="row justify-content-center mt-4">
                <div class="col-xl-6 col-md-8">
                    <div class="card op-card shadow border-0" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%) !important;">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="fas fa-user-graduate fa-4x text-white op-card-icon"></i>
                            </div>
                            <h3 class="op-card-title text-center mb-3">Modul Pengadaan Barang</h3>
                            <p class="op-card-text text-center" style="font-size: 1.1rem;">Manajemen stok buku, seragam, dan kebutuhan operasional M1T. (Sedang dalam pengembangan)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ====================== LIVE EDIT MONITORING ======================

        // 1. Add New Row - Refresh-less
        $('#btnTambahPerbaikanInline').on('click', function() {
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menambah...');

            $.ajax({
                url: "{{ route('monitoring-perbaikan.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    fasilitas: "Data Baru",
                    progress: "Pending"
                },
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="fas fa-plus mr-1"></i> Tambah Data');
                    $('.empty-row').remove();
                    
                    const item = res.data; // Assuming controller returns the object
                    const rowCount = $('#monitoring-table-body tr').length + 1;
                    
                    const newRow = `
                        <tr data-id="${item.id}">
                            <td class="text-center no-col">${rowCount}</td>
                            <td><input type="text" class="form-control-inline font-weight-bold live-edit" data-field="fasilitas" value="${item.fasilitas}"></td>
                            <td><textarea class="form-control-inline live-edit" data-field="kerusakan" rows="1"></textarea></td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <input type="date" class="form-control-inline text-center live-edit" data-field="tanggal_mulai" style="width: 130px;">
                                    <span class="mx-1 text-muted">-</span>
                                    <input type="date" class="form-control-inline text-center live-edit" data-field="tanggal_selesai" style="width: 130px;">
                                </div>
                            </td>
                            <td class="text-center">
                                <select class="form-control-inline live-edit status-dropdown bg-status-pending" data-field="progress">
                                    <option value="Cari Vendor" class="bg-status-cari-vendor">Cari Vendor</option>
                                    <option value="Pending" selected class="bg-status-pending">Pending</option>
                                    <option value="On Progress" class="bg-status-on-progress">On Progress</option>
                                    <option value="Selesai" class="bg-status-selesai">Selesai</option>
                                </select>
                            </td>
                            <td><textarea class="form-control-inline live-edit" data-field="rencana" rows="1"></textarea></td>
                            <td class="text-right">
                                <div class="d-flex align-items-center justify-content-end">
                                    <span class="mr-1 text-success font-weight-bold">Rp</span>
                                    <input type="number" class="form-control-inline text-right text-success font-weight-bold live-edit budget-input" data-field="budget" value="0">
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-link text-danger p-0 delete-btn" data-id="${item.id}"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    `;
                    $('#monitoring-table-body').append(newRow);
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-plus mr-1"></i> Tambah Data');
                }
            });
        });

        // 2. Live Update on Blur or Change
        $(document).on('change', '.live-edit', function() {
            const input = $(this);
            const tr = input.closest('tr');
            const id = tr.data('id');
            const field = input.data('field');
            const value = input.val();

            // Show temporary loading indicator or style
            input.css('background-color', '#fff9db');

            $.ajax({
                url: "/monitoring-perbaikan/update/" + id,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "PUT",
                    [field]: value,
                    // Send all fields to satisfy validation if necessary, or update controller to be partial
                    fasilitas: tr.find('[data-field="fasilitas"]').val(),
                    kerusakan: tr.find('[data-field="kerusakan"]').val(),
                    tanggal_mulai: tr.find('[data-field="tanggal_mulai"]').val(),
                    tanggal_selesai: tr.find('[data-field="tanggal_selesai"]').val(),
                    progress: tr.find('[data-field="progress"]').val(),
                    rencana: tr.find('[data-field="rencana"]').val(),
                    budget: tr.find('[data-field="budget"]').val(),
                },
                success: function(res) {
                    input.css('background-color', 'transparent');
                    updateGrandTotal();

                    // Update dropdown color if it was a status change
                    if (field === 'progress') {
                        input.removeClass('bg-status-cari-vendor bg-status-pending bg-status-on-progress bg-status-selesai');
                        if (value === 'Cari Vendor') input.addClass('bg-status-cari-vendor');
                        else if (value === 'Pending') input.addClass('bg-status-pending');
                        else if (value === 'On Progress') input.addClass('bg-status-on-progress');
                        else if (value === 'Selesai') input.addClass('bg-status-selesai');
                    }
                    
                    // Toast notification
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true
                    });
                    Toast.fire({ icon: 'success', title: 'Tersimpan' });
                },
                error: function() {
                    input.css('background-color', '#ffe3e3');
                    Swal.fire('Error', 'Gagal menyimpan perubahan', 'error');
                }
            });
        });

        // 3. Update Grand Total locally
        function updateGrandTotal() {
            let total = 0;
            $('.budget-input').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#grand-total-budget').text(new Intl.NumberFormat('id-ID').format(total));
        }

        // 4. Delete Data - Refresh-less
        $(document).on('click', '.delete-btn', function() {
            const btn = $(this);
            const tr = btn.closest('tr');
            const id = btn.data('id');
            
            Swal.fire({
                title: 'Hapus data?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/monitoring-perbaikan/destroy/" + id,
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(res) {
                            tr.fadeOut(400, function() {
                                $(this).remove();
                                updateGrandTotal();
                                
                                // Re-index numbers
                                $('#monitoring-table-body tr').each(function(index) {
                                    $(this).find('.no-col').text(index + 1);
                                });

                                if ($('#monitoring-table-body tr').length === 0) {
                                    $('#monitoring-table-body').append(`
                                        <tr class="empty-row">
                                            <td colspan="9" class="text-center py-5 text-muted">Belum ada data monitoring perbaikan.</td>
                                        </tr>
                                    `);
                                }
                            });
                        }
                    });
                }
            });
        });

        // ====================== TAB LOGIC ======================
        function moveIndicator(target) {
            const indicator = document.getElementById('tab-indicator');
            const divider = document.querySelector('.premium-divider');
            const tab = target instanceof jQuery ? target[0] : target;
            if (!indicator || !divider || !tab) return;
            const tabRect = tab.getBoundingClientRect();
            const dividerRect = divider.getBoundingClientRect();
            const leftPos = tabRect.left - dividerRect.left + (tabRect.width / 2) - 50;
            indicator.style.left = leftPos + 'px';
            indicator.className = 'divider-indicator';
            if (tab.classList.contains('color-primary')) indicator.classList.add('bg-primary-indicator');
            if (tab.classList.contains('color-danger'))  indicator.classList.add('bg-danger-indicator');
            if (tab.classList.contains('color-warning')) indicator.classList.add('bg-warning-indicator');
            if (tab.classList.contains('color-info'))    indicator.classList.add('bg-info-indicator');
            if (tab.classList.contains('color-success')) indicator.classList.add('bg-success-indicator');
            if (tab.classList.contains('color-dark'))    indicator.classList.add('bg-dark-indicator');
        }

        setTimeout(() => {
            const activeTab = document.querySelector('.premium-tab.active');
            if (activeTab) moveIndicator(activeTab);
        }, 500);

        $('button[data-toggle="tab"]').on('show.bs.tab', function (e) {
            moveIndicator(e.target);
        });

    });
</script>
@endsection
