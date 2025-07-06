<?php
$dir = __DIR__ . '/captures';
$files = array_diff(scandir($dir), ['.', '..']);
$data = [];

foreach ($files as $file) {
    if (is_file("$dir/$file")) {
        $timestamp = date("d/m/Y H:i:s", filemtime("$dir/$file"));
        $data[] = [
            'filename' => $file,
            'timestamp' => $timestamp
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($data);
