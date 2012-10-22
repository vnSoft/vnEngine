<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CookieModelSession
 *
 */
class CookieModelSession {
    
    function set($sCookieName, $value, $iTime, $bExpireTime = false) {
        if($bExpireTime)
            setcookie($sCookieName, $value, $iTime);
        else
            setcookie($sCookieName, $value, time()+$iTime);
    }
    
    function get($sCookieName) {
        $value = null;
        
        if($this->isVarSet($sCookieName))
            $value = $_COOKIE[$sCookieName];
        
        return $value;
    }
    
    function isVarSet($sCookieName) {
        return isset($_COOKIE[$sCookieName]);
    }
}

?>
