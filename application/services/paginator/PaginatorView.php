<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PaginatorView
 *
 */
class PaginatorView extends View{
    
    public function __construct($sPath) {
        $this->m_sPath = APPROOT.'services'.DIRECTORY_SEPARATOR.'paginator'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$sPath;
    }
}

?>
