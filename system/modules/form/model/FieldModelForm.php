<?php

/**
 * @author Bartek
 * @version 1.0
 * @created 11-mar-2012 18:12:05
 */
class FieldModelForm extends Model {

    private $m_sFormName;
    private $m_sName;
    private $m_sLabel;
    private $m_iType;
    private $m_value;
    private $m_sClass = '';
    private $m_sID = '';
    private $m_parameters = array();
    private $m_options = array();
    private $m_rules = array();
    private $m_bIsValid = false;
    private $m_bCustomRender = false;

    public function __construct($sName, $iType, $value = '') {
        if ($iType < MIN_FIELD_TYPE OR $iType > MAX_FIELD_TYPE)
            trigger_error("Wrong field type!", E_ERROR);

        $this->m_sName = $sName;
        $this->m_iType = $iType;
        $this->m_value = $value;
    }
    public function setLabel($sLabel){
        $this->m_sLabel = $sLabel;
    }
    public function setClass($sClass) {
        $this->m_sClass = $sClass;
    }

    public function setID($sID) {
        $this->m_sID = $sID;
    }

    public function setParameter($sKey, $value) {
        $this->m_parameters[$sKey] = $value;
    }

    public function setFormName($sFormName) {
        $this->m_sFormName = $sFormName;
    }

    public function setValue($value) {
        $this->m_bIsValid = false;
        $this->m_value = $value;
    }

    public function unsetValue() {
        $this->m_bIsValid = false;
        $this->m_value = '';
    }
    public function getLabel(){
        return $this->m_sLabel;
    }
    public function getValue() {
        return $this->m_value;
    }
    
    public function getType() {
        return $this->m_iType;
    }

    public function getName() {
        return $this->m_sName;
    }

    public function setOption($key, $value) {
        $this->m_options[$key] = $value;
    }

    public function addRule($rule) {
        $rule->setValue($this->m_value);
        $this->m_rules[$rule->getName()] = $rule;
    }

    public function executeRules() {
        $this->m_bIsValid = true;
        foreach ($this->m_rules as &$rule) {
            $rule->setValue($this->m_value);
            $rule->execute();
            if (!$rule->passed()) 
                $this->m_bIsValid = false;
                 
        }
        $this->m_bExecuted = true;

    }

    public function isValid() {
        return $this->m_bIsValid;
    }

    public function render() {

        $parameters = '';
        foreach ($this->m_parameters as $sKey => $sValue)
            $parameters .= " $sKey=\"$sValue\"";

        $iFormID = md5($this->m_sFormName);
        $sName = $this->m_sName . "-$iFormID";
        switch ($this->m_iType) {
            case TEXT_FIELD:
                $content = "<input value='{$this->m_value}' name='$sName' type='text' class='{$this->m_sClass}' id='{$this->m_sID}' $parameters />";
                break;
            case PASSWORD_FIELD:
                $content = "<input value='{$this->m_value}' name='$sName' type='password' class='{$this->m_sClass}' id='{$this->m_sID}' $parameters />";
                break;
            case CHECKBOX_FIELD:
                $content = "<input value='{$this->m_value}' name='{$sName}' type='checkbox' class='{$this->m_sClass}' id='{$this->m_sID}' $parameters />";
                break;
            case CHECKBOX_ARRAY_FIELD:
                $content = "<input value='{$this->m_value}' name='{$sName}[]' type='checkbox' class='{$this->m_sClass}' id='{$this->m_sID}' $parameters />";
                break;
            case RADIOBUTTON_FIELD:
                $content = "<input value='{$this->m_value}' name='$sName' type='radio' class='{$this->m_sClass}' id='{$this->m_sID}' $parameters />";
                break;
            case FILE_FIELD:
                $content = "<input name='{$this->m_sName}' type='file' class='{$this->m_sClass}' id='{$this->m_sID}' $parameters />";
                break;
            case HIDDEN_FIELD:
                $content = "<input value='{$this->m_value}' name='$sName' type='hidden' class='{$this->m_sClass}' id='{$this->m_sID}' $parameters />";
                break;
            case BUTTON_FIELD:
                $content = "<input value='{$this->m_value}' name='$sName' type='button' class='{$this->m_sClass}' id='{$this->m_sID}' $parameters />";
                break;
            case SUBMIT_FIELD:
                $content = "<input value='{$this->m_value}' name='$sName' type='submit' class='{$this->m_sClass}' id='{$this->m_sID}' $parameters />";
                break;
            case TEXTAREA_FIELD:
                $content = "<textarea name='$sName' class='{$this->m_sClass}' id='{$this->m_sID}' $parameters >{$this->m_value}</textarea>";
                break;
            case SELECT_FIELD:
                $content = "<select name='$sName' class='{$this->m_sClass}' id='{$this->m_sID}' $parameters >";
                foreach ($this->m_options as $key => $val) {
                    if ($this->m_value == $val)
                        $content .= "<option selected='selected' value='$val'>$key</option>";
                    else
                        $content .= "<option value='$val'>$key</option>";
                }
                $content .= "</select>";
        }
        echo $content;
    }

    public function setCustomRender($bCustomRender) {
        $this->m_bCustomRender = $bCustomRender;
    }

    public function getErrorMessages() {
        $result = array();
        
        if(!$this->m_bIsValid) {
            
            foreach ($this->m_rules as &$rule) {
                if(!$rule->passed()) 
                    $result [] = $rule->getErrorMessage();
            }
        }

        return $result;
    }

}

?>