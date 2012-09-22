<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');


class PageControllerPage extends Controller {
    
    public function defaultAction() {
        Search::start("text");
    }
}

?>
