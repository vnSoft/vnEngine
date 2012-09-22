<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class PageInterfacePage implements Searchable{
    
    public function getSearchManifest() {
        return new SearchManifest('page', 'page', 'search', 'page', array('name', 'content'));
    }
}

?>
