<?php
declare(strict_types=1);

// ── Base URL ──────────────────────────────────────────────────────────────────
// APP_URL  = folder chứa index.php (dùng cho assets, links tĩnh)
// BASE_URL = entry point index.php (dùng cho routing)
define('APP_URL',  'http://localhost/web2-OPT2/scholarship-project/public');
define('BASE_URL', 'http://localhost/web2-OPT2/scholarship-project/public/index.php');

// ── Autoload core files ───────────────────────────────────────────────────────
require_once __DIR__ . '/../app/core/Session.php';
Session::start();

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/Router.php';

// ── Autoload models ───────────────────────────────────────────────────────────
$modelDir = __DIR__ . '/../app/models/';
foreach (glob($modelDir . '*.php') as $modelFile) {
    require_once $modelFile;
}

// ── Dispatch ──────────────────────────────────────────────────────────────────
$router = new Router();
$router->dispatch();
