# ============================================================
# FREE TALK 一鍵啟動腳本（Windows PowerShell）
# ============================================================
# 用法：
#   .\setup.ps1
#
# 若 PowerShell 阻擋執行：以系統管理員身份開 PowerShell 跑一次
#   Set-ExecutionPolicy -Scope CurrentUser RemoteSigned
#
# 預期：機器只裝了 Docker Desktop，其他什麼都沒有
# ============================================================

$ErrorActionPreference = "Stop"

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  FREE TALK setup" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# 1. 確認 Docker 在跑
try {
    docker info | Out-Null
} catch {
    Write-Host "[!] Docker daemon 沒在跑，請先開啟 Docker Desktop 後重試" -ForegroundColor Red
    exit 1
}

# 2. 啟動 container
Write-Host "[1/7] Starting containers (first time builds image, ~3 min)..." -ForegroundColor Yellow
docker compose up -d --build

# 3. 等 MySQL 就緒
Write-Host "[2/7] Waiting for MySQL to be ready..." -ForegroundColor Yellow
while ($true) {
    docker compose exec -T db mysqladmin ping -h localhost -uroot -proot --silent 2>$null | Out-Null
    if ($LASTEXITCODE -eq 0) { break }
    Write-Host "." -NoNewline
    Start-Sleep -Seconds 1
}
Write-Host " ready"

# 4. .env
if (-not (Test-Path ".env")) {
    Write-Host "[3/7] Creating .env from .env.example..." -ForegroundColor Yellow
    docker compose exec -T app cp .env.example .env
} else {
    Write-Host "[3/7] .env already exists, skipping" -ForegroundColor Yellow
}

# 5. composer install
Write-Host "[4/7] Installing PHP packages (composer install)..." -ForegroundColor Yellow
docker compose exec -T app composer install --no-interaction --prefer-dist

# 6. npm install + build
Write-Host "[5/7] Installing Node packages (npm install)..." -ForegroundColor Yellow
docker compose exec -T app npm install --silent

Write-Host "[6/7] Building frontend assets (npm run dev)..." -ForegroundColor Yellow
docker compose exec -T app npm run dev

# 7. APP_KEY + migrate + seed
Write-Host "[7/7] Generating APP_KEY, running migrations, seeding demo data..." -ForegroundColor Yellow
docker compose exec -T app php artisan key:generate --force
docker compose exec -T app php artisan migrate:fresh --seed --force

Write-Host ""
Write-Host "==========================================" -ForegroundColor Green
Write-Host "  Setup complete!" -ForegroundColor Green
Write-Host "==========================================" -ForegroundColor Green
Write-Host ""
Write-Host "  App       : http://localhost:8000"
Write-Host "  Mailpit   : http://localhost:8025"
Write-Host "  MySQL     : localhost:3306 (root / root)"
Write-Host ""
Write-Host "  Demo accounts (password: 'password'):"
Write-Host "    alice@example.com"
Write-Host "    bob@example.com"
Write-Host "    charlie@example.com"
Write-Host ""
Write-Host "  Stop all : docker compose down"
Write-Host ""
