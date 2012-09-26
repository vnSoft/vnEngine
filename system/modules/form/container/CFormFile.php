<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class CFormFile {
    private $m_sMime;
    private $m_sExtension;
    private $m_sPath;
    private $m_bUploaded;
    private $m_iSize;
    
    public function __construct($sPath = '', $sExtension = '', $sMime = '', $iSize = 0, $bUploaded = false) {
        $this->m_sMime = $sMime;
        $this->m_sExtension = $sExtension;
        $this->m_sPath = $sPath;
        $this->m_iSize = $iSize;
        $this->m_bUploaded = $bUploaded;
    }
    
    public function getMime() {
        return $this->m_sMime;
    }

    public function setMime($sMime) {
        $this->m_sMime = $sMime;
    }

    public function getExtension() {
        return $this->m_sExtension;
    }

    public function setExtension($sExtension) {
        $this->m_sExtension = $sExtension;
    }

    public function getPath() {
        return $this->m_sPath;
    }

    public function setPath($sPath) {
        $this->m_sPath = $sPath;
    }
    
    public function getSize() {
        return $this->m_iSize;
    }

    public function setSize($iSize) {
        $this->m_iSize = $iSize;
    }

    public function isUploaded() {
        return $this->m_bUploaded;
    }

    public function setUploaded($bUploaded) {
        $this->m_bUploaded = $bUploaded;
    }


}

?>
