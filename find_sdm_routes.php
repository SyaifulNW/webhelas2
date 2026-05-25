<?php
$lines = file('routes/web.php');
foreach ($lines as $i => $line) {
    if (strpos($line, 'Route::') !== false && (stripos($line, 'cs') !== false || stripos($line, 'hr') !== false || stripos($line, 'employ') !== false || stripos($line, 'karyawan') !== false || stripos($line, 'salary') !== false || stripos($line, 'gaji') !== false || stripos($line, 'absensi') !== false || stripos($line, 'lembur') !== false || stripos($line, 'kpi') !== false)) {
        echo "Line " . ($i + 1) . ": " . trim($line) . "\n";
    }
}
