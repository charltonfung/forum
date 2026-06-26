# ============================================================
# Laravel forum 開發用 image
# ============================================================
# 基底：PHP 8.1 CLI（不用 fpm + nginx，直接用 artisan serve 就好，學習階段足夠）
#
# 包含：
#   - PHP 8.1 + Laravel 需要的 extension（pdo_mysql, mbstring, zip, gd 等）
#   - Composer
#   - Node.js 18 + npm（前端資源編譯用）
#
# 不在這層做 composer install / npm install — 因為 source code 是 volume mount，
# build 時做了也會被蓋掉。改成「容器起來後再執行一次」。
# ============================================================
FROM php:8.1-cli

# 系統相依（git 給 composer 用、libzip / libpng / libonig 給 PHP ext 用）
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        curl \
        libzip-dev \
        libpng-dev \
        libonig-dev \
        ca-certificates \
        gnupg \
    && rm -rf /var/lib/apt/lists/*

# Laravel 需要的 PHP extension
RUN docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        gd \
        bcmath

# Composer（從官方 image 複製過來，免重新裝）
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js 18（前端編譯用）
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# 預設指令：
# 因為 source code 是 volume mount，container 第一次起來時 vendor/ 跟 .env 可能還沒準備好
# （setup.sh 要在 container 跑起來「之後」才能 exec composer install / cp .env.example .env）
# 所以這裡先 busy-wait 等這兩個檔存在，setup 完成後才真的啟動 dev server。
# `exec` 是讓 php 取代 shell process，這樣 Ctrl+C / docker stop 訊號能正確傳給 php。
CMD ["sh", "-c", "until [ -d vendor ] && [ -f .env ]; do echo 'Waiting for vendor/ and .env (setup in progress)...'; sleep 2; done; exec php artisan serve --host=0.0.0.0 --port=8000"]
