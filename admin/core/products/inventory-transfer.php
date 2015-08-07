<?
require_once 'inc_security.php';
//Phần này được tùy biến riêng
//Giao diện quản lý quỹ tiền sẽ gồm 2 danh sách, 2 phần left column và right column có kích thước bằng nhau
//file template fullwidth_half.html

//Phần xử lý
$action_modal = getValue('action_modal','str','POST','',2);
$action = getValue('action','str','POST','',2);
if($action == 'execute') {
    switch ($action_modal) {
        case 'add_money_ticket_in' :
            checkPermission('add');
            //Thời gian tạo phiếu lấy từ thời gian hệ thống
            $fin_date = time();
            $fin_admin_id = $admin_id;
            $myform = new generate_form();
            $myform->addTable($bg_table);
            $myform->add('fin_date', 'fin_date', 1, 1, $fin_date, 0);
            $myform->add('fin_updated_time', 'fin_date', 1, 1, $fin_date, 0);
            $myform->add('fin_money', 'fin_money', 1, 0, 0, 0);
            $myform->add('fin_pay_type', 'fin_pay_type', 1, 0, PAY_TYPE_CASH);
            $myform->add('fin_cat_id', 'fin_cat_id', 1, 0, 0, 1, 'Bạn chưa chọn loại lý do thu');
            $myform->add('fin_reason_other', 'fin_reason_other', 0, 0, '');
            $myform->add('fin_billcode', 'fin_billcode', 0, 0, '');
            $myform->add('fin_username', 'fin_username', 0, 0, '', 1, 'Bạn chưa nhập tên người nộp tiền');
            $myform->add('fin_address', 'fin_address', 0, 0, '', 1, 'Bạn chưa nhập địa chỉ người nộp tiền');
            $myform->add('fin_note', 'fin_note', 0, 0, '');
            $myform->add('fin_admin_id', 'fin_admin_id', 1, 1, 0, 1, 'Bạn chưa đăng nhập');
            $bg_errorMsg .= $myform->checkdata();
            if (!$bg_errorMsg) {
                $db = new db_execute_return();
                $last_id = $db->db_execute($myform->generate_insert_SQL());
                unset($db);
                //log action
                log_action(ACTION_LOG_ADD, 'Thêm mới phiếu thu ' . $last_id . ' bảng ' . $bg_table);
                redirect('index.php');
            }
            break;

        case 'edit_money_ticket_in' :
            checkPermission('edit');
            //Không thay đổi thời gian tạo, cập nhật thêm thời gian updated
            $fin_updated = time();
            $record_id = getValue('record_id', 'int', 'POST', 0);
            $myform = new generate_form();
            $myform->addTable($bg_table);
            $myform->add('fin_updated_time', 'fin_updated_time', 1, 1, $fin_updated, 0);
            $myform->add('fin_money', 'fin_money', 1, 0, 0, 0);
            $myform->add('fin_pay_type', 'fin_pay_type', 1, 0, PAY_TYPE_CASH);
            $myform->add('fin_cat_id', 'fin_cat_id', 1, 0, 0, 1, 'Bạn chưa chọn loại lý do thu');
            $myform->add('fin_reason_other', 'fin_reason_other', 0, 0, '');
            $myform->add('fin_billcode', 'fin_billcode', 0, 0, '');
            $myform->add('fin_username', 'fin_username', 0, 0, '', 1, 'Bạn chưa nhập tên người nộp tiền');
            $myform->add('fin_address', 'fin_address', 0, 0, '', 1, 'Bạn chưa nhập địa chỉ người nộp tiền');
            $myform->add('fin_note', 'fin_note', 0, 0, '');
            $myform->add('fin_admin_id', 'fin_admin_id', 1, 1, 0, 1, 'Bạn chưa đăng nhập');
            $bg_errorMsg .= $myform->checkdata();
            if (!$bg_errorMsg) {
                $db = new db_execute($myform->generate_update_SQL('fin_id', $record_id));
                unset($db);
                //log action
                log_action(ACTION_LOG_ADD, 'Chỉnh sửa phiếu thu ' . $record_id . ' bảng ' . $bg_table);
                redirect('index.php');
            }
            break;
        case 'add_money_ticket_out' :
            checkPermission('add');
            //Thời gian tạo phiếu lấy từ thời gian hệ thống
            $fin_date = time();
            $fin_admin_id = $admin_id;
            $myform = new generate_form();
            $myform->addTable($bg_table);
            $myform->add('fin_date', 'fin_date', 1, 1, $fin_date, 0);
            $myform->add('fin_updated_time', 'fin_date', 1, 1, $fin_date, 0);
            $myform->add('fin_money', 'fin_money', 1, 0, 0, 0);
            $myform->add('fin_pay_type', 'fin_pay_type', 1, 0, PAY_TYPE_CASH);
            $myform->add('fin_cat_id', 'fin_cat_id', 1, 0, 0, 1, 'Bạn chưa chọn loại lý do chi');
            $myform->add('fin_reason_other', 'fin_reason_other', 0, 0, '');
            $myform->add('fin_billcode', 'fin_billcode', 0, 0, '');
            $myform->add('fin_username', 'fin_username', 0, 0, '', 1, 'Bạn chưa nhập tên người nhận tiền');
            $myform->add('fin_address', 'fin_address', 0, 0, '', 1, 'Bạn chưa nhập địa chỉ người nhận tiền');
            $myform->add('fin_note', 'fin_note', 0, 0, '');
            $myform->add('fin_admin_id', 'fin_admin_id', 1, 1, 0, 1, 'Bạn chưa đăng nhập');
            $bg_errorMsg .= $myform->checkdata();
            if (!$bg_errorMsg) {
                $db = new db_execute_return();
                $last_id = $db->db_execute($myform->generate_insert_SQL());
                unset($db);
                //log action
                log_action(ACTION_LOG_ADD, 'Thêm mới phiếu chi ' . $last_id . ' bảng ' . $bg_table);
                redirect('index.php');
            }
            break;

        case 'edit_money_ticket_out' :
            checkPermission('edit');
            //Không thay đổi thời gian tạo, cập nhật thêm thời gian updated
            $fin_updated = time();
            $record_id = getValue('record_id', 'int', 'POST', 0);
            $myform = new generate_form();
            $myform->addTable($bg_table);
            $myform->add('fin_updated_time', 'fin_updated_time', 1, 1, $fin_updated, 0);
            $myform->add('fin_money', 'fin_money', 1, 0, 0, 0);
            $myform->add('fin_pay_type', 'fin_pay_type', 1, 0, PAY_TYPE_CASH);
            $myform->add('fin_cat_id', 'fin_cat_id', 1, 0, 0, 1, 'Bạn chưa chọn loại lý do chi');
            $myform->add('fin_reason_other', 'fin_reason_other', 0, 0, '');
            $myform->add('fin_billcode', 'fin_billcode', 0, 0, '');
            $myform->add('fin_username', 'fin_username', 0, 0, '', 1, 'Bạn chưa nhập tên người nộp tiền');
            $myform->add('fin_address', 'fin_address', 0, 0, '', 1, 'Bạn chưa nhập địa chỉ người nộp tiền');
            $myform->add('fin_note', 'fin_note', 0, 0, '');
            $myform->add('fin_admin_id', 'fin_admin_id', 1, 1, 0, 1, 'Bạn chưa đăng nhập');
            $bg_errorMsg .= $myform->checkdata();
            if (!$bg_errorMsg) {
                $db = new db_execute($myform->generate_update_SQL('fin_id', $record_id));
                unset($db);
                //log action
                log_action(ACTION_LOG_ADD, 'Chỉnh sửa phiếu chi ' . $record_id . ' bảng ' . $bg_table);
                redirect('index.php');
            }
            break;
    }
}


