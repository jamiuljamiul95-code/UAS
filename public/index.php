<?php
define('ROOT', dirname(__DIR__));
require_once ROOT . '/vendor/autoload.php';

// Load .env
$env = parse_ini_file(ROOT . '/.env');
foreach ($env as $key => $value) {
    $_ENV[$key] = $value;
}

// BASE_URL = path folder project relatif ke domain.
// Contoh: kalau diakses lewat http://localhost/mizu-design/public/
// maka BASE_URL = /mizu-design/public
// Kalau nanti pakai virtual host / production (index.php di root domain), otomatis jadi "".
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));

require_once ROOT . '/routes/web.php';
