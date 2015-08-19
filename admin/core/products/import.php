<?
require_once 'inc_security.php';
//kiểm tra quyền nhập hàng
checkCustomPermission('NHAP_HANG');
//Đếm tất cả số thực đơn đang có
$db_count = new db_count('SELECT count(*) AS count FROM products');
$all_count = $db_count->total;
unset($db_count);
//Lấy danh mục thực đơn
$pro_cat_id = array('' => 'Tất cả (' . $all_count . ')');
$db_query = new db_query('SELECT * FROM categories_multi WHERE cat_type = "products"');
while ($row = mysqli_fetch_assoc($db_query->result)) {
    //đếm số thực đơn trong cate này
    $db_count = new db_count('SELECT count(*) AS count FROM products WHERE pro_cat_id = ' . $row['cat_id']);
    $pro_count = $db_count->total;
    unset($db_count);
    $pro_cat_id[$row['cat_id']] = $row['cat_name'] . ' (' . $pro_count . ')';
}

$mindow_title = 'Nhập hàng vào kho';
$left_column = '';
$right_column = '';

$left_column .= '
<div class="text-center section-title">Danh sách mặt hàng</div>
<div id="mindow-listing-product">';

//Danh sách mặt hàng
$listing_product = '';
$list = new dataGrid('pro_id', $listing_product_size, '#mindow-listing-product');
$list->add('pro_name', 'Tên mặt hàng', 'string', 1, 0);
$list->add('', 'ĐVT');
$list->add('', 'Giá TB');
$list->addSearch('', 'pro_cat_id', 'array', $pro_cat_id, getValue('pro_cat_id'));
$sql_search = '';
$search_cat_id = getValue('pro_cat_id');
if ($search_cat_id) {
    $sql_search .= ' AND pro_cat_id = ' . $search_cat_id . ' ';
}
$db_count = new db_count('SELECT count(*) AS count
                          FROM products
                          WHERE 1 ' . $list->sqlSearch() . $sql_search);
$total = $db_count->total;
unset($db_count);

$db_listing = new db_query('SELECT *
                            FROM products
                            WHERE 1 ' . $list->sqlSearch() . $sql_search . '
                            ORDER BY ' . $list->sqlSort() . ' pro_id ASC
                            ' . $list->limit($total));
$total_row = mysqli_num_rows($db_listing->result);

$listing_product .= $list->showHeader($total_row);

$i = 0;
$array_unit = array();
$db_query = new db_query('SELECT * FROM units');
while ($row = mysqli_fetch_assoc($db_query->result)) {
    $array_unit[$row['uni_id']] = $row['uni_name'];
}
while ($row = mysqli_fetch_assoc($db_listing->result)) {
    $i++;
    if(!$row['uni_id'] || !isset($array_unit[$row['uni_id']])) {
        $array_unit[$row['uni_id']] = '';
    }
    $row['pro_code'] = format_codenumber($row['pro_id'], 6, PREFIX_PRODUCT_CODE);
    $row['pro_image'] = get_picture_path($row['pro_image']);
    $listing_product .= $list->start_tr($i, $row['pro_id'], 'class="menu-normal record-item" onclick="mindowScript.activeProductList('.$row['pro_id'].')" ondblclick="mindowScript.addProduct(' . $row['pro_id'] . ')" data-record_id="' . $row['pro_id'] . '" data-pro_name="' . $row['pro_name'] . '" data-pro_code="' . $row['pro_code'] . '" data-pro_unit="' . $array_unit[$row['pro_unit_id']] . '" data-pro_image="' . $row['pro_image'] . '"');
    /* code something */
    $listing_product .= '<td class="text-left" style="width : 50%">' . $row['pro_name'] . '</td>';
    $listing_product .= '<td class="center">' . $array_unit[$row['pro_unit_id']] . '</td>';
    $listing_product .= '<td class="text-right"></td>';
    $listing_product .= $list->end_tr();
}
$listing_product .= $list->showFooter();

$left_column .= $listing_product;
$left_column .= '</div>';

if($isAjaxRequest) {
    $action = $_REQUEST['action'];
    $ajax_container = $_REQUEST['container'];
    switch($action) {
        case 'pagingAjax' :
            if($ajax_container == '#mindow-listing-product') {
                echo $listing_product;
            }
            break;
        case 'searchAjax':
            echo $listing_product;
            break;
    }
    die();
}
//List kho hàng
$list_store = category_type('stores');
$list_store_option = '';
foreach ($list_store as $store) {
    $selected = '';
    if($configuration['con_default_store'] == $store['cat_id']) {
        $selected = 'selected';
    }
    $list_store_option .= '<option value="' . $store['cat_id'] . '" '.$selected.'>' . $store['cat_name'] . '</option>';
}
//List nhà cung cấp
$list_suppliers = array();
$list_suppliers_option = '';
$db_sup = new db_query('SELECT * FROM suppliers');
while ($row = mysqli_fetch_assoc($db_sup->result)) {
    $list_suppliers_option .= '<option value="' . $row['sup_id'] . '">' . $row['sup_name'] . '</option>';
}
//thời gian mặc định nhập hàng
$default_time = time();
$default_time_string = date('d/m/Y H:i', $default_time);
//Các input điều khiển việc nhập hàng
$right_control = '
<div class="col-xs-5">
    <div class="row">
        <div class="row-title">Ngày nhập</div>
        <div class="row-control">
            <input type="text" disabled value="' . $default_time_string . '"/>
            <input type="hidden" name="bio_start_time" value="'.$default_time.'" id="bio_start_time"/>
        </div>
    </div>
    <div class="row">
        <div class="row-title">Nhập vào kho</div>
        <div class="row-control">
            <select name="bio_store_id" id="bio_store_id">
                ' . $list_store_option . '
            </select>
        </div>
    </div>
</div>
<div class="col-xs-7">
    <div class="row">
        <div class="row-title">Nhà cung cấp</div>
        <div class="row-control">
            <select id="select-supplier">
                ' . $list_suppliers_option . '
            </select>
        </div>
    </div>
    <div class="row">
        <div class="row-title">Ghi chú</div>
        <div class="row-control">
            <input type="text" id="bio_note" name="bio_note" />
        </div>
    </div>
</div>
';

$right_column .=
    '<div class="text-center section-title">Thông tin hóa đơn</div>
<div id="import-control">
' . $right_control . '
</div>
<div id="listing-import" class="col-xs-12 row">
    <div class="table-listing-bound">
        <table class="table table-bordered table-hover table-listing">
            <thead>
                <tr>
                    <th width="40">STT</th>
                    <th width="100">Mã hàng</th>
                    <th>Tên hàng</th>
                    <th width="40">ĐVT</th>
                    <th width="40">SL</th>
                    <th width="100">Giá nhập</th>
                    <th width="100">Thành tiền</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>';

$footer_control = '
<div class="footer-control row col-xs-12">
    <div class="col-xs-4 product-info">
        <div class="row">
            <div class="row-title pull-left">Mã hàng</div>
            <div class="row-control pull-left">
                <input type="text" id="product-id" disabled/>
            </div>
        </div>
        <div class="row">
            <div class="row-title pull-left">Số lượng</div>
            <div class="row-control pull-left">
                <input type="text" id="product-number"/>
            </div>
        </div>
        <div class="row">
            <div class="row-title pull-left">Giá nhập</div>
            <div class="row-control pull-left">
                <input type="text" id="product-price"/>
            </div>
        </div>
        <div class="row">
            <div class="row-title pull-left">Tên hàng</div>
            <div class="row-control pull-left">
                <input type="text" id="product-name" disabled/>
            </div>
        </div>
    </div>
    <div class="col-xs-2">
        <div class="pro-image-thumb">
            <img src="" alt="" id="pro-image"/>
        </div>
    </div>
    <div class="col-xs-6 info-money">
        <div class="row">
            <div class="row-title pull-left text-right">Thanh toán</div>
            <div class="row-control  pull-right">
                <span id="total-money">0</span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <label>
                    <input type="checkbox" class="pull-left" id="check-debit"/>
                    Ghi nợ
                </label>
            </div>
            <div class="col-xs-8">
                <label>
                    <input type="radio" class="pull-left" checked name="pay-type" value="' . PAY_TYPE_CASH . '"/>
                    Tiền mặt
                </label>
                <label>
                    <input type="radio" class="pull-left" name="pay-type" value="' . PAY_TYPE_CARD . '"/>
                    Thẻ
                </label>
            </div>

        </div>
        <div class="row" id="info-debit">
            <div class="col-xs-8">
                <div class="row">
                    <div class="row-title pull-left">Trả trước</div>
                    <input type="text" class="pull-left text-right" id="pre-pay" disabled/>
                </div>
                <div class="row">
                    <div class="row-title pull-left">Ngày hẹn trả</div>
                    <input type="text" class="pull-left text-right" id="debit-date" disabled value="'.date('d/m/Y',time() + 86400).'"/>
                </div>
            </div>
            <div class="col-xs-4">
                <div>Còn lại phải trả</div>
                <div class="text-bold text-center" id="money-debit">0</div>
            </div>
        </div>
    </div>
</div>
';
$right_column .= $footer_control;

$footer_button = '
<div class="col-xs-4">
    <label class="control-btn">
        <i class="fa fa-file-o"></i>
        Thêm mới
    </label>
    <label class="control-btn">
        <i class="fa fa-edit"></i>
        Chỉnh sửa
    </label>
</div>
<div class="col-xs-8">
    <label class="control-btn pull-right" onclick="mindowScript.billSubmit()">
        <i class="fa fa-save"></i>
        Lưu hóa đơn
    </label>
</div>';

$rainTpl = new RainTPL();
add_more_css('custom_import.css',$load_header);
$rainTpl->assign('load_header', $load_header);
$rainTpl->assign('left_column', $left_column);
$rainTpl->assign('right_column', $right_column);
$rainTpl->assign('footer_control', $footer_button);
$custom_script = file_get_contents('script_import.html');
$rainTpl->assign('custom_script', $custom_script);

$rainTpl->draw('mindow_iframe_2column');