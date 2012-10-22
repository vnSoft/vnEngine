<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class Paginator extends Service {

    static function render($sLink, $pager) {
        if ($sLink[strlen($sLink) - 1] == '/')
            $sLink = substr($sLink, 0, strlen($sLink) - 1);

        $view = new PaginatorView('pages');
        $view->sLink = $sLink;
        if ($pager instanceof CPager)
            $view->iPage = $pager->getPageNumber();
        else
            $view->iPage = 1;
        if ($pager instanceof CPager)
            $view->iPageCount = $pager->getPageCount();
        else
            $view->iPageCount = 1;

        ob_start();
        $view->render();
        $paginator = ob_get_contents();
        ;
        ob_end_clean();
        return $paginator;
    }

}

?>
