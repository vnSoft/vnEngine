<?php defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class CSorterField {
    private $m_sFieldName;
    private $m_sSortType;

    public function __construct($sFieldName, $sSortType = "ASC") {
        $this->m_sFieldName = $sFieldName;

        if($sSortType == "ASC" OR $sSortType == "DESC")
            $this->m_sSortType = $sSortType;
        else
            $this->m_sSortType = "ASC";
    }

    public function getName() {
        return $this->m_sFieldName;
    }

    public function getType() {
        return $this->m_sSortType;
    }

    public function setName($sFieldName) {
        $this->m_sFieldName = $sFieldName;
    }

    public function setType($sSortType) {
        if($sSortType == "ASC" OR $sSortType == "DESC")
            $this->m_sSortType = $sSortType;
        else
            $this->m_sSortType = "ASC";
    }
}
?>
