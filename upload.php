<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>⬆️ อัปโหลดภาพ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Niramit&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Niramit', sans-serif;
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>
  <div id="navbar"></div>

  <div class="container py-4">
    <h3 class="text-center text-success mb-4">⬆️ อัปโหลดภาพ</h3>

    <form id="uploadForm" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="imageFile" class="form-label">เลือกไฟล์ภาพ (jpg, png, gif):</label>
        <input
          class="form-control"
          type="file"
          id="imageFile"
          name="imageFile"
          accept="image/*"
          required
        />
      </div>
      <button type="submit" class="btn btn-primary">อัปโหลด</button>
    </form>

    <div id="result" class="mt-3"></div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    fetch('index.php')
      .then(res => res.text())
      .then(html => {
        document.getElementById('navbar').innerHTML = html;
        const links = document.querySelectorAll('.nav-link');
        links.forEach(link => {
          if (link.href === location.href) {
            link.classList.add('active');
          }
        });
      });

    document.getElementById('uploadForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);

      const res = await fetch('upload.php', {
        method: 'POST',
        body: formData
      });

      const resultDiv = document.getElementById('result');
      if (res.ok) {
        resultDiv.innerHTML = '<div class="alert alert-success">อัปโหลดสำเร็จ</div>';
        e.target.reset();
      } else {
        resultDiv.innerHTML = '<div class="alert alert-danger">เกิดข้อผิดพลาดในการอัปโหลด</div>';
      }
    });
  </script>
</body>
</html>
