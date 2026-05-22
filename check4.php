<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kelas = \App\Models\Kelas::where('nama_kelas', 'like', '%Financial Expert%')->first();
if (!$kelas) {
    die("Financial Expert class not found.\n");
}

// Let's inspect the users table to see what users exist and what roles they have
$users = \App\Models\User::all();
foreach ($users as $u) {
    // Simulate the query logic for this user
    $userRole = strtolower($u->role);
    $viewType = ($userRole === 'administrator') ? 'cs' : '';
    
    $query = \App\Models\Data::whereIn('status_peserta', ['peserta_baru', 'pindah_salesplan']);
    
    if ($viewType === 'cs') {
        $query->where('created_by_role', 'cs-mbc');
    } elseif ($viewType === 'chapter') {
        $query->whereIn('created_by_role', ['chapter', 'reseller', 'agen']);
    } elseif ($userRole === 'cs-mbc') {
        $query->whereNotIn('created_by_role', ['chapter', 'reseller', 'agen']);
    }

    if ($userRole === 'marketing') {
        if (stripos($u->name, 'Felmi') !== false) {
            $query->whereIn('leads', ['Event', 'Open House']);
        } elseif (stripos($u->name, 'Nisa') !== false) {
            $query->whereIn('leads', ['Online', 'Sosmed']);
        } else {
            $query->whereIn('leads', ['Marketing', 'Ads', 'Sosmed', 'Zoom', 'Open House']);
        }
        $query->where('created_by_role', 'cs-mbc');
    } elseif (!in_array($userRole, ['administrator', 'manager', 'chapter', 'reseller', 'agen', 'operasional']) && $u->name !== 'Agus Setyo') {
        $query->where('created_by', $u->name);
    }
    
    $totalAbsoluteDatabase = (clone $query)->count();
    
    // Find those who are 'sudah_transfer' for Financial Expert
    $sudahTransfer = (clone $query)->whereHas('salesplan', function($q) use ($kelas) {
        $q->where('kelas_id', $kelas->id)->where('status', 'sudah_transfer');
    })->get();
    
    if ($totalAbsoluteDatabase > 0) {
        echo "User: " . $u->name . " (Role: " . $u->role . ") - Total Database: " . $totalAbsoluteDatabase . "\n";
        echo "  Sudah Ikut (count=" . $sudahTransfer->count() . "):\n";
        foreach ($sudahTransfer as $d) {
            echo "    - Name: " . $d->nama . " (ID: " . $d->id . ") | Created By: " . $d->created_by . " | Created By Role: '" . $d->created_by_role . "'\n";
        }
    }
}
