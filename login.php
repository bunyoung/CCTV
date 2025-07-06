<?php
session_start();
require 'config.php';

$error = '';
$alert = '';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(isset($_POST['username']) ? $_POST['username'] : '');
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $password_md5 = md5($password);

    $stmt = mysqli_prepare($conn, "SELECT id, username FROM users WHERE username = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $password_md5);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $alert = 'success';
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        $alert = 'fail';
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>เข้าสู่ระบบ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Niramit&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Niramit', sans-serif;
      background-color: #f5f5f5;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-container {
      max-width: 400px;
      width: 100%;
      padding: 2rem;
      background: white;
      border-radius: 1rem;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<!-- 🔊 เสียงแจ้งเตือน -->
<audio id="sound-success" src="https://www.myinstants.com/media/sounds/success-fanfare.mp3"></audio>
<audio id="sound-error" src="https://www.myinstants.com/media/sounds/error.mp3"></audio>

<div class="login-container">
  <h3 class="text-center mb-4">🔐 เข้าสู่ระบบ</h3>

  <form method="POST" action="">
    <div class="mb-3">
      <label class="form-label">ชื่อผู้ใช้</label>
      <input type="text" name="username" class="form-control" required autofocus>
    </div>

    <div class="mb-3">
      <label class="form-label">รหัสผ่าน</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
    <p class="mt-3 text-center">ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
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
      title: 'เข้าสู่ระบบสำเร็จ',
      text: 'กำลังเปลี่ยนหน้า...',
      timer: 2500,
      showConfirmButton: false
    });
    soundSuccess.play();
    setTimeout(function () {
      window.location.href = "index.php";
    }, 2500);
  <?php else: ?>
    Swal.fire({
      icon: 'error',
      title: 'เข้าสู่ระบบล้มเหลว',
      text: '<?php echo $error; ?>',
      confirmButtonText: 'ลองใหม่'
    });
    soundError.play();
  <?php endif; ?>
});
</script>
<?php endif; ?>
</body>
</html>
