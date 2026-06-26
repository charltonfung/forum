# FREE TALK（Laravel forum）

簡單的多人文章討論版，使用者可發文、留言、互按讚。

## 技術棧

- **後端**：Laravel 8 + Jetstream（auth）+ Fortify（密碼重設）
- **前端**：Blade + Tailwind CSS 2
- **資料庫**：MySQL 8
- **PHP**：7.3+ 或 8.x

## 功能

- 註冊 / 登入 / 登出（含忘記密碼 email）
- 文章 CRUD（軟刪除、分頁、每頁 20 篇）
- 文章下留言（軟刪除、可刪自己的留言）
- 文章 / 留言點讚（防重複、可取消）
- 個人中心（看自己的文章 + 修改會員資料）

---

## 一鍵啟動（推薦）

只要本機有 **Docker Desktop**，跑一個 script 就會把所有東西準備好，含 demo 資料。

### Mac / Linux

```bash
chmod +x setup.sh
./setup.sh
```

### Windows

```powershell
.\setup.ps1
```

跑完約 3-5 分鐘（第一次要 build image + 裝套件）。完成後：

| URL | 是什麼 |
|---|---|
| http://localhost:8000 | Laravel app |
| http://localhost:8025 | Mailpit（看開發信件） |
| localhost:3306 | MySQL（root / root，host 端 GUI 工具用） |

### Demo 帳號

| Email | 密碼 |
|---|---|
| alice@example.com | password |
| bob@example.com | password |
| charlie@example.com | password |

預設帶 10 篇文章 + 留言 + 點讚資料。

> 不需要手動編 `.env` — script 會自動 `cp .env.example .env`。
> 而且 Docker 模式下，DB / Mail / APP_URL 這些設定都由 `docker-compose.yml` 的 `environment:` 覆蓋，
> Laravel 直接讀環境變數，根本不會用 .env 對應的值。

---

## Docker 啟動（手動逐步）

```powershell
docker compose up -d --build
docker compose exec app cp .env.example .env
docker compose exec app composer install
docker compose exec app npm install
docker compose exec app npm run dev
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed
```

### 常用 Docker 指令

```powershell
docker compose up -d              # 啟動（背景）
docker compose down               # 停止 + 移除 container（DB 資料保留）
docker compose down -v            # 連 DB volume 一起砍（資料清空）
docker compose logs -f app        # 看 app 即時 log
docker compose exec app bash      # 進到 app container 內部
docker compose exec app php artisan migrate   # 任何 artisan 指令前面加 docker compose exec app
docker compose exec app php artisan migrate:fresh --seed   # 重建 DB + 灌 demo 資料
```

---

## 啟動方式 B：傳統（本機裝 PHP / MySQL）

如果不想用 Docker，需要本機已裝 PHP 8.x、Composer、Node.js、MySQL 8。

### 1. 安裝相依套件

```powershell
cd <專案目錄>

composer install
npm install
```

### 2. 設定 `.env`

```powershell
copy .env.example .env
php artisan key:generate
```

打開 `.env` 確認 DB 設定：

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=freetalk
DB_USERNAME=root
DB_PASSWORD=
```

### 3. 建資料庫 + 跑 migration

```powershell
mysql -uroot -p -e "CREATE DATABASE freetalk DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate
```

### 4. 編譯前端資源

```powershell
npm run dev
```

### 5. 啟動 Laravel

```powershell
php artisan serve
```

打開 `http://localhost:8000`。

### 6.（選）寄信功能要看到內容

選一個：
- **log driver**：`.env` 設 `MAIL_MAILER=log`，信會寫到 `storage/logs/laravel.log`
- **MailHog**：本機跑 MailHog（`scoop install mailhog` 後 `mailhog`），`.env` 設 `MAIL_HOST=127.0.0.1` `MAIL_PORT=1025`

---

## 常見問題

| 症狀 | 原因 / 解法 |
|---|---|
| `No application encryption key has been specified` | 沒跑 `php artisan key:generate` |
| `SQLSTATE[HY000] [1049] Unknown database 'freetalk'` | DB 還沒建，回到 step 3 |
| `SQLSTATE[HY000] [2002] No connection could be made` | MySQL 沒啟動 |
| 樣式破破的（沒邊框 / 沒間距） | `npm run dev` 沒跑，前端資源沒編譯 |
| 500 錯誤看不到細節 | `.env` 把 `APP_DEBUG=false` 改成 `true`，本地 dev 用 |
| 註冊後進不去 `/user_profile` | `verified` middleware 卡住（email 沒驗證）。dev 階段可在 `routes/web.php` 把 `verified` 拿掉 |
| Docker：`Bind for 0.0.0.0:3306 failed: port is already allocated` | 本機 MySQL 在跑佔走 port。停掉本機 MySQL，或改 `docker-compose.yml` 的 ports 對應 |
| Docker：改了 `.env` 沒生效 | `docker-compose.yml` 用 environment 覆蓋了 `.env`。改 yml 內的 env vars，或進 container 內 `php artisan config:clear` |
| Docker：composer / npm 改了東西沒生效 | 進 container 重跑：`docker compose exec app composer install` |

---

## 目錄速覽

```
forum/
├── app/
│   ├── Http/Controllers/         ← ArticlesController / CommentsController / LikesController
│   └── Models/                   ← User / Article / Comment / ArticleLike / CommentLike
├── database/migrations/          ← 包含 users / articles / comments / likes 等 table 結構
├── resources/views/
│   ├── layouts/article.blade.php ← 主 layout（nav + footer）
│   ├── articles/                 ← 文章列表 / 內頁 / 發表 / 編輯
│   └── user_profile.blade.php    ← 個人中心
└── routes/web.php                ← 所有路由
```

## 常用指令

```powershell
php artisan serve           # 啟動 dev server
php artisan migrate         # 跑 migration
php artisan migrate:fresh   # 砍掉所有表重建
php artisan tinker          # 互動式 REPL
php artisan route:list      # 列出所有路由
npm run dev                 # 編譯前端（dev 模式）
```
