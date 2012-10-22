<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');
class ProcesserInterfaceForm {
    private $m_controller;
    
    function process($sFormName, $controller, $sPrepareMethod = '', $sValidMethod = '', $sInvalidMethod = '') {
        $form = Form::$form;
        $this->m_controller = $controller;
        $form->restoreForm($sFormName, Router::getInstance()->getCurrentRequest());
        if (!$form->isEmpty() AND $form->isSent() AND $form->getCurrentFormName() == $sFormName) {
             if($form->isValid())
                 return $controller->$sValidMethod();
             else {
                 if($sInvalidMethod != '')
                    return $controller->$sInvalidMethod($form->getErrorMessages());
                 else 
                     return $this->showErrors($form->getErrorMessages());
             }
        } else
            return $controller->$sPrepareMethod();
    }
    
    function showErrors($errorList) {
        $sController = get_class($this->m_controller);
        $iPos = strpos($sController, "Controller")+10;
        $sModuleName = substr($sController, $iPos);
        $view = new TemplateView('formError');
        $view->messages = $errorList;
        $view->sModule = $sModuleName;
        $this->m_controller->addView($view);
    }
}

?>
