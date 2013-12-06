<?php
namespace Session;
defined('DS') or die();

abstract class BitField {
    protected $bits = '';
    protected $roles = array();

    public function __construct($bits = '') {
        $this->setRoles();
        $this->bits = $bits . str_repeat('0', count($this->roles) - strlen($bits));
    }

    public function set($name, $value) {			
        $indexed = array_search($name, $this->roles);
    
        if ($indexed !== false) {
            $this->bits[$indexed] = ($value ? '1' : '0');
        }
    }

    public function get($name) {
        $indexed = array_search($name, $this->roles);
        
        if ($indexed !== false) {
            return ($this->bits[$indexed] == '1' ? true : false);
        }
    
        return false;
    }

    public function __toString() {
        return $this->bits;
    }

    public function roles() {
        return $this->roles;
    }
    
    abstract public function setRoles();
}