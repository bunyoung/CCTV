let motionInterval = null;
let lastImageData = null;

function startMotionDetection(stream) {
  const video = document.getElementById('webcam');
  const canvas = document.createElement('canvas');
  const context = canvas.getContext('2d');

  motionInterval = setInterval(() => {
    if (!video.videoWidth || !video.videoHeight) return;

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    const currentImageData = context.getImageData(0, 0, canvas.width, canvas.height);

    if (lastImageData) {
      const diff = calculateImageDifference(lastImageData.data, currentImageData.data);
      if (diff > 10) {
        console.log('ตรวจพบการเคลื่อนไหว (' + diff.toFixed(2) + ')');
        // สามารถ trigger action อื่นได้ เช่น startRecording() หรือ captureImage()
      }
    }

    lastImageData = currentImageData;
  }, 1000); // ตรวจจับทุก 1 วินาที
}

function stopMotionDetection() {
  clearInterval(motionInterval);
  motionInterval = null;
  lastImageData = null;
}

function calculateImageDifference(data1, data2) {
  let diff = 0;
  for (let i = 0; i < data1.length; i += 4) {
    const r = Math.abs(data1[i] - data2[i]);
    const g = Math.abs(data1[i + 1] - data2[i + 1]);
    const b = Math.abs(data1[i + 2] - data2[i + 2]);
    diff += (r + g + b) / 3;
  }

  return diff / (data1.length / 4);
}
