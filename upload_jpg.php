<?php
header('Content-Type: application/json');

// เริ่มต้น response พื้นฐาน
$response = [
    'status' => 'error',
    'message' => 'ไม่มีไฟล์ที่ส่งมา'
];

// ตรวจสอบว่าได้รับไฟล์จาก client หรือไม่
if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode($response);
    exit;
}

$file = $_FILES['file'];

// ตรวจสอบว่าไฟล์อัปโหลดมาสำเร็จหรือไม่
if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    $response['message'] = 'เกิดข้อผิดพลาดในการอัปโหลด: ' . $file['error'];
    echo json_encode($response);
    exit;
}

// ตรวจสอบ MIME type
$allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedMimeTypes)) {
    http_response_code(400);
    $response['message'] = 'ไฟล์ไม่ใช่รูปภาพที่รองรับ (jpeg, jpg, png)';
    echo json_encode($response);
    exit;
}

// สร้างโฟลเดอร์ปลายทางหากยังไม่มี
$uploadDir = __DIR__ . '/uploads/images/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        $response['message'] = 'ไม่สามารถสร้างโฟลเดอร์ปลายทางได้';
        echo json_encode($response);
        exit;
    }
}

// กำหนดชื่อไฟล์ใหม่ (กันชนกันและใช้ timestamp)
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$newFilename = uniqid('snapshot_', true) . '.' . $extension;
$destination = $uploadDir . $newFilename;

// ย้ายไฟล์ไปยังโฟลเดอร์ปลายทาง
if (move_uploaded_file($file['tmp_name'], $destination)) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'filename' => $newFilename,
        'url' => 'uploads/images/' . $newFilename
    ]);
} else {
    http_response_code(500);
    $response['message'] = 'ไม่สามารถย้ายไฟล์ที่อัปโหลดได้';
    echo json_encode($response);
}
