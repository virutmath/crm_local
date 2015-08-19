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
$top_control    = '';
$content_column = '';
$footer_control = '';




$content_column = '
<div class="table-listing-bound">
    <table class="table table-bordered table-hover table-listing" id="table-listing-right">
        <thead>
            <tr>
                <th width="32px;">STT</th>
                <th><strong>Thời gian</strong> </th>
                <th><strong>Bàn</strong> </th>
                <th><strong>Khách</strong> </th>
                <th><strong>Số HĐ</strong> </th>
                <th><strong>Đồ ăn</strong> </th>
                <th><strong>Cộng trước giảm</strong> </th>
                <th><strong>Giảm giá</strong> </th>
                <th><strong>Phí DV</strong> </th>
                <th><strong>VAT</strong> </th>
                <th><strong>Tổng</strong> </th>
                <th><strong>Tiền mặt</strong> </th>
                <th><strong>Thẻ</strong> </th>
                <th><strong>Ghi nợ</strong> </th>
                <th><strong>Ghi chú</strong> </th>
            </tr>
        </thead>
        <tbody>
            <tr class="footer">
                <td colspan="15"><span class="fl nowrap">Hiển thị 0/0 dòng</span><div class="fr show-all-page"></div></td>
            </tr>
        </tbody>
    </table>
</div>
';




//lấy ra tất cả kho hàng
$list_store = '';
$db_store = new db_query('SELECT * FROM categories_multi WHERE cat_type = "stores"');
while($row_store = mysqli_fetch_assoc($db_store->result)){
    $list_store .= '<option value="'.$row_store['cat_id'].'">'.$row_store['cat_name'].'</option>';
}unset($db_store);
/* Danh sách các quản lý thu ngân*/
$list_user = '';
$db_user = new db_query('SELECT * FROM admin_users');
while($row_user = mysqli_fetch_assoc($db_user->result)){
    $list_user .= '<option value="'.$row_user['adm_id'].'">'.$row_user['adm_name'].'</option>';
}unset($db_user);

$top_control .='
    <div class="control_right">
        <span class="fl pull_span"> Thời gian:</span>
        <input class="form-control datetime-local input_date fl" value="'.date('d/m/Y',time() - 86400*30).'" id="start_date" type="text">
        <i class="fa fa-arrow-right fl pull_span"></i>
        <input class="form-control datetime-local input_date fl" value="'.date('d/m/Y').'" id="end_date" type="text">
        <span class="fl pull_span"> Kho hàng:</span>
        <label><select class="form-control list_store" id="store_id" >
                    '.$list_store.'
                </select>
        </label>
        <label><select class="form-control list_store" id="admin_id" >
                    '.$list_user.'
                </select>
        </label>
        <button class="btn btn-success" onclick="fillData()"><i class="fa fa-check-circle-o"></i> Lọc dữ liệu </button>
        <button class="btn btn-danger"><i class="fa fa-file-excel-o"></i> Xuất excel </button>
    </div>
';

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
$rainTpl->assign('content_column',$content_column);
$rainTpl->assign('top_control', $top_control);
$rainTpl->assign('footer_control', $footer_control);
$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('report_1column');