<?php

/**
 * @author Rudzki
 * @version 1.0
 * @created 02-mar-2012 14:00:46
 */
class MySQLInterfaceDatabase {

    private $m_handle;

    function __construct() {
        $this->m_handle = new MySQLModelDatabase();
    }

    /**
     *
     * @param sHost
     * @param sLogin
     * @param sPassword
     */
    public function connect($sHost, $sLogin, $sPassword) {
        $this->m_handle->connect($sHost, $sLogin, $sPassword);
        return $this;
    }

    /**
     *
     * @param sName
     */
    public function selectDB($sName) {
        $this->m_handle->selectDB($sName);
        return $this;
    }

    /**
     *
     * @param sCharset
     */
    public function setCharset($sCharset) {
        $this->m_handle->setCharset($sCharset);
        return $this;
    }

    public function setLanguageMap($languageMap) {
        $this->m_handle->setLanguageMap($languageMap);
        return $this;
    }

    public function setLanguage($sLanguage) {
        $this->m_handle->setLanguage($sLanguage);
        return $this;
    }

    /**
     *
     * @param sValue
     */
    public function escape($sValue) {
        return $this->m_handle->escape($sValue);
    }

    public function execQuery() {
        $result = $this->m_handle->execQuery();
        return new DatabaseResult($result);
    }

    public function custom($sQuery) {
        $this->m_handle->custom($sQuery);
        return $this;
    }

    public function setFilter(CFilter $filter) {
        $this->m_handle->setFilter($filter);
        return $this;
    }

    public function setSorter(CSorter $sorter) {
        $this->m_handle->setSorter($sorter);
        return $this;
    }
    
    public function setPager(CPager $pager){
        $this->m_handle->setPager($pager);
        return $this;
    }
    /**
     *
     * @param sTableName
     */
    public function get($sTableName) {
        $this->m_handle->get($sTableName);
        return $this;
    }

    /**
     *
     * @param sTableName
     * @param fieldNames
     */
    public function select($sTableName, $fieldNames = array()) {
        $this->m_handle->select($sTableName, $fieldNames);
        return $this;
    }

    /**
     * fieldNames is an associative array
     * array("field1" => "COUNT", "field2" = "AVERAGE", 'field3' => "")
     *
     * @param sTableName
     * @param fieldNames
     */
    public function selectAggregate($sTableName, $fieldNames = array()) {
        $this->m_handle->selectAggregate($sTableName, $fieldNames);
        return $this;
    }

    /**
     *
     * @param sTableName
     * @param sFieldName
     */
    public function selectDistinct($sTableName, $sFieldNames = array()) {
        $this->m_handle->selectDistinct($sTableName, $sFieldNames);
        return $this;
    }

    /**
     *
     * @param sTableName
     * @param valuesMap
     */
    public function insert($sTableName, $valuesMap = array()) {
        $this->m_handle->insert($sTableName, $valuesMap);
        return $this;
    }

    /**
     * decreases values by specified values
     *
     * @param sTableName
     * @param valuesMap
     */
    public function decrease($sTableName, $valuesMap = array()) {
        $this->m_handle->decrease($sTableName, $valuesMap);
        return $this;
    }

    /**
     * increases values by specified values
     *
     * @param sTableName
     * @param valuesMap
     */
    public function increase($sTableName, $valuesMap = array()) {
        $this->m_handle->increase($sTableName, $valuesMap);
        return $this;
    }

    /**
     * defines new values
     *
     * @param sTableName
     * @param valuesMap
     */
    public function update($sTableName, $valuesMap = array()) {
        $this->m_handle->update($sTableName, $valuesMap);
        return $this;
    }

    /**
     *
     * @param sTableName
     */
    public function delete($sTableName) {
        $this->m_handle->delete($sTableName);
        return $this;
    }

    /**
     * sAggregateType defines function aggregating value
     *
     * @param sFieldName
     * @param sComparator
     * @param Value
     * @param sAggregateType
     */
    public function andWhere($sFieldName, $sComparator, $Value, $sAggregateType = '') {
        $this->m_handle->andWhere($sFieldName, $sComparator, $Value, $sAggregateType);
        return $this;
    }

    /**
     * sAggregateType defines function aggregating value
     *
     * @param sFieldName
     * @param sComparator
     * @param Value
     * @param sAggregateType
     */
    public function orWhere($sFieldName, $sComparator, $Value, $sAggregateType = '') {
        $this->m_handle->orWhere($sFieldName, $sComparator, $Value, $sAggregateType);
        return $this;
    }

    /**
     * sAggregateType defines function aggregating value
     *
     * @param sFieldName
     * @param sComparator
     * @param value
     * @param sAggregateType
     */
    public function where($sFieldName, $sComparator, $value, $sAggregateType = '') {
        $this->m_handle->where($sFieldName, $sComparator, $value, $sAggregateType);
        return $this;
    }

    /**
     * fieldNames is an associative array
     * array("field1" => "ASC", "field2" = "DESC")
     *
     * @param fieldNames
     */
    public function orderBy($fieldNames ) {
        $this->m_handle->orderBy($fieldNames);
        return $this;
    }

    /**
     *
     * @param iStart
     * @param iCount
     */
    public function limit($iStart, $iCount) {
        $this->m_handle->limit($iStart, $iCount);
        return $this;
    }

    public function getLastID() {
        return $this->m_handle->getLastID();
    }

    /**
     *
     * @param sFieldName
     */
    public function groupBy($sFieldName) {
        $this->m_handle->groupBy($sFieldName);
        return $this;
    }

    /**
     *
     * @param resultHandle
     */
    public function getAllRows(DatabaseResult $result) {
        $resultModel = new MySQLResultModelDatabase();
        $result->setToModel($resultModel);
        return $resultModel->getAllRows();
    }

    /**
     *
     * @param resultHandle
     */
//    public function getAllRowsNum(DatabaseResult $result) {
//        $resultModel = new MySQLResultModelDatabase();
//        $result->setToModel($resultModel);
//        return $resultModel->getAllRowsNum();
//    }

    /**
     *
     * @param resultHandle
     */
    public function getRow(DatabaseResult $result) {
        $resultModel = new MySQLResultModelDatabase();
        $result->setToModel($resultModel);
        return $resultModel->getRow();
    }

    /**
     *
     * @param resultHandle
     */
    public function getRowCount(DatabaseResult $result) {
        $resultModel = new MySQLResultModelDatabase();
        $result->setToModel($resultModel);
        return $resultModel->getRowCount();
    }

    /**
     *
     * @param resultHandle
     * @param sAttributeName
     */
    public function getAttribute(DatabaseResult $result, $sAttributeName) {
        $resultModel = new MySQLResultModelDatabase();
        $result->setToModel($resultModel);
        return $resultModel->getAttribute($sAttributeName);
    }


    /**
     *
     * @param resultHandle
     * @param iNewPosition
     */
    public function seekTo(DatabaseResult $result, $iNewPosition) {
        $resultModel = new MySQLResultModelDatabase();
        $result->setToModel($resultModel);
        return $resultModel->seekTo($iNewPosition);
    }

    /**
     *
     * @param resultHandle
     * @param iResultCount
     */
    public function seek(DatabaseResult $result, $iResultCount) {
        $resultModel = new MySQLResultModelDatabase();
        $result->setToModel($resultModel);
        return $resultModel->seek($iResultCount);
    }

}

?>