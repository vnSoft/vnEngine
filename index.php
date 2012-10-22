<?php

//Used for including and creating files; contains system separators
define('DOCROOT',  realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
define('APPROOT',  DOCROOT.'application'.DIRECTORY_SEPARATOR);
define('CONFROOT', DOCROOT.'config'.DIRECTORY_SEPARATOR);
define('SYSROOT',  DOCROOT.'system'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR);
define('MODROOT',  DOCROOT.'system'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR);

//ROOT is used for creating internal links; contains only '/' separators
define('ROOT', str_replace(DIRECTORY_SEPARATOR, "/", str_replace(realpath(getenv("DOCUMENT_ROOT")), '', realpath(dirname(__FILE__))))."/");
date_default_timezone_set('Europe/Warsaw');
function __autoload($sName){
    if(strpos($sName, "Template") !== false) {
        $sName = strtolower(str_replace("Template", "", $sName));
        include APPROOT . "templates" . DIRECTORY_SEPARATOR . $sName . DIRECTORY_SEPARATOR . "$sName.php";
    }
}


require_once SYSROOT.'lang.php';
require_once SYSROOT.'core.php';
require_once SYSROOT.'request.php';
require_once SYSROOT.'router.php';
require_once SYSROOT.'view.php';
require_once SYSROOT.'template.php';
require_once SYSROOT.'controller.php';
require_once SYSROOT.'model.php';
require_once SYSROOT.'service.php';
require_once SYSROOT.'applicationModule.php';
require_once SYSROOT.'systemModule.php';
require_once SYSROOT.'container.php';
require_once SYSROOT.'ModuleInterface.php';

Core::init();
Core::loadConfig();
Core::initCache();
Core::loadSystemModules();

Session::$session->start();

Core::loadServices();
Core::loadApplicationModules();

Router::getInstance()->process(new Request());

Session::$session->end();





?>
