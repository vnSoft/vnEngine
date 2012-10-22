<?php
/**
 * @author Rudzki
 * @version 1.0
 * @created 17-lut-2012 13:33:49
 */
class Session extends SystemModule {
	public static $session;
        public static $cookie;

	public static function init() {
            parent::init();
            self::$session = new SessionInterfaceSession();
            self::$cookie = new CookieInterfaceSession();
            
	}

}
?>