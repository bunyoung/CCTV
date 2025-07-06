<?php
session_start();

// ล้างข้อมูล session ทั้งหมด
$_SESSION = [];

// ทำลาย session
session_destroy();

// เปลี่ยนเส้นทางไปยังหน้า login หรือหน้าแรก
header('Location: login.php');
exit;
