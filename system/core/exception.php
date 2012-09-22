<?php

class WrongInputDataException extends Exception {

    protected $message;

    public function __construct($sMessage) {
        $this->message = $sMessage;
    }

}

class WrongDataException extends Exception {

    protected $message;

    public function __construct($sMessage) {
        $this->message = $sMessage;
    }

}

class ObjectDoesntExistException extends Exception {

    protected $message;

    public function __construct($sMessage) {
        $this->message = $sMessage;
    }

}

class DatabaseException extends Exception {

    protected $message;

    public function __construct($sMessage, $sQuery = '') {
        $this->message = $sMessage;

        if (Core::$s_config['running_mode'] == Core::DEBUG)
            $this->message .= "<br> Query: " . $sQuery;
    }

}

class LoadingFileException extends Exception {

    protected $message;

    public function __construct($sMessage) {
        $this->message = $sMessage;
    }

}

class AccessFileException extends Exception {

    protected $message;

    public function __construct($sMessage, $sPath = '') {
        $this->message = $sMessage;

        if (Core::$s_config['running_mode'] == Core::DEBUG)
            $this->message .= "<br> Path: " . $sPath;
    }

}


class PagingException extends Exception {

    protected $message;

    public function __construct($sMessage, $iPage) {
        $this->message = $sMessage;

        if (Core::$s_config['running_mode'] == Core::DEBUG)
            $this->message .= "<br/> Page number: " . $iPage;
    }

}

class AccessDirectoryException extends Exception {

    protected $message;

    public function __construct($sMessage, $sPath = '') {
        $this->message = $sMessage;

        if (Core::$s_config['running_mode'] == Core::DEBUG)
            $this->message .= "<br/> Path: " . $sPath;
    }

}

class InvalidFormException extends Exception {

    protected $message;
    private $m_sFormName;
    private $m_otherData;
    public function __construct($sMessage,$sFormName, $otherData = array()) {
        $this->message = $sMessage;
        $this->m_sFormName = $sFormName;
        $this->m_otherData = $otherData;
    }
   
    public function getFormName(){
        return $this->m_sFormName;
    }
    public function getOtherData(){
        return $this->m_otherData;
    }
    
   

}

?>
