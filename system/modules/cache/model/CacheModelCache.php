<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CacheModelCache
 *
 */
class CacheModelCache {
    
    public function add($sKey, $value, $iTime = 0) {
        apc_store($sKey, $value, $iTime);
    }
    
    public function get($sKey) {
        return apc_fetch($sKey);
    }
    
    public function delete($sKey) {
        apc_delete($sKey);
    }
    
    public function exists($sKey) {
        return apc_exists($sKey);
    }
    
    public function clear() {
        apc_clear_cache();
    }
}

?>
