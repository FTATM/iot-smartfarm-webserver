@echo off
echo =====================================
echo Installing SmartFarm Windows Services
echo =====================================
echo.

call "%~dp0..\.env.bat"

echo %PYTHON_PATH%
echo %SCRIPT_CAMERA_PATH%

echo Creating logs folder if not exist...
if not exist "%SCRIPT_CAMERA_PATH%\logs" mkdir "%SCRIPT_CAMERA_PATH%\logs"

echo.
echo Removing old services (if exist)...
nssm stop .SocketServerService 2>nul
nssm stop .TaskCameraService 2>nul
nssm remove .SocketServerService confirm 2>nul
nssm remove .TaskCameraService confirm 2>nul

echo.
echo Installing SocketServerService...
nssm install .SocketServerService "%PYTHON_PATH%" "%SCRIPT_CAMERA_PATH%\socket_server.py"
nssm set .SocketServerService AppDirectory "%SCRIPT_CAMERA_PATH%"
nssm set .SocketServerService Start SERVICE_AUTO_START
nssm set .SocketServerService AppStdout "%SCRIPT_CAMERA_PATH%\logs\socket_log.txt"
nssm set .SocketServerService AppStderr "%SCRIPT_CAMERA_PATH%\logs\socket_error.txt"
nssm set .SocketServerService AppExit Default Restart

echo.
echo Installing TaskCameraService...
nssm install .TaskCameraService "%PYTHON_PATH%" "%SCRIPT_CAMERA_PATH%\taskcamera_server.py"
nssm set .TaskCameraService AppDirectory "%SCRIPT_CAMERA_PATH%"
nssm set .TaskCameraService Start SERVICE_AUTO_START
nssm set .TaskCameraService AppStdout "%SCRIPT_CAMERA_PATH%\logs\camera_log.txt"
nssm set .TaskCameraService AppStderr "%SCRIPT_CAMERA_PATH%\logs\camera_error.txt"
nssm set .TaskCameraService AppExit Default Restart

echo.
echo Setting Docker containers to auto-restart...
for /f "tokens=*" %%i in ('docker ps -aq') do docker update --restart unless-stopped %%i

echo.
echo Starting services...
net start .SocketServerService
timeout /t 10 > nul
net start .TaskCameraService

echo.
echo =====================================
echo Installation Completed
echo =====================================
pause
