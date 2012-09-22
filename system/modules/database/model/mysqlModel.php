<?php

class MySQLModelDatabase extends Model {

    private $m_handle;
    private $m_bConnected;
    private $m_sQuery;
    private $m_sOrder;
    private $m_sLimit;
    private $m_sWhere;
    private $m_sGroupBy;
    private $m_filter;
    private $m_sorter;
    private $m_pager;
    private $m_sLanguage;
    private $m_languageMap;

    /**
     *
     * @param sHost
     * @param sLogin
     * @param sPassword
     */
    public function connect($sHost, $sLogin, $sPassword) {
        $this->m_handle = mysql_connect($sHost, $sLogin, $sPassword);
        if ($this->m_handle !== false)
            $this->m_bConnected = true;
        else
            $this->m_bConnected = false;
    }

    public function custom($sQuery) {
        $this->m_sQuery = $sQuery;
    }

    /**
     *
     * @param sName
     */
    public function selectDB($sName) {
        if ($this->m_bConnected) {
            if (!mysql_select_db($sName, $this->m_handle))
                throw new DatabaseException("Wrong database name!");
        } else
            throw new DatabaseException("Not connected to database!");
    }

    /**
     *
     * @param sCharset
     */
    public function setCharset($sCharset) {
        if ($this->m_bConnected) {
            if (!mysql_set_charset($sCharset, $this->m_handle))
                throw new DatabaseException("Wrong charset!");
        }
        else
            throw new DatabaseException("Not connected to database!");
    }
    
    public function setLanguageMap($languageMap) {
        $this->m_languageMap = $languageMap;
    }

    public function setLanguage($sLanguage) {
        foreach($this->m_languageMap['languages'] as $sLang) {
            if(strtolower($sLang) == strtolower($sLanguage)) {
                $this->m_sLanguage = strtolower($sLanguage);
                break;
            }
        }
    }

    /**
     *
     * @param sValue
     */
    public function escape($sValue) {
        $sSafeValue = '';

        if ($this->m_bConnected)
            $sSafeValue = mysql_real_escape_string($sValue, $this->m_handle);
        else
            throw new DatabaseException("Not connected to database!");

        return $sSafeValue;
    }
    
    public function setFilter(CFilter $filter) {
        $this->m_filter = $filter;
    }

    public function setSorter(CSorter $sorter) {
        $this->m_sorter = $sorter;
    }
    public function setPager(CPager $pager){
        $this->m_pager = $pager;
    }

    public function execQuery() {
        $result = null;
        if($this->m_pager !== null AND $this->m_pager instanceof CPager)
            $this->processPager();
        if($this->m_filter !== null AND $this->m_filter instanceof CFilter)
            $this->processFilter();
        if($this->m_sorter !== null AND $this->m_sorter instanceof CSorter)
            $this->processSorter();

        if ($this->m_bConnected) {
            $result = mysql_query($this->m_sQuery . $this->m_sWhere . $this->m_sGroupBy . $this->m_sOrder . $this->m_sLimit) OR trigger_error("<br/>Database query failed: ".$this->m_sQuery . $this->m_sWhere . $this->m_sOrder . $this->m_sGroupBy . $this->m_sLimit."<br/>");
            if ($result === false)
                throw new DatabaseException("Database query failed!", $this->m_sQuery . $this->m_sWhere . $this->m_sGroupBy . $this->m_sOrder . $this->m_sLimit);
        } else
            throw new DatabaseException("Not connected to database!");
        
        $this->m_sQuery = '';
        $this->m_sWhere = '';
        $this->m_sOrder = '';
        $this->m_sGroupBy = '';
        $this->m_sLimit = '';
        $this->m_filter = null;
        $this->m_sorter = null;
        $this->m_pager = null;
        return $result;
    }

    /**
     *
     * @param sTableName
     */
    public function get($sTableName) {
        $this->m_sQuery = "SELECT * FROM $sTableName ";
    }

    public function getDistinct($sTableName) {
        $this->m_sQuery = "SELECT DISTINCT * FROM $sTableName ";
    }

    /**
     *
     * @param sTableName
     * @param fieldNames
     */
    public function select($sTableName, $fieldNames = array()) {
        if ($fieldNames == array()) {
            get($sTableName);
        } else {
            $this->m_sQuery = "SELECT ";

            foreach ($fieldNames as $field) {
//                if(!empty($this->m_sLanguage)) {
//                    if(!empty($this->m_languageMap['tables'][$sTableName]) AND in_array($field, $this->m_languageMap['tables'][$sTableName]))
//                        $field = $field."_".$this->m_sLanguage;
//                }

                $this->m_sQuery .= " $field, ";
            }

            $this->m_sQuery = substr($this->m_sQuery, 0, -2);
            $this->m_sQuery .= " FROM $sTableName ";
        }
    }

