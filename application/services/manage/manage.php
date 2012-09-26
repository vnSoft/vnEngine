<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');


class Manage extends Service {
    
    public static function add($manageManifest) {
        if($manageManifest instanceof AddManifest)
            $manageManifest->process();
    }
    
    public static function edit($manageManifest) {
        if($manageManifest instanceof EditManifest)
            $manageManifest->process();
    }
}

?>
