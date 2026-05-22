<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kelas = \App\Models\Kelas::where('nama_kelas', 'like', '%Financial Expert%')->first();
if (!$kelas) {
    die("Financial Expert class not found.\n");
}

echo "Financial Expert ID: " . $kelas->id . "\n\n";

// Let's query all Data records that have a salesplan for Financial Expert with status 'sudah_transfer'
$records = \App\Models\Data::whereHas('salesplan', function($q) use ($kelas) {
    $q->where('kelas_id', $kelas->id)->where('status', 'sudah_transfer');
})->get();

echo "Total records with sudah_transfer in Financial Expert: " . $records->count() . "\n";
foreach ($records as $r) {
    echo "- Name: " . $r->nama 
       . " | ID: " . $r->id
       . " | status_peserta: " . $r->status_peserta 
       . " | created_by_role: " . $r->created_by_role 
       . " | created_by: " . $r->created_by 
       . " | kota_nama: " . $r->kota_nama 
       . "\n";
}