//Phần hiển thị
//Khởi tạo
$left_control = '';
$right_control = '';
$footer_control = '';

$left_column = '';
$right_column = '';
$left_column_title = 'Danh sách phiếu kiểm kê kho hàng';
$right_column_title = 'Danh sách phiếu chuyển kho hàng';
$context_menu = '';


$add_btn = getPermissionValue('add');
$edit_btn = getPermissionValue('edit');
$trash_btn = getPermissionValue('trash');
//control button trái
$left_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);

//Thêm nút cài đặt và thùng rác bên phải của danh sách
$left_control .= '<div class="control-table-listing top_right_control pull-right">
    <span class="control-btn"><i class="fa fa-cog"></i> Cài đặt</span>
    <span class="control-btn control-list-trash" onclick="list_trash(\'in\')"><i class="fa fa-recycle"></i> Thùng rác</span>
</div>';
//Hiển thị danh sách phiếu thu bên trái
#Bắt đầu với datagird
$list = new dataGrid($id_field,30);
$list->add('', 'Ngày thu');
$list->add('','Số phiếu');
$list->add('','Người nhận');
$list->add('','Diễn giải');
$list->add('','Số tiền');



//Khối bên phải, hiển thị danh sách phiếu chi
$right_control = list_admin_control_button($add_btn,$edit_btn,$trash_btn,1);
//Thêm nút cài đặt và thùng rác bên phải của danh sách
$right_control .= '<div class="control-table-listing top_right_control pull-right">
    <span class="control-btn"><i class="fa fa-cog"></i> Cài đặt</span>
    <span class="control-btn control-list-trash" onclick="list_trash(\'out\')"><i class="fa fa-recycle"></i> Thùng rác</span>
