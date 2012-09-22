<?php defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class SorterInterfaceSorter {

    function create($sSorterName) {
        $sorter = null;

        $sorterModel = new SorterModelSorter();
        $sorter = $sorterModel->create($sSorterName);

        return $sorter;
    }

    function exists($sSorterName) {
        $bExists = false;

        $sorterModel = new SorterModelSorter();
        $bExists = $sorterModel->exists($sSorterName);

        return $bExists;
    }

    function get($sSorterName) {
        $sorter = null;

        $sorterModel = new SorterModelSorter();
        if($this->sorterExists($sSorterName))
            $sorter = $sorterModel->get($sSorterName);
        else
            $sorter = $sorterModel->create($sSorterName);

        return $sorter;
    }

    function save(CSorter $sorter) {
        $sorterModel = new SorterModelSorter();
        return $sorterModel->save($sorter);
    }

    function delete($sSorterName) {
        $sorterModel = new SorterModelSorter();
        return $sorterModel->delete($sSorterName);
    }
}
?>
