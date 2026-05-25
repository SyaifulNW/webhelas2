<?php
$lines = file('resources/views/layouts/masteradmin.blade.php');
foreach ($lines as $i => $line) {
    if (strpos($line, 'DATABASE CALON PESERTA') !== false || strpos($line, 'database') !== false) {
        if (strpos($line, 'nav-link') !== false || strpos($line, 'DATABASE') !== false) {
            echo "Line " . ($i + 1) . ": " . trim($line) . "\n";
        }
    }
}
