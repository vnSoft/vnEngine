<?php

$map = new CTranslatorMap();

$map->addExactRoute('kontakt', 'page/page/add');
$map->addRoute("strona/[NUMBER]", "page/page/show/id/[1]");

$config = array(
    'default_module' => 'page',
    'map' => $map
);
?>
