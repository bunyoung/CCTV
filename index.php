<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <title>เมนูระบบกล้องวงจรปิด</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Niramit&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Niramit', sans-serif;
            background-color: #f8f9fa;
        }

        .menu-container {
            max-width: 900px;
            margin: 40px auto;
        }

        .card-menu {
            cursor: pointer;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .card-menu:hover {
            box-shadow: 0 4px 20px rgba(0, 123, 255, 0.3);
            transform: translateY(-4px);
        }

        .card-body {
            text-align: center;
            padding: 30px 15px;
        }

        .icon {
            font-size: 48px;
            margin-bottom: 15px;
            color: #0d6efd;
        }

        .card-title {
            font-size: 22px;
            font-weight: 600;
            color: #0d6efd;
        }

        .qr-box {
            text-align: center;
            margin-top: 50px;
        }

        .qr-box p {
            font-size: 18px;
            color: #333;
        }

        .qr-box img {
            border: 4px dashed #0d6efd;
            padding: 10px;
            border-radius: 12px;
        }
    </style>
</head>

<body>
    <div class="container menu-container">
        <h2 class="text-center mb-4 text-primary">📷 ระบบกล้องวงจรปิด - เมนูหลัก</h2>
        <div class="row g-4">
            <div class="col-12 col-md-3">
                <div class="card card-menu" onclick="location.href='gallery_video.php'">
                    <div class="card-body">
                        <div class="icon">🖼️</div>
                        <h5 class="card-title">ดูวิดีโอย้อนหลัง</h5>
                        <p class="card-text text-muted">ตรวจสอบวิดีโอที่บันทึกไว้</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card card-menu" onclick="location.href='upload_form.php'">
                    <div class="card-body">
                        <div class="icon">⬆️</div>
                        <h5 class="card-title">อัปโหลดวิดีโอ</h5>
                        <p class="card-text text-muted">เพิ่มวิดีโอใหม่เข้าสู่ระบบ</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card card-menu" onclick="location.href='webcam.html'">
                    <div class="card-body">
                        <div class="icon">📷</div>
                        <h5 class="card-title">เปิดกล้องวงจร</h5>
                        <p class="card-text text-muted">ดูและควบคุมกล้องสด</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card card-menu" onclick="location.href='settings.php'">
                    <div class="card-body">
                        <div class="icon">⚙️</div>
                        <h5 class="card-title">ตั้งค่า</h5>
                        <p class="card-text text-muted">จัดการค่าระบบ</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="qr-box">
            <p>📱 เปิดดูวิดีโอย้อนหลังผ่านมือถือ</p>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=http://10.4.71.212/webcam/gallery_video.php" alt="QR Code สำหรับมือถือ">
            <p class="mt-2"><small>สแกนด้วยกล้องมือถือเพื่อเข้าถึงระบบ</small></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
