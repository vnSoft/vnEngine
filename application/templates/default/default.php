<?php

class TemplateDefault extends Template {

    public function render() {
        $this->initStaticViews();
        require_once APPROOT . "templates" . DIRECTORY_SEPARATOR . $this->m_sName . DIRECTORY_SEPARATOR . "index.php";
    }

    public function initialize() {
        $this->addStyle('css/style.css');

        $this->addScript('js/jquery.js');
        $this->addScript('js/jquery-ui.js');
        $this->addScript('js/scripts.js');

        $this->addMetadata("<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>");
        $this->addMetadata("<meta http-equiv='Cache-Control' content='no-cache' />");
    }

    function initStaticViews() {

       
    }

}

?>
