<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Database implements SystemModule {
    public static $mysql;
    public static $s_config;
    public static $s_languageMap;
    
    public static function init() {

        require_once ('interface/mysqlInterfaceDatabase.php');
        require_once ('model/mysqlModel.php');
        require_once ('model/mysqlResultModel.php');
        require_once ('databaseResult.php');
        self::loadConfig();
        self::$mysql = new MySQLInterfaceDatabase();
        self::$mysql->connect(self::$s_config['host'], self::$s_config['user'], self::$s_config['password']);
        self::$mysql->selectDB(self::$s_config['name']);
        self::$mysql->setCharset('utf8');
        
        if(self::$s_config['languages'])
            self::$mysql->setLanguageMap(self::$s_languageMap);
    }

    public static function loadConfig() {
        require_once (DOCROOT.'config'.DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'database_config.php');
         self::$s_config = $config;
         self::$s_languageMap = $languageMap;
    }
    
    
}
?>
