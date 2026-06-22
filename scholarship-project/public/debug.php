<?php
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$publicPath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

define('APP_URL', $scheme . '://' . $host . $publicPath);
define('BASE_URL', APP_URL . '/index.php');

echo '<h3>URL Check</h3>';
echo 'APP_URL: ' . APP_URL . '<br>';
echo 'CSS URL: ' . APP_URL . '/assets/css/style.css' . '<br><br>';

// Test if CSS file exists
$cssPath = __DIR__ . '/assets/css/style.css';
echo 'CSS file exists: ' . (file_exists($cssPath) ? '<b style="color:green">YES</b>' : '<b style="color:red">NO – path: ' . $cssPath . '</b>') . '<br>';

// Test CSS content
if (file_exists($cssPath)) {
    echo 'CSS file size: ' . filesize($cssPath) . ' bytes<br>';
}

echo '<br><a href="' . APP_URL . '/assets/css/style.css" target="_blank">Click để mở CSS trực tiếp</a>';
?>
