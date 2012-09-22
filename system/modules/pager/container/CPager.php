<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class CPager {

    private $m_sName;
    private $m_iSize;
    private $m_iPageNumber;
    private $m_iAllRecordsNum = -1;
    private $m_previousFilter = null;

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

    function calcPagesRemainNum($sModelName, CFilter $filter = null) {
        $iPagesRemain = 0;

        $iRecordsNum = $this->getAllRecordsNum($sModelName, $filter);

        $iItemsRemain = $iRecordsNum - $this->m_iSize * $this->m_iPageNumber;
        $fPartResult = $iItemsRemain / $this->m_iSize;

        $iPagesRemain = (int) $fPartResult;

        if ($iPagesRemain != $fPartResult AND $fPartResult > 0)
            $iPagesRemain++;

        return $iPagesRemain;
    }

    function isOutOfBound($sModelName, CFilter $filter = null) {
        $bOut = true;

        if ($this->m_iPageNumber < 1)
            $bOut = true;
        else {
            $iRecordsNum = $this->getAllRecordsNum($sModelName, $filter);

            $iItemsRemain = $iRecordsNum - $this->m_iSize * $this->m_iPageNumber;
            $fPartResult = $iItemsRemain / $this->m_iSize;

            $bOut = $fPartResult <= -1;
        }
        return $bOut;
    }

    private function getAllRecordsNum($sModelName, CFilter $filter = null) {
        if ($this->m_iAllRecordsNum == -1 OR !$this->m_previousFilter->compareTo($filter)) {
            $iRecordsNum = 0;
            
            $iRecordsNum = Pager::$pager->getAllRecordsNum($sModelName, $filter);

            $this->m_iAllRecordsNum = $iRecordsNum;
            $this->m_previousFilter = $filter;
        }
        
        return $this->m_iAllRecordsNum;
    }

}

?>
