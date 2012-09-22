<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Database implements SystemModule {
    public static $mysql;
    public static $languageMap;
    
    public static function init() {
        parent::init();
        self::$mysql = new MySQLInterfaceDatabase();
        self::$mysql->connect(self::$config['host'], self::$config['user'], self::$config['password']);
        self::$mysql->selectDB(self::$config['name']);
        self::$mysql->setCharset('utf8');
        
        if(self::$config['languages'])
            self::$mysql->setLanguageMap(self::$s_languageMap);
    }

    public static function loadConfig() {
        require_once (DOCROOT.'config'.DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'database_config.php');
         self::$config = $config;
         self::$languageMap = $languageMap;
    }
    
    
}
?>
