<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

/**
 * Description of builderInterface
 *
 */
class BuilderInterfaceForm {

    function build($sFormFile, $sModule) {
        if(Cache::$cache->exists('form-'.$sFormFile))
            $form = Cache::$cache->get('form-'.$sFormFile.'-'.$sModule);
        else {
            $content = file_get_contents(APPROOT.'modules/'.strtolower($sModule).'/form/'.$sFormFile.'.xml');
            $form = new SimpleXMLElement($content);
            Cache::$cache->add('form-'.$sFormFile, $form);
        }

        //Tworzenie formularza
        $sFormName = $form['name'];
        $sAction = empty($form['action']) ? ROOT : $form['action'];
        $sSendMethod = empty($form['method']) ? 'POST' : "{$form['method']}";
        $iFormType = empty($form['type']) ? NORMAL_FORM : constant("{$form['type']}");
        Form::$form->createForm("$sFormName", "$sAction", $sSendMethod, $iFormType);

        //Właściwości formularza
        if (!empty($form->target))
            Form::$form->setTarget("{$form->target}");
        if (!empty($form->id))
            Form::$form->setFormID("{$form->id}");
        if (!empty($form->class))
            Form::$form->setFormClass("{$form->class}");

        //Parametry formularza
        if (!empty($form->parameters)) {
            foreach ($form->parameters->parameter as $parameter)
                Form::$form->setFormParameter("{$parameter['name']}", "{$parameter}");
        }

        //Pola
        foreach ($form->fields->field as $field) {
            $sName = empty($field['name']) ? 'field' : $field['name'];
            $iType = empty($field['type']) ? TEXT_FIELD : constant("{$field['type']}");
            $value = empty($field['value']) ? '' : "{$field['value']}";

            if(strpos($value, "$") === 0)
                $value = call_user_func(ucfirst($sModule).'::lang', substr($value, 1));
            
            Form::$form->createField("$sName", $iType, "$value");

            //Właściwości pola
            if (!empty($field->label)) {
                if(strpos($field->label, "$") === 0)
                    $field->label = call_user_func(ucfirst($sModule).'::lang', substr($field->label, 1));
                Form::$form->setFieldLabel("$sName", "{$field->label}");
            }
            if (!empty($field->id))
                Form::$form->setFieldID("$sName", "{$field->id}");
            if (!empty($field->class))
                Form::$form->setFieldClass("$sName", "{$field->class}");

            //Parametry pola
            if (!empty($field->parameters)) {
                foreach ($field->parameters->parameter as $parameter)
                    Form::$form->setFieldParameter("$sName", "{$parameter['name']}", "{$parameter}");
            }

            //Opcje wyboru
            if (!empty($field->options)) {
                foreach ($field->options->option as $option)
                    Form::$form->setFieldOption("$sName", "{$option['name']}", $option);
            }

            //Reguły
            if (!empty($field->rules)) {
                foreach ($field->rules->rule as $rule) {
                    $sRuleName = empty($rule['name']) ? 'rule' : $rule['name'];
                    $iRuleType = empty($rule['type']) ? LENGTH_RULE : constant("{$rule['type']}");
                    
                    Form::$form->createRule("$sRuleName", $iRuleType);

                    //Typ reguły
                    switch ($iRuleType) {
                        case LENGTH_RULE:
                            Form::$form->setRuleLength("$sRuleName", "{$rule->length['min']}", "{$rule->length['max']}");
                            break;
                        case DATA_TYPE_RULE:
                            Form::$form->setRuleDataType("$sRuleName", "{$rule->type}");
                            break;
                        case DATA_FORMAT_RULE:
                            Form::$form->setRuleDataFormat("$sRuleName", constant("{$rule->format}"));
                            break;
                        case EXISTANCE_RULE:
                            Form::$form->setRuleExistanceData("$sRuleName", "{$rule->exists['table']}", "{$rule->exists['attribute']}");
                            break;
                        case EQUALITY_RULE:
                            Form::$form->setRuleFieldToCompare("$sRuleName", "{$rule->compare['field']}");
                            break;
                        default:
                            break;
                    }

                    Form::$form->addFieldRule("$sName", "$sRuleName");
  
                }
            }

            Form::$form->addField("$sName");
        }

        return "$sFormName";
    }

}

?>
