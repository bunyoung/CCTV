<?php
// delete_videos.php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['files']) || !is_array($data['files'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ถูกต้อง']);
    exit;
}

$uploadDir = __DIR__ . '/uploads/';
$errors = [];
foreach ($data['files'] as $file) {
    $safeFile = basename($file); // ป้องกัน Path Traversal
    $filePath = $uploadDir . $safeFile;
    if (file_exists($filePath)) {
        if (!unlink($filePath)) {
            $errors[] = "ไม่สามารถลบไฟล์: $safeFile";
        }
    } else {
        $errors[] = "ไม่พบไฟล์: $safeFile";
    }
}

if (count($errors) > 0) {
    echo json_encode(['success' => false, 'message' => implode('; ', $errors)]);
} else {
    echo json_encode(['success' => true, 'message' => 'ลบไฟล์วิดีโอเรียบร้อยแล้ว']);
}
