<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');


class Search extends Service {
    
    public static function start($sText) {
        $manifestList = self::createManifestList();
        
        $searchEngine = new SearchEngine();
        $result = $searchEngine->search($manifestList, $sText);
    }
    
    public static function createManifestList() {
        $manifestList = array();
        
        foreach(self::config('clients') as $client) {
            $sModule = $client['module'];
            $sInterface = $client['interface'];
            $manifestList []= $sModule::$$sInterface->getSearchManifest();
        }
        
        return $manifestList;
    }
}

?>
