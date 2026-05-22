<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kelas = \App\Models\Kelas::where('nama_kelas', 'like', '%Financial Expert%')->first();
if (!$kelas) {
    die("Kelas Financial Expert not found\n");
}

// Replicate base query for cs-mbc view type
$query = \App\Models\Data::whereIn('status_peserta', ['peserta_baru', 'pindah_salesplan'])
    ->where('created_by_role', 'cs-mbc');

// Let's get the 1 person who has status 'sudah_transfer' for Financial Expert
$sudahIkut = (clone $query)->whereHas('salesplan', function($q) use ($kelas) {
    $q->where('kelas_id', $kelas->id)->where('status', 'sudah_transfer');
})->get();

echo "Sudah Ikut (count=" . $sudahIkut->count() . "):\n";
foreach($sudahIkut as $d) {
    echo "- Name: " . $d->nama . ", WA: " . $d->no_wa . ", Created By: " . $d->created_by . "\n";
}

// Let's also see if there's any other context
