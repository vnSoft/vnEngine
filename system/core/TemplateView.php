<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');


class TemplateView extends View {
    
    protected static $m_sTemplateName;
    
    public function __construct($sPath) {
        $this->m_sPath = APPROOT.'templates'.DIRECTORY_SEPARATOR.self::$m_sTemplateName.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$sPath;
    }
    
    public static function setTemplateName($sTemplateName) {
        self::$m_sTemplateName = $sTemplateName;
    }
}

?>
