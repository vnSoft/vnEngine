<?php defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class CFilter {
    private $m_sName;
    private $m_fields;

    public function __construct($sName, $fields = array()) {
        $this->m_sName = $sName;
        $this->m_fields = $fields;
    }

    public function getName() {
        return $this->m_sName;
    }

    public function getFields() {
        return $this->m_fields;
    }

    public function getField($sFieldName) {
        $field = null;
        foreach($this->m_fields as &$f) {
            if($f->getFieldName() == $sFieldName) {
                $field = $f;
                break;
            }
        }

        return $field;
    }

    public function setName($sName) {
        $this->m_sName = $sName;
    }

    public function addField(CFilterField $field) {
        $this->m_fields []= $field;
    }

    public function deleteField($sFieldName) {
        foreach($this->m_fields as $key => $field) {
            if($field->getFieldName() == $sFieldName)
                unset($this->m_fields[$key]);
        }
    }

    public function clearFields() {
        $this->m_fields = array();
    }
    
    public function getStringID() {
        $sID = $this->m_sName;
        foreach($this->m_fields as $field)
            $sID .= $field->toString();
        return $sID;
    }
    
    public function compareTo(CFilter $other){
        $bSame = false;
        
        $fields = $this->m_fields;
        $otherFields = $other->getFields();
        $iFieldsNum = count($fields);
        
        $bFlag = $this->m_sName === $other->getName() AND $iFieldsNum === count($otherFields);
        
        $iCounter = 0;
        while($bFlag AND $iCounter <$iFieldsNum){
            $field = $fields[$iCounter];
            $otherField = $otherFields[$iCounter];
            $bFlag = $field.compareTo($otherField);
        }
        
        $bSame = $bFlag;
        
        return $bSame;
    }

}
?>
