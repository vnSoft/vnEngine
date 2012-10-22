<?php

/**
 * @author Bartek
 * @version 1.0
 * @created 11-mar-2012 18:12:05
 */
class RuleModelForm extends Model {

    private $m_sName;
    private $m_bPassed = false;
    private $m_value;
    private $m_iType;
    //LENGTH_RULE
    private $m_iLenMin;
    private $m_iLenMax;
    //DATA_TYPE_RULE
    private $m_iDataType;
    //DATA_FORMAT_RULE
    private $m_sDataFormatExp;
    //EXISTANCE_RULE
    private $m_sTable;
    private $m_sAttribute;
    //EQUALITY_RULE
    private $m_fieldToCompare;
    private $m_sErrorMessage;

    /**
     *
     * @param sName
     * @param iType
     */
    public function __construct($sName, $iType) {
        if ($iType < MIN_RULE_TYPE OR $iType > MAX_RULE_TYPE)
            trigger_error("Wrong rule type!", E_ERROR);

        $this->m_sName = $sName;
        $this->m_iType = $iType;

        $this->setErrorMessage();
    }

    /**
     *
     * @param value
     */
    public function setValue($value) {
        $this->m_bPassed = false;
        $this->m_value = $value;
    }

    public function getName() {
        return $this->m_sName;
    }

    public function execute() {
        switch ($this->m_iType) {
            case LENGTH_RULE:
                $this->m_bPassed = $this->checkLength();
                break;
            case DATA_TYPE_RULE:
                $this->m_bPassed = $this->checkDataType();
                break;
            case DATA_FORMAT_RULE:
                break;
            case EXISTANCE_RULE:
                break;
            case EQUALITY_RULE:
                $this->m_bPassed = ($this->m_fieldToCompare->getValue() == $this->m_value);
                break;
            default:
                $this->m_bPassed = false;
                break;
        }
    }

    public function passed() {
        return $this->m_bPassed;
    }

    public function setLength($iLenMin, $iLenMax) {
        $this->m_iLenMin = $iLenMin;
        $this->m_iLenMax = $iLenMax;
    }

    public function setDataType($iDataType) {
        if ($iDataType < MIN_DATA_TYPE OR $iDataType > MAX_DATA_TYPE)
            trigger_error("Wrong data type!", E_ERROR);

        $this->m_iDataType = $iDataType;
    }

    public function setDataFormat($sDataFormatExp) {
        $this->m_sDataFormatExp = $sDataFormatExp;
    }

    public function setExistanceData($sTable, $sAttribute) {
        $this->m_sTable = $sTable;
        $this->m_sAttribute = $sAttribute;
    }

    public function setFieldToCompare(&$fieldToCompare) {
        $this->m_fieldToCompare = &$fieldToCompare;
    }

    private function setErrorMessage() {
        switch ($this->m_iType) {
            case LENGTH_RULE:
                $this->m_sErrorMessage = Lang::$formWrongLength;
                break;
            case DATA_TYPE_RULE:
                $this->m_sErrorMessage = Lang::$formWrongDataType;
                break;
            case DATA_FORMAT_RULE:
                $this->m_sErrorMessage = Lang::$formWrongDataFormat;
                break;
            case EXISTANCE_RULE:
                $this->m_sErrorMessage = Lang::$formValueExists;
                break;
            case EQUALITY_RULE:
                $this->m_sErrorMessage = Lang::$formFieldValuesNotEqual;
                break;
        }
    }

    public function getErrorMessage() {
        return $this->m_sErrorMessage;
    }




    private function checkLength() {
        $bPassed = false;
        if(strlen("{$this->m_value}") >= $this->m_iLenMin AND strlen("{$this->m_value}") <= $this->m_iLenMax)
            $bPassed = true;

        return $bPassed;
    }

    private function checkDataType() {
        $bPassed = false;
        switch($this->m_iDataType) {
            case DIGIT_DATA_TYPE:
                $bPassed = ctype_digit("{$this->m_value}");
                break;
            case ALNUM_DATA_TYPE:
                $bPassed = (ctype_digit("{$this->m_value}") OR $this->isValueAlnum());
                break;
            case ALPHA_DATA_TYPE:
                //$bPassed = preg_match("/[p{L}]+/", $this->m_value);
                $bPassed = preg_match('#^[a-z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ ]*$#is', $this->m_value);
                break;
            case EMAIL_DATA_TYPE:
                $bPassed = preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,3}$/i", $this->m_value);
                break;
            case SAFE_HTML_DATA_TYPE:
                $bPassed = $this->isValueSafeHtml();
                break;
            case TEXT_DATA_TYPE:
                $bPassed = ($this->m_value !== null);
                break;
            case DATE_DATA_TYPE:
                $bPassed = preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $this->m_value);
                break;
            default:
                $bPassed = false;
                break;
        }

        return $bPassed;
    }

    private function isValueAlnum() {
        return preg_match('#^[a-z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ ]*$#is', $this->m_value);
    }

    private function isValueSafeHtml() {
        return true;
    }

}

?>