<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class DatabaseException extends Exception {

    protected $message;

    public function __construct($sMessage, $sQuery = '') {
        $this->message = $sMessage;

        if (Core::$s_config['running_mode'] == Core::DEBUG)
            $this->message .= "<br> Query: " . $sQuery;
    }

}

?>
