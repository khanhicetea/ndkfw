<?php
namespace Http;
defined('DS') or die();

class Request {
    public static function getHttpHost() {
        return filter_input(INPUT_SERVER, 'HTTP_HOST');
    }
    
    public static function getHttpUserAgent() {
        return filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
    }
    
    public static function getHttpAccept() {
        return filter_input(INPUT_SERVER, 'HTTP_ACCEPT');
    }
    
    public static function getHttpAcceptLanguage() {
        return filter_input(INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE');
    }
    
    public static function getHttpAcceptEncoding() {
        return filter_input(INPUT_SERVER, 'HTTP_ACCEPT_ENCODING');
    }
    
    public static function getHttpConnection() {
        return filter_input(INPUT_SERVER, 'HTTP_CONNECTION');
    }
    
    public static function getPath() {
        return filter_input(INPUT_SERVER, 'PATH');
    }
    
    public static function getServerSignature() {
        return filter_input(INPUT_SERVER, 'SERVER_SIGNATURE');
    }
    
    public static function getServerSoftware() {
        return filter_input(INPUT_SERVER, 'SERVER_SOFTWARE');
    }
    
    public static function getServerName() {
        return filter_input(INPUT_SERVER, 'SERVER_NAME');
    }
    
    public static function getServerAddr() {
        return filter_input(INPUT_SERVER, 'SERVER_ADDR');
    }
    
    public static function getServerPort() {
        return filter_input(INPUT_SERVER, 'SERVER_PORT');
    }
    
    public static function getRemoteAddr() {
        return filter_input(INPUT_SERVER, 'REMOTE_ADDR');
    }
    
    public static function getDocumentRoot() {
        return filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
    }
    
    public static function getServerAdmin() {
        return filter_input(INPUT_SERVER, 'SERVER_ADMIN');
    }
    
    public static function getScriptFilename() {
        return filter_input(INPUT_SERVER, 'SCRIPT_FILENAME');
    }
    
    public static function getRemotePort() {
        return filter_input(INPUT_SERVER, 'REMOTE_PORT');
    }
    
    public static function getGatewayInterface() {
        return filter_input(INPUT_SERVER, 'GATEWAY_INTERFACE');
    }
    
    public static function getServerProtocol() {
        return filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
    }
    
    public static function getRequestMethod() {
        return filter_input(INPUT_SERVER, 'REQUEST_METHOD');
    }
    
    public static function getQueryString() {
        return filter_input(INPUT_SERVER, 'QUERY_STRING');
    }
    
    public static function getRequestUri() {
        return filter_input(INPUT_SERVER, 'REQUEST_URI');
    }
    
    public static function getScriptName() {
        return filter_input(INPUT_SERVER, 'SCRIPT_NAME');
    }
    
    public static function getPathInfo() {
        return filter_input(INPUT_SERVER, 'PATH_INFO');
    }
    
    public static function getPathTranslated() {
        return filter_input(INPUT_SERVER, 'PATH_TRANSLATED');
    }
    
    public static function getPhpSelf() {
        return filter_input(INPUT_SERVER, 'PHP_SELF');
    }
    
    public static function getRequestTimeFloat() {
        return filter_input(INPUT_SERVER, 'REQUEST_TIME_FLOAT');
    }
    
    public static function getRequestTime() {
        return filter_input(INPUT_SERVER, 'REQUEST_TIME');
    }
}