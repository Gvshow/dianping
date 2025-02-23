<?php
header('Content-Type: application/json');

$clickCount = 0;
if (file_exists('jl.txt')) {
    $lines = file('jl.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines) {
        $clickCount = count($lines);
    }
}

echo json_encode(['clickCount' => $clickCount]);
?> 