<?php
namespace Session;
defined('DS') or die();

interface AuthUser {
    public function getId();
    public function checkLogin();
    public function permission($role);
}