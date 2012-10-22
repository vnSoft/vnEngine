<?php defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class SorterModelSorter extends Model {

    function create($sSorterName) {
        $sorter = new CSorter($sSorterName);

        return $sorter;
    }

    function exists($sSorterName) {
        $bExists = false;

        $sCodedName = md5($sSorterName);
        if(Session::$session->isVarSet('sorters-'.$sCodedName) AND
                Session::$session->get('sorters-'.$sCodedName) instanceof CSorter)
            $bExists = true;
        else
            $bExists = false;

        return $bExists;
    }

    function get($sSorterName) {
        $sorter = null;

        $sorter = Session::$session->get('sorters-'.md5($sSorterName));

        return $sorter;
    }

    function save(CSorter $sorter) {
        $sCodedName = md5($sorter->getName());
        Session::$session->set('sorters-'.$sCodedName, $sorter);
    }

    function delete($sSorterName) {
        $sCodedName = md5($sSorterName);
        Session::$session->delete('sorters-'.$sCodedName);
    }
}
?>
