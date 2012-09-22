<?php

/**
 * @author Rudzki
 * @version 1.0
 * @created 31-sty-2012 17:09:49
 */
class Core {

    const DEBUG = 1;
    const RELEASE = 2;

    public static $s_systemModules;
    public static $s_config;
    public static $s_applicationModules;

    public static function init() {
        //Ustawienie funkcji obsługi błędów i wyjątków
        set_exception_handler(array('Core', 'exceptionHandler'));
        set_error_handler(array('Core', 'errorHandler'));
        register_shutdown_function(array('Core', 'shutDownHandler'));
    }

    /**
     * Loads configuration from file
     */
    public static function loadConfig() {

        require_once CONFROOT . 'config.php';

        Core::$s_config = $config;
    }

    /**
     * Loads system modules files listed in file 'config/systemModules.php'
     */
    public static function loadSystemModules() {
        require_once CONFROOT . 'systemModules.php';

        Core::$s_systemModules = $systemModules;

        foreach (Core::$s_systemModules as $module) {
            $module = strtolower($module);
            require_once MODROOT . $module . DIRECTORY_SEPARATOR . $module . '.php';
            call_user_func(array(ucfirst($module), 'init'));
        }
        
        require_once APPROOT.'app_system'.DIRECTORY_SEPARATOR.'app_system.php';
        call_user_func(array('App_System', 'init'));
    }

    /**
     * Loads application modules files listed in file 'config/applicationModules.php'
     */
    public static function loadApplicationModules() {
        require_once CONFROOT . 'applicationModules.php';

        Core::$s_applicationModules = $applicationModules;
        foreach (Core::$s_applicationModules as $module) {
            $module = strtolower($module);
            require_once APPROOT . "modules" . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $module . '.php';
            call_user_func(array($module, 'init'));
        }
    }

    /**
     * Loads file with 'include' function
     *
     * @param sFileName
     */
    public static function load($sFileName) {
        include $sFileName;
    }

    /**
     * Overloads system function for error handling.
     * Errors terminate the system
     *
     * @param code
     * @param error
     * @param file
     * @param line
     * @param context
     */
    public static function errorHandler($code, $error, $file, $line, $context) {
        if (self::$s_config['running_mode'] == self::DEBUG) {
            die("An Error occured: ($code) $error<br/>File: $file | Line: $line");
        } else if (self::$s_config['running_mode'] == self::RELEASE) {
            if ($code != E_NOTICE) {
                $template = new TemplateError('error', 'pl');
                $template->setErrorMessage($error);
                $template->render();
                die();
            }
        }
    }

    /**
     * Overloads system function for exception handling.
     * Control flow is directed to current controller
     *
     * @param e
     */
    public static function exceptionHandler($e) {
        try {
            Router::getInstance()->processException($e);
        } catch (Exception $e) {
            trigger_error("Uncaught exception: " . $e->getMessage(), E_USER_ERROR);
        }
    }

    /**
     * Overloads system function called while system is shutting down.
     */
    public static function shutDownHandler() {
        
    }

    public static function includeModuleFiles($dir) {
        if (strstr($dir, "view") !== false OR strstr($dir, "form") !== false)
            return;
        if (strstr($dir, APPROOT) === false) {
            $dir = APPROOT . 'modules' . DIRECTORY_SEPARATOR . $dir;
        }
        foreach (new DirectoryIterator($dir) as $file) {
            if (!$file->isDot()) {
                if ($file->isDir())
                    self::includeModuleFiles($file->getPathname());
                else {
                    require_once $file->getPathname();
                }
            }
        }
    }
    
    public static function includeSystemModuleFiles($dir) {
        if (strstr($dir, "view") !== false OR strstr($dir, "form") !== false)
            return;
        if (strstr($dir, APPROOT) === false) {
            $dir = MODROOT . $dir;
        }
        foreach (new DirectoryIterator($dir) as $file) {
            if (!$file->isDot()) {
                if ($file->isDir())
                    self::includeModuleFiles($file->getPathname());
                else {
                    require_once $file->getPathname();
                }
            }
        }
    }

}

?>