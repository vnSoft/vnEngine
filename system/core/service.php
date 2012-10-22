<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class Service {
    protected static $properties = array();
    
    public static function init() {
        $sClassName = strtolower(get_called_class());
        Core::includeServiceFiles($sClassName);
         if(empty(static::$properties[$sClassName]))
            static::$properties[$sClassName] = array();
        static::loadConfig();
    }
    
    public static function loadConfig() {
        $sClassName = strtolower(get_called_class());
        if(file_exists(DOCROOT.'config/services/'.$sClassName.".php")) {
            require_once DOCROOT.'config/services/'.$sClassName.".php";
            static::$properties[$sClassName]['config'] = $config;
        }
    }
    
    public static function config($sIndex) {
        $sClassName = strtolower(get_called_class());
        if(isset(static::$properties[$sClassName]['config'][$sIndex]))
            return static::$properties[$sClassName]['config'][$sIndex];
        else
            return null;
    }
}

?>
