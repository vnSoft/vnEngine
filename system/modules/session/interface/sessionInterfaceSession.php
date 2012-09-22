<?php

/**
 * @author Rudzki
 * @version 1.0
 * @created 17-lut-2012 13:33:49
 */
class SessionInterfaceSession {

    /**
     * iTimeout defines living time of session in minutes, bIP and bBrowser defines
     * whether verification by IP and Browser is performed
     */
    public function start($iTimeout = null, $bIP = null, $bBrowser = null) {
        if ($iTimeout == null)
            $iTimeout = Session::$config['timeout'];

        if ($bIP == null)
            $bIP = Session::$config['IP'];

        if ($bBrowser == null)
            $bBrowser = Session::$config['browser'];
        
        $session = new SessionModelSession();
        $session->start($iTimeout, $bIP, $bBrowser);
    }

    public function end() {
        $session = new SessionModelSession();
        $session->end();
    }

    public function destroy() {
        $session = new SessionModelSession();
        $session->destroy();
    }

    public function regenerateID(){
        $session = new SessionModelSession();
        $session->regenerateID();
    }
    public function get($sKey) {
        $session = new SessionModelSession();
        return $session->get($sKey);
    }

    public function set($sKey, $value) {
        $session = new SessionModelSession();
        $session->set($sKey, $value);
    }

    public function isVarSet($sKey) {
        $session = new SessionModelSession();
        return $session->isVarSet($sKey);
    }

    public function delete($sKey) {
        $session = new SessionModelSession();
        $session->delete($sKey);
    }

    public function getBrowser(){
        $sessionModel = new SessionModelSession();
        return $sessionModel->getBrowser();
    }
}

?>