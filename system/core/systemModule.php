<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class SystemModule {
    protected static $config;
    
    public static function init() {
        $sClassName = strtolower(get_called_class());
        Core::includeSystemModuleFiles($sClassName);
        self::loadConfig();
    }
    
    public static function loadConfig() {
        $sClassName = strtolower(get_called_class());
        if(file_exists(DOCROOT.'config/system/'.$sClassName.".php")) {
            require_once DOCROOT.'config/system/'.$sClassName.".php";
            self::$config = $config;
        }
    }
}

?>
