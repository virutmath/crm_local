<?
require_once '../../resources/security/security.php';
$module_id	= 15;
$module_name = '';
checkAccessModule($module_id);
checkLogged();
$bg_errorMsg = '';
$bg_table = '';
$id_field = '';
$cat_field = '';
$cat_type = 'supplier';
$cat_table = 'categories_multi';
$today = time();
$formDate = gmdate("d/m/Y", $today - 2592000);
$toDate = gmdate("d/m/Y", $today);
$modal_title = array(
    'loadFormAddCategory'=>'',
    'loadFormEditCategory'=>'',
    'loadFormAddRecord'=>'',
    'loadFormEditRecord'=>''
);