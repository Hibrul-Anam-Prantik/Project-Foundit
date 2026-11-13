@echo off
set "PATH=D:\Softwares\others\php;D:\Softwares\others\composer;%PATH%"

if not exist "%~dp0public\storage" (
    mklink /J "%~dp0public\storage" "%~dp0storage\app\public" >nul 2>&1
)

echo Starting FoundIT Local Server at http://127.0.0.1:8000 ...
php artisan serve --host=127.0.0.1 --port=8000
pause
