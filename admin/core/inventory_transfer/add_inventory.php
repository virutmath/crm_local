<?
require_once 'inc_security.php';
//kiểm tra quyền nhập hàng
checkCustomPermission('add');

//Đếm tất cả số mặt hàng đang có
$db_count   = new db_count('SELECT count(*) AS count FROM products');
$all_count  = $db_count->total;
unset($db_count);


//Lấy danh mục mặt hàng
$pro_cat_id = array('' => 'Tất cả (' . $all_count . ')');
$db_query   = new db_query('SELECT * FROM categories_multi WHERE cat_type = "products"');
while ($row = mysqli_fetch_assoc($db_query->result)) {
    //đếm số mặt hàng trong cate này
    $db_count   = new db_count('SELECT count(*) AS count FROM products WHERE pro_cat_id = ' . $row['cat_id']);
    $pro_count  = $db_count->total;
    unset($db_count);
    $pro_cat_id[$row['cat_id']] = $row['cat_name'] . ' (' . $pro_count . ')';
}

$left_column    = '';
$right_column   = '';

$left_column .= '
<div class="text-center section-title">Danh sách mặt hàng</div>
<div id="mindow-listing-product">';

//Danh sách mặt hàng
$listing_menu = '';
$list = new dataGrid('pro_id',30, '#mindow-listing-product');
$list->add('pro_name', 'Tên mặt hàng', 'string', 1, 0);
$list->add('', 'ĐVT');
$list->addSearch('', 'pro_cat_id', 'array', $pro_cat_id, getValue('pro_cat_id'));
$sql_search = '';
$search_cat_id = getValue('pro_cat_id','int','GET',0);
if ($search_cat_id) {
    $sql_search .= ' AND pro_cat_id = ' . $search_cat_id . ' ';
}
// dem ban ghi trong menu
$db_count   = new db_count('SELECT count(*) AS count
                          FROM products
                          WHERE 1 ' . $list->sqlSearch() . $sql_search);
$total      = $db_count->total;
unset($db_count);

$db_listing = new db_query('SELECT *
                            FROM products
                            WHERE 1 ' . $list->sqlSearch() . $sql_search . '
                            ORDER BY ' . $list->sqlSort() . ' pro_id ASC
                            ' . $list->limit($total));
$total_row  = mysqli_num_rows($db_listing->result);

    $listing_menu .= $list->showHeader($total_row);


/* tạo mảng đơn vị tính */
$array_unit     = array();
$db_query       = new db_query('SELECT * FROM units');
while ($row = mysqli_fetch_assoc($db_query->result)) {
    $array_unit[$row['uni_id']] = $row['uni_name'];
} unset($db_query);

/* Vòng lặp các bản ghi */
$i = 0;
while ($row = mysqli_fetch_assoc($db_listing->result)) {
    $i++;
    $listing_menu .= $list->start_tr($i, $row['pro_id'], 'class="menu-normal record-item" ondblclick="mindowScript.addProducts(' . $row['pro_id'] . ')" data-record_id="' . format_codenumber($row['pro_id'],6,PREFIX_PRODUCT_CODE) . '" data-pro_name="' . $row['pro_name'] . '" data-pro_unit="' . $array_unit[$row['pro_unit_id']] . '"');
    /* code something */
    $listing_menu .= '<td class="center">' . $row['pro_name'] . '</td>';
    $listing_menu .= '<td class="center">' . $array_unit[$row['pro_unit_id']] . '</td>';
    $listing_menu .= $list->end_tr();
} unset($db_listing);
$listing_menu .= $list->showFooter();


//phân trang ajax
//ajax paging
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    //catching ajax request
    $action = getValue('action','str','POST','',3);
    switch($action) {
        case 'searchAjax' :
        case 'pagingAjax' :
            $container = getValue('container','str','POST','',3);
            if($container == '#mindow-listing-product') {
                echo $listing_menu;die();
            }
            break;
    }
    $search_ajax = getValue('search','int','GET',0);
    if($search_ajax) {
        echo $listing_menu;die();
    }
    die();
}
$left_column .= $listing_menu;
$left_column .= '</div>';



//List cơ sở
$list_agencies  = array();
$list_store     = '';
$db_agen        = new db_query('SELECT * FROM categories_multi WHERE  cat_type = "stores"');
while ($row = mysqli_fetch_assoc($db_agen->result)) {
    $list_store .= '<option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
}

//Các input thông tin khuyễn mãi
$right_control = '
<div class="col-xs-6">
    <div class="row">
        <div class="row-title"> Nhân viên </div>
        <div class="row-control">
            <input type="text" name="staff_name" class="form-control" id="staff_name">
            <input type="hidden" name="staff_id" id="staff_id">
        </div>
    </div>
</div>
<div class="col-xs-6">
    <div class="row">
        <div class="row-title"> Kho hàng</div>
        <div class="row-control">
            <select name="store_name" id="store_name" class="form-control">
                ' . $list_store . '
            </select>
        </div>
    </div>
</div>
';

/* Phần hiển thị các thông tin cột bên phải */
$right_column .=
    '<div class="text-center section-title">Thông tin kiểm hàng</div>
<div id="import-control">
' . $right_control . '
</div>
<div id="listing-import" class="col-xs-12 row">
    <div class="table-listing-bound">
        <table class="table table-bordered table-hover table-listing">
            <thead>
                <tr>
                    <th width="40">STT</th>
                    <th width="120">Mã hàng</th>
                    <th>Tên mặt hàng</th>
                    <th width="100px">SL hệ thống</th>
                    <th width="100px">SL thực tế</th>

                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>';


/* Phần hiển thị nội dung footer */
$right_column .= '
<div class="footer-control">
    <div class="col-xs-5">
        <div class="row">
            <div class="row-title">Ghi chú: </div>
            <div class="row-control">
                <textarea class="note-promo" name="note_inventory" id="note_inventory"></textarea>
            </div>
        </div>
    </div>
    <div class="col-xs-7">
    <div class="row-title">Hướng dẫn: </div>
        <div class="notice-promo">Cách 1: bấm đúp chuột vào mặt hàng bên trái vào danh sách kiểm kho<br/>
            - Nhập số lượng thực tế vào danh sách kiểm kê<br/>

        </div>
    </div>
</div>
';

/* Phần hiển thị các chức năng điều khiển footer*/
$footer_control = '

<div class="col-xs-12">
    <label class="control-btn pull-right" onclick="mindowScript.addInventory()">
        <i class="fa fa-save"></i>
        Thêm mới
    </label>
</div>';



$rainTpl = new RainTPL();
add_more_css('custom.css', $load_header);
$rainTpl->assign('load_header', $load_header);
$rainTpl->assign('left_column', $left_column);
$rainTpl->assign('right_column', $right_column);
$rainTpl->assign('footer_control', $footer_control);
$custom_script = file_get_contents('script_import_inventory.html');
$rainTpl->assign('custom_script', $custom_script);
$rainTpl->draw('mindow_iframe_2column');