@echo off
title MangaStream Server
echo ========================================================
echo   MangaStream - He thong Doc Truyen Tranh & Manga Shop
echo   Do An Lap Trinh Web PHP & MySQL - Muc Tieu Diem A
echo ========================================================
echo.
echo Dang khoi dong Web Server tai http://127.0.0.1:8000 ...
echo Nhan Ctrl + C de dung server.
echo.
"C:\xampp1\php\php.exe" artisan serve --port=8000
pause
