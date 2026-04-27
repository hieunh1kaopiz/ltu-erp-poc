<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý phiên bản — LTU ERP</title>
    <?php
    $manifest = json_decode(@file_get_contents(__DIR__ . '/../../../public/mix-manifest.json'), true) ?: [];
    $cssPath  = $manifest['/css/app.css'] ?? '/css/app.css';
    ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($cssPath) ?>">
</head>
<body>
    <div class="container">
        <h1>Quản lý phiên bản</h1>
        <p>Phiên bản hiện tại:
            <span class="version-badge"><?= htmlspecialchars($version) ?></span>
        </p>

        <h3>Trạng thái</h3>
        <ul>
            <li>App: ✅ Đang chạy</li>
            <li>Maintenance: <?php
                echo file_exists(__DIR__ . '/../../../storage/framework/down')
                    ? '🔧 ON' : '✅ OFF';
            ?></li>
        </ul>

        <h3>Ghi chú POC</h3>
        <p><em>
            Trang này là placeholder. Trong bản POC, deploy được trigger từ
            <strong>Semaphore Web UI</strong> (Hub) — không phải từ trang này.
            Việc tích hợp ERP UI gọi Semaphore API thuộc giai đoạn Phase 2 của plan.
        </em></p>

        <hr>
        <p><a href="/">← Về trang chủ</a></p>
    </div>
</body>
</html>
