<?php
$lines = file('resources/views/admin/salesplan/index.blade.php');
foreach ($lines as $i => $line) {
    if (strpos($line, 'Kelas') !== false || strpos($line, '<form') !== false || strpos($line, '<select') !== false || strpos($line, 'Search') !== false) {
        echo "Line " . ($i + 1) . ": " . trim($line) . "\n";
    }
}
