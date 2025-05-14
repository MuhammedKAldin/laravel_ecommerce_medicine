@echo off

:: Change to the directory containing the batch file
cd /d %~dp0

:: Start "npm run dev" in a new command window inside BK-Dashboard
start cmd /k "npm run dev"

:: Wait for 5 seconds to ensure npm starts first
timeout /t 5 /nobreak > nul

:: Start "php artisan serve" in a new command window inside BK-Backend
start cmd /k "php artisan serve"

:: Wait a moment before opening the browser to ensure processes start
timeout /t 2 /nobreak > nul

:: Open the browser to http://localhost:5173/
start http://localhost:5173/

:: Exit the batch script
exit
