<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kelas = \App\Models\Kelas::where('nama_kelas', 'like', '%Financial Expert%')->first();
if ($kelas) {
    $data = \App\Models\Data::whereHas('salesplan', function($q) use ($kelas) {
        $q->where('kelas_id', $kelas->id)->where('status', 'sudah_transfer');
    })->get();
    foreach($data as $d) {
        echo $d->nama . ' - ' . $d->no_wa . PHP_EOL;
    }
} else {
    echo 'Kelas not found';
}
