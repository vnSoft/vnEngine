<?php 
defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class PagerInterfacePager {
    
    function create($sPagerName, $iSize = 0){
        $pager = null;
        
        if($iSize == 0)
            $iSize = Pager::config('size');
        
        $pagerModel = new PagerModelPager();
        $pager = $pagerModel->create($sPagerName, $iSize);
        
        return $pager;
    }
    
    function exists($sPagerName){
        $bExists = false;
        
        $pagerModel = new PagerModelPager();
        $bExists = $pagerModel->exists($sPagerName);
        
        return $bExists;
    }
    
    function get($sPagerName){
        $pager = null;
        
        $pagerModel = new PagerModelPager();
        $pager = $pagerModel->get($sPagerName);
        
        return $pager;
    }
    
    function save(CPager $pager){
        $pagerModel = new PagerModelPager();
        $pagerModel->save($pager);
    }
    
    function delete($sPagerName){
        $pagerModel = new PagerModelPager();
        $pagerModel->delete($sPagerName);
    }
}

?>
