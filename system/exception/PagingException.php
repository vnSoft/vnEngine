<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class PagingException extends Exception {

    protected $message;

    public function __construct($sMessage, $iPage) {
        $this->message = $sMessage;

        if (Core::$s_config['running_mode'] == Core::DEBUG)
            $this->message .= "<br/> Page number: " . $iPage;
    }

}

?>