    /**
     * fieldNames is an associative array
     * array("field1" => "COUNT", "field2" = "AVERAGE", 'field3' => "")
     *
     * @param sTableName
     * @param fieldNames
     */
    public function selectAggregate($sTableName, $fieldNames = array()) {
        if ($fieldNames == array()) {
            get($sTableName);
        } else {
            $this->m_sQuery = "SELECT ";

            foreach ($fieldNames as $field => $agg) {

//                if(!empty($this->m_sLanguage)) {
//                    if(!empty($this->m_languageMap['tables'][$sTableName]) AND in_array($field, $this->m_languageMap['tables'][$sTableName]))
//                        $field = $field."_".$this->m_sLanguage;
//                }

                if (empty($agg)) {
                    $this->m_sQuery .= " $field, ";
                } else if ($this->isAggregatorType($agg))
                    $this->m_sQuery .= " $agg($field), ";
                else
                    throw new DatabaseException("Wrong aggregate type!");
            }

            $this->m_sQuery = substr($this->m_sQuery, 0, -2);
            $this->m_sQuery .= " FROM $sTableName ";
        }
    }

    /**
     *
     * @param sTableName
     * @param sFieldName
     */
    public function selectDistinct($sTableName, $fieldNames = array()) {
        if ($fieldNames == array()) {
            $this->getDistinct($sTableName);
        } else {
            $this->m_sQuery = "SELECT DISTINCT ";

            foreach ($fieldNames as $field) {
//                if(!empty($this->m_sLanguage)) {
//                    if(!empty($this->m_languageMap['tables'][$sTableName]) AND in_array($field, $this->m_languageMap['tables'][$sTableName]))
//                        $field = $field."_".$this->m_sLanguage;
//                }
                
                $this->m_sQuery .= " $field, ";
            }

            $this->m_sQuery = substr($this->m_sQuery, 0, -2);
            $this->m_sQuery .= " FROM $sTableName ";
        }
    }

    /**
     *
     * @param sTableName
     * @param valuesMap
     */
    public function insert($sTableName, $valuesMap = array()) {
        if ($valuesMap == array())
            throw new DatabaseException("Wrong insert values!");

        $this->m_sQuery = "INSERT INTO $sTableName ";

        $sFields = '';
        $sValues = '';

        foreach ($valuesMap as $sField => $value) {
            if ($valuesMap[$sField] === null OR isset($valuesMap[$sField])) {
                
//                if(!empty($this->m_sLanguage)) {
//                    if(!empty($this->m_languageMap['tables'][$sTableName]) AND in_array($sField, $this->m_languageMap['tables'][$sTableName]))
//                        $sField = $sField."_".$this->m_sLanguage;
//                }

                $sFields .= " $sField, ";
                $sValues .= ($value === null ? "NULL" : " '" . $this->escape($value) . "'").", ";
            } else
                throw new DatabaseException("Wrong insert value: $sField");
        }

        $sFields = substr($sFields, 0, -2);
        $sValues = substr($sValues, 0, -2);

        $this->m_sQuery .= " ($sFields) VALUES($sValues) ";
    }

    /**
     * decreases values by specified values
     *
     * @param sTableName
     * @param valuesMap
     */
    public function decrease($sTableName, $valuesMap = array()) {
        if ($valuesMap == array())
            throw new DatabaseException("Wrong decrease values!");

        $this->m_sQuery = "UPDATE $sTableName SET ";

        $sContent = '';

        foreach ($valuesMap as $sField => $value) {
            if (isset($valuesMap[$sField]) and ctype_digit("$value")) {
                
//                if(!empty($this->m_sLanguage)) {
//                    if(!empty($this->m_languageMap['tables'][$sTableName]) AND in_array($sField, $this->m_languageMap['tables'][$sTableName]))
//                        $sField = $sField."_".$this->m_sLanguage;
//                }
                $sContent .= " $sField = $sField - $value, ";
            } else
                throw new DatabaseException("Wrong increase values!");
        }

        $sContent = substr($sContent, 0, -2);

        $this->m_sQuery .= " $sContent ";
    }

