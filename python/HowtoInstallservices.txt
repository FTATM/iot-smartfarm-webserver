=====================================================================================================
INSTALLATION GUIDE
=====================================================================================================

REQUIREMENTS
-----------------------------------------------------------------------------------------------------
1. Install Python 3.10 (Recommended)
2. Install Docker Desktop
3. Download NSSM and copy nssm.exe to:
   C:\Windows\System32

=====================================================================================================
INSTALLATION STEPS
=====================================================================================================

1) CHECK PYTHON
-----------------------------------------------------------------------------------------------------
Open Command Prompt (CMD) and run:

    where python

Example output:
    C:\Users\Admin001\AppData\Local\Programs\Python\Python310\python.exe

Copy this path for the next step.

-----------------------------------------------------------------------------------------------------

2) EDIT install_services.bat
-----------------------------------------------------------------------------------------------------
Open file: install_services.bat

Edit the following variables:

- PYTHON_PATH
  Example:
    set PYTHON_PATH=C:\Users\Admin001\AppData\Local\Programs\Python\Python310\python.exe

- PROJECT_PATH
  Example:
    set PROJECT_PATH=D:\your_project_folder

Make sure both paths are correct.

-----------------------------------------------------------------------------------------------------

3) INSTALL SERVICES
-----------------------------------------------------------------------------------------------------
Right click on:
    install_services.bat

Select:
    Run as Administrator

Wait until installation completes successfully.

-----------------------------------------------------------------------------------------------------

4) OPEN SERVICES MANAGER
-----------------------------------------------------------------------------------------------------
Press:
    Win + R

Type:
    services.msc

Press Enter.

-----------------------------------------------------------------------------------------------------

5) FIND SERVICES
-----------------------------------------------------------------------------------------------------
Look for the following services:

    SocketServerService
    TaskCameraService

-----------------------------------------------------------------------------------------------------

6) SET RECOVERY OPTIONS (IMPORTANT)
-----------------------------------------------------------------------------------------------------
For BOTH services:

Right click → Properties → Recovery tab

Set:
    First failure        → Restart the Service
    Second failure       → Restart the Service
    Subsequent failures  → Restart the Service

Click Apply → OK

-----------------------------------------------------------------------------------------------------

7) RESTART COMPUTER
-----------------------------------------------------------------------------------------------------
Restart your computer to allow services to start automatically.

=====================================================================================================
VERIFY INSTALLATION
=====================================================================================================

After reboot:

1. Open services.msc
2. Make sure both services show status: RUNNING

=====================================================================================================
TROUBLESHOOTING
=====================================================================================================

- If python not found:
  Reinstall Python 3.10 and check "Add Python to PATH"

- If service cannot start:
  1. Verify PYTHON_PATH is correct
  2. Verify PROJECT_PATH is correct
  3. Make sure Docker Desktop is running
