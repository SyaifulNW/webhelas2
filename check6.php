<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kelas = \App\Models\Kelas::where('nama_kelas', 'like', '%Financial Expert%')->first();
if (!$kelas) {
    die("Financial Expert class not found.\n");
}

$users = \App\Models\User::all();

foreach ($users as $user) {
    $userId = $user->id;
    $userRole = strtolower($user->role);

    // --- Replicate DataController index method base query ---
    $query = \App\Models\Data::whereIn('status_peserta', ['peserta_baru', 'pindah_salesplan']);

    $viewType = ''; // standard request has empty view_type initially unless tab is clicked, or administrator
    if (empty($viewType) && $userRole === 'administrator') {
        $viewType = 'cs';
    }
    
    if ($viewType === 'cs') {
        $query->where('created_by_role', 'cs-mbc');
    } elseif ($viewType === 'chapter') {
        $query->whereIn('created_by_role', ['chapter', 'reseller', 'agen']);
    } elseif ($userRole === 'cs-mbc') {
        $query->whereNotIn('created_by_role', ['chapter', 'reseller', 'agen']);
    }

    if ($userRole === 'marketing') {
        $query->whereIn('leads', ['Marketing', 'Ads', 'Sosmed', 'Zoom', 'Open House']);
        $query->where('created_by_role', 'cs-mbc');
    } elseif (!in_array($userRole, ['administrator', 'manager', 'chapter', 'reseller', 'agen', 'operasional']) && $user->name !== 'Agus Setyo') {
        $query->where('created_by', $user->name);
    }

    // Absolute Total Database
    $totalAbsoluteDatabase = (clone $query)->count();

    // Now apply Belum Ikut filter for Financial Expert
    $ikutKelasFilter = '0';
    $daftarKelasFilter = $kelas->id;

    if ($ikutKelasFilter !== null && $ikutKelasFilter !== '') {
        if ($ikutKelasFilter == '1') {
            $query->whereHas('salesplan', function($q) use ($daftarKelasFilter) {
                $q->where('status', 'sudah_transfer');
                if (!empty($daftarKelasFilter)) {
                    $q->where('kelas_id', $daftarKelasFilter);
                }
            });
        } else {
            if (!empty($daftarKelasFilter)) {
                $query->whereDoesntHave('salesplan', function($q) use ($daftarKelasFilter) {
                    $q->where('status', 'sudah_transfer')
                      ->where('kelas_id', $daftarKelasFilter);
                });
            }
        }
    }

    $totalFiltered = (clone $query)->count();

    if ($totalAbsoluteDatabase == 151 || $totalFiltered == 150) {
        echo "User: " . $user->name . " (Role: " . $user->role . ")\n";
        echo "  Total Database: " . $totalAbsoluteDatabase . "\n";
        echo "  Total Filtered (Belum Ikut Financial Expert): " . $totalFiltered . "\n";
        
        // Find diff
        $qBase = \App\Models\Data::whereIn('status_peserta', ['peserta_baru', 'pindah_salesplan']);
        if ($viewType === 'cs') {
            $qBase->where('created_by_role', 'cs-mbc');
        } elseif ($userRole === 'cs-mbc') {
            $qBase->whereNotIn('created_by_role', ['chapter', 'reseller', 'agen']);
        }
        if (!in_array($userRole, ['administrator', 'manager', 'chapter', 'reseller', 'agen', 'operasional']) && $user->name !== 'Agus Setyo') {
            $qBase->where('created_by', $user->name);
        }
        
        $allIds = (clone $qBase)->pluck('id')->toArray();
        
        $qFiltered = clone $qBase;
        $qFiltered->whereDoesntHave('salesplan', function($q) use ($kelas) {
            $q->where('status', 'sudah_transfer')
              ->where('kelas_id', $kelas->id);
        });
        $filteredIds = (clone $qFiltered)->pluck('id')->toArray();
        
        $diff = array_diff($allIds, $filteredIds);
        echo "  Diff Count: " . count($diff) . "\n";
        foreach ($diff as $id) {
            $d = \App\Models\Data::find($id);
            echo "    - ID: " . $d->id . " | Name: " . $d->nama . " | Created by: " . $d->created_by . "\n";
        }
    }
}
