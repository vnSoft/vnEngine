<?php

/**
 * @author Bartek
 * @version 1.0
 * @created 11-mar-2012 18:12:05
 */
class Form extends SystemModule {
    public static $form;
    public static $builder;
    public static $processer;

    public static function init() {
        parent::init();
        
        self::$form = new FormInterfaceForm();
        self::$builder = new BuilderInterfaceForm();
        self::$processer = new ProcesserInterfaceForm();
    }
    

}

?>