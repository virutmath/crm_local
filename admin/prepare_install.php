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
ob_clean();
truncate_database();
import_server_config();