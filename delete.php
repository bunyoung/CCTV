<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['filename'])) {
    http_response_code(400);
    exit('Missing filename');
}

$filename = basename($data['filename']);
$filepath = __DIR__ . '/captures/' . $filename;

if (!file_exists($filepath)) {
    http_response_code(404);
    exit('File not found');
}

if (unlink($filepath)) {
    echo json_encode(['status' => 'deleted']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Cannot delete file']);
}
