<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class Mailer extends Service {

    private static $sSender = "";
    private static $sSubject = "";
    private static $sMessage = "";
    private static $sHeaders = "";
    private static $sHash = "";
    private static $bHasAttachement = false;
    private static $addresses = array();
    

    static function setSender($sSenderEmail) {
        self::$sSender = $sSenderEmail;
    }

    static function setSubject($sSubject) {
        self::$sSubject = $sSubject;
    }

    static function setHeaders($sHeaders) {
        self::$sHeaders = $sHeaders;
    }

    static function addHeader($sHeader) {
        self::$sHeaders .= $sHeader . "\r\n";
    }

    static function setMessage($sMessage) {
        self::$sMessage = $sMessage;
    }

    static function addAttachement($sFileName, $sMimeType, $sAttachementName = '') {
        $data = chunk_split(base64_encode(file_get_contents($sFileName))); 
        
        self::$sMessage .= "\n\n--___" . self::$sHash . "==" ."\r\n";
        self::$sMessage .= "Content-Type: " . $sMimeType . "; name=\"" . $sAttachementName . "\"" ."\r\n";
        self::$sMessage .= "Content-Transfer-Encoding: base64" ."\r\n";
        self::$sMessage .= "Content-Disposition: attachment; filename=\"" . $sAttachementName . "\"" ."\r\n\n";
        self::$sMessage .= $data;
    }
    static function addMessage($sMessage) {
        if(self::$bHasAttachement) {
        self::$sMessage .= "--___" . self::$sHash . "==\n";
        self::$sMessage .= "Content-Type: text/html; charset=\"utf-8\"" ."\r\n";
        self::$sMessage .= "Content-Transfer-Encoding: 8bit" ."\r\n\n";
        self::$sMessage .= $sMessage ."\r\n\n";
        } else {
            self::$sMessage .= $sMessage;
        }
    }
    
    static function addAddress($sEmailAddress) {
        self::$addresses []= $sEmailAddress;
    }

    static function initDefaultHeaders() {
        self::$sHeaders = "MIME-Version: 1.0" . "\r\n";
        self::$sHeaders .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        self::$sHeaders .= "From: <" . self::$sSender . ">\r\n";
        self::$sHeaders .= "Reply-To: <" . self::$sSender . ">\r\n";
    }
    
    static function initAttachementHeaders() {
        self::$sHash = md5(time());
        self::$sHeaders = "X-Mailer: " . 'xMailer' . "\r\n";
        self::$sHeaders .= "From: <" . self::$sSender . ">\r\n";
        self::$sHeaders .= "Reply-To: <" . self::$sSender . ">\r\n";
        self::$sHeaders .= "MIME-Version: 1.0" . "\r\n";
        self::$sHeaders .= "Content-Type: multipart/mixed;" . "\r\n";
        self::$sHeaders .= "\tboundary=\"___" . self::$sHash . "==\"" . "\r\n\n";
        
        self::$bHasAttachement = true;
        
    }

    static function mail($sEmailAdress = '', $sMessage = '', $sSubject = '') {
        $bSuccess = true;

        if ($sMessage == '')
            $sMessage = self::$sMessage;

        if ($sSubject == '')
            $sSubject = self::$sSubject;

        if ($sEmailAdress != '') {
            $bSuccess = mail($sEmailAdress, $sSubject, $sMessage, self::$sHeaders);
        } else {
            foreach (self::$addresses as $sEmailAdress) {
                $bSuccess = mail($sEmailAdress, $sSubject, $sMessage, self::$sHeaders);
            }
        }

        return $bSuccess;
    }

}

?>
