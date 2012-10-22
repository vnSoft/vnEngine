<?php

/**
 * @author Rudzki
 * @version 1.0
 * @created 31-sty-2012 17:09:49
 * @completed 13-02-2012
 */
abstract class Controller {

    protected $m_template;
    protected $m_request;
    protected static $sPagerName = "pager";
    protected static $sSorterName = "sorter";
    protected static $sFilterName = "filter";
    
    
    abstract function getMethodList();

    /**
     * Creates new controller object
     * Creates new template from current language and template name
     * @param Request request
     */
    public function __construct(Request $request) {
        $this->m_request = $request;

        $template = "Template" . ucfirst($request->getTemplate());
        if (class_exists($template, true))
            $this->m_template = new $template($request->getTemplate(), $request->getLanguage());
        else {
            $template = "Template" . ucfirst(strtolower(Core::$s_config['template']));
            $this->m_template = new $template(Core::$s_config['template'], $request->getLanguage());
        }

        $this->m_template->initialize();
    }

    /**
     * Default action of controller
     */
    public function defaultAction() {
        
    }

    /**
     *
     * @param e
     */
    public function handleException(Exception $e) {
        try {
            //Try to fix problem with default action
            $this->m_template->addException($e);
            $this->defaultAction();
            
        } catch(Exception $e) {
            //Unable to fix problem; render plain template with exception message
            $this->m_template->addException($e);
            $this->m_template->render();
        }
    }
    
    public function addView($view) {
        $this->m_template->addView($view);
    }
    
    protected function render($view = null) {

        if($view != null)
            $this->m_template->addView($view);
        
        $this->m_template->render();
    }
    
    protected function renderForm($view) {
        $view->form = Form::$form;

        $this->m_template->addView($view);
        $this->m_template->render();
    }
        

    protected function retrievePager() {
        $pager = null;

        if (Pager::$pager->exists(static::$sPagerName))
            $pager = Pager::$pager->get(static::$sPagerName);
        else {
            $pager = Pager::$pager->create(static::$sPagerName);
            
        }

        $pageNumber = $this->m_request->getParam('page');
        if ($pageNumber === null)
            $pageNumber = '1';
        $pager->setPageNumber($pageNumber);

        return $pager;
    }

    protected function retrieveFilter() {
        $filter = null;

        if (Filter::$filter->exists(static::$sFilterName))
            $filter = Filter::$filter->get(static::$sFilterName);
        else {
            $filter = Filter::$filter->create(static::$sFilterName);
        }

        return $filter;
    }

    protected function retrieveSorter() {
        $sorter = null;

        if (Sorter::$sorter->exists(static::$sSorterName))
            $sorter = Sorter::$sorter->get(static::$sSorterName);
        else 
            $sorter = Sorter::$sorter->create(static::$sSorterName);

        return $sorter;
    }
  

}

?>