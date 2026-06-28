<?php
define('ROOT', dirname(__DIR__));
require_once ROOT . '/vendor/autoload.php';

// Load env
$env = parse_ini_file(ROOT . '/.env');
foreach ($env as $key => $value) {
    $_ENV[$key] = $value;
}

// Router sederhana
// Extract path dari query string (dari .htaccess) atau REQUEST_URI
$uri = isset($_GET['path']) ? trim($_GET['path'], '/') : trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Jika app dipanggil dari subfolder atau public subfolder, hapus prefix script path.
$scriptDir = trim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($scriptDir !== '') {
    $uri = preg_replace('#^' . preg_quote($scriptDir, '#') . '/?#', '', $uri);
}

$uri = preg_replace('#^index\.php/?#', '', $uri);
$uri = trim($uri, '/');

$debugData = [];
$debugData[] = 'TIMESTAMP=' . date('Y-m-d H:i:s');
$debugData[] = 'SCRIPT_NAME=' . ($_SERVER['SCRIPT_NAME'] ?? '');
$debugData[] = 'REQUEST_URI=' . ($_SERVER['REQUEST_URI'] ?? '');
$debugData[] = 'QUERY_STRING=' . ($_SERVER['QUERY_STRING'] ?? '');
$debugData[] = 'GET_path=' . ($_GET['path'] ?? '');
$debugData[] = 'uri=' . $uri;
file_put_contents(ROOT . '/storage/logs/router-debug.txt', implode("\n", $debugData) . "\n---\n", FILE_APPEND);

if (isset($_GET['debug'])) {
    echo nl2br(implode("\n", $debugData));
    exit;
}

require_once ROOT . '/routes/web.php';