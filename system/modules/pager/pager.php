<?php

class Pager extends SystemModule{
    public static $pager;
    
    public static function init(){
        parent::init();
        self::$pager = new PagerInterfacePager();
    }
    
}

?>
