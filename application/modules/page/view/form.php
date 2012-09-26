<?php
$form = Form::$form;

$form->beginFormRender();
$form->renderField('name');
$form->renderField('file');
$form->renderField('send');
$form->finishFormRender();
?>
