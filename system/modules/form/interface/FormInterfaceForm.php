<?php

/**
 * @author Bartek
 * @version 1.0
 * @created 11-mar-2012 18:12:05
 */

class FormInterfaceForm {

    private $m_forms = array();
    private $m_fields = array();
    private $m_rules = array();
    private $m_currentForm;
    private $m_iCurrentFormID;

    public function createForm($sFormName, $sAction, $sSendMethod = 'POST', $iFormType = NORMAL_FORM) {

        $form = new FormModelForm($sFormName, $sAction, $sSendMethod, $iFormType);
        $this->m_forms[$sFormName] = $form;
        $this->m_currentForm = $form;
        $this->m_iCurrentFormID = md5($sFormName);
    }
    public function isEmpty(){
        return empty($this->m_currentForm);
    }
    public function restoreForm($sFormName, Request $request) {
        $iFormID = md5($sFormName);
        if (Session::$session->isVarSet($iFormID) AND $request->getParam('isSent-' . $iFormID) === 'true') {
            //Odzyskanie struktury z sesji
            $form = unserialize(Session::$session->get($iFormID));
            //Usunięcie odzyskanej struktury z sesji
            Session::$session->delete($iFormID);

            $form->retrieveFieldsValues($request);
            if ($request->getParam('isSent-' . $iFormID) === 'true') {
                $form->setAsSent();
                $form->validate();
            }
            
            $this->m_forms[$sFormName] = $form;
            $this->m_currentForm = $form;
            $this->m_iCurrentFormID = $iFormID;
        }
        
    }

    public function setCurrentForm($sFormName) {
        $this->m_currentForm = $this->m_forms[$sFormName];
        $this->m_iCurrentFormID = md5($sFormName);
    }

    public function getCurrentFormName() {
        return $this->m_currentForm->getName();
    }

    public function getValues() {
        return $this->m_currentForm->getValues();
    }

    public function getValue($sFieldName) {
        return $this->m_currentForm->getValue($sFieldName);
    }

    public function isSent() {
        return $this->m_currentForm->isSent();
    }

    public function isValid() {
        return $this->m_currentForm->isValid();
    }

    public function validate() {
        $this->m_currentForm->validate();
    }

    public function setCustomRender($bCustomRender) {
        $this->m_currentForm->setCustomRender($bCustomRender);
    }

    public function addField($sFieldName) {
        $this->m_currentForm->addField($this->m_fields[$sFieldName . '-' . $this->m_iCurrentFormID]);
    }

    public function setTarget($sTarget) {
        $this->m_currentForm->setTarget($sTarget);
    }

    public function setFormID($sID) {
        $this->m_currentForm->setID($sID);
    }

    public function setFormAction($sAction) {
        $this->m_currentForm->setAction($sAction);
    }

    public function setFormClass($sClass) {
        $this->m_currentForm->setClass($sClass);
    }

    public function setFormParameter($key, $value) {
        $this->m_currentForm->setParameter($key, $value);
    }

    public function beginFormRender() {
        $this->m_currentForm->beginRender();
    }

    public function renderField($sFieldName) {
        $this->m_currentForm->renderField($sFieldName);
    }
    
    public function renderValue($sFieldName, $value, $sParameterKey = '', $parameterValue = '') {
        $this->m_currentForm->renderValue($sFieldName, $value, $sParameterKey, $parameterValue);
    }

    public function finishFormRender() {
        $this->m_currentForm->finishRender();
    }

    public function retrieveFieldsValues(Request $request) {

        $this->m_currentForm->retrieveFieldsValues($request);
    }

    public function getWrongFieldNames() {
        return $this->m_currentForm->getWrongFieldNames();
    }

    public function createField($sName, $iType, $value = '') {
        $field = new FieldModelForm($sName, $iType, $value);
        $this->m_fields[$sName . '-' . $this->m_iCurrentFormID] = $field;
    }

    public function setFieldParameter($sFieldName, $sKey, $sValue) {
        $this->m_fields[$sFieldName . '-' . $this->m_iCurrentFormID]->setParameter($sKey, $sValue);
    }

    public function modifyFieldParameter($sFieldName, $sKey, $sValue){
        $field = $this->m_currentForm->getField($sFieldName);
        $field->setParameter($sKey, $sValue);
    }
    
    public function setFieldLabel($sFieldName, $sLabel) {
        $this->m_fields[$sFieldName . '-' . $this->m_iCurrentFormID]->setLabel($sLabel);
    }
    public function getFieldLabel($sFieldName) {
        return $this->m_currentForm->getField($sFieldName)->getLabel();
    }
    public function getDescFieldNames(){
        return $this->m_currentForm->getDescFieldNames();
    }
    public function setFieldID($sFieldName, $sID) {
        $this->m_fields[$sFieldName . '-' . $this->m_iCurrentFormID]->setID($sID);
    }

    public function setFieldClass($sFieldName, $sClass) {
        $this->m_fields[$sFieldName . '-' . $this->m_iCurrentFormID]->setClass($sClass);
    }

    public function setFieldValue($sFieldName, $value) {
        $this->m_fields[$sFieldName . '-' . $this->m_iCurrentFormID]->setValue($value);
    }

    public function setFieldOption($sFieldName, $sKey, $sValue) {
        $this->m_fields[$sFieldName . '-' . $this->m_iCurrentFormID]->setOption($sKey, $sValue);
    }

    public function addFieldRule($sFieldName, $sRuleName) {
        $this->m_fields[$sFieldName . '-' . $this->m_iCurrentFormID]->addRule(clone $this->m_rules[$sRuleName . '-' . $this->m_iCurrentFormID]);
    }

    public function getFieldErrorMessages($sFieldName) {
        if ($this->isSent()){
            return $this->m_currentForm->getField($sFieldName)->getErrorMessages();
        }
            
        else
            return array();
    }
    
    public function getErrorMessages() {
        $errorList = array();
        foreach($this->getWrongFieldNames() as $sFieldName)
            $errorList [$sFieldName] = $this->getFieldErrorMessages ($sFieldName);
        return $errorList;
    }

    public function createRule($sName, $iType) {
        $rule = new RuleModelForm($sName, $iType);
        $this->m_rules[$sName . '-' . $this->m_iCurrentFormID] = $rule;
    }

    public function setRuleLength($sRuleName, $iMinLength, $iMaxLength) {
        $this->m_rules[$sRuleName . '-' . $this->m_iCurrentFormID]->setLength($iMinLength, $iMaxLength);
    }

    public function setRuleDataType($sRuleName, $iDataType) {
        $this->m_rules[$sRuleName . '-' . $this->m_iCurrentFormID]->setDataType($iDataType);
    }

    public function setRuleDataFormat($sRuleName, $sDataFormatExp) {
        $this->m_rules[$sRuleName . '-' . $this->m_iCurrentFormID]->setDataFormat($sDataFormatExp);
    }

    public function setRuleExistanceData($sRuleName, $sTable, $sAttribute) {
        $this->m_rules[$sRuleName . '-' . $this->m_iCurrentFormID]->setExistanceData($sTable, $sAttribute);
    }

    public function setRuleFieldToCompare($sRuleName, $sFieldName) {
        $this->m_rules[$sRuleName . '-' . $this->m_iCurrentFormID]->setFieldToCompare($this->m_fields[$sFieldName . '-' . $this->m_iCurrentFormID]);
    }

}

?>