    /**
     * increases values by specified values
     *
     * @param sTableName
     * @param valuesMap
     */
    public function increase($sTableName, $valuesMap = array()) {
        if ($valuesMap == array())
            throw new DatabaseException("Wrong increase values!");

        $this->m_sQuery = "UPDATE $sTableName SET ";

        $sContent = '';

        foreach ($valuesMap as $sField => $value) {
            if (isset($valuesMap[$sField]) and ctype_digit("$value")) {
//                if(!empty($this->m_sLanguage)) {
//                    if(!empty($this->m_languageMap['tables'][$sTableName]) AND in_array($sField, $this->m_languageMap['tables'][$sTableName]))
//                        $sField = $sField."_".$this->m_sLanguage;
//                }
                
                $sContent .= " $sField = $sField + $value, ";
            } else
                throw new DatabaseException("Wrong increase values!");
        }

        $sContent = substr($sContent, 0, -2);

        $this->m_sQuery .= " $sContent ";
    }

    /**
     * defines new values
     *
     * @param sTableName
     * @param valuesMap
     */
    public function update($sTableName, $valuesMap = array()) {
        if ($valuesMap == array())
            throw new DatabaseException("Wrong update values!");

        $this->m_sQuery = "UPDATE $sTableName SET ";

        $sContent = '';

        foreach ($valuesMap as $sField => $value) {
            if ($valuesMap[$sField] === null OR isset($valuesMap[$sField])) {
//                if(!empty($this->m_sLanguage)) {
//                    if(!empty($this->m_languageMap['tables'][$sTableName]) AND in_array($sField, $this->m_languageMap['tables'][$sTableName]))
//                        $sField = $sField."_".$this->m_sLanguage;
//                }
                
                $sContent .= " $sField = ".($value === null ? "NULL" : "'" . $this->escape($value) ."'"). ", ";
            } else
                throw new DatabaseException("Wrong update values!");
        }

        $sContent = substr($sContent, 0, -2);

        $this->m_sQuery .= " $sContent ";
    }

    /**
     *
     * @param sTableName
     */
    public function delete($sTableName) {
        $this->m_sQuery = "DELETE FROM $sTableName ";
    }

    /**
     * sAggregateType defines function aggregating value
     *
     * @param sFieldName
     * @param sComparator
     * @param Value
     * @param sAggregateType
     */
    public function andWhere($sFieldName, $sComparator, $value, $sAggregator = '') {

        if ($this->isComparatorType($sComparator)) {

            if($value === null)
                $safeCompare = " IS NULL ";
            else
                $safeCompare = " $sComparator '".$this->escape($value)."' ";


            if ($sAggregator == '')
                $this->m_sWhere .= " AND $sFieldName $safeCompare ";
            else if ($this->isAggregatorType($sAggregator)) {
                
                $this->m_sWhere .= " AND $sAggregator($sFieldName) $safeCompare ";
            } else
                throw new DatabaseException("Wrong aggregator type!");
        } else
            throw new DatabaseException("Wrong comparator type!");
    }

    /**
     * sAggregateType defines function aggregating value
     *
     * @param sFieldName
     * @param sComparator
     * @param Value
     * @param sAggregateType
     */
    public function orWhere($sFieldName, $sComparator, $value, $sAggregator) {

        if ($this->isComparatorType($sComparator)) {

            if($value === null)
                $safeCompare = " IS NULL ";
            else
                $safeCompare = " $sComparator '".$this->escape($value)."' ";

            if ($sAggregator == '')
                $this->m_sWhere .= " OR $sFieldName $safeCompare ";
            else if ($this->isAggregatorType($sAggregator)) {
                $this->m_sWhere .= " OR $sAggregator($sFieldName) $safeCompare ";
            } else
                throw new DatabaseException("Wrong aggregator type!");
        } else
            throw new DatabaseException("Wrong comparator type!");
    }

    /**
     * sAggregateType defines function aggregating value
     *
     * @param sFieldName
     * @param sComparator
     * @param value
     * @param sAggregateType
     */
    public function where($sFieldName, $sComparator, $value, $sAggregator = '') {

        if ($this->isComparatorType($sComparator)) {

            if($value === null)
                $safeCompare = " IS NULL ";
            else
                $safeCompare = " $sComparator '".$this->escape($value)."' ";

                
            if ($sAggregator == '')
                $this->m_sWhere .= " WHERE $sFieldName $safeCompare ";
            else if ($this->isAggregatorType($sAggregator)) {
                $this->m_sWhere .= " WHERE $sAggregator($sFieldName) $safeCompare ";
            } else
                throw new DatabaseException("Wrong aggregator type!");
        } else
            throw new DatabaseException("Wrong comparator type!");
    }

