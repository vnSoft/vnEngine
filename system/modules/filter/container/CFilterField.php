<?php defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');


class CFilterField {
    private $m_sFieldName;
    private $m_sFilterComparator;
    private $m_value;
    public function __construct($sFieldName, $sFilterComparator, $value = '') {
        $this->m_sFieldName = $sFieldName;
        $this->m_sFilterComparator = $sFilterComparator;
        $this->m_value = $value;
    }

    public function getFieldName() {
        return $this->m_sFieldName;
    }
    public function getComparator() {
        return $this->m_sFilterComparator;
    }
    public function getValue() {
        return $this->m_value;
    }

    public function setFieldName($sFieldName) {
        $this->m_sFieldName = $sFieldName;
    }
    public function setComparator($sFilterComparator) {
        $this->m_sFilterComparator = $sFilterComparator;
    }
    public function setValue($value) {
        $this->m_value = $value;
    }
    
    public function compareTo(CFilterField $other){
        return $this->m_sFieldName === $other->getFieldName() 
                AND $this->m_sFilterComparator === $other->getComparator()
                AND $this->m_value === $other->getValue();
    }
}
?>
