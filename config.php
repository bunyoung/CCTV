<?php
// config.php
$host = 'localhost';
$dbname = 'camera';
$user = 'root';
$pass = '';
$dbname='camera';
$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());
}