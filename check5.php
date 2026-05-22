<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kelas = \App\Models\Kelas::where('nama_kelas', 'like', '%Financial Expert%')->first();
if (!$kelas) {
    die("Financial Expert class not found.\n");
}

// Replicate query for Linda (cs-mbc)
$queryBase = \App\Models\Data::whereIn('status_peserta', ['peserta_baru', 'pindah_salesplan'])
    ->whereNotIn('created_by_role', ['chapter', 'reseller', 'agen'])
    ->where('created_by', 'Linda'); // since Linda is cs-mbc and not admin

// Let's get all 151 ids
$allIds = (clone $queryBase)->pluck('id')->toArray();
echo "Total Database count: " . count($allIds) . "\n";

// Let's apply the "Belum Ikut" filter for Financial Expert
$queryFiltered = clone $queryBase;
$queryFiltered->whereDoesntHave('salesplan', function($q) use ($kelas) {
    $q->where('status', 'sudah_transfer')
      ->where('kelas_id', $kelas->id);
});

$filteredIds = (clone $queryFiltered)->pluck('id')->toArray();
echo "Total Filtered count: " . count($filteredIds) . "\n";

// Find the ID that is in allIds but NOT in filteredIds
$diffIds = array_diff($allIds, $filteredIds);
echo "Difference IDs: " . implode(', ', $diffIds) . "\n";

foreach ($diffIds as $id) {
    $d = \App\Models\Data::find($id);
    echo "ID: " . $d->id . " | Name: " . $d->nama . " | created_by: " . $d->created_by . "\n";
    echo "Salesplans:\n";
    foreach ($d->salesplan as $sp) {
        echo "  - Kelas: " . ($sp->kelas?->nama_kelas ?? 'N/A') . " (ID: " . $sp->kelas_id . ") | Status: " . $sp->status . "\n";
    }
}
