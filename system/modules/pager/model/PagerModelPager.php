<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class PagerModelPager {

    const NAME_PREFIX = 'pagers-';

    function create($sPagerName, $iSize = 0) {
        $pager = new CPager($sPagerName, $iSize);

        return $pager;
    }

    function exists($sPagerName) {
        $bExists = false;

        $sCodedName = md5($sPagerName);
        if (Session::$session->isVarSet(self::NAME_PREFIX . $sCodedName) AND
                Session::$session->get(self::NAME_PREFIX . $sCodedName) instanceof CPager)
            $bExists = true;
        else
            $bExists = false;

        return $bExists;
    }

    function get($sPagerName) {
        $pager = null;

        $sCodedName = md5($sPagerName);
        $pager = Session::$session->get(self::NAME_PREFIX . $sCodedName);

        return $pager;
    }

    function save(CPager $pager) {

        $sCodedName = md5($pager->getName());
        Session::$session->set(self::NAME_PREFIX . $sCodedName, $pager);
    }

    function delete($sPagerName) {

        $sCodedName = md5($sPagerName);
        Session::$sesion->delete(self::NAME_PREFIX . $sCodedName);
    }

    function getAllRecordsNum($sModelName, Cfilter $filter = null) {
        $iRecordsNum = 0;

        $safeModelName = Database::$mysql->escape($sModelName);
        if ($filter !== null)
            Database::$mysql->setFilter($filter);
        $result = Database::$mysql->custom("SELECT COUNT(*) FROM $safeModelName")->execQuery();
        $row = Database::$mysql->getRow($result);
        $iRecordsNum = $row['COUNT(*)'];
        
        return $iRecordsNum;
    }

}

?>