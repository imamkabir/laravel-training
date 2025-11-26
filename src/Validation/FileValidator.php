<?php
namespace Validation;
class FileValidator {
    public static function isAllowed($file){
        $max = 20 * 1024 * 1024; // 20 MB
        if($file['size'] > $max) return false;
        return true;
    }
}
