<?php
$videoDir = "uploads/videos/";
$videos = glob($videoDir . "*.webm");

// เรียงลำดับใหม่ -> เก่า
usort($videos, function($a, $b) {
  return filemtime($b) - filemtime($a);
});
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <title>📽️ วิดีโอที่บันทึก</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Niramit&display=swap" rel="stylesheet" />
    <style>
    body {
        font-family: 'Niramit', sans-serif;
        background-color: #f1f3f5;
    }

    .video-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease;
    }

    .video-card:hover {
        transform: translateY(-4px);
    }

    video {
        width: 100%;
        max-height: 240px;
        border-radius: 12px;
        border: 2px solid #0d6efd33;
    }

    .video-title {
        font-size: 16px;
        font-weight: 600;
        margin-top: 10px;
        color: #0d6efd;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .video-date {
        font-size: 13px;
        color: #888;
    }

    .download-btn {
        margin-top: 10px;
        font-size: 14px;
    }

    @media (max-width: 576px) {
        .video-title {
            font-size: 14px;
        }
    }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="text-center mb-4">
            <h2 class="text-primary">🎞️ วิดีโอที่บันทึกไว้</h2>
            <p class="text-muted">สามารถดูย้อนหลังและดาวน์โหลดวิดีโอได้</p>
        </div>

        <div class="row g-4">
            <?php if (count($videos) === 0): ?>
            <div class="col-12 text-center">
                <p class="text-danger">❌ ไม่พบวิดีโอในระบบ</p>
            </div>
            <?php endif; ?>

            <?php foreach ($videos as $file): ?>
            <?php
          $filename = basename($file);
          $filesize = filesize($file);
          $date = date("d/m/Y H:i", filemtime($file));
        ?>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="video-card">
                    <video controls preload="metadata">
                        <source src="<?= $videoDir . $filename ?>" type="video/mp4">
                        ไม่รองรับวิดีโอ
                    </video>
                    <div class="video-title"><?= htmlspecialchars($filename) ?></div>
                    <div class="video-date">📅 <?= $date ?></div>
                    <a href="<?= $videoDir . $filename ?>" class="btn btn-outline-primary btn-sm w-100 download-btn"
                        download>
                        ⬇️ ดาวน์โหลด
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>