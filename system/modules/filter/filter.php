<?php

class Filter extends SystemModule {
    public static $filter;

    public static function init() {
        parent::init();
        self::$filter = new FilterInterfaceFilter();
    }


}

?>
