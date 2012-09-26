<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');


class PageControllerPage extends Controller implements Editable, Addable {
    private static $sForm = 'page_form';
    
    public function defaultAction() {
        $this->createForm();
        if(Form::$form->isSent()) {
            

        }
        $view = new PageView('form');
        $this->m_template->addView($view);
        $this->m_template->render();
            
            
    }
    
    private function createForm() {
        $form = Form::$form;
        $form->createForm('test', ROOT.'page/page/', "POST", FILE_FORM);
        
        $form->createField('file', FILE_FIELD);
        $form->createField('send', SUBMIT_FIELD, "Wyślij");
        $form->createField('name', TEXT_FIELD);
        
        $form->addField('file');
        $form->addField('send');
        $form->addField('name');
        
        $form->restoreForm('test', $this->m_request);
    }
    

    
    public function add() {
        Manage::add($this->getAddManifest());
    }
    
    public function edit() {
        Manage::edit($this->getEditManifest());
    }

    public function addInvalid() {
        
    }

    public function addPrepare() {
        
    }

    public function addValid() {
        
    }

    public function editInvalid() {
        
    }

    public function editPrepare() {
        
    }

    public function editValid() {
        
    }
    
    public function getEditManifest() {
        return new EditManifest(self::$sForm, $this, 'prepareForm', 'editValid', 'editInvalid');
    }

    public function getAddManifest() {
        return new AddManifest(self::$sForm, $this, 'prepareForm', 'addValid', 'addInvalid');
    }
}

?>
