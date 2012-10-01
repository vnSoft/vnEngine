<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Lang
 *
 */
class Lang {
    
    function get($sLangVar) {
        return static::$sLangVar;
    }
}

?>
