<?php
class DatabaseResult {
    private $m_result; //mysql database result
    private $m_iIndex = 0;

    public function  __construct($result) {
        $this->m_result = $result;
    }

    public function setToModel(MySQLResultModelDatabase &$mySQLModel) {
        $mySQLModel->setResult($this->m_result, $this->m_iIndex);
    }
}

?>
