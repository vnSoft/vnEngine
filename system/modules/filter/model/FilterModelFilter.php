<?php defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class FilterModelFilter extends Model {

    function create($sFilterName) {
        $filter = new CFilter($sFilterName);
        
        return $filter;
    }

    function exists($sFilterName) {
        $bExists = false;

        $sCodedName = md5($sFilterName);
        if(Session::$session->isVarSet('filters-'.$sCodedName) AND
                Session::$session->get('filters-'.$sCodedName) instanceof CFilter)
            $bExists = true;
        else
            $bExists = false;

        return $bExists;
    }

    function get($sFilterName) {
        $filter = null;
        
        $filter = Session::$session->get('filters-'.md5($sFilterName));

        return $filter;
    }

    function save(CFilter $filter) {
        $sCodedName = md5($filter->getName());
        Session::$session->set('filters-'.$sCodedName, $filter);
    }

    function delete($sFilterName) {
        $sCodedName = md5($sFilterName);
        Session::$session->delete('filters-'.$sCodedName);
    }
}
?>
