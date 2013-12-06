<?php
namespace Session;
defined('DS') or die();

class Auth {
    
    private static $auth_user = null;
    
    public static function setUser(AuthUser $user) {
        self::$auth_user = $user;
    }
    
    public static function getUser() {
        return self::$auth_user;
    }
    
    public static function allow($role) {
        Session::set('ROLE_' . $role, TRUE);
    }
    
    public static function deny($role) {
        Session::remove('ROLE_' . $role);
    }

    public static function check($role) {
        return (Session::get('ROLE_' . $role) != null);
    }
    
}