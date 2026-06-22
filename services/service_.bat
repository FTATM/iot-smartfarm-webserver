@echo off
setlocal EnableDelayedExpansion

REM ==========================================
REM CHECK ACCESS
REM ==========================================

net session >nul 2>&1

if %errorlevel% neq 0 (
    echo Requesting Administrator...

    powershell -Command ^
    "Start-Process cmd -ArgumentList '/c ""%~f0"" %*' -Verb RunAs"

    exit /b
)

REM ==========================================
REM ROOT PATH
REM ==========================================

set "ROOT=%~dp0"

REM ตัด \ ตัวท้ายออก
if "%ROOT:~-1%"=="\" set "ROOT=%ROOT:~0,-1%"

REM ==========================================
REM LOAD .ENV
REM ==========================================

set "ENV_FILE=%ROOT%\..\.env"

if not exist "%ENV_FILE%" (
    echo ERROR: .env not found
    echo %ENV_FILE%
    exit /b 1
)

for /f "usebackq tokens=1,* delims==" %%A in ("%ENV_FILE%") do (
    if not "%%A"=="" (
        set "%%A=%%B"
    )
)

if "%PHP_PATH%"=="" (
    echo ERROR: PHP_PATH not found in .env
    exit /b 1
)

REM ==========================================
REM NSSM
REM ==========================================

set "NSSM=%ROOT%\nssm.exe"

REM ==========================================
REM PHP
REM ==========================================

set "PHP=%PHP_PATH%"

REM ==========================================
REM SERVICES
REM ==========================================

set "S1=runtime_schedule"
set "S2=runtime_ai"

set "SCRIPT1=%ROOT%\runtime_schedule.php"
set "SCRIPT2=%ROOT%\runtime_ai.php"

REM ==========================================
REM VALIDATION
REM ==========================================

if not exist "%NSSM%" (
    echo ERROR: NSSM not found
    echo %NSSM%
    exit /b 1
)

if not exist "%PHP%" (
    echo ERROR: PHP not found
    echo %PHP%
    exit /b 1
)

if not exist "%SCRIPT1%" (
    echo ERROR: Script not found
    echo %SCRIPT1%
    exit /b 1
)

if not exist "%SCRIPT2%" (
    echo ERROR: Script not found
    echo %SCRIPT2%
    exit /b 1
)

REM ==========================================
REM ARGUMENTS
REM ==========================================

if "%1"=="" goto help
if "%2"=="" goto help

set "ACTION=%1"
set "SERVICE=%2"
REM ==========================================
REM INSTALL
REM ==========================================

if /I "%ACTION%"=="install" (

    if /I "%SERVICE%"=="runtime_schedule" (

        sc query %S1% >nul 2>&1

        if not errorlevel 1 (
            echo Service %S1% already exists
            goto end
        )

        "%NSSM%" install %S1% "%PHP%" "%SCRIPT1%"
        "%NSSM%" set %S1% Start SERVICE_AUTO_START
        "%NSSM%" set %S1% AppExit Default Restart

        goto end
    )

    if /I "%SERVICE%"=="runtime_ai" (

        sc query %S2% >nul 2>&1

        if not errorlevel 1 (
            echo Service %S2% already exists
            goto end
        )

        "%NSSM%" install %S2% "%PHP%" "%SCRIPT2%"
        "%NSSM%" set %S2% Start SERVICE_AUTO_START
        "%NSSM%" set %S2% AppExit Default Restart

        goto end
    )

    if /I "%SERVICE%"=="all" (

        call "%~f0" install runtime_schedule
        call "%~f0" install runtime_ai

        goto end
    )
)

REM ==========================================
REM REMOVE
REM ==========================================

if /I "%ACTION%"=="remove" (

    if /I "%SERVICE%"=="all" (

        "%NSSM%" remove %S1% confirm
        "%NSSM%" remove %S2% confirm

        goto end
    )

    "%NSSM%" remove %SERVICE% confirm

    goto end
)

REM ==========================================
REM ALL SERVICES
REM ==========================================

if /I "%SERVICE%"=="all" (

    if /I "%ACTION%"=="start" (
        net start %S1%
        net start %S2%
        goto end
    )

    if /I "%ACTION%"=="stop" (
        net stop %S1%
        net stop %S2%
        goto end
    )

    if /I "%ACTION%"=="restart" (

        net stop %S1%
        net stop %S2%

        timeout /t 2 /nobreak >nul

        net start %S1%
        net start %S2%

        goto end
    )

    if /I "%ACTION%"=="status" (

        echo.
        echo =====================================
        echo %S1%
        echo =====================================
        sc query %S1%

        echo.
        echo =====================================
        echo %S2%
        echo =====================================
        sc query %S2%

        goto end
    )
)

REM ==========================================
REM SINGLE SERVICE
REM ==========================================

if /I "%ACTION%"=="start" (
    net start %SERVICE%
    goto end
)

if /I "%ACTION%"=="stop" (
    net stop %SERVICE%
    goto end
)

if /I "%ACTION%"=="restart" (

    net stop %SERVICE%

    timeout /t 2 /nobreak >nul

    net start %SERVICE%

    goto end
)

if /I "%ACTION%"=="status" (

    sc query %SERVICE%

    goto end
)

goto help

:help

echo.
echo ==========================================
echo === Example ===
echo service_.bat status runtime_schedule
echo. 
echo === SERVICES ===
echo  - runtime_schedule
echo  - runtime_ai
echo  - all
echo.
echo === COMMANDS ===
echo  - install 
echo  - remove
echo  - start
echo  - stop
echo  - restart
echo  - status
echo.

:end
endlocal