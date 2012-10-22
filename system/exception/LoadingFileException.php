<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class LoadingFileException extends Exception {

    protected $message;

    public function __construct($sMessage) {
        $this->message = $sMessage;
    }

}

?>
