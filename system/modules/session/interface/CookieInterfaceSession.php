<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CookieInterfaceSession
 *
 */
class CookieInterfaceSession {
    protected $m_model;
    
    public function __construct() {
        $this->m_model = new CookieModelSession();
    }
    
    function set($sCookieName, $value, $iTime, $bExpireTime = false) {
        return $this->m_model->set($sCookieName, $value, $iTime, $bExpireTime);
    }
    
    function get($sCookieName) {
        return $this->m_model->get($sCookieName);
    }
    
    function isVarSet($sCookieName) {
        return $this->m_model->isVarSet($sCookieName);
    }
    
}

?>
