<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

abstract class ApplicationModule {
    protected static $s_sDefaultController = 'DefaultControllerModule';
    protected static $config;
    
    public static function init() {
        $sClassName = strtolower(get_called_class());
        Core::includeModuleFiles($sClassName);
        static::loadConfig();
    }
    
    public static function defaultAction(Request $request) {
        $sController = static::$s_sDefaultController;
        $controller = new $sController($request);
        $controller->defaultAction();
    }

    public static function getDefaultController(Request $request) {
        $sController = static::$s_sDefaultController;
        return new $sController($request);
    }

    public static function loadConfig() {
        $sClassName = strtolower(get_called_class());
        if(file_exists(DOCROOT.'config/modules/'.$sClassName.".php")) {
            require_once DOCROOT.'config/modules/'.$sClassName.".php";
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
