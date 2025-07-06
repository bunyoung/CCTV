const video = document.getElementById('webcam');
const previewImage = document.getElementById('previewImage');
let stream = null;

async function startCamera() {
  try {
    stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
    video.srcObject = stream;
  } catch (err) {
    alert('ไม่สามารถเปิดกล้องได้: ' + err.message);
  }
}

function stopCamera() {
  if (stream) {
    stream.getTracks().forEach(track => track.stop());
    video.srcObject = null;
    stream = null;
  }
}

function captureImage() {
  if (!stream) return alert('กล้องยังไม่เปิด');

  const canvas = document.createElement('canvas');
  canvas.width = video.videoWidth || 640;
  canvas.height = video.videoHeight || 480;
  const ctx = canvas.getContext('2d');
  ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

  canvas.toBlob(blob => {
    const imageUrl = URL.createObjectURL(blob);
    previewImage.src = imageUrl;
    previewImage.style.display = 'block';

    const downloadLink = document.createElement('a');
    downloadLink.href = imageUrl;
    downloadLink.download = 'snapshot.png';
    downloadLink.click();
  }, 'image/png');
}

function toggleImagePreview() {
  if (!previewImage.src) {
    alert('ยังไม่มีภาพที่จับได้');
    return;
  }
  previewImage.style.display = previewImage.style.display === 'block' ? 'none' : 'block';
}
