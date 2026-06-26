#!/usr/bin/env bash
# ============================================================
# FREE TALK 一鍵啟動腳本（Mac / Linux）
# ============================================================
# 用法：
#   chmod +x setup.sh
#   ./setup.sh
#
# 預期：機器只裝了 Docker Desktop，其他什麼都沒有
# 跑完：http://localhost:8000 直接可以登入用 demo 帳號 demo
# ============================================================

set -e   # 遇錯立即中止
set -o pipefail

echo ""
echo "=========================================="
echo "  FREE TALK setup"
echo "=========================================="
echo ""

# 1. 確認 Docker 在跑
if ! docker info > /dev/null 2>&1; then
    echo "[!] Docker daemon 沒在跑，請先開啟 Docker Desktop 後重試"
    exit 1
fi

# 2. 啟動 container（第一次會 build）
echo "[1/7] Starting containers (first time builds image, ~3 min)..."
docker compose up -d --build

# 3. 等 MySQL 就緒
echo "[2/7] Waiting for MySQL to be ready..."
until docker compose exec -T db mysqladmin ping -h localhost -uroot -proot --silent > /dev/null 2>&1; do
    printf "."
    sleep 1
done
echo " ready"

# 4. .env
if [ ! -f .env ]; then
    echo "[3/7] Creating .env from .env.example..."
    docker compose exec -T app cp .env.example .env
else
    echo "[3/7] .env already exists, skipping"
fi

# 5. composer install
echo "[4/7] Installing PHP packages (composer install)..."
docker compose exec -T app composer install --no-interaction --prefer-dist

# 6. npm install + build
echo "[5/7] Installing Node packages (npm install)..."
docker compose exec -T app npm install --silent

echo "[6/7] Building frontend assets (npm run dev)..."
docker compose exec -T app npm run dev

# 7. APP_KEY + migrate + seed
echo "[7/7] Generating APP_KEY, running migrations, seeding demo data..."
docker compose exec -T app php artisan key:generate --force
docker compose exec -T app php artisan migrate:fresh --seed --force

echo ""
echo "=========================================="
echo "  Setup complete!"
echo "=========================================="
echo ""
echo "  App       : http://localhost:8000"
echo "  Mailpit   : http://localhost:8025"
echo "  MySQL     : localhost:3306 (root / root)"
echo ""
echo "  Demo accounts (password: 'password'):"
echo "    alice@example.com"
echo "    bob@example.com"
echo "    charlie@example.com"
echo ""
echo "  Stop all : docker compose down"
echo ""
