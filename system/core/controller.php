<?php

/**
 * @author Rudzki
 * @version 1.0
 * @created 31-sty-2012 17:09:49
 * @completed 13-02-2012
 */
class Controller {

    protected $m_template;
    protected $m_request;
    protected static $sPagerName = "pager";
    protected static $sSorterName = "sorter";
    protected static $sFilterName = "filter";
    

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

    protected function retrievePager() {
        $pager = null;

        if (Pager::$pager->exists(self::$sPagerName))
            $pager = Pager::$pager->get(self::$sPagerName);
        else {
            $pager = Pager::$pager->create(self::$sPagerName);
            $pager->setSize(3);
        }

        $pageNumber = $this->m_request->getParam('page');
        if ($pageNumber === null)
            $pageNumber = '1';
        $pager->setPageNumber($pageNumber);

        return $pager;
    }

    protected function retrieveFilter() {
        $filter = null;

        if (Filter::$filter->exists(self::$sFilterName))
            $filter = Filter::$filter->get(self::$sFilterName);
        else {
            $filter = Filter::$filter->create(self::$sFilterName);
        }

        return $filter;
    }

    protected function retrieveSorter() {
        $sorter = null;

        if (Sorter::$sorter->exists(self::$sSorterName))
            $sorter = Sorter::$sorter->get(self::$sSorterName);
        else 
            $sorter = Sorter::$sorter->create(self::$sSorterName);

        return $sorter;
    }
  

}

?>