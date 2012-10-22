<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

abstract class ApplicationModule {
    protected static $s_sDefaultController = 'DefaultControllerModule';
    protected static $properties = array();
    
    public static function init() {
        $sClassName = strtolower(get_called_class());
        Core::includeModuleFiles($sClassName);
        if(empty(static::$properties[$sClassName]))
            static::$properties[$sClassName] = array();
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
    
    public static function setLanguage($sLanguage) {
        $sClassName = strtolower(get_called_class());
        if(file_exists(APPROOT.'modules/'.$sClassName."/lang/$sLanguage.php"))
            require_once APPROOT.'modules/'.$sClassName."/lang/$sLanguage.php";
         $sLangClassName = ucfirst($sClassName)."Lang";
         return static::$properties[$sClassName]['language'] = new $sLangClassName();
    }
    
    public static function lang($sLangVariableName) {
        $sClassName = strtolower(get_called_class());
        return static::$properties[$sClassName]['language']->get($sLangVariableName);
    }
}

?>
