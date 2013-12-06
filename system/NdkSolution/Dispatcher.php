<?php
namespace NdkSolution;
use \Http\Request;
defined('DS') or die();

class Dispatcher {
    private $_uri;
    private $_routes;
    private $_segments;
    private $_controller;
    private $_controller_name;

    public function __construct() {
        $uri = Request::getRequestUri();
        
        if (Config::get('url_rewrite')) {
            $uri = str_replace(Config::get('url_suffix'), '', $uri);
        } else {
            $script_file = Request::getScriptName();
            $uri = str_replace($script_file, '', $uri);
        }
        
        $this->_routes = array();
        $this->_uri = rtrim($uri, '/');
    }
    
    private function loadLanguage() {
        $language_codes  = Config::get('language_codes');
        $language_code = $language_codes[0];
        $segments = array_slice(explode('/', $this->_uri), 1);
        
        if (Config::get('multi_languages')) {    
            $language_code = array_shift($segments);
        }
        
        Language::setup($language_code);
        $this->_uri = '/' . implode('/', $segments);
    }

    public function addRoute($routes) {        
        $this->_routes = array_merge($this->_routes, $routes);
    }
    
    private function parseRoute($route, $to_route, $uri) {
        return preg_replace($route, $to_route, $uri);
    }
    
    private function route() {
        $pattern_alias = array('/', '[num]', '[word]', '[any]');
        $pattern_routes = array('\/', '([0-9]{1,})', '([A-Za-z0-9]{1,})', '([A-Za-z0-9_-]{1,})');
        $uri = $this->_uri;
        
        foreach ($this->_routes as $route => $to_route) {
            $new_pattern = '#^' . str_replace($pattern_alias, $pattern_routes, $route) . '$#is';
            $uri = $this->parseRoute($new_pattern, $to_route, $this->_uri);
            
            if ($uri != $this->_uri) {
                break;
            }
        }
        
        $this->_uri = $uri;        
    }
    
    private function loadController() {
        $this->_segments = array_slice(explode('/', $this->_uri), 1);
        
        $controller = '';
        $c_tmp = '';
        $controller_folder = APPLICATION_PATH . 'controllers' . DS;
        $current_path = $controller_folder;
        $controller_path = '';

        do {
            $c_tmp = array_shift($this->_segments);
            $controller_path = $current_path . ucfirst($c_tmp) . 'Controller' . EXT;

            if (file_exists($controller_path)) {
                $controller = ucfirst($c_tmp) . 'Controller';
            } else {
                $current_path = $current_path . $c_tmp . DS;
            }
        } while ($controller == '' and count($this->_segments) > 0);

        if ($controller == '') {
            $controller = ucfirst(Config::get('default_controller')) . 'Controller';
            $controller_path = $controller_folder . $controller . EXT;
        }

        if (is_readable($controller_path)) {
            load_file($controller_path);
            if (class_exists($controller)) {
                $this->_controller = new $controller();
                $this->_controller_name = $controller;
            }

            return true;
        } 
    }
    
    private function loadMethod() {
        $method = array_shift($this->_segments);
        if (! $method) {
            $method = Config::get('default_method');
        }

        $m_tmp = $method . '_' . strtolower(Request::getRequestMethod());
        if (method_exists($this->_controller, $m_tmp)) {
            $method = $m_tmp;
        }

        if (method_exists($this->_controller, $method)) {
            call_user_func_array(array($this->_controller, $method), $this->_segments);
        } else {
            throw_error('Method `' . $method . '` is not found !');
        }
    }

    public function run() {
        $this->loadLanguage();
        $this->addRoute(load_file(APPLICATION_PATH . 'configs' . DS . 'routes' . EXT, true));
        $this->route();
        $this->loadController();
        $this->loadMethod();
    }
}