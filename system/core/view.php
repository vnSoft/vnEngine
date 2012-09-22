<?php

/**
 * @author Rudzki
 * @version 1.0
 * @created 31-sty-2012 17:09:49
 * @completed 13-02-2012
 */
abstract class View {

    protected $m_sPath;
    protected $m_sTemplatePath;
    protected $m_data = array();

    /**
     *
     * @param sName
     */
    public function __construct($sPath) {
        $sClassName =  get_class($this);
        $sModuleName = str_replace("View", '', $sClassName);
        $this->m_sPath = APPROOT.'modules'.DIRECTORY_SEPARATOR.strtolower($sModuleName).DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$sPath;
    }

    /**
     *
     * @param sKey
     */
    public function __get($sKey) {
        if (isset($this->m_data[$sKey]))
            return $this->m_data[$sKey];
    }

    public function render() {

        include $this->m_sPath.".php";
    }

    /**
     *
     * @param sKey
     * @param value
     */
    public function __set($sKey, $value) {
        $this->m_data[$sKey] = $value;
    }

    function setTemplatePath($sTemplatePath) {
        $this->m_sTemplatePath = $sTemplatePath;
    }

}

?>