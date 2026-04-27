<?php
/**
 * Front controller cho LTU ERP POC.
 *
 * Routes hỗ trợ:
 *   GET /                       Trang chủ (hiển thị VERSION)
 *   GET /admin/deployment       Màn hình quản lý deployment (UI placeholder)
 *   GET /_internal/health       Health check endpoint (bypass maintenance)
 */

require_once __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

$basePath = realpath(__DIR__ . '/..');
$uri      = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// ---- Maintenance mode middleware ----
$maintenanceFile = "$basePath/storage/framework/down";
$except = ['/_internal/health'];

if (file_exists($maintenanceFile) && !in_array($uri, $except, true)) {
    http_response_code(503);
    header('Content-Type: text/html; charset=utf-8');
    $info = json_decode(file_get_contents($maintenanceFile), true) ?: [];
    $msg  = htmlspecialchars($info['message'] ?? 'Hệ thống đang bảo trì');
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'>";
    echo "<title>503 - Maintenance</title></head><body>";
    echo "<h1>503 — Hệ thống đang bảo trì</h1>";
    echo "<p>$msg</p>";
    echo "<p>Vui lòng thử lại sau ít phút.</p>";
    echo "</body></html>";
    exit;
}

// ---- Helpers ----
function getVersion(string $basePath): string {
    $f = "$basePath/VERSION";
    return file_exists($f) ? trim(file_get_contents($f)) : 'unknown';
}

function getDb(): ?PDO {
    try {
        return new PDO(
            "mysql:host=" . ($_ENV['DB_HOST'] ?? '127.0.0.1')
            . ";port=" . ($_ENV['DB_PORT'] ?? '3306')
            . ";dbname=" . ($_ENV['DB_DATABASE'] ?? '')
            . ";charset=utf8mb4",
            $_ENV['DB_USERNAME'] ?? '',
            $_ENV['DB_PASSWORD'] ?? '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (Throwable $e) {
        return null;
    }
}

// ---- Routes ----
if ($uri === '/_internal/health') {
    $token    = $_SERVER['HTTP_X_INTERNAL_TOKEN'] ?? '';
    $expected = $_ENV['INTERNAL_HEALTH_TOKEN'] ?? '';
    if (!$expected || !hash_equals($expected, $token)) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'forbidden']);
        exit;
    }
    $db = getDb();
    header('Content-Type: application/json');
    echo json_encode([
        'status'      => 'ok',
        'app_version' => getVersion($basePath),
        'db'          => $db ? 'ok' : 'fail',
        'php'         => PHP_VERSION,
    ]);
    exit;
}

if ($uri === '/admin/deployment') {
    $version = getVersion($basePath);
    include "$basePath/resources/views/admin/deployment.php";
    exit;
}

if ($uri === '/' || $uri === '') {
    $version = getVersion($basePath);
    include "$basePath/resources/views/home.php";
    exit;
}

http_response_code(404);
header('Content-Type: text/html; charset=utf-8');
echo "<h1>404 Not Found</h1>";
