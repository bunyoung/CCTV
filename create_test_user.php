<?php
require 'config.php';

$username = 'testuser';
$password = 'test1234';

// ตรวจสอบว่ามี user นี้อยู่หรือไม่
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);

if ($stmt->fetch()) {
    echo "User '$username' มีอยู่แล้วในระบบ";
    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
if ($stmt->execute([$username, $password_hash])) {
    echo "สร้าง User ทดสอบสำเร็จ<br>";
    echo "Username: $username<br>";
    echo "Password: $password";
} else {
    echo "เกิดข้อผิดพลาดในการสร้าง User";
}
