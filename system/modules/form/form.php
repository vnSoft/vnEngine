<?php

/**
 * @author Bartek
 * @version 1.0
 * @created 11-mar-2012 18:12:05
 */
class Form implements SystemModule {
    public static $form;
    public static $builder;

    public static function init() {
        require_once('model/ruleModel.php');
        require_once('model/fieldModel.php');
        require_once('model/formModel.php');
        require_once('model/constants.php');
        require_once('interface/formInterface.php');
        require_once('interface/builderInterface.php');
        self::$form = new FormInterfaceForm();
        self::$builder = new BuilderInterfaceForm();
    }
    

}

?>