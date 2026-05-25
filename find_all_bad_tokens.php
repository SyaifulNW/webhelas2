<?php
$content = file_get_contents('resources/views/admin/database/database.blade.php');

// Simple parser to find all script blocks
preg_match_all('/<script\b[^>]*>(.*?)<\/script>/is', $content, $matches, PREG_OFFSET_CAPTURE);

foreach ($matches[1] as $idx => $matchPair) {
    $scriptText = $matchPair[0];
    $offset = $matchPair[1];
    
    // Find line number of this script block start
    $beforeText = substr($content, 0, $offset);
    $startLine = substr_count($beforeText, "\n") + 1;
    
    // Check if there is any stray '<' not inside quotes or comments
    // Let's tokenise or simply inspect lines
    $lines = explode("\n", $scriptText);
    foreach ($lines as $i => $line) {
        $trimmed = trim($line);
        // If it starts with a tag or has stray '<'
        if (preg_match('/^\s*<[a-zA-Z]/', $line)) {
            echo "Script block starting on line $startLine has tag on inner line " . ($i + 1) . " (Global line " . ($startLine + $i) . "): " . $trimmed . "\n";
        }
    }
}
