@echo off
echo === Starting Services ===

echo Starting socket server...
start "" cmd /k python socket_server.py

timeout /t 4 > nul

echo Starting Docker Containers...
docker start quirky_curran

timeout /t 8 > nul

echo Starting task camera server...
start "" cmd /k python taskcamera_server.py

echo === All services started ===