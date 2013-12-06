<?php
namespace Logger\Adapter;
use Logger\ILogger;
use FileSystem\File;

defined('DS') or die();

class FileLogger implements ILogger {
    private $file = null;
    
    public function __construct($dir_path, $file_name = null) {
        if (empty($file_name)) {
            $file_name = date("d-m-Y") . '.log';
        }
        
        $this->file = new File($dir_path . $file_name);        
        $this->file->openHandle(File::FILE_APPEND);
    }
    
    public function logTrace($msg) {
        $log_msg = date("d-m-Y H:i:s") . "\t" . $msg . "\n";
        $this->file->write($log_msg);
    }

    public function readLogs(array $options) {
        
    }
    
    public function __destruct() {
        $this->file->closeHandle();
    }

}