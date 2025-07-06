const webcam = document.getElementById('webcam');
const startBtn = document.getElementById('startBtn');
const stopBtn = document.getElementById('stopBtn');
const actionSelect = document.getElementById('actionSelect');
const actionBtn = document.getElementById('actionBtn');
const recordStatus = document.getElementById('recordStatus');

let mediaStream = null;
let mediaRecorder = null;
let recordedChunks = [];

startBtn.addEventListener('click', async () => {
  try {
    mediaStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
    webcam.srcObject = mediaStream;
    startBtn.disabled = true;
    stopBtn.disabled = false;
    actionSelect.disabled = false;
    actionBtn.disabled = false;
    Swal.fire('กล้องพร้อมใช้งาน', 'คุณสามารถเริ่มบันทึกได้แล้ว', 'success');
  } catch (err) {
    Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเปิดกล้องได้: ' + err.message, 'error');
  }
});

stopBtn.addEventListener('click', () => {
  if (mediaStream) {
    mediaStream.getTracks().forEach(track => track.stop());
    webcam.srcObject = null;
    startBtn.disabled = false;
    stopBtn.disabled = true;
    actionSelect.disabled = true;
    actionBtn.disabled = true;
    Swal.fire('ปิดกล้องเรียบร้อย', '', 'info');
  }
});

actionBtn.addEventListener('click', () => {
  const action = actionSelect.value;
  if (!action) {
    Swal.fire('กรุณาเลือกการทำงาน', '', 'warning');
    return;
  }

  if (action === 'snapshot') {
    captureImage();
  } else if (action === 'record') {
    if (mediaRecorder && mediaRecorder.state === 'recording') {
      stopRecording();
    } else {
      startRecording();
    }
  }
});

function captureImage() {
  const canvas = document.createElement('canvas');
  canvas.width = webcam.videoWidth;
  canvas.height = webcam.videoHeight;
  const context = canvas.getContext('2d');
  context.drawImage(webcam, 0, 0);

  function uploadFile(blob, filename) {
  const formData = new FormData();
  formData.append('file', blob, filename);

  fetch('upload_jpg.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      alert('อัปโหลดสำเร็จ: ' + data.filename);
    } else {
      alert('เกิดข้อผิดพลาด: ' + data.message);
    }
  })
  .catch(err => {
    alert('เกิดข้อผิดพลาด: ' + err.message);
  });
}
}

function startRecording() {
  recordedChunks = [];
  mediaRecorder = new MediaRecorder(mediaStream, { mimeType: 'video/webm' });

  mediaRecorder.ondataavailable = e => {
    if (e.data.size > 0) recordedChunks.push(e.data);
  };

  mediaRecorder.onstop = () => {
    const blob = new Blob(recordedChunks, { type: 'video/webm' });
    uploadFile(blob, `video_${Date.now()}.webm`);
    recordStatus.textContent = 'หยุดบันทึกแล้ว';
    actionBtn.textContent = 'เริ่มบันทึกวิดีโอ';
  };

  mediaRecorder.start();
  recordStatus.textContent = 'กำลังบันทึก...';
  actionBtn.textContent = 'หยุดบันทึกวิดีโอ';
}

function stopRecording() {
  if (mediaRecorder && mediaRecorder.state === 'recording') {
    mediaRecorder.stop();
  }
}

// ⬇️ เพิ่มการอัปโหลด + SweetAlert2
async function uploadFile(blob, filename) {
  const formData = new FormData();
  formData.append('file', blob, filename);

  Swal.fire({
    title: 'กำลังอัปโหลด...',
    html: 'กรุณารอสักครู่',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading()
  });

  try {
    const response = await fetch('upload.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.json();

    if (!response.ok || result.status !== 'success') {
      throw new Error(result.message || 'เกิดข้อผิดพลาด');
    }

    Swal.fire({
      icon: 'success',
      title: 'อัปโหลดสำเร็จ',
      html: `ไฟล์: <strong>${result.filename}</strong>`,
      timer: 3000,
      showConfirmButton: false
    });
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'อัปโหลดล้มเหลว',
      text: error.message
    });
  }
  uploadFile(blob, 'snapshot_ชื่อภาพ.png', 'upload_jpg.php');

function uploadFile(blob, filename, uploadUrl = 'upload.php') {
  const formData = new FormData();
  formData.append('file', blob, filename);

  Swal.fire({
    title: 'กำลังอัปโหลด...',
    html: 'กรุณารอสักครู่',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading()
  });

  fetch(uploadUrl, {
    method: 'POST',
    body: formData
  })
    .then(res => res.json().then(data => ({ ok: res.ok, data })))
    .then(({ ok, data }) => {
      if (!ok || data.status !== 'success') throw new Error(data.message);
      Swal.fire({
        icon: 'success',
        title: 'อัปโหลดสำเร็จ',
        html: `<strong>${data.filename}</strong>`,
        timer: 2500,
        showConfirmButton: false
      });
    })
    .catch(err => {
      Swal.fire({
        icon: 'error',
        title: 'เกิดข้อผิดพลาด',
        text: err.message
      });
    });
}
}
