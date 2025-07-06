<?php
session_start();
require 'config.php';

$error = '';
$success = '';
$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(isset($_POST['username']) ? $_POST['username'] : '');
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (!$username || !$password || !$confirm_password) {
        $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
        $alert = 'incomplete';
    } elseif ($password !== $confirm_password) {
        $error = 'รหัสผ่านไม่ตรงกัน';
        $alert = 'mismatch';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว';
            $alert = 'duplicate';
        } else {
            $password_hash = md5($password);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ss", $username, $password_hash);

            if (mysqli_stmt_execute($stmt)) {
                $success = 'สมัครสมาชิกสำเร็จ! กำลังนำคุณไปยังหน้าล็อกอิน...';
                $alert = 'success';
            } else {
                $error = 'เกิดข้อผิดพลาดในการสมัครสมาชิก';
                $alert = 'fail';
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>สมัครสมาชิก</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Niramit&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Niramit', sans-serif;
      background: #f8f9fa;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .card {
      max-width: 400px;
      width: 100%;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      background: #fff;
    }
  </style>
</head>
<body>

<!-- 🔊 เสียงแจ้งเตือน -->
<audio id="sound-success" src="https://www.myinstants.com/media/sounds/success-fanfare.mp3"></audio>
<audio id="sound-error" src="https://www.myinstants.com/media/sounds/error.mp3"></audio>

<div class="card">
  <h2 class="text-center mb-4 text-primary">สมัครสมาชิก</h2>
  <form method="POST" action="">
    <div class="mb-3">
      <label for="username" class="form-label">ชื่อผู้ใช้</label>
      <input type="text" id="username" name="username" class="form-control" required autofocus />
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">รหัสผ่าน</label>
      <input type="password" id="password" name="password" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน</label>
      <input type="password" id="confirm_password" name="confirm_password" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-primary w-100">สมัครสมาชิก</button>
    <p class="mt-3 text-center">มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
  </form>
</div>

<?php if ($alert): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
  var soundSuccess = document.getElementById("sound-success");
  var soundError = document.getElementById("sound-error");

  <?php if ($alert == 'success'): ?>
    Swal.fire({
      icon: 'success',
      title: 'สำเร็จ!',
      text: '<?php echo $success; ?>',
      timer: 3000,
      showConfirmButton: false
    });
    soundSuccess.play();
    setTimeout(function () {
      window.location.href = "login.php";
    }, 3000);
  <?php else: ?>
    Swal.fire({
      icon: 'error',
      title: 'ผิดพลาด',
      text: '<?php echo $error; ?>',
      confirmButtonText: 'ปิด'
    });
    soundError.play();
  <?php endif; ?>
});
</script>
<?php endif; ?>
</body>
</html>
