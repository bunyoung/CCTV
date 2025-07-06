<?php
// load_schedule.php
header('Content-Type: application/json');

$file = 'storage/schedule_data.json';

if (file_exists($file)) {
    $content = file_get_contents($file);
    echo $content;
} else {
    echo json_encode(['status' => 'empty', 'message' => 'No schedule found']);
}
