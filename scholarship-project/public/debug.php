<?php
define('APP_URL',  'http://localhost/web2-OPT2/scholarship-project/public');
define('BASE_URL', 'http://localhost/web2-OPT2/scholarship-project/public/index.php');

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
