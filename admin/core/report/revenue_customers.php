<?
require_once 'inc_security.php';
//Phần xử lý
$action_modal    = getValue('action_modal','str','POST','',2);
$action          = getValue('action','str','POST','',2);
if($action == 'execute') {
    switch ($action_modal) {

    }
}

//Phần hiển thị
//Khởi tạo
$left_control       = '';
$right_control      = '';
$footer_control     = '';
$left_column        = '';
$right_column       = '';
$top_control        = '';
$list_store         = '';

//lấy ra tất cả kho hàng

$db_store = new db_query('SELECT * FROM categories_multi WHERE cat_type = "stores"');
while($row_store = mysqli_fetch_assoc($db_store->result)){
    $list_store .= '<option value="'.$row_store['cat_id'].'">'.$row_store['cat_name'].'</option>';
}unset($db_store);
// phần top_control điều khiển trên đầu
$top_control = '
    <div class="control_right">
        <span class="fl pull_span"> Thời gian:</span>
        <label>
        <input class="form-control datetime-local input_date fl" value="'.date('d/m/Y',time() - 86400*30).'" id="start_date" type="text">
        <i class="fa fa-arrow-right fl pull_span"></i>
        <input class="form-control datetime-local input_date fl" value="'.date('d/m/Y').'" id="end_date" type="text">
        &nbsp;
        </label>
        <button class="btn btn-success" onclick="fillCustomers()"><i class="fa fa-check-circle-o"></i> Lọc dữ liệu </button>
        <button class="btn btn-danger"><i class="fa fa-file-excel-o"></i> Xuất excel </button>

    </div>
    <div class="clearfix"></div>
';



// phấn menu left
//lấy ra danh mục
$bg_table = "customers";

$list_category = array();
$db_cat_customer = new db_query('SELECT * FROM customer_cat');
$list_category = $db_cat_customer->resultArray();
unset($db_cat_customer);


$db_count = new db_count('SELECT count(*) as count FROM ' . $bg_table);
unset($db_count);
ob_start();
?>
    <ul id="tree" class="list_category">
        <li>
            <label><input type="checkbox" name="all_customers" id="chk_all"> <b>Tất cả</b> </label>
        </li>

        <li class="cat_parent list-vertical-item" >

        <?
        //foreach lại 1 lần nữa trong mảng categoy để lấy ra các category con của cat cha hiện tại
        foreach ($list_category as $cat_child) {?>
            <li class="cat_item list-vertical-item" data-cat="<?= $cat_child['cus_cat_id'] ?>" data-parent="<?= $cat_child['cus_cat_id']?>">
                <i class="fa fa-minus-square-o collapse-li"></i>
                <label>
                    <input type="checkbox" class="group_product" data-cat="<?= $cat_child['cus_cat_id'] ?>" name="pro_item">
                    <span><?= $cat_child['cus_cat_name'] ?></span>
                </label>
                <ul class="item_pro">
                    <?
                    // lấy ra những product thuộc các group_cat
                    $db_customers = new db_query('SELECT cus_id,cus_name,cus_cat_id FROM customers WHERE cus_cat_id = '.$cat_child['cus_cat_id'].'');
                    while($row_customer = mysqli_fetch_assoc($db_customers->result)){?>
                        <li class="product_item" data-cat_pro="<?=$cat_child['cus_cat_id'] ?>" data-parent_pro="<?=$row_customer['cus_cat_id']?>">
                            <label>
                                <input type="checkbox" name="pro_item" class="group_product pro_item" value="<?=$row_customer['cus_id']?>" id="item_product_<?=$row_customer['cus_id']?>">
                                <span><?= $row_customer['cus_name'] ?></span>
                            </label>
                        </li>
                    <?} unset($db_customers)?>
                </ul>
            </li>
            <?
        }?>

    </ul>


<?
$left_column = ob_get_contents();
ob_clean();

$footer_control = '
<div class="total_money">
    <label>Số HĐ: <span id="total-bill" class="number_return"> 0 </span></label>
    &nbsp;&nbsp;
    <label>Doanh thu: <span id="total-money" class="number_return"> 0 </span></label>
    &nbsp;&nbsp;
    <label>Ghi có: <span id="total-round" class="number_return"> 0 </span></label>
    &nbsp;&nbsp;
    <label>Ghi nợ: <span id="total-debit" class="number_return"> 0 </span></label>
</div>';

$rainTpl = new RainTPL();
add_more_css('custom.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));
$rainTpl->assign('left_control',$left_control);
$rainTpl->assign('right_control',$right_control);
$rainTpl->assign('top_control', $top_control);
$rainTpl->assign('footer_control', $footer_control);
$rainTpl->assign('left_column',$left_column);
$rainTpl->assign('right_column',$right_column);
$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('fullwidth_2column_report_transfer');