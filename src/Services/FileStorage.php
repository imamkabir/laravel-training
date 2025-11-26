<?php
namespace Services;
class FileStorage {
    public static function saveToUploads(string $tempPath, string $destName){
        $dest = __DIR__ . '/../../public/uploads/' . $destName;
        rename($tempPath, $dest);
        return $dest;
    }
}
