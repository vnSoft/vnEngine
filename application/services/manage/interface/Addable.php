<?php
defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Rudzki
 */
interface Addable {
    public function getAddManifest();
    public function addValid();
    public function addInvalid();
    public function addPrepare();
}

?>