    /**
     * fieldNames is an associative array
     * array("field1" => "ASC", "field2" = "DESC")
     *
     * @param fieldNames
     */
    public function orderBy($fieldNames = array()) {
        if ($fieldNames == array())
            throw new DatabaseException("Wrong order values!");

        $this->m_sOrder = " ORDER BY ";

        foreach ($fieldNames as $field => $order) {
            if ($this->isOrderType($order)) {
                

                $this->m_sOrder .= " $field $order, ";
            }else
                throw new DatabaseException("Wrong order type!");
        }

        $this->m_sOrder = substr($this->m_sOrder, 0, -2);

        $this->m_sOrder .= " ";
    }

    /**
     *
     * @param iStart
     * @param iCount
     */
    public function limit($iStart, $iCount) {

        if (ctype_digit("$iStart") and ctype_digit("$iCount")) {
            $this->m_sLimit = " LIMIT $iStart, $iCount ";
        } else
            throw new DatabaseException("Wrong limit parameters!");
    }

    public function getLastID() {
        $iResult = -1;

        if ($this->m_bConnected)
            $iResult = mysql_insert_id($this->m_handle);
        else
            throw new DatabaseException("Not connected to database!");

        return $iResult;
    }

    /**
     *
     * @param sFieldName
     */
    public function groupBy($sFieldName) {
        $this->m_sGroupBy = " GROUP BY $sFieldName ";
    }

    private function processFilter() {
        $fields = $this->m_filter->getFields();
        
        if(count($fields) < 1)
            return;

        foreach($fields as $field) {
            $sComparator = $field->getComparator();
            
            if($this->isComparatorType($sComparator)) {
                $sFieldName = $field->getFieldName();


                if(strlen($this->m_sWhere) > 0)
                    $this->m_sWhere .= " AND ";
                else
                    $this->m_sWhere = " WHERE ";
                $this->m_sWhere .= $sFieldName." $sComparator '".$this->escape($field->getValue())."' ";
            } else
                throw new DatabaseException("Wrong comparator type!");
        }
    }

    private function processSorter() {
        $fields = $this->m_sorter->getFields();

        if(count($fields) < 1)
            return;

        foreach($fields as $field) {
            $sSortType = $field->getType();
            $sFieldName = $field->getName();

            if($this->isOrderType($sSortType)) {
                    if(strlen($this->m_sOrder) > 0)
                        $this->m_sOrder .= ", ";
                    else
                        $this->m_sOrder = " ORDER BY ";
                $this->m_sOrder .= $sFieldName." $sSortType ";
            } else
                throw new DatabaseException("Wrong order type!");
        }
    }
    
    private function processPager(){
        
        if($this->m_sLimit == ''){
            
            $iSize = $this->m_pager->getSize();
            if(ctype_digit("$iSize")){
                
                $iPageNumber = $this->m_pager->getPageNumber();
                if(ctype_digit("$iPageNumber")){
                    $iStartIndeks = ($iPageNumber-1)*$iSize;
                    $this->m_sLimit = "LIMIT $iStartIndeks, $iSize";
                    
                }else
                    throw new WrongDataException("Wrong pager page number type!");
                
            }else
                throw new WrongDataException("Wrong pager size type!");
        }
        
    }
    private function isAggregatorType($sAggregate) {
        $result = false;

        $sAggregate = strtoupper($sAggregate);

        switch ($sAggregate) {
            case "COUNT":
                $result = true;
                break;
            case "SUM":
                $result = true;
                break;
            case "AVERAGE":
                $result = true;
                break;
            default:
                $result = false;
                break;
        }

        return $result;
    }

    private function isComparatorType($sComparator) {
        $result = false;

        $sComparator = strtoupper($sComparator);

        switch ($sComparator) {
            case "=":
                $result = true;
                break;
            case "<":
                $result = true;
                break;
            case ">":
                $result = true;
                break;
            case "<=":
                $result = true;
                break;
            case ">=":
                $result = true;
                break;
            case "LIKE":
                $result = true;
                break;
            case "<>":
                $result = true;
                break;
            default:
                $result = false;
                break;
        }

        return $result;
    }

    private function isOrderType($sOrder) {
        $result = false;

        $sOrder = strtoupper($sOrder);

        switch ($sOrder) {
            case "ASC":
                $result = true;
                break;
            case "DESC":
                $result = true;
                break;
            default:
                $result = false;
                break;
        }

        return $result;
    }

}

?>
