<?php defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sorter
 *
 */
class Sorter implements SystemModule {
    public static $sorter;

    public static function init() {
        require_once ('container/CSorterField.php');
        require_once ('container/CSorter.php');
        require_once ('interface/SorterInterfaceSorter.php');
        require_once ('model/SorterModelSorter.php');
        self::loadConfig();
        self::$sorter = new SorterInterfaceSorter();
    }

    public static function loadConfig() {
    }
}
?>
