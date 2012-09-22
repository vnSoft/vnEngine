<?php defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class FilterInterfaceFilter {

    function create($sFilterName) {
        $filter = null;
        
        $filterModel = new FilterModelFilter();
        $filter = $filterModel->create($sFilterName);

        return $filter;
    }

    function exists($sFilterName) {
        $bExists = false;

        $filterModel = new FilterModelFilter();
        $bExists = $filterModel->exists($sFilterName);

        return $bExists;
    }

    function get($sFilterName) {
        $filter = null;

        $filterModel = new FilterModelFilter();
        if($this->filterExists($sFilterName))
            $filter = $filterModel->get($sFilterName);
        else
            $filter = $filterModel->create($sFilterName);

        return $filter;
    }
    
    function save(CFilter $filter) {
        $filterModel = new FilterModelFilter();
        return $filterModel->save($filter);
    }

    function delete($sFilterName) {
        $filterModel = new FilterModelFilter();
        return $filterModel->delete($sFilterName);
    }
}
?>
