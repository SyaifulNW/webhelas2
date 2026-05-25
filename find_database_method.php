<?php
$lines = file('app/Http/Controllers/AdminController.php');
$found = false;
foreach ($lines as $i => $line) {
    if (strpos($line, 'public function database') !== false) {
        $found = true;
    }
    if ($found) {
        echo ($i + 1) . ": " . $line;
        if ($i > 250) { // Limit output
            break;
        }
    }
}
