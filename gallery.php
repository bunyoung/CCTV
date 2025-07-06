<?php
$dir = __DIR__ . '/captures/';
$images = array_filter(scandir($dir), function ($file) use ($dir) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
});

$groupedImages = [];
foreach ($images as $img) {
    $fileTime = filemtime($dir . $img);
    $dateKey = date('Y-m-d', $fileTime);
    $groupedImages[$dateKey][] = $img;
}

$dates = array_keys($groupedImages);
rsort($dates);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ระบบดูภาพย้อนหลัง</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Niramit&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Niramit', sans-serif;
      background-color: #f8f9fa;
    }
    .card-img-top {
      max-height: 150px;
      object-fit: cover;
    }
    .img-checkbox {
      z-index: 2;
    }
  </style>
</head>
<body>
<div class="container py-4">
  <h2 class="mb-4 text-center text-primary">ระบบดูภาพย้อนหลัง</h2>

  <div class="mb-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div>
      <label for="dateFilter" class="form-label fw-semibold">กรองตามวันที่:</label>
      <select id="dateFilter" class="form-select">
        <option value="all">ทั้งหมด</option>
        <?php foreach ($dates as $d): ?>
          <option value="<?= $d ?>"><?= date('d/m/Y', strtotime($d)) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="selectAll">
      <label for="selectAll" class="form-check-label">เลือกทั้งหมด</label>
    </div>
    <button id="deleteSelected" class="btn btn-danger" disabled>🗑️ ลบภาพที่เลือก</button>
  </div>

  <div id="gallery" class="row g-3">
    <?php foreach ($groupedImages as $date => $imgs): ?>
      <div class="col-12 date-group" data-date="<?= $date ?>">
        <h5 class="mb-2 border-bottom"><?= date('d/m/Y', strtotime($date)) ?></h5>
        <div class="row g-3">
          <?php foreach ($imgs as $img): ?>
            <div class="col-6 col-md-3 col-lg-2">
              <div class="card shadow-sm position-relative">
                <input type="checkbox" class="form-check-input position-absolute m-1 img-checkbox" data-filename="<?= htmlspecialchars($img) ?>" />
                <img src="captures/<?= rawurlencode($img) ?>" class="card-img-top" alt="<?= htmlspecialchars($img) ?>">
                <div class="card-body p-2 text-truncate" style="font-size: 0.85rem;"><?= htmlspecialchars($img) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
  const deleteBtn = document.getElementById('deleteSelected');
  const selectAllCheckbox = document.getElementById('selectAll');
  const dateFilter = document.getElementById('dateFilter');

  function updateDeleteButton() {
    const anyChecked = document.querySelectorAll('.img-checkbox:checked').length > 0;
    deleteBtn.disabled = !anyChecked;
  }

  selectAllCheckbox.addEventListener('change', () => {
    const visibleCheckboxes = [...document.querySelectorAll('.date-group:not([style*="display: none"]) .img-checkbox')];
    visibleCheckboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
    updateDeleteButton();
  });

  document.querySelectorAll('.img-checkbox').forEach(cb => {
    cb.addEventListener('change', () => {
      const allVisible = [...document.querySelectorAll('.date-group:not([style*="display: none"]) .img-checkbox')];
      const allChecked = allVisible.every(c => c.checked);
      selectAllCheckbox.checked = allChecked;
      updateDeleteButton();
    });
  });

  dateFilter.addEventListener('change', () => {
    const value = dateFilter.value;
    selectAllCheckbox.checked = false;
    deleteBtn.disabled = true;
    document.querySelectorAll('.img-checkbox').forEach(cb => cb.checked = false);

    document.querySelectorAll('.date-group').forEach(group => {
      group.style.display = (value === 'all' || group.dataset.date === value) ? 'block' : 'none';
    });
  });

  deleteBtn.addEventListener('click', () => {
    const selected = [...document.querySelectorAll('.img-checkbox:checked')].map(cb => cb.dataset.filename);
    if (!selected.length) return;

    Swal.fire({
      title: 'ยืนยันการลบ?',
      text: `ต้องการลบรูปที่เลือก (${selected.length}) หรือไม่?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'ลบเลย',
      cancelButtonText: 'ยกเลิก'
    }).then(result => {
      if (result.isConfirmed) {
        fetch('delete_images.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ files: selected })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            selected.forEach(file => {
              const cb = document.querySelector(`.img-checkbox[data-filename="${file}"]`);
              if (cb) cb.closest('.col-6, .col-md-3, .col-lg-2').remove();
            });
            Swal.fire('สำเร็จ', 'ลบภาพเรียบร้อยแล้ว', 'success');
            updateDeleteButton();
          } else {
            Swal.fire('ผิดพลาด', data.message || 'ไม่สามารถลบภาพได้', 'error');
          }
        })
        .catch(err => Swal.fire('ผิดพลาด', err.message, 'error'));
      }
    });
  });
</script>
</body>
</html>
