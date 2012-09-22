<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

abstract class SystemModule {
    protected static $config;
    
    public static function init() {
        $sClassName = strtolower(get_called_class());
        Core::includeSystemModuleFiles($sClassName);
        static::loadConfig();
    }
    
    public static function loadConfig() {
        $sClassName = strtolower(get_called_class());
        if(file_exists(DOCROOT.'config/system/'.$sClassName.".php")) {
            require_once DOCROOT.'config/system/'.$sClassName.".php";
            static::$config = $config;
        }
    }
    
    public static function config($sIndex) {
        if(isset(static::$config[$sIndex]))
            return static::$config[$sIndex];
        else
            return null;
    }
}

?>
