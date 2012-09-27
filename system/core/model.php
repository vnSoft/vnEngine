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

    function __construct() {
        if($this->m_sTableName == '') {
            $sClassName =  get_class($this);
            $iPos = strpos($sClassName, "Model");
            $this->m_sTableName = strtolower(substr($sClassName, 0, $iPos));
        }

        Cache::$cache->createModelSpace(ucfirst($this->m_sTableName));
    }

    function __destruct() {   
    }
    
    function createFromRow($row) {
        
    }

    function exists($iModelID) {
        $bExists = false;
        $sKeyName = "{$this->m_sTableName}_id";

        if(Cache::$cache->isInModelSpace($this->m_sTableName, 'exists', $iModelID)) 
            $bExists =  Cache::$cache->getFromModelSpace($this->m_sTableName, 'exists', $iModelID);
        else {
            if(ctype_digit("$iModelID")) {
                $result = Database::$mysql->get($this->m_sTableName)->where($sKeyName, '=', $iModelID)->execQuery();
                $bExists = (Database::$mysql->getRowCount($result) > 0);
            } else {
                $sExceptionVar = 'Lang::$wrong'.ucfirst($$this->m_sTableName).'ID';
                throw new ObjectDoesntExistException ($$sExceptionVar);
            }
        }

        $bExists =  Cache::$cache->addToModelSpace($this->m_sTableName, 'exists', $iModelID, $bExists);
        return $bExists;
    }
    
    function checkExistance($iModelID) {
        $bExists = false;

        $bExists = $this->exists($iModelID);
        if(!$bExists) {
            $sExceptionVar = 'Lang::$'.ucfirst($this->m_sTableName).'DoesntExist';
            throw new ObjectDoesntExistException (($$sExceptionVar));
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

            $res = Database::$mysql->get($this->m_sTableName)->where($sKeyName, '=', $iModelID)->execQuery();
            $container = $this->createFromRow(Database::$mysql->getRow($res));
        }
        
        Cache::$cache->add($this->m_sTableName."-".$iModelID, $container);
        return $container;
    }
    
    public function getList($filter = null, $sorter = null, $pager = null) {
        $list = array();
        $sCacheID = '';
        if($filter != null) $sCacheKey .= ":f:".$filter->getName();
        if($sorter != null) $sCacheKey .= ":s:".$sorter->getName();
        if($pager != null) $sCacheKey .= ":p:".$pager->getName();
        
        if(Cache::$cache->isInModelSpace($this->m_sTableName, 'list', $sCacheID))
            $list = Cache::$cache->getFromModelSpace($this->m_sTableName, 'list', $sCacheID);
        else {
            $this->setLimits($filter, $sorter, $pager);

            $result = Database::$mysql->get($this->m_sTableName)->execQuery();
            foreach(Database::$mysql->getAllRows($result) as $row) 
                $list []= $this->createFromRow ($row);
        }
        
        Cache::$cache->addToModelSpace($this->m_sTableName, 'list', $sCacheID, $list);
        return $list;
    }
    
    function setLimits($filter = null, $sorter = null, $pager = null) {
        if($pager !== null) {
            if (!$pager->isOutOfBound($this->m_sTableName, $filter))
                Database::$mysql->setPager($pager);
            else
                throw new PagingException(Lang::$wrongPageNumber, $pager->getPageNumber());
        }
        if ($filter !== null)
            Database::$mysql->setFilter($filter);
        if ($sorter !== null)
            Database::$mysql->setSorter($sorter);
    }
    
}

?>