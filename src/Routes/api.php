<?php
// API router — always return JSON, protect against stray HTML/errors
// Turn off HTML error display for API
ini_set('display_errors', '0');
error_reporting(E_ALL);

// Buffer output so we can clean accidental output
ob_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

require_once __DIR__ . '/../Controllers/FileUploadController.php';

try {
    if ($uri === '/api/upload' && $method === 'POST') {
        \Controllers\FileUploadController::handleUpload();
        // controller will handle output
        $out = ob_get_clean();
        // If controller already printed JSON, just exit
        if (trim($out) !== '') {
            // if output looks like JSON, echo it; otherwise wrap it
            // but prefer controller's output
            echo $out;
        }
        exit;
    }

    // Default 404 JSON
    ob_end_clean();
    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'not_found']);
    exit;
} catch (\Throwable $e) {
    ob_end_clean();
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'server_error', 'message' => $e->getMessage()]);
    exit;
}
