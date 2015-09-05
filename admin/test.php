<?
session_start();
error_reporting(0);
require_once("../functions/functions.php");
require_once("../classes/database.php");
require_once("../classes/rain.tpl.class.php");
require_once('resources/security/inc_constant.php');
require_once("resources/security/functions.php");
require_once("resources/security/functions_local.php");
require_once("resources/security/functions_1.php");

checkLogged('login.php');
$username = getValue('userlogin','str','SESSION','');
$password = getValue('password','str','SESSION','');


//??c file log l?y ra các query ch?a ???c synchronize
$array_query = read_logs();
$array_query = base64_encode(json_encode($array_query));
//var_dump($array_query);
//l?y config
$server_config = read_server_config();
$array_query = (json_decode(base64_decode($array_query),1));

//foreach($array_query as $time=>$list_query) {
//    foreach($list_query as $query) {
//        echo decode_combine($query) . '<Br>';
//    }
//}
echo decode_combine('NTUyOWU2YjA3NjBkNzNkMzhkM2QzYTViYjMzZTNlYWZGSDVHRUlXSFZQT1dHeUVDVlRBMXBhV3lvYUVzTVRJbW5sdXdxSkVzTVRJbW4xOWNNUGt3cUpFc0wzSW1xVDlnTUtXc25KRGZMM0l4SzNBMExKTXpLMnl4WVRBMU1TOWhvM0V5WVRBMU1TOW1xVFNscVM5MG5KMXlZVEExTVM5eXJVRWxMSTl6TUpIZkwzSXhLM011cVBrd3FKRXNMM0ltcVQ5Z01LV3NNVHltTDI5MW9hRGZMM0l4SzJFeUx6eTBZVEExTVM5akxLeXNxVXlqTUZ4dEl4U1pJSElHVlB0NFlRTmZaUGphV2xqa0FRRGtBUVZtQW1MbVlRTmZaUGpqWVFOZlpQeC0=');