<?php
namespace FileSystem;
defined('DS') or die();

class File {
    
    const FILE_READ = 'r';
    const FILE_WRITE = 'w';
    const FILE_APPEND = 'a';
    const FILE_BINARY = 'b';
    

    private $file_path = null;
    private $file_handle = null;


    public function __construct($file_path) {
        $this->file_path = $file_path;
    }
    
    public function getFilePath() {
        return $this->file_path;
    }
    
    public function isExist() {
        return file_exists($this->file_path);
    }
    
    public function isReadable() {
        return is_readable($this->file_path);
    }
    
    public function isWritable() {
        return is_writable($this->file_path);
    }
    
    public function openHandle($mode = self::FILE_READ) {
        if ($this->file_handle === null) {
            $this->file_handle = fopen($this->file_path, $mode);
        }
        
        if ($this->file_handle === FALSE) {
            throw new \Exception("File `{$this->file_path}` cannot open !");
        }
        
        return $this->file_handle;
    }
    
    public function closeHandle() {
        if ($this->file_handle) {
            fclose($this->file_handle);
        }
            
        $this->file_handle = null;
    }
    
    public function write($data) {
        if (empty($this->file_handle)) {
            throw new \Exception("File handle is not opened !");
        }
        
        return fwrite($this->file_handle, $data);
    }
    
    public static function createFile($file_path, $overwrite = FALSE) {
        $file = new self($file_path);
        
        if ($file->isExist() && (!$overwrite)) {
            return FALSE;
        }
        
        $file->openHandle(self::FILE_WRITE);
        $file->closeHandle();
        
        return $file;
    }
    
    public function getDir($levels = 1) {
        $dir = $this->file_path;
        for ($i = 0; $i < $levels; $i++) {
            $dir = dirname($dir);
        }
        
        return $dir;
    }
    
    public static function deleteFile($file_path) {
        return unlink($file_path);
    }
    
    public static function copyFile($src_file_path, $dst_file_path) {
        return copy($src_file_path, $dst_file_path);
    }
    
    public static function moveFile($src_file_path, $dst_file_path) {
        return rename($src_file_path, $dst_file_path);
    }
    
}