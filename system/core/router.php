<?php
/**
 * @author Rudzki
 * @version 1.0
 * @created 31-sty-2012 17:09:49
 * @completed 13-02-2012
 */
class Router {

    private static $s_instance = null;
    private static $s_request = null;

    private function __construct() {
        
    }

    /**
     * Returns instance of Router class
     */
    public static function getInstance() {
        if (Router::$s_instance == null) {
            Router::$s_instance = new Router();
        }

        return Router::$s_instance;
    }

    /**
     * Used to process standard request.
     * 
     * @param request
     */
    public function process(Request $request) {
        Router::$s_request = $request;
        $sModule = $request->getModule();
        $sController = $request->getController();
        $sAction = $request->getAction();

        if (class_exists(ucfirst($sModule), true)) {
            $sControllerClass = $sController . 'Controller' . $sModule;
            if (class_exists($sControllerClass, false) AND !empty($sController)) {
                $controller = new $sControllerClass($request);
                if (method_exists($controller, $sAction))
                    $controller->$sAction();
                else
                    $controller->defaultAction($request);
            } else 
                call_user_func(ucfirst($sModule).'::defaultAction', $request);
        } else {
            $this->processDefault($request);
        }
    }

    /**
     * Used to process exceptions
     *
     * @param e
     */
    public function processException(Exception $e) {
        if(!self::$s_request instanceof Request)
            trigger_error("Unexpected initialization exception: ".$e->getMessage(), E_USER_ERROR);
        $sController = Router::$s_request->getController();
        $sModule = Router::$s_request->getModule();
        if (class_exists($sModule, true)) {
            $sController = $sController . 'Controller' . $sModule;
            if (class_exists($sController, false)) {
                $controller = new $sController(Router::$s_request);
                $controller->handleException($e);
            } else
                call_user_func(ucfirst($sModule).'::getDefaultController', Router::$s_request)->handleException($e);
        }
        else {
            $sModule = Core::$s_config['default_module'];
            call_user_func(ucfirst($sModule).'::getDefaultController',Router::$s_request)->handleException($e);
        }
    }

    /**
     * Used to process default path
     */
    public function processDefault(Request $request) {
        $sModule = Core::$s_config['default_module'];
        call_user_func(ucfirst($sModule).'::defaultAction', $request);
    }

    public function getCurrentRequest() {
        return Router::$s_request;
    }

}

?>