<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LTU ERP — Trang chủ</title>
    <?php
    // Mix manifest cho versioned assets
    $manifest = json_decode(@file_get_contents(__DIR__ . '/../../public/mix-manifest.json'), true) ?: [];
    $cssPath = $manifest['/css/app.css'] ?? '/css/app.css';
    $jsPath  = $manifest['/js/app.js']  ?? '/js/app.js';
    ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($cssPath) ?>">
</head>
<body>
    <div id="app" class="container">
        <h1>{{ appName }}</h1>
        <p>Phiên bản hiện tại:
            <span class="version-badge"><?= htmlspecialchars($version) ?></span>
        </p>
        <p>{{ message }}</p>
        <hr>
        <p>
            <a href="/admin/deployment">→ Vào màn hình quản lý phiên bản</a>
        </p>
    </div>
    <script src="<?= htmlspecialchars($jsPath) ?>"></script>
</body>
</html>
