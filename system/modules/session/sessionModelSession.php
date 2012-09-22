<?php

/**
 * @author Rudzki
 * @version 1.0
 * @created 17-lut-2012 13:33:49
 */
class SessionModelSession extends Model {

    public function __construct() {
        
    }

    public function start($iTimeout, $bIP, $bBrowser) {
        session_set_cookie_params($iTimeout * 60);
        session_start();

        if (!$this->isValid($iTimeout, $bIP, $bBrowser))
            $this->clear();

        $_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['browser'] = $this->getBrowser();
        $_SESSION['timeout'] = time();
    }

    public function end() {
        session_write_close();
    }

    public function clear() {
        session_unset();
    }

    public function destroy() {
        session_destroy();
    }

    public function get($sKey) {
        if (isset($_SESSION[$sKey]))
            return $_SESSION[$sKey];
    }

    public function set($sKey, $value) {
        $_SESSION[$sKey] = $value;
    }

    public function isVarSet($sKey) {
        return isset($_SESSION[$sKey]);
    }

    public function delete($sKey) {
        unset($_SESSION[$sKey]);
    }
    public function regenerateID(){
        session_regenerate_id();
    }

    private function isValid($iTimeout, $bIP, $bBrowser) {
        $bValid = true;

        if ($bIP) {
            if (isset($_SESSION['IP'])) {
                if ($_SESSION['IP'] != $_SERVER['REMOTE_ADDR'])
                    $bValid = false;
            } else
                $bValid = false;
        }

        if ($bBrowser AND $bValid) {
            if (isset($_SESSION['browser'])) {
                if ($_SESSION['browser'] != $this->getBrowser())
                    $bValid = false;
            } else
                $bValid = false;
        }

        if ($iTimeout > 0 AND $bValid) {
            if (isset($_SESSION['timeout'])) {
                $now = time();
                if ($now - $_SESSION['timeout'] > $iTimeout * 60)
                    $bValid = false;
            } else
                $bValid = false;
        }

        return $bValid;
    }

    public function getBrowser() {
        $browser = '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        if (preg_match('/MSIE/i', $userAgent))
            $browser = "Internet Explorer";
        elseif (preg_match('/Firefox/i', $userAgent))
            $browser = "Mozilla Firefox";
        elseif (preg_match('/Chrome/i', $userAgent))
            $browser = "Google Chrome";
        elseif (preg_match('/Safari/i', $userAgent))
            $browser = "Apple Safari";
        elseif (preg_match('/Flock/i', $userAgent))
            $browser = "Flock";
        elseif (preg_match('/Opera/i', $userAgent))
            $browser = "Opera";
        elseif (preg_match('/Netscape/i', $userAgent))
            $browser = "Netscape";

        return $browser;
    }

}

?>