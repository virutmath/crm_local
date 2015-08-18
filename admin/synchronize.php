<?
session_start();
error_reporting(E_ALL);
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


//đọc file log lấy ra các query chưa được synchronize
$array_query = read_logs();
echo json_encode($array_query);
$array_query = base64_encode(json_encode($array_query));
//lấy config
$server_config = read_server_config();
//bắn các câu query lên qua curl
$curl = curl_get_content($server_config['synchronize_url'],array('queries'=>$array_query,'action'=>'syncLogQuery','username'=>$username,'password'=>$password));
$response = json_decode($curl,1);
$db_delete = new db_execute('TRUNCATE TABLE synchronize_trigger',1,false);
$db_exe = new db_execute('INSERT INTO synchronize_trigger VALUES (' . time().')',1,false);