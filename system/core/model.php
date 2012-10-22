<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

/**
 * @author Rudzki
 * @version 1.0
 * @created 31-sty-2012 17:09:49
 * @completed 13-02-2012
 */
class Model {
    protected $m_sTableName = '';
    protected $m_sClassName = '';

    function __construct() {
        if($this->m_sTableName == '') {
            $sClassName =  get_class($this);
            $iPos = strpos($sClassName, "Model");
            $this->m_sTableName = strtolower(substr($sClassName, 0, $iPos));
        }
        if($this->m_sClassName == '') {
            $sClassName =  get_class($this);
            $iPos = strpos($sClassName, "Model");
            $this->m_sClassName = substr($sClassName, 0, $iPos);
        }

        Cache::$cache->createModelSpace(ucfirst($this->m_sTableName));
    }

    function __destruct() {   
    }
    
    function getModelName() {
        return $this->m_sClassName;
    }
    
    function getTableName() {
        return $this->m_sTableName;
    }
    
    function getFields() {
        return array();
    }
    
    function getLangMapItems() {
        return array();
    }
    
    function createFromRow($row) {
       
    }

    function exists($iModelID) {
        $bExists = false;
        $sKeyName = "{$this->m_sTableName}_id";
        
        if(Cache::$cache->isInModelSpace("`{$this->m_sTableName}`", 'exists', $iModelID)) 
            $bExists =  Cache::$cache->getFromModelSpace($this->m_sTableName, 'exists', $iModelID);
        else {
            if(ctype_digit("$iModelID")) {
                $result = Database::$mysql->get("`{$this->m_sTableName}`")->where($sKeyName, '=', $iModelID)->execQuery();
                $bExists = (Database::$mysql->getRowCount($result) > 0);
            } else {
                $sExceptionVar = 'wrong'.$this->m_sClassName.'ID';
                throw new ObjectDoesntExistException (Lang::sGet($sExceptionVar));
            }
        }

        Cache::$cache->addToModelSpace($this->m_sTableName, 'exists', $iModelID, $bExists);
        return $bExists;
    }
    
    function checkExistance($iModelID) {
        $bExists = false;
        

        $bExists = $this->exists($iModelID);
        if(!$bExists) {
            $sExceptionVar = strtolower($this->m_sClassName).'DoesntExist';
            throw new ObjectDoesntExistException ((Lang::sGet($sExceptionVar)));
        }

        return $bExists;
    }
    
    function get($iModelID) {
        $container = null;
        
        if(Cache::$cache->exists($this->m_sTableName."-".$iModelID))
              $container = Cache::$cache->get($this->m_sTableName."-".$iModelID);
        else {
            $sKeyName = "{$this->m_sTableName}_id";
            $this->checkExistance($iModelID);

            $res = Database::$mysql->get("`{$this->m_sTableName}`")->where($sKeyName, '=', $iModelID)->execQuery();
            $container = $this->createFromRow(Database::$mysql->getRow($res));
        }
        Cache::$cache->add($this->m_sTableName."-".$iModelID, $container);
        return $container;
    }
    
    public function getList($filter = null, $sorter = null, $pager = null) {
        $list = array();
        $sCacheID = '';
        if($filter != null) $sCacheID .= ":f:".$filter->getStringID();
        if($sorter != null) $sCacheID .= ":s:".$sorter->getStringID();
        if($pager != null) $sCacheID .= ":p:".$pager->getStringID();
        
        
        
        if(Cache::$cache->isInModelSpace($this->m_sTableName, 'list', $sCacheID))
            $list = Cache::$cache->getFromModelSpace($this->m_sTableName, 'list', $sCacheID);
        else {
            $this->setLimits($filter, $sorter, $pager);

            $result = Database::$mysql->get("`{$this->m_sTableName}`")->execQuery();
            foreach(Database::$mysql->getAllRows($result) as $row) 
                $list []= $this->createFromRow ($row);
        }
        
        Cache::$cache->addToModelSpace($this->m_sTableName, 'list', $sCacheID, $list);
        return $list;
    }
    
    function setLimits($filter = null, $sorter = null, $pager = null) {
        if($pager !== null) {
            if ($pager->getPageNumber() > 0 AND ctype_digit("{$pager->getPageNumber()}"))
                Database::$mysql->setPager($pager);
            else
                throw new PagingException(Lang::$wrongPageNumber, $pager->getPageNumber());
        }
        if ($filter !== null)
            Database::$mysql->setFilter($filter);
        if ($sorter !== null)
            Database::$mysql->setSorter($sorter);
    }
    
    function add(Container $container) {
        $iModelID = 0;
        
        $container->validate();
        
        $fields = $this->getFields();
        $additionMap = array();
        
        foreach($fields as $field) {
            if($field == strtolower($this->m_sTableName)."_id")
                continue;
            
            $sMethodName = "get".str_replace("_", "", $field);
            if(method_exists($container, $sMethodName))
                $additionMap[$field] = $container->$sMethodName();
        }
        Database::$mysql->insert("`{$this->m_sTableName}`", $additionMap)->execQuery();
        
        $iModelID = Database::$mysql->getLastID();
        Cache::$cache->clearModelSpace($this->m_sTableName);
        
        return $iModelID;
    }
    
    function edit(Container $container) {
        $bSuccess = false;
        
        $container->validate();
        
        $fields = $this->getFields();
        $additionMap = array();
        
        foreach($fields as $field) {
            if($field == strtolower($this->m_sTableName)."_id")
                continue;
            
            $sMethodName = "get".str_replace("_", "", $field);
            if(method_exists($container, $sMethodName))
                $additionMap[$field] = $container->$sMethodName();
        }
        Database::$mysql->update("`{$this->m_sTableName}`", $additionMap)
                ->where(strtolower($this->m_sTableName)."_id", '=', $container->getID())->execQuery();
        
        $bSuccess = true;
        Cache::$cache->clearModelSpace($this->m_sTableName);
        
        return $bSuccess;
    }
    
    function delete($iModelID) {
        $bSuccess = false;
        
        $this->checkExistance($iModelID);
        
        Database::$mysql->delete("`{$this->m_sTableName}`")
                ->where(strtolower($this->m_sTableName)."_id", '=', $iModelID)->execQuery();
        
        $bSuccess = true;
        Cache::$cache->clearModelSpace($this->m_sTableName);
        
        return $bSuccess;
    }
}

?>