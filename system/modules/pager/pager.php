<?php

class Pager implements SystemModule{
    public static $pager;
    
    public static function init(){
        require_once 'container/CPager.php';
        require_once 'interface/PagerInterfacePager.php';
        require_once 'model/PagerModelPager.php';
        self::loadConfig();
        self::$pager = new PagerInterfacePager();
    }
    
    public static function loadConfig(){
        
    }
}

?>
