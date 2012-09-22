<?php

class Page extends ApplicationModule {
    protected static $s_sDefaultController = 'PageControllerPage';
    public static $page;
  
    public static function init() {
        parent::init();
        self::$page = new PageInterfacePage();
    }


}

?>
