@echo off
echo Starting PHP local server at http://localhost:8000 ...
start php -S localhost:8000

timeout /t 2 >nul

echo Starting ngrok tunnel ...
start cmd /k ngrok http 8000

echo ----------------------------------------------
echo Server started. Please check ngrok window.
echo Copy the HTTPS link (e.g., https://xxxx.ngrok.io/mobile.html)
echo and open it on your mobile device.
echo ----------------------------------------------
pause
