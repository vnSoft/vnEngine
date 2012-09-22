<?php
/**
 * @author Rudzki
 * @version 1.0
 * @created 17-lut-2012 13:33:49
 */
class Session implements SystemModule {

	public static $session;
        public static $s_config;


	public static function init() {
            //Loading files
            require_once ('sessionInterfaceSession.php');
            require_once ('sessionModelSession.php');

            Session::$session = new SessionInterfaceSession();
            Session::loadConfig();
	}

        public static function loadConfig() {
            require_once (MODROOT.'session'.DIRECTORY_SEPARATOR.'config.php');
            Session::$s_config = $config;
        }

}
?>