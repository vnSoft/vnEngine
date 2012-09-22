<?php defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sorter
 *
 */
class Sorter extends SystemModule {
    public static $sorter;

    public static function init() {
        parent::init();
        self::$sorter = new SorterInterfaceSorter();
    }

    
}
?>
