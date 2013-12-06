<?php
namespace FileSystem;
defined('DS') or die();

class Folder {
    
    private $dir_path;
    
    public function __construct($dir_path) {
        $this->dir_path = $dir_path;
    }
    
    public function isReadable() {
        return is_readable($this->dir_path);
    }
    
    public function isWritable() {
        return is_writable($this->dir_path);
    }
    
    public function isExists() {
        return is_dir($this->dir_path);
    }
    
    public static function createFolder($dir_path, $mode = 0755, $recursive = TRUE) {
        if (is_dir($dir_path)) {
            return FALSE;
        }
        
        return mkdir($dir_path, $mode, $recursive);
    }
    
    public static function deleteFolder($dir_path) {
        $dir_path = rtrim($dir_path, '/');
        foreach (glob($dir_path . '/*') as $file) {
            if (is_dir($file)) {
                self::deleteFolder($file);
            } else {
                File::deleteFile($file);
            }
        }
        return rmdir($dir_path);
    }
    
}