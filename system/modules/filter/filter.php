<?php

class Filter implements SystemModule {
    public static $filter;

    public static function init() {
        require_once ('container/CFilter.php');
        require_once ('container/CFilterField.php');
        require_once ('interface/FilterInterfaceFilter.php');
        require_once ('model/FilterModelFilter.php');
        self::loadConfig();
        self::$filter = new FilterInterfaceFilter();
    }

    public static function loadConfig() {
    }
}

?>
