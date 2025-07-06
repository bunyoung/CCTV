<?php
// ปิด error
error_reporting(0);
ini_set('display_errors', 0);

// ตอบกลับ JSON
header('Content-Type: application/json');

// ตรวจสอบ Method
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(array(
        'status' => 'error',
        'message' => 'ต้องใช้ POST method เท่านั้น'
    ));
    exit;
}

// ตรวจสอบไฟล์
if (!isset($_FILES['video'])) {
    echo json_encode(array(
        'status' => 'error',
        'message' => 'ไม่พบไฟล์วิดีโอที่อัปโหลด'
    ));
    exit;
}

$file = $_FILES['video'];

// ตรวจสอบข้อผิดพลาด
if ($file['error'] !== 0) {
    echo json_encode(array(
        'status' => 'error',
        'message' => 'เกิดข้อผิดพลาดในการอัปโหลด (รหัส: ' . $file['error'] . ')'
    ));
    exit;
}

// ตรวจสอบประเภทไฟล์
$allowedExtensions = array('mp4', 'webm', 'mov', 'avi');
$fileName = $file['name'];
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if (!in_array($fileExt, $allowedExtensions)) {
    echo json_encode(array(
        'status' => 'error',
        'message' => 'ประเภทไฟล์ไม่รองรับ (อนุญาต: mp4, webm, mov, avi)'
    ));
    exit;
}

// โฟลเดอร์ปลายทาง
$uploadDir = dirname(__FILE__) . '/uploads/videos';
$publicPath = 'uploads/videos';

// สร้างโฟลเดอร์หากยังไม่มี
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// ตั้งชื่อไฟล์ใหม่
$newName = 'video_' . date('Ymd_His') . '_' . rand(1000, 9999) . '.' . $fileExt;
$targetPath = $uploadDir . $newName;

// ย้ายไฟล์
if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode(array(
        'status' => 'success',
        'message' => 'อัปโหลดวิดีโอสำเร็จ',
        'path' => $publicPath . $newName
    ));
} else {
    echo json_encode(array(
        'status' => 'error',
        'message' => 'ไม่สามารถย้ายไฟล์ได้'
    ));
}
?>
