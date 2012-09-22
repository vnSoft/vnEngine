<?php

class MySQLResultModelDatabase extends Model {

    private $m_result = null;
    private $m_iIndex = 0;

    public function __construct() {

    }


    public function getAllRows() {
        $rowList = array();
        
        if($this->m_result != null) {
            if($this->getRowCount() > 0) {
                
                $iOldIndex = $this->m_iIndex;
                $this->seekTo(0);
                $iCount = $this->getRowCount();
                for($i = 0; $i < $iCount; $i++) {
                    $rowList []= $this->stripRowSlashes(mysql_fetch_array($this->m_result));
                }
                if($iOldIndex < $this->getRowCount())
                    $this->seekTo($iOldIndex);
                else {
                    $this->seekTo($iOldIndex-1);
                    $this->getRow();
                }
            }
        } else
            throw new DatabaseException("Empty database result!");

        return $rowList;
    }


    public function getRow() {
        $row = array();

        if($this->m_result != null) {
            if($this->getRowCount() > 0) {
                $row = $this->stripRowSlashes(mysql_fetch_array($this->m_result));
                $this->m_iIndex++;
            }
        } else
            throw new DatabaseException("Empty database result!");

        return $row;

    }


    public function getRowCount() {
        $iCount = 0;

        if($this->m_result != null) {
            $iCount = mysql_num_rows($this->m_result);
            $this->m_iIndex++;
        } else
            throw new DatabaseException("Empty database result!");

        return $iCount;
    }

    /**
     *
     * @param sAttributeName
     */
    public function getAttribute($sAttributeName) {
        $row = $this->getRow();
        $this->seek(-1);
        return $row[$sAttributeName];
    }

    /**
     *
     * @param iAttributeNumber
     */
    public function getAttributeNum($iAttributeNumber) {
        $row = $this->getRow();
        $this->seek(-1);
        return $row[$iAttributeNumber];
    }

    public function stripSlashes($value) {
        if($value === null)
            return $value;
        else
            return stripcslashes($value);
    }

    public function seekTo($iNewPosition) {
        if($this->m_result != null) {
            if($iNewPosition < $this->getRowCount() AND $iNewPosition >= 0) {
                mysql_data_seek($this->m_result, $iNewPosition);
                $this->m_iIndex = $iNewPosition;
            } else
                throw new DatabaseException("Wrong seek position!");
        } else
            throw new DatabaseException("Empty database result!");
    }

    public function seek($iRowCount) {
        if($this->m_result != null) {
            if($iRowCount + $this->m_iIndex >= 0 AND $iRowCount + $this->m_iIndex < $this->getRowCount()) {
                mysql_data_seek($this->m_result, $this->m_iIndex+$iRowCount);
                $this->m_iIndex+=$iRowCount;
            } else
                throw new DatabaseException("Wrong seek position!");
        } else
            throw new DatabaseException("Empty database result!");
    }

    /**
     *
     * @param mySQLResult
     */
    public function setResult(&$result, &$iIndex) {
        $this->m_result = $result;
        $this->m_iIndex = &$iIndex;
    }

    private function stripRowSlashes($row) {
        foreach($row as &$attribute)
            $attribute = $this->stripSlashes($attribute);
        return $row;
    }



}

?>