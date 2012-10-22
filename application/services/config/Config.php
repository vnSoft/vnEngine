<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class Config extends Service {

    static function get($sName) {
        $res = Database::$mysql->select('config', array('value'))
                ->where('name', '=', $sName)
                ->execQuery();
        if (Database::$mysql->getRowCount($res) > 0) {
            $row = Database::$mysql->getRow($res);
            return $row[0];
        } else
            return null;
    }

    static function set($sName, $value) {
        if (self::exists($sName)) {
            Database::$mysql->update('config', array('name' => $sName, 'value' => $value))
                    ->where('name', '=', $sName)
                    ->execQuery();
        } else
            self::add($sName, $value);
    }

    static function exists($sName) {
        $res = Database::$mysql->selectAggregate('config', array('value' => 'count'))
                ->where('name', '=', $sName)
                ->execQuery();
        $row = Database::$mysql->getRow($res);
        return $row[0];
    }

    static function add($sName, $value) {
        Database::$mysql->insert('config', array('name' => $sName, 'value' => $value))
                    ->where('name', '=', $sName)->execQuery();
    }

}

?>
