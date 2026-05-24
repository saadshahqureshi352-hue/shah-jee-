@echo off
cd /d C:\laragon\www\shah-jee-courier

REM Create directories
mkdir app\Filament\Resources\WalletResource\Pages 2>nul
mkdir app\Filament\Resources\APIKeyResource\Pages 2>nul
mkdir app\Filament\Resources\RateMatrixResource\Pages 2>nul
mkdir app\Filament\Resources\CODReconciliationResource\Pages 2>nul
mkdir app\Filament\Resources\PayoutResource\Pages 2>nul

REM Run Python script
python create_pages.py

pause