</div>';
#Bắt đầu với datagrid
$list = new dataGrid($id_field,30);
$list->add('','Ngày chi');
$list->add('','Số phiếu');
$list->add('','Người nhận');
$list->add('','Diễn giải');
$list->add('','Số tiền');




//footer control
//Phần bộ lọc của phiếu thu
$footer_control .=
    '<form class="form-inline col-xs-6" action="index.php" method="post">
        <input type="hidden" value="filterMoneyIn" name="action" />
        <div class="form-group text-center col-xs-5">
            <label class="">Từ</label>
            <input type="text" class="form-control input-date" placeholder="Từ ngày" datepick-element="1" name="start_date_in" value=""/>
            &nbsp;&nbsp;
            <label class="">Đến</label>
            <input type="text" class="form-control input-date" placeholder="Đến ngày" datepick-element="1" name="end_date_in" value=""/>
        </div>
        <div class="form-group text-center col-xs-4">
            <label class="">Nhân viên:</label>
            <select class="form-control">
                <option value="1">Nguyễn Hữu Công</option>
                <option value="2">Nguyễn Hữu Công1</option>
                <option value="3">Nguyễn Hữu Công2</option>
                <option value="4">Nguyễn Hữu Công3</option>
            </select>
        </div>
        <div class="form-group col-xs-3 text-center">
            <button class="btn btn-success footer-submit"><i class="fa fa-filter"></i> Lọc dữ liệu</button>
        </div>
    </form>';



//Phần hiển thị bộ lọc của phiếu chi
$footer_control .=
    '<form class="form-inline col-xs-6" action="index.php" method="post">
        <input type="hidden" name="action" value="filterMoneyOut"/>
        <div class="form-group text-center col-xs-5">
            <label class="">Từ</label>
            <input type="text" class="form-control input-date" placeholder="Từ ngày" datepick-element="1" name="start_date_out" value=""/>
            &nbsp;&nbsp;
            <label class="">Đến</label>
            <input type="text" class="form-control input-date" placeholder="Đến ngày" datepick-element="1" name="end_date_out" value=""/>
        </div>
        <div class="form-group text-center col-xs-4">
            <label class="">Nhân viên</label>
            <select class="form-control">
                <option value="333" selected="selected">Tất cả</option>
                <option value="1">Nguyễn Hữu Công</option>
                <option value="2">Nguyễn Hữu Công1</option>
                <option value="3">Nguyễn Hữu Công2</option>
                <option value="4">Nguyễn Hữu Công3</option>
            </select>
        </div>
        <div class="form-group col-xs-2 text-center">
            <button class="btn btn-success footer-submit"><i class="fa fa-filter"></i> Lọc dữ liệu</button>
        </div>
    </form>';
$footer_control .='
    <div class="clearfix"></div>
    <div class="button_tab">
        <ul>
            <li><a href="http://localhost:1236/admin/core/products/index.php">DANH SÁCH MẶT HÀNG</a></li>
            <li><a href="#">KIỂM KÊ - CHUYỂN KHO</a></li>
        </ul>
    </div>
';
if($isAjaxRequest) {
    $action = getValue('action', 'str','POST','');
    if($action == 'filterMoneyIn') {
        //lọc phiếu thu
        echo $left_column;
        die();
    }
    if($action == 'filterMoneyOut') {
        echo $right_column;
        die();
    }
}

$rainTpl = new RainTPL();
add_more_css('custom.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('error_msg',print_error_msg($bg_errorMsg));

$rainTpl->assign('left_control',$left_control);
$rainTpl->assign('right_control',$right_control);
$rainTpl->assign('footer_control', $footer_control);

$rainTpl->assign('left_column',$left_column);
$rainTpl->assign('right_column',$right_column);

$rainTpl->assign('left_column_title',$left_column_title);
$rainTpl->assign('right_column_title',$right_column_title);
$custom_script = file_get_contents('script_half.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('fullwidth_half');