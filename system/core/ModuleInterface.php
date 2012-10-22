<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Interface
 *
 */
class ModuleInterface {
    protected $m_model;
    
    function getFields() {
        return $this->m_model->getFields();
    }
    
    function get($iModelID) {
        return $this->m_model->get($iModelID);
    }
    
    function getList($filter = null, $sorter = null, $pager = null) {
        return $this->m_model->getList($filter, $sorter, $pager);
    }
    
    function checkExistance($iModelID) {
        return $this->m_model->checkExistance($iModelID);
    }
    
    function exists($iModelID) {
        return $this->m_model->exists($iModelID);
    }
    
    function add(Container $container) {
        return $this->m_model->add($container);
    }
    
    function edit(Container $container) {
        return $this->m_model->edit($container);
    }
    
    function delete($iModelID) {
        return $this->m_model->delete($iModelID);
    }
}
?>
