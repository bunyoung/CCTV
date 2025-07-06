<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['segment'])) {
    $file = $_FILES['segment'];
    $targetDir = 'uploads/';
    if (!file_exists($targetDir)) mkdir($targetDir);
    $filename = $targetDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $filename)) {
        echo 'OK';
    } else {
        http_response_code(500);
        echo 'Failed to save file.';
    }
} else {
    http_response_code(400);
    echo 'No file received.';
}
?>
