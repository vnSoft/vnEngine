<?php defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class CSorter {
    private $m_sName;
    private $m_fields;

    public function __construct($sName, $fields = array()) {
        $this->m_sName = $sName;
        $this->m_fields = $fields;
    }

    function getName() {
        return $this->m_sName;
    }

    public function getFields() {
        return $this->m_fields;
    }

    public function getField($sFieldName) {
        $field = null;
        foreach($this->m_fields as &$f) {
            if($f->getName() == $sFieldName) {
                $field = $f;
                break;
            }
        }

        return $field;
    }

    public function setName($sName) {
        $this->m_sName = $sName;
    }

    public function addField(CSorterField $field) {
        $this->m_fields []= $field;
    }

    public function deleteField($sFieldName) {
        foreach($this->m_fields as &$field) {
            if($field->getName() == $sFieldName)
                unset($field);
        }
    }
    
    public function getStringID() {
        $sID = $this->m_sName;
        foreach($this->m_fields as $field)
            $sID .= $field->toString();
        return $sID;
    }

    public function clearFields() {
        $this->m_fields = array();
    }
}
?>
