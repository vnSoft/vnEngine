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
interface Editable {
    public function getEditManifest();
    public function editValid();
    public function editInvalid();
    public function editPrepare();
}

?>
