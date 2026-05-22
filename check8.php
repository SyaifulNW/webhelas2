<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$classes = \App\Models\Kelas::all();
foreach ($classes as $c) {
    echo "ID: " . $c->id . " | Nama: " . $c->nama_kelas . "\n";
}
