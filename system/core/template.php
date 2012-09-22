<?php

/**
 * @author Rudzki
 * @version 1.0
 * @created 31-sty-2012 17:09:49
 * @completed 13-02-2012
 */
class Template {

    protected $m_sName;
    protected $m_sLanguage;
    protected $m_sCharset;
    protected $m_sTitle;
    protected $m_sDescription;
    protected $m_sKeywords;
    protected $m_sMetadata;
    protected $m_views = array();
    protected $m_staticViews = array();
    protected $m_scripts = array();
    protected $m_styles = array();
    protected $m_sRedirectURL;
    protected $m_iRedirectDelay;
    protected $m_exception = null;
    protected $m_bExceptionOccured = false;
    protected $m_sPath;

    /**
     *
     * @param sTemplateName
     * @param sLanguage
     */
    public function __construct($sTemplateName, $sLanguage) {
        $this->m_sName = $sTemplateName;
        $this->m_sLanguage = $sLanguage;

        $sClassName =  get_class($this);
        $sTemplateName = str_replace("Template", '', $sClassName);
        $this->m_sPath = ROOT.'application/templates/'.strtolower($sTemplateName).'/';
        require_once APPROOT . "lang" . DIRECTORY_SEPARATOR . $this->m_sLanguage . ".php";
    }

    /**
     *
     * @param view
     */
    public function addView(View $view) {

        $view->setTemplatePath($this->m_sPath);
        $this->m_views[] = $view;
    }

    /**
     *
     * @param staticView
     * @param name
     */
    public function addStaticView(View $staticView, $name) {

        $staticView->setTemplatePath($this->m_sPath);
        $this->m_staticViews[$name] = $staticView;
    }

    public function render() {
        require_once APPROOT . "templates" . DIRECTORY_SEPARATOR . $this->m_sName . DIRECTORY_SEPARATOR . "index.php";
    }

    /**
     *
     * @param sPath
     */
    public function addScript($sPath) {
        $this->m_scripts[] = ROOT.'application/templates/'.strtolower($this->m_sName).'/'.$sPath;
    }

    /**
     *
     * @param sPath
     */
    public function addStyle($sPath) {
        $this->m_styles[] = ROOT.'application/templates/'.strtolower($this->m_sName).'/'.$sPath;
    }

    /**
     *
     * @param sCharset
     */
    public function setCharset($sCharset) {
        $this->m_sCharset = $sCharset;
    }

    /**
     *
     * @param sLanguage
     */
    public function setLanguage($sLanguage) {
        $this->m_sLanguage = $sLanguage;
    }

    /**
     *
     * @param sDescription
     */
    public function setDescription($sDescription) {
        $this->m_sDescription = $sDescription;
    }

    /**
     *
     * @param sTitle
     */
    public function setTitle($sTitle) {
        $this->m_sTitle = $sTitle;
    }

    /**
     *
     * @param sKeywords
     */
    public function setKeywords($sKeywords) {
        $this->m_sKeywords = $sKeywords;
    }

    /**
     *
     * @param sMetadata
     */
    public function addMetadata($sMetadata) {
        $this->m_sMetadata .= $sMetadata . "\n";
    }

    public function addException(Exception $e) {
        $this->m_exception = $e;
        $this->m_bExceptionOccured = true;
    }

    public function exceptionOccured() {
        return $this->m_bExceptionOccured;
    }

    /**
     *
     * @param sURL
     * @param iDelay
     */
    public function redirect($sURL, $iDelay) {
        $this->m_sRedirectURL = $sURL;
        $this->m_iRedirectDelay = $iDelay;
        $this->addMetadata("<meta http-equiv='refresh' content='$iDelay;url=$sURL'>");
    }

    public function loadStaticViews() {

    }

    public function initialize() {
        $this->loadStaticViews();
    }

}

?>