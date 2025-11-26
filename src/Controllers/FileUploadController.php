<?php
namespace Controllers;
// Keep strict includes local
require_once __DIR__ . '/../Services/VirusScanner.php';
require_once __DIR__ . '/../Database/connection.php';

use Services\VirusScanner;

class FileUploadController {
    public static function handleUpload(){
        // Always return JSON
        header('Content-Type: application/json; charset=utf-8');

        // Prevent PHP notices/warnings from breaking JSON response
        ini_set('display_errors', '0');
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

        // Clear any accidental output before we start
        if (ob_get_length()) {
            ob_clean();
        }

        try {
            if(!isset($_FILES['file'])) {
                http_response_code(400);
                echo json_encode(['error'=>'no_file']);
                return;
            }
            $file = $_FILES['file'];
            $tmp = $file['tmp_name'] ?? null;
            $orig = basename($file['name'] ?? 'unknown');
            $size = (int)($file['size'] ?? 0);

            if(!$tmp || $size === 0){
                http_response_code(400);
                echo json_encode(['error'=>'empty_or_invalid_file']);
                return;
            }

            // secure filename
            $safeName = preg_replace('/[^a-zA-Z0-9._-]/','_', $orig);
            $sha = hash_file('sha256', $tmp);
            $uniq = substr($sha,0,12) . '_' . $safeName;
            $tempPath = __DIR__ . '/../../storage/temp/' . $uniq;

            if(!move_uploaded_file($tmp, $tempPath)){
                http_response_code(500);
                echo json_encode(['error'=>'move_failed']);
                return;
            }

            // run the scanner
            $scanner = new VirusScanner();
            $result = $scanner->scan($tempPath);
            if(!is_array($result)){
                $result = ['risk_level'=>'unknown','message'=>'Invalid scanner result'];
            }

            // write log (non-blocking best-effort)
            $logEntry = [
                'id'=>$uniq,
                'original'=>$orig,
                'size'=>$size,
                'sha256'=>$sha,
                'result'=>$result,
                'time'=>date('c')
            ];
            @file_put_contents(__DIR__ . '/../../storage/logs/scans.log', json_encode($logEntry, JSON_UNESCAPED_SLASHES).PHP_EOL, FILE_APPEND);

            // connect DB
            $pdo = \Database\getPDO();
            $stmt = $pdo->prepare('INSERT INTO uploads(id, filename, original_name, size, sha256, status, risk_level, message, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $now = date('c');

            if(($result['risk_level'] ?? '') === 'clean'){
                $dest = __DIR__ . '/../../public/uploads/' . $uniq;
                if(!@rename($tempPath, $dest)){
                    // fallback to copy/remove
                    copy($tempPath, $dest);
                    @unlink($tempPath);
                }
                $stmt->execute([$uniq,$uniq,$orig,$size,$sha,'clean',$result['risk_level'],$result['message'],$now]);
                echo json_encode(['status'=>'clean','saved_name'=>$uniq,'sha256'=>$sha]);
                return;
            } else {
                // quarantine (keep in temp)
                $stmt->execute([$uniq,$uniq,$orig,$size,$sha,'quarantined',$result['risk_level'],$result['message'],$now]);
                echo json_encode(['status'=>'infected','message'=>$result['message'],'sha256'=>$sha]);
                return;
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            // log server error
            @file_put_contents(__DIR__ . '/../../storage/logs/error.log', date('c') . ' ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo json_encode(['error'=>'exception','message'=>$e->getMessage()]);
            return;
        }
    }
}
