<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class SearchEngine {
    public function search($manifestList, $sText) {
        $result = array();
        foreach($manifestList as $manifest) {
            $result = array_merge($result, $manifest->search($sText));
        }
        return $result;
        
    }
}

?>
