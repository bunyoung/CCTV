<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8" />
<title>แดชบอร์ด</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Niramit&display=swap" rel="stylesheet" />
<style>
  body { font-family: 'Niramit', sans-serif; background: #f8f9fa; padding: 2rem; }
</style>
</head>
<body>
  <div class="container">
    <h1 class="mb-4">ยินดีต้อนรับ, <?= htmlspecialchars($_SESSION['username']) ?></h1>

    <p>นี่คือตัวอย่างหน้าแดชบอร์ดหลังจากเข้าสู่ระบบ</p>

    <a href="logout.php" class="btn btn-danger">ออกจากระบบ</a>
  </div>
</body>
</html>
