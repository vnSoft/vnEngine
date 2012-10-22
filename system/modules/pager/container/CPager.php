<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class CPager {

    private $m_sName;
    private $m_iSize;
    private $m_iPageNumber;
    private $m_iPageCount = 1;

    function __construct($sName, $iSize = 0, $iPageNumber = 0) {
        $this->m_sName = $sName;
        $this->m_iSize = $iSize;
        $this->m_iPageNumber = $iPageNumber;
    }

    function getName() {
        return $this->m_sName;
    }

    function setName($sName) {
        $this->m_sName = $sName;
    }

    function getSize() {
        return $this->m_iSize;
    }

    function setSize($iSize) {
        $this->m_iSize = $iSize;
    }

    function getPageNumber() {
        return $this->m_iPageNumber;
    }

    function setPageNumber($iPageNumber) {
        $this->m_iPageNumber = $iPageNumber;
    }

    public function getPageCount() {
        return $this->m_iPageCount;
    }

    public function setPageCount($iPageCount) {
        $this->m_iPageCount = $iPageCount;
    }
    
    public function processResultCount($iResultCount) {
        if($iResultCount <= $this->m_iSize)
            $this->m_iPageCount = 1;
        else 
            $this->m_iPageCount = ceil($iResultCount / $this->m_iSize);
    }

        
    public function getStringID() {
        $sID = $this->m_sName."-".$this->m_iSize."-".$this->m_iPageNumber;
        return $sID;
    }

}

?>
