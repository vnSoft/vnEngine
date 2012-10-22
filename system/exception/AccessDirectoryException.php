<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class AccessDirectoryException extends Exception {

    protected $message;

    public function __construct($sMessage, $sPath = '') {
        $this->message = $sMessage;

        if (Core::$s_config['running_mode'] == Core::DEBUG)
            $this->message .= "<br/> Path: " . $sPath;
    }

}

?>
