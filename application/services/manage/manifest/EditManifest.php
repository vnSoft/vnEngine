<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');
require_once('ManageManifest.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EditManifest
 *
 */
class EditManifest extends ManageManifest{
    private $m_sFormName;
    private $m_controller;
    
    public function __construct($sFormName, $controller) {
        $this->m_sFormName = $sFormName;
        $this->m_controller = $controller;
    }
    
    public function process() {
        $form = Form::$form;
        $form->restoreForm($this->m_sFormName, Router::getInstance()->getCurrentRequest());
        if (!$form->isEmpty() AND $form->getCurrentFormName() == $this->m_sFormName AND $form->isSent()) {
            if ($form->isValid())
                $this->m_controller->editValid();
            else
                $this->m_controller->editInvalid();
        } else 
            $this->m_controller->editPrepare();
    }
}

?>
