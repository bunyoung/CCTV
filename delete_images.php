<?php
// delete_images.php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['files']) || !is_array($input['files'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไฟล์ไม่ถูกต้อง']);
    exit;
}

$dir = __DIR__ . '/captures/';
$deleted = [];
$errors = [];

foreach ($input['files'] as $file) {
    // ป้องกัน path traversal
    $file = basename($file);
    $filePath = $dir . $file;

    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            $deleted[] = $file;
        } else {
            $errors[] = "ไม่สามารถลบไฟล์ $file ได้";
        }
    } else {
        $errors[] = "ไฟล์ $file ไม่พบ";
    }
}

if (empty($errors)) {
    echo json_encode(['success' => true, 'deleted' => $deleted]);
} else {
    echo json_encode(['success' => false, 'message' => implode('; ', $errors)]);
}
