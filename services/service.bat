@echo off
title Service Manager

:menu

cls

echo.
echo ==========================================
echo Selected Service : %SERVICE%
echo ==========================================
echo.
echo Select Action
echo.
echo 1. install
echo 2. remove
echo 3. start
echo 4. stop
echo 5. restart
echo 6. status
echo 0. Exit
echo.

set /p ACTION_CHOICE=Action :

if "%ACTION_CHOICE%"=="1" set ACTION=install
if "%ACTION_CHOICE%"=="2" set ACTION=remove
if "%ACTION_CHOICE%"=="3" set ACTION=start
if "%ACTION_CHOICE%"=="4" set ACTION=stop
if "%ACTION_CHOICE%"=="5" set ACTION=restart
if "%ACTION_CHOICE%"=="6" set ACTION=status
if "%ACTION_CHOICE%"=="0" exit

if not defined ACTION (
    echo.
    echo Invalid action.
    pause
    goto menu
)

cls

echo.
echo ==========================================
echo SERVICE MANAGER
echo ==========================================
echo.
echo Select Service
echo.
echo 1. Schedule
echo 2. AI
echo 3. All
echo 0. Back
echo.

set /p SERVICE_CHOICE=Service :

if "%SERVICE_CHOICE%"=="1" set SERVICE=runtime_schedule
if "%SERVICE_CHOICE%"=="2" set SERVICE=runtime_ai
if "%SERVICE_CHOICE%"=="3" set SERVICE=all
if "%SERVICE_CHOICE%"=="0" goto menu

if not defined SERVICE (
    echo.
    echo Invalid service.
    pause
    goto menu
)


echo.
echo ==========================================
echo Execute
echo ==========================================
echo Service : %SERVICE%
echo Action  : %ACTION%
echo.

echo "%~dp0"
echo.

call "%~dp0service_.bat" %ACTION% %SERVICE%

echo.
pause

set SERVICE=
set ACTION=

goto menu