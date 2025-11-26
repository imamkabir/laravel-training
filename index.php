<?php
// Simple router: API under /api, otherwise web
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if(strpos($uri, '/api/') === 0){
    require __DIR__ . '/src/Routes/api.php';
    exit;
} else {
    require __DIR__ . '/src/Routes/web.php';
    exit;
}
