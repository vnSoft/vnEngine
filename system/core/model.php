<?php

/**
 * @author Rudzki
 * @version 1.0
 * @created 31-sty-2012 17:09:49
 * @completed 13-02-2012
 */
class Model {

    function __construct() {
    }

    function __destruct() {   
    }


    function exists($iModelID) {
        $bExists = false;

        $sClassName =  get_class($this);
        $iPos = strpos($sClassName, "Model");
        $sTableName = strtolower(substr($sClassName, 0, $iPos));
        $sKeyName = "{$sTableName}_id";

        if(ctype_digit("$iModelID")) {
            $result = Database::$mysql->get($sTableName)->where($sKeyName, '=', $iModelID)->execQuery();
            $bExists = (Database::$mysql->getRowCount($result) > 0);
        } else {
            $sExceptionVar = 'Lang::$wrong'.ucfirst($sTableName).'ID';
            throw new ObjectDoesntExistException ($$sExceptionVar);
        }

        return $bExists;
    }
}

?>