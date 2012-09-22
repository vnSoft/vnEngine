<?php

// form
define("NORMAL_FORM", 0);
define("FILE_FORM", 1);

// field
define("TEXT_FIELD", 2);
define("PASSWORD_FIELD", 3);
define("SELECT_FIELD", 4);
define("TEXTAREA_FIELD", 5);
define("CHECKBOX_FIELD", 6);
define("RADIOBUTTON_FIELD", 7);
define("FILE_FIELD", 8);
define("HIDDEN_FIELD", 9);
define("BUTTON_FIELD", 10);
define("SUBMIT_FIELD", 11);
define("CHECKBOX_ARRAY_FIELD", 12);

define("MAX_FIELD_TYPE", CHECKBOX_ARRAY_FIELD);
define("MIN_FIELD_TYPE", TEXT_FIELD);

// rule
define("LENGTH_RULE", 11);
define("DATA_TYPE_RULE", 12);
define("DATA_FORMAT_RULE", 13);
define("EXISTANCE_RULE", 14);
define("EQUALITY_RULE", 15);

define("MIN_RULE_TYPE", LENGTH_RULE);
define("MAX_RULE_TYPE", EQUALITY_RULE);

define("DIGIT_DATA_TYPE", 16);
define("ALNUM_DATA_TYPE", 17);
define("ALPHA_DATA_TYPE", 18);
define("EMAIL_DATA_TYPE", 19);
define("SAFE_HTML_DATA_TYPE", 20);
define("TEXT_DATA_TYPE", 21);
define("DATE_DATA_TYPE", 22);

define("MIN_DATA_TYPE", DIGIT_DATA_TYPE);
define("MAX_DATA_TYPE", DATE_DATA_TYPE);
?>
