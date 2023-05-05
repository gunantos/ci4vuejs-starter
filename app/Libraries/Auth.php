<?php
namespace App\Libraries;

class Auth 
{
    public static function isRoles($key) {
        $role = session()->roles;
        if (\is_array($role)) {
            $ind = array_search($key, $role);
            if ($ind > -1) {
                return true;
            }
        }
        return false;
    }
}