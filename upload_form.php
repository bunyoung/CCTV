<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>อัปโหลดวิดีโอ</title>
    <link href="https://fonts.googleapis.com/css2?family=Niramit&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Niramit', sans-serif;
            font-size: 18px;
            background-color: #f9f9f9;
            color: #333;
            text-align: center;
            padding: 40px;
        }
        form {
            display: inline-block;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        input[type="file"] {
            margin: 10px 0;
        }
        button {
            padding: 10px 25px;
            font-size: 18px;
            font-family: 'Niramit', sans-serif;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background-color: #388E3C;
        }
        .message {
            margin-top: 20px;
            font-weight: bold;
        }
        video {
            margin-top: 20px;
            max-width: 100%;
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <h1>อัปโหลดวิดีโอ</h1>

    <form id="uploadForm" enctype="multipart/form-data">
        <input type="file" name="video" accept="video/*" required><br>
        <button type="submit">อัปโหลด</button>
    </form>

    <div class="message" id="message"></div>
    <video id="videoPlayer" controls style="display:none;"></video>

    <script>
        const form = document.getElementById('uploadForm');
        const message = document.getElementById('message');
        const videoPlayer = document.getElementById('videoPlayer');

        form.onsubmit = function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('upload_video.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    message.innerHTML = '✅ ' + data.message;
                    videoPlayer.src = data.path;
                    videoPlayer.style.display = 'block';
                } else {
                    message.innerHTML = '❌ ' + data.message;
                    videoPlayer.style.display = 'none';
                }
            })
            .catch(err => {
                message.innerHTML = '❌ ไม่สามารถอัปโหลดได้';
                videoPlayer.style.display = 'none';
            });
        };
    </script>

</body>
</html>
