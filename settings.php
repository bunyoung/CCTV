<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>⚙️ ตั้งค่า</title>
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
    <h3 class="text-center text-warning mb-4">⚙️ ตั้งค่า</h3>

    <form id="settingsForm">
      <div class="mb-3">
        <label for="lineToken" class="form-label">Token Line Notify:</label>
        <input type="text" class="form-control" id="lineToken" name="lineToken" placeholder="ใส่ Token ที่นี่" />
      </div>
      <div class="mb-3">
        <label for="s3Bucket" class="form-label">ชื่อ Bucket AWS S3:</label>
        <input type="text" class="form-control" id="s3Bucket" name="s3Bucket" placeholder="ใส่ชื่อ Bucket" />
      </div>
      <div class="mb-3">
        <label for="s3Region" class="form-label">AWS Region:</label>
        <input type="text" class="form-control" id="s3Region" name="s3Region" placeholder="เช่น us-east-1" />
      </div>
      <button type="submit" class="btn btn-warning">บันทึกตั้งค่า</button>
    </form>

    <div id="settingsResult" class="mt-3"></div>
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

    // ตัวอย่างการบันทึกตั้งค่าแบบ localStorage
    const form = document.getElementById('settingsForm');
    const resultDiv = document.getElementById('settingsResult');

    function loadSettings() {
      document.getElementById('lineToken').value = localStorage.getItem('lineToken') || '';
      document.getElementById('s3Bucket').value = localStorage.getItem('s3Bucket') || '';
      document.getElementById('s3Region').value = localStorage.getItem('s3Region') || '';
    }

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      localStorage.setItem('lineToken', form.lineToken.value.trim());
      localStorage.setItem('s3Bucket', form.s3Bucket.value.trim());
      localStorage.setItem('s3Region', form.s3Region.value.trim());

      resultDiv.innerHTML = '<div class="alert alert-success">บันทึกตั้งค่าเรียบร้อย</div>';
    });

    loadSettings();
  </script>
</body>
</html>
