<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class SearchManifest {
    private $m_sTableName;
    private $m_columns = array();
    private $m_sModule;
    private $m_sController;
    private $m_sAction;
    
    public function __construct($sModule = '', $sController = '', $sAction = '', $sTableName = '', $columns = array()) {
         $this->m_sTableName = $sTableName;
         $this->m_columns = $columns;
         $this->m_sModule = $sModule;
         $this->m_sController = $sController;
         $this->m_sAction = $sAction;
    }
    
    public function search($sText) {
        return array();
    }
}

?>
