<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Database extends SystemModule {
    public static $mysql;
    public static $languageMap;
    
    public static function init() {
        parent::init();
        self::$mysql = new MySQLInterfaceDatabase();
        self::$mysql->connect(self::config('host'), self::config('user'), self::config('password'));
        self::$mysql->selectDB(self::config('name'));
        self::$mysql->setCharset('utf8');
        
        if(self::config('languages'))
            self::$mysql->setLanguageMap(self::$languageMap);
    }

    public static function loadConfig() {
        require_once (DOCROOT.'config'.DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'database.php');
        self::$properties['database']['config'] = $config;
        self::$languageMap = $languageMap;
    }
    
    
}
?>
