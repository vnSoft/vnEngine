<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class Translator extends Service {

    static function defaultAction() {
        $request = Router::getInstance()->getCurrentRequest();
        $sPath = $request->getPath();
        
        if(empty($sPath))
            call_user_func(ucfirst(self::config('default_module')) . '::defaultAction', $request);
        else {
            $newRequest = self::config('map')->processExactRoute($sPath);
            if($newRequest === false) {
                $newRequest = self::config('map')->processRoute($sPath);
                if($newRequest === false)
                    call_user_func(ucfirst(self::config('default_module')) . '::defaultAction', $request);
                else 
                    Router::getInstance ()->process($newRequest);
            } else
                Router::getInstance ()->process($newRequest);
        }
        
    }
}

?>
