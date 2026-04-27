# LTU ERP — POC App

Đây là app **mô phỏng** ERP của LTU dùng cho POC hệ thống Centralized Deployment.
App được giữ tối thiểu, chỉ đủ để chạy lại đúng các bước deploy mà thiết kế gốc yêu cầu:

- `composer install --no-dev --optimize-autoloader` → tạo `vendor/`
- `npm ci && npm run production` → tạo `public/js/app.js`, `public/css/app.css`, `public/mix-manifest.json`
- `php artisan down / up` → bật/tắt maintenance mode
- `php artisan migrate` → chạy SQL migrations
- `php artisan tinker --execute="..."` → eval PHP để Ansible verify DB / version
- `GET /_internal/health` → JSON health check, bypass maintenance

## Cấu trúc

```
ltu-erp-poc/
├── composer.json              # Deps: vlucas/phpdotenv
├── package.json               # Deps: laravel-mix, vue 2.7
├── webpack.mix.js             # Mix config
├── artisan                    # Custom CLI (down/up/migrate/tinker/...)
├── VERSION                    # File version source-of-truth
├── .env.example
├── public/
│   └── index.php              # Front controller + /_internal/health
├── resources/
│   ├── js/app.js              # Vue 2 entry
│   ├── sass/app.scss
│   └── views/
│       ├── home.php
│       └── admin/deployment.php
├── database/
│   └── migrations/
│       └── 2026_01_01_000001_create_deploy_schedules_table.sql
└── storage/framework/
    ├── sessions/              # SESSION_DRIVER=file
    ├── views/
    └── cache/
```

## Setup local nhanh (test trước khi đẩy lên Hub)

```bash
cp .env.example .env
# Sửa DB_* trong .env trỏ đến MySQL local

composer install
npm ci
npm run production

php artisan migrate
php -S 127.0.0.1:8000 -t public
# Mở http://127.0.0.1:8000
```

## Tag phiên bản

Mỗi lần phát hành version mới:

1. Sửa file `VERSION` (ví dụ: `v0.2.0`).
2. Commit + tag git:
   ```bash
   git add VERSION
   git commit -m "Release v0.2.0"
   git tag v0.2.0
   git push origin main --tags
   ```

Hub Build CI sẽ checkout đúng tag để build artifact.
