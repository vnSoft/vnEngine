<?php

interface ApplicationModule {

    public static function init();

    public static function defaultAction(Request $request);

    public static function getDefaultController(Request $request);

}

?>
