<?php defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class TemplateError extends Template {
    protected $m_sMessage = '';

    public function setErrorMessage($sMessage) {
        $this->m_sMessage = $sMessage;
    }
}
?>
