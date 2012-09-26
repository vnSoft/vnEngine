<?php

/**
 * @author Bartek
 * @version 1.0
 * @created 11-mar-2012 18:12:05
 */
class FormModelForm extends Model {

    private $m_sName;
    private $m_sAction;
    private $m_sMethod;
    private $m_sTarget = '_self';
    private $m_sID;
    private $m_sClass;
    private $m_iFormType;
    private $m_parameters = array();
    private $m_fields = array();
    private $m_bIsValid = false;
    private $m_bCustomRender = false;
    private $m_bIsSent = false;

    public function __construct($sName, $sAction, $sMethod = 'POST', $iFormType = NORMAL_FORM) {
        if ($sMethod !== 'POST' AND $sMethod !== 'GET')
            trigger_error("Wrong form send method!", E_ERROR);
        else if ($iFormType !== NORMAL_FORM AND $iFormType !== FILE_FORM)
            trigger_error("Wrong form type!", E_ERROR);

        $this->m_sName = $sName;
        $this->m_sAction = $sAction;
        $this->m_sMethod = $sMethod;
        $this->m_iFormType = $iFormType;
    }

    public function getName() {
        return $this->m_sName;
    }

    public function getValues() {
        $fieldValues = array();
        foreach ($this->m_fields as $field) {
            $fieldValues[$field->getName()] = $field->getValue();
        }

        return $fieldValues;
    }
    
    public function getValue($sFieldName) {
        $value = '';
        if (!empty($this->m_fields[$sFieldName]))
            $value = $this->m_fields[$sFieldName]->getValue();

        return $value;
    }

    public function isValid() {
        return $this->m_bIsValid;
    }
    public function getDescFieldNames(){
        $names = array();
        
        foreach($this->m_fields as $field)
            if($field->getLabel()!= null)
                $names[] = $field->getName();
        
        return $names;
    }
    public function validate() {

        $this->m_bIsValid = true;
        foreach ($this->m_fields as &$field) {
            $field->executeRules();
            if (!$field->isValid())
                $this->m_bIsValid = false;
        }
    }

    public function getWrongFieldNames() {
        $fieldNames = array();
        
        foreach ($this->m_fields as &$field)
            if (!$field->isValid())
                $fieldNames[] = $field->getName();

        return $fieldNames;
    }

    public function setCustomRender($bCustomRender) {
        $this->m_bCustomRender = $bCustomRender;
    }

    public function setValue($sFieldName, $value) {
        $this->m_fields[$sFieldName]->setValue($value);
    }

    public function addField(&$field) {
        $field->setCustomRender($this->m_bCustomRender);
        $field->setFormName($this->m_sName);
        $this->m_fields[$field->getName()] = &$field;
    }

    public function getField($sFieldName) {
        return $this->m_fields[$sFieldName];
    }

    public function setTarget($sTarget) {
        $this->m_sTarget = $sTarget;
    }

    public function setID($sID) {
        $this->m_sID = $sID;
    }

    public function setAction($sAction) {
        $this->m_sAction = $sAction;
    }

    public function setClass($sClass) {
        $this->m_sClass = $sClass;
    }

    public function setParameter($key, $value) {
        $this->m_parameters[$key] = $value;
    }

    public function setAsSent() {
        $this->m_bIsSent = true;
    }

    public function isSent() {
        return $this->m_bIsSent;
    }

    public function beginRender() {
        $sTag = "<form name='{$this->m_sName}' action='{$this->m_sAction}' target='{$this->m_sTarget}' method='{$this->m_sMethod}' class='{$this->m_sClass}' id='{$this->m_sID}' ";

        if ($this->m_iFormType == FILE_FORM)
            $sTag .= " enctype='multipart/form-data' ";

        foreach ($this->m_parameters as $key => $value)
            $sTag .= "$key=\"$value\" ";


        $sTag .= ">";

        echo $sTag;
    }

    public function renderField($sName) {
        $this->m_fields[$sName]->render();
    }

    public function finishRender() {
        $iFormID = md5($this->m_sName);
        $sHidden = "<input type='hidden' name='isSent-$iFormID' value='true' />";
        echo "$sHidden </form>";
        $this->save();
    }

    public function retrieveFieldsValues(Request $request) {
        $this->m_bIsValid = false;
        $this->m_bIsSent = false;
        $iFormID = md5($this->m_sName);
        foreach ($this->m_fields as $field) {
            $field->unsetValue();
            
            if($field->getType() == FILE_FIELD)
                $this->processUploadedFile($field);
            else {
                $value = $request->getParam($field->getName() . "-$iFormID");
                if (!empty($value) OR $value === '0')
                    $field->setValue($value);
            }
        }
    }

    private function save() {
        $iFormID = md5($this->m_sName);
        Session::$session->set($iFormID, serialize($this));
    }
    
    private function processUploadedFile(FieldModelForm &$field) {
        if(isset($_FILES[$field->getName()])) {
            $sTmpName = $_FILES[$field->getName()]['tmp_name'];
            $sMime = $_FILES[$field->getName()]['type'];
            $iSize = $_FILES[$field->getName()]['size'];
            $iError = $_FILES[$field->getName()]['error'];
            $sExtension = '';
            $mimes = Form::config('mimes');
            if(isset($mimes[$sMime]))
                $sExtension = $mimes[$sMime];
            
            if($iError == 0) {
                $sNewName = md5(date("Y-m-d H:i:s")).".tmp";
                if(move_uploaded_file($sTmpName, APPROOT.'media/tmp/'.$sNewName)) 
                    $file = new CFormFile($sTmpName, $sExtension, $sMime, $iSize, true);
                else
                    $file = new CFormFile($sTmpName, $sExtension, $sMime, $iSize, false);
            }
            else
                $file = new CFormFile($sTmpName, $sExtension, $sMime, $iSize, false);
            $field->setValue($file);
        }
        
    }

}

?>