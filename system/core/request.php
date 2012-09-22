<?php

/**
 * @author Rudzki
 * @version 1.0
 * @created 31-sty-2012 17:09:49
 * @completed 13-02-2012
 */
class Request {

    private static $s_sLanguage;
    private static $s_sTemplate;
    private $m_sModule;
    private $m_sController;
    private $m_sAction;
    private $m_parameters = array();
    private $m_sLastURL;
    private $m_sPath;
    private $m_sUserLanguage;
    private $m_sUserIP;
    private $m_userBrowser = array();
    private $m_bIsAJAX = false;

    /**
     * Default constructor. Dispatches request from browser address
     */
    public function __construct($sModule = null, $sController = null, $sAction = null) {
        
        if(isset($_SERVER['HTTP_REFERER']))
            $this->m_sLastURL = $_SERVER['HTTP_REFERER'];
        else
            $this->m_sLastURL = ROOT;

        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            $this->m_bIsAJAX = true;
        
        $this->setUserData();

        if ($sModule != '')
            $this->createFromData($sModule, $sController, $sAction);
        else
            $this->createFromAddress();

        $this->m_parameters = array_merge($this->m_parameters, $_POST);
        $this->m_parameters = array_merge($this->m_parameters, $_GET);
        $_POST = array();
        $_GET = array();

        $this->setCurrentTemplate();
        $this->setCurrentLanguage();
    }

    /**
     * Used to set specific request parameter
     *
     * @param sKey
     * @param value
     */
    public function setParam($sKey, $value) {
        $this->m_parameters[$sKey] = $value;
    }

    /**
     * Used to get specific request parameter
     * 
     * @param sKey
     */
    public function getParam($sKey) {
        if (isset($this->m_parameters[$sKey]))
            return $this->m_parameters[$sKey];
    }

    /**
     * Returns module of processed controller
     */
    public function getModule() {
        return $this->m_sModule;
    }

    /**
     * Returns action of processed controller
     */
    public function getAction() {
        return $this->m_sAction;
    }

    /**
     * Returns controller to process.
     */
    public function  getController() {
        return $this->m_sController;
    }
    
    public function getPath(){
        return $this->m_sPath;
    }
    /**
     * Returns last visited URL
     * @return string sLastURL
     */
    public function getLastURL() {
        return $this->m_sLastURL;
    }
    
    /**
     * Returns language used by user's browser
     */
    public function getUserLanguage() {
        return $this->m_sUserLanguage;
    }

    /**
     * Return user's browser type
     */
    public function getUserBrowser() {
        return $this->m_sUserBrowser;
    }

    /**
     * Returns user's IP
     */
    public function getUserIP() {
        return $this->m_sUserIP;
    }

    /**
     * Returns current template
     * @return string sTemplate
     */
    public function getTemplate() {
        return Request::$s_sTemplate;
    }

    /**
     * Returns current language
     * @return string sLanguage
     */
    public function getLanguage() {
        return Request::$s_sLanguage;
    }

    /**
     * Returns true if application is called by AJAX
     * @return boolean $bIsAJAX
     ublic function isAJAX() {
        return $this->m_bIsAJAX;
    }*/
    public function isAJAX() {
        return $this->m_bIsAJAX;
    }

    /**
     * Creates request from method parameters
     *
     * @param sController
     * @param sAction
     */
    private function createFromData($sModule, $sController, $sAction) {
        $this->m_sModule = $sModule;
        $this->m_sController = $sController;
        $this->m_sAction = $sAction;
    }

    /**
     * Creates request from browser address
     * Address pattern: module/controller/action/param1/value1/param2/value2/...
     */
    private function createFromAddress() {
        $path = parse_url($_SERVER['REQUEST_URI']);
        $sPath = substr_replace($path['path'], "", 0, strlen(ROOT));
        $this->m_sPath = $sPath; 
        $parameters = explode('/', trim($sPath, '/'));
        
        if(!empty($parameters[0]))
            $this->m_sModule = $parameters[0];
        if(!empty($parameters[1]))
            $this->m_sController = $parameters[1];
        if(!empty($parameters[2]))
            $this->m_sAction = $parameters[2];

        array_shift($parameters);
        array_shift($parameters);
        array_shift($parameters);

        $parameters = array_chunk($parameters, 2);
        foreach ($parameters as $param) {
            if (isset($param[1])) {
                $this->m_parameters[$param[0]] = $param[1];
            }
        }
    }

    /**
     * Sets user's IP, browser type and language
     */
    private function setUserData() {
        $this->m_sUserIP = $_SERVER['REMOTE_ADDR'];
        $this->m_userBrowser['name'] = Session::$session->getBrowser();
        //$this->m_userBrowser['system'];
        //$this->m_userBrowser['version'];

        //Exploding language header, i.e: 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3' => 'de'
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = explode($_SERVER['HTTP_ACCEPT_LANGUAGE'], ";");
            $lang = explode($lang[0], ",");
            $lang = explode($lang[0], "-");
            $this->m_sUserLanguage = $lang[0];
        } else
            $this->m_sUserLanguage = "pl";
        //--------
    }

    /**
     * Takes current language from:
     *  1. Request param
     *  2. Session
     *  3. Config
     * and saves it into request
     */
    private function setCurrentLanguage() {
        if(isset($this->m_parameters['lang'])) {
            Session::$session->set('lang'.md5('lang'), $this->m_parameters['lang']);
        }
        if(!Session::$session->isVarSet('lang'.md5('lang')))
            Session::$session->set('lang'.md5('lang'), Core::$s_config['language']);

        Request::$s_sLanguage = Session::$session->get('lang'.md5('lang'));
    }

    /**
     * Takes current template from:
     *  1. Request param
     *  2. Session
     *  3. Config
     * and saves it into request
     */
    private function setCurrentTemplate() {
        if(isset($this->m_parameters['template'])) {
            Session::$session->set('template'.md5('template'), strtolower($this->m_parameters['template']));
        }
        if(!Session::$session->isVarSet('template'.md5('template')))
            Session::$session->set('template'.md5('template'), strtolower(Core::$s_config['template']));

        Request::$s_sTemplate= Session::$session->get('template'.md5('template'));

    }

}

?>