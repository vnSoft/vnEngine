<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class Cache extends SystemModule {
    public static $cache;
    
    public static function init() {
        parent::init();
        self::$cache = new CacheInterfaceCache();
    }

}

?>
