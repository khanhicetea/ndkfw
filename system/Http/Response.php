<?php
namespace Http;
use \Session\Session as Session;

defined('DS') or die();

class Response {
    private static $_instance = null;
    protected static $status_messages = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found', // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );
    private $status_code;
    private $http_headers;
    private $lock;

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public static function getStatusMessage($status_code) {
        if (array_key_exists($status_code, self::$status_messages)) {
            return self::$status_messages[$status_code];
        }

        return false;
    }

    private function __construct() {
        $this->lock = false;
    }

    private function responseCode() {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            http_response_code($this->status_code);
        } else {
            $status_msg = self::getStatusMessage($this->status_code);
            if ($status_msg === false) {
                die();
            }

            if (filter_input(INPUT_SERVER, 'FCGI_SERVER_VERSION') != null) {
                header('Status: ' . $this->status_code . ' ' . $status_msg);
            } else {
                $protocol = Request::getServerProtocol();
                header($protocol . ' ' . $this->status_code . ' ' . $status_msg);
            }
        }
    }

    public function responseHeaders() {
        // set http headers
        foreach ($this->http_headers as $name => $value) {
            header($name . ': ' . $value);
        }
        header('X-Framework-Powered-By: NDKSolution');
    }

    public function printOut($content, $status_code = 200, $http_headers = array()) {
        if ($this->lock === false) {
            $this->lock = true;
            $this->status_code = $status_code;
            $this->http_headers = $http_headers;

            $this->responseCode();
            $this->responseHeaders();

            // print out content
            echo $content;
        }
    }

    public function streamDownload($file_path, $file_name = '', $mime_type = 'application/octet-stream') {
        if ($this->lock === false) {
            $this->lock = true;

            if (is_readable($file_path)) {
                if ($file_name === '') {
                    $file_name = basename($file_path);
                }

                $file_size = filesize($file_path);
                $modified_time = date('r', filemtime($file_path));

                $this->http_headers = array(
                    'Content-Type' => $mime_type,
                    'Cache-Control' => 'public, must-revalidate, max-age=0',
                    'Pragma' => 'no-cache',
                    'Content-Length:' => $file_size,
                    'Content-Disposition' => 'inline; filename=' . $file_name,
                    'Content-Transfer-Encoding' => 'binary',
                    'Last-Modified' => $modified_time,
                    'Connection' => 'close'
                );

                $this->status_code = 200;

                $this->responseCode();
                $this->responseHeaders();

                readfile($file_path);
            } else {
                $this->status_code = 404;
                $this->responseCode();
            }
        }
    }

    public function redirect($url) {
        Session::transferFlash();

        $this->status_code = 302;
        $this->http_headers = array('Location' => $url);

        $this->responseCode();
        $this->responseHeaders();

        die();
    }

}
