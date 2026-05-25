<?php
$lines = file('app/Http/Controllers/HomeController.php');
foreach ($lines as $i => $line) {
    if (strpos($line, 'databaseTotal') !== false || strpos($line, 'databaseBaru') !== false) {
        echo "Line " . ($i + 1) . ": " . trim($line) . "\n";
    }
}
