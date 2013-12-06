<?php
namespace Logger;
defined('DS') or die();

interface ILogger {
    public function logTrace($msg);
    public function readLogs(Array $options);
}