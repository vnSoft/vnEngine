<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class ApplicationModule {
    protected static $s_sDefaultController = 'DefaultControllerModule';
    public static $config;
    
    public static function init() {
        $sClassName = strtolower(get_called_class());
        Core::includeModuleFiles($sClassName);
        self::loadConfig();
    }
    
    public static function defaultAction(Request $request) {
        $sController = self::$s_sDefaultController;
        $controller = new $sController($request);
        $controller->defaultAction();
    }

    public static function getDefaultController(Request $request) {
        $sController = self::$s_sDefaultController;
        return new $sController($request);
    }

    public static function loadConfig() {
        $sClassName = strtolower(get_called_class());
        if(file_exists(DOCROOT.'config/modules/'.$sClassName.".php")) {
            require_once DOCROOT.'config/modules/'.$sClassName.".php";
            self::$config = $config;
        }
    }
}

?>
