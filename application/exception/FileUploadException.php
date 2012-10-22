<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileUploadException
 *
 */
class FileUploadException extends Exception {

    protected $message;

    public function __construct($sMessage) {
        $this->message = $sMessage;
    }

}

?>
