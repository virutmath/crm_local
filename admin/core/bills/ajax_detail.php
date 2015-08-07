<?
require_once 'inc_security.php';
$data_record_id     = getValue('data_record_id','int','POST',0,3);
$position           = getValue('position','str','POST','',3);
$bill               = getValue('bill','str','POST','',3);

$tr                 = '';
$right_content      = '';
$phuphi             = ''; 

$gio_vao            = '';
$gio_ra             = '';
$khu_vuc            = '';
$ban                = '';
$dia_diem           = '';
$store              = '';
$customer           = '';
$customer_id        = 0;
$nhanvien           = '';
$nhanvien_id        = 0;
$thu_ngan           = '';
$status             = '';
$type               = '';
$opacity            = '';
$da_tt              = 0;
$con_lai            = 0;
$totalAll           = 0;
$totalAll_          = 0;
$phu_phi            = 0;
$pp                 = 0;
$giam_gia           = 0;
$sale               = 0;
$vat_               = 0;
$vat                = 0;
$tong_thanhtoan     = 0;

//$left_column        = '';
function percen($struc,$per){
    $return = $struc * $per / 100;
    return $return;
}
if(trim($position)  === 'left' || trim($bill) == 'in'){
    $title          = 'Hóa đơn bán hàng';
    $title_left     = 'Danh sách thực đơn';
    $title_right    = 'Thông tin hóa đơn';
    
    $list = new dataGrid('bid_menu_id',30);
    $list->add('men_id', 'Mã');
    $list->add('men_name', 'Tên thực đơn');
    $list->add('uni_name', 'ĐVT');
    $list->add('bid_menu_number', 'SL');
    $list->add('bid_menu_price', 'Đơn giá');
    $list->add('bid_menu_discount', 'Giảm');
    $list->add('', 'Thành tiền');
    // lấy ra số bản hóa đơn bán
    if(trim($position)  == 'left'){
        // tổng số thực đơn trong hóa đơn
        $db_count = new db_count('SELECT count(*) as count
                                    FROM bill_in_detail
                                    WHERE 1 '.$list->sqlSearch().' AND bid_bill_id = ' . $data_record_id . '
                                    ');
        $total = $db_count->total;unset($db_count);
        // lọc danh sách thực đơn của hóa đơn
        $menu_listing   = new db_query('SELECT * FROM bill_in_detail 
                                        INNER JOIN menus 
                                        ON bid_menu_id = men_id
                                        WHERE bid_bill_id = ' . $data_record_id . ' '
                                        . $list->limit($total));                               
        $total          = mysqli_num_rows($menu_listing->result);
        $tr             .= $list->showHeader($total);
        $i              = 0;
        while ($row     = mysqli_fetch_assoc($menu_listing->result)){
            $total      = $row['bid_menu_price'] * $row['bid_menu_number'] - $row['bid_menu_discount'];
            $i++;
            // đơn vị tính
            $unit       = new db_query('SELECT uni_name FROM units WHERE uni_id = ' . $row['men_unit_id']);
            $row_       = mysqli_fetch_assoc($unit->result);unset($unit);
            //
            $tr         .= $list->start_tr($i,$row['bid_menu_id'],'class="menu-normal record-item" onclick="active_record('.$row['bid_menu_id'].',\'left\')" data-record_id="'.$row['bid_menu_id'].'"');
            $tr         .= 
                '<td>'.format_codenumber($row['men_id'],6,'').'</td>
                <td style="text-align: left !important;">'.$row['men_name'].'</td>
                <td>'.$row_['uni_name'].'</td>
                <td>'.$row['bid_menu_number'].'</td>
                <td style="text-align: right !important;">'.number_format($row['bid_menu_price']).'</td>
                <td>'.number_format($row['bid_menu_discount']).'</td>
                <td style="text-align: right !important;">'.number_format($total).'</td>';
            $tr         .= $list->end_tr();
            $totalAll   += $total;
        }
        $tr             .= $list->showFooter();
        unset($menu_listing);
        // lấy ra thời gian vào và ra của khách hàng
        $db_bill_in     = new db_query('SELECT * FROM bill_in
                                        WHERE bii_id = ' . $data_record_id);
        if($data_bill_in   = mysqli_fetch_assoc($db_bill_in->result)){
            $gio_vao        = date('d/m/Y h:m', $data_bill_in['bii_start_time']);
            $gio_ra         = date('d/m/Y h:m', $data_bill_in['bii_end_time']);
            // lấy ra số bàn và vị trí bàn khách hàng
            $db_des_sec     = new db_query('SELECT * FROM desks 
                                            INNER JOIN sections ON des_sec_id = sec_id
                                            WHERE des_id = ' . $data_bill_in['bii_desk_id']);
            if($data_des_sec   = mysqli_fetch_assoc($db_des_sec->result)){
                $khu_vuc        = $data_des_sec['sec_name'];
                $ban            = $data_des_sec['des_name'];
            };unset($db_des_sec);
            // lấy ra địa điểm
            $db_age_sed     = new db_query('SELECT * FROM service_desks 
                                            INNER JOIN agencies ON sed_agency_id = age_id
                                            WHERE sed_id = ' . $data_bill_in['bii_service_desk_id']);
            if($data_age_sed   = mysqli_fetch_assoc($db_age_sed->result)){
                $dia_diem       = $data_age_sed['age_name'].' - '.$data_age_sed['sed_name'];
            };unset($db_age_sed);
            // lấy ra kho
            $db_store       = new db_query('SELECT * FROM categories_multi
                                            WHERE cat_id = ' . $data_bill_in['bii_store_id']);
            if($data_store     = mysqli_fetch_assoc($db_store->result)){
                $store          = $data_store['cat_name'];
            };unset($db_store);
            // lấy ra tên nhân viên
            $db_user        = new db_query('SELECT * FROM users
                                            WHERE use_id = ' . $data_bill_in['bii_staff_id']);
            if($data_user      = mysqli_fetch_assoc($db_user->result)){
                $nhanvien       = $data_user['use_name'];
            };unset($db_user);
            // lấy ra tên thu ngân
            $db_adm         = new db_query('SELECT * FROM admin_users
                                            WHERE adm_id = ' . $data_bill_in['bii_admin_id']);
            if($data_adm       = mysqli_fetch_assoc($db_adm->result)){
                $thu_ngan       = $data_adm['adm_name'];
            };unset($db_adm);  
            $customer_id    = $data_bill_in['bii_customer_id'];
            $nhanvien_id    = $data_bill_in['bii_staff_id'];
            $phu_phi        = $data_bill_in['bii_extra_fee'];
            $giam_gia       = $data_bill_in['bii_discount'];
            $vat_           = $data_bill_in['bii_vat'];
            $tong_thanhtoan = $data_bill_in['bii_round_money'];
            
            $pp             = percen($data_bill_in['bii_true_money'],$data_bill_in['bii_extra_fee']);
            $sale           = percen($data_bill_in['bii_true_money'],$data_bill_in['bii_discount']);
            $vat            = percen($data_bill_in['bii_true_money'],$data_bill_in['bii_vat']);
            $totalAll_      = $totalAll - $pp - $sale - $vat;
            $da_tt          = $data_bill_in['bii_round_money'];
            if($data_bill_in['bii_status']   == BILL_STATUS_SUCCESS){
                $status     = 'Đã trả đủ';
                $opacity    = 'style="opacity: .4;"';
            }else{
                $status     = 'Ghi nợ';   
                $con_lai    = $totalAll_ - $da_tt;
            }
            if($data_bill_in['bii_type']   == PAY_TYPE_CASH){
                $type       = 'Tiền mặt';
            }else{
                $type       = 'Thẻ';
            }
            if($data_bill_in['bii_customer_id'] == 0){
                $customer   = 'Khách lẻ';
            }else{
                $db_cus     = new db_query('SELECT cus_name FROM customers WHERE cus_id = ' . $data_bill_in['bii_customer_id']);
                $row_       = mysqli_fetch_assoc($db_cus->result); unset($db_cus);
                $customer   = $row_['cus_name']; 
            }
        };unset($db_bill_in);
    }// end postion = left
    // xem chi tiet hoa don ban trong thung rac
    if(trim($bill)      == 'in'){
        $db_count       = new db_count('SELECT count(*) as count
                                    FROM trash
                                    WHERE 1 '.$list->sqlSearch().' 
                                    AND tra_record_id = ' . $data_record_id . '
                                    AND tra_table = \'bill_' . $bill . '_detail\'
                                    ');
        $total = $db_count->total;unset($db_count);
        // loc danh sach thuc don cua hoa don ban trong thung rac
        $list_trash_bill_in = new db_query('SELECT tra_data FROM trash
                                            WHERE tra_record_id = ' . $data_record_id . '
                                            AND tra_table = \'bill_' . $bill . '_detail\'' . ' ' . $list->limit($total));
        $total      = mysqli_num_rows($list_trash_bill_in->result);
        $tr         .= $list->showHeader($total);
        $i          = 0;
        while($row  = mysqli_fetch_assoc($list_trash_bill_in->result)){
            $data   = json_decode(base64_decode($row['tra_data']),1);
            $total      = $data['bid_menu_price'] * $data['bid_menu_number'] - $data['bid_menu_discount'];
            $i++;
            // don vi tinh
            $unit       = new db_query('SELECT * FROM menus LEFT JOIN units ON men_unit_id = uni_id WHERE men_id = ' . $data['bid_menu_id']);
            $row_       = mysqli_fetch_assoc($unit->result);unset($unit);
            //
            $tr         .= $list->start_tr($i,$data['bid_menu_id'],'class="menu-normal record-item" onclick="active_record('.$data['bid_menu_id'].',\'left\')" data-record_id="'.$data['bid_menu_id'].'"');
            $tr         .= 
                '<td>'.format_codenumber($row_['men_id'],6,'').'</td>
                <td style="text-align: left !important;">'.$row_['men_name'].'</td>
                <td>'.$row_['uni_name'].'</td>
                <td>'.$data['bid_menu_number'].'</td>
                <td style="text-align: right !important;">'.number_format($data['bid_menu_price']).'</td>
                <td>'.number_format($data['bid_menu_discount']).'</td>
                <td style="text-align: right !important;">'.number_format($total).'</td>';
            $tr         .= $list->end_tr();
            $totalAll   += $total;
        }
        $tr             .= $list->showFooter();unset($list_trash_bill_in);
        // thong tin phia ben phai cua hoa don 
        $db_listing         = new db_query('SELECT * FROM trash WHERE tra_record_id = ' . $data_record_id . ' AND tra_table = \'bill_' . $bill . '\'');
        if($row             = mysqli_fetch_assoc($db_listing->result)){
            $data           = json_decode(base64_decode($row['tra_data']),1);
            // so ban va vi tri ban
            $db_desk        = new db_query('SELECT sec_name, des_name FROM desks INNER JOIN sections ON des_sec_id = sec_id WHERE des_id = ' . $data['bii_desk_id']);
            $row_desk       = mysqli_fetch_assoc($db_desk->result); unset($db_desk);
            // kho
            $db_store       = new db_query('SELECT cat_name FROM categories_multi WHERE cat_id = ' . $data['bii_store_id']);
            $row_store      = mysqli_fetch_assoc($db_store->result); unset($db_store);
            // khach hang
            $db_cus         = new db_query('SELECT cus_id, cus_name FROM customers WHERE cus_id = ' . $data['bii_customer_id']);
            $row_cus        = mysqli_fetch_assoc($db_cus->result); unset($db_cus);
            if($row_cus['cus_id'] == 0){
                $customer   = 'Khách lẻ';
            }else{
                $customer   = $row_cus['cus_name'];
            }
            // phu phi
            $pp             = percen($data['bii_true_money'],$data['bii_extra_fee']);
            // giam gia
            $sale           = percen($data['bii_true_money'],$data['bii_discount']);
            // VAT
            $vat            = percen($data['bii_true_money'],$data['bii_vat']);
            // tong tien
            $totalAll_      = $totalAll - $pp - $sale - $vat;
            // nhan vien
            $db_user        = new db_query('SELECT use_name FROM users WHERE use_id = ' . $data['bii_staff_id']);
            $row_user       = mysqli_fetch_assoc($db_user->result); unset($db_user);
            // da thanh toan
            $da_tt                  = $data['bii_round_money'];
            // trang thai
            if($data['bii_status']  == BILL_STATUS_SUCCESS){
                $status             = 'Đã trả đủ';
                $opacity            = 'style="opacity: .4;"';
            }else{
                $status             = 'Ghi nợ';   
                $con_lai            = $totalAll_ - $da_tt;
            }
            // loai thanh toans
            if($data['bii_type']    == PAY_TYPE_CASH){
                $type               = 'Tiền mặt';
            }else{
                $type               = 'Thẻ';
            }
            // thu ngan
            $db_adm                 = new db_query('SELECT adm_name FROM admin_users WHERE adm_id = ' . $data['bii_admin_id']);
            $row_admin              = mysqli_fetch_assoc($db_adm->result); unset($db_adm);
            // dia diem 
            $db_age_sed             = new db_query('SELECT age_name, sed_name FROM service_desks INNER JOIN agencies ON sed_agency_id = age_id WHERE sed_id = ' . $data['bii_service_desk_id']);
            $row_sever_desk         = mysqli_fetch_assoc($db_age_sed->result); unset($db_age_sed);
            //
            $gio_vao        = date('d/m/Y h:m', $data['bii_start_time']);
            $gio_ra         = date('d/m/Y h:m', $data['bii_end_time']);
            $khu_vuc        = $row_desk['sec_name'];
            $ban            = $row_desk['des_name'];
            $dia_diem       = $row_sever_desk['age_name'].' - '.$row_sever_desk['sed_name'];
            $store          = $row_store['cat_name'];
            $nhanvien       = $row_user['use_name'];       
            $thu_ngan       = $row_admin['adm_name'];
            $customer_id    = $data['bii_customer_id'];
            $nhanvien_id    = $data['bii_staff_id'];
            
            $phu_phi        = $data['bii_extra_fee'];
            $giam_gia       = $data['bii_discount'];
            $vat_           = $data['bii_vat'];
            $tong_thanhtoan = $data['bii_round_money'];
        }unset($db_listing);
    }
    /// HTML 
    $right_content  .= '<table cellpadding="0" cellspacing="0" border="0" class="bill-inf">';
    $right_content  .= '<tr><td>Giờ vào - ra:</td><td>'.$gio_vao.' - '.$gio_ra.'</td></tr>';
    $right_content  .= '<tr><td>Khu vực - Bàn:</td><td>'.$khu_vuc.' - '.$ban.'</td></tr>';
    $right_content  .= '<tr><td>Địa điểm:</td><td>'.$dia_diem.'</td></tr>';
    $right_content  .= '<tr><td>Xuất từ kho:</td><td>'.$store.'</td></tr>';
    $right_content  .= '<tr><td>Khách hàng:</td><td>'.$customer.' <i class="fa fa-picture-o" onclick="show_cus({\'box\':\'.content-form-cus\',\'id\':\'' . $customer_id . '\',\'tabel\':\'customers\'});"></i></td></tr>';
    $right_content  .= '<tr><td>Nhân viên:</td><td>'.$nhanvien.' <i class="fa fa-picture-o" onclick="show_cus({\'box\':\'.content-form-cus\',\'id\':\'' . $nhanvien_id . '\',\'tabel\':\'users\'});"></i></td></tr>';
    $right_content  .= '<tr><td>Thu ngân:</td><td>'.$thu_ngan.'</td></tr>';
    $right_content  .= '<tr><td>Trạng thái:</td><td>'.$status.'</td></tr>';
    $right_content  .= '<tr><td>Thanh toán bằng:</td><td>'.$type.'</td></tr>';
    $right_content  .= '</table>';
    
    $right_content  .= '<div class="cn-kh" '.$opacity.'>';
    $right_content  .= '<span class="cn-n-kh">Công nợ khách hàng</span>'; 
    $right_content  .= '<form action="" method=""><table cellpadding="0" cellspacing="0" border="0" class="tb-cnkh">';
    $right_content  .= '<tr><td>Đã thanh toán:</td><td class="text-right"><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.$da_tt.'"/></td></tr>';
    $right_content  .= '<tr><td>Còn lại:</td><td class="text-right"><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.$con_lai.'"/></td></tr>';
    $right_content  .= '<tr><td>Ngày hẹn:</td><td class="text-right"><input name="" class="inp-cnkh text-right" readonly="readonly" value="0"/></td></tr>';
    $right_content  .= '</table></form></div>';
    $right_content  .= '<input name="" class="cn-gh-ch" value=""/>';
    $right_content  .= '<div class="print-close">';
    $right_content  .= '<span class="bill-print"><i class="fa fa-print"></i> In hóa đơn</span>';
    $right_content  .= '<span class="bill-close" onclick="detail_close(\'#bill_detail\')"><i class="fa fa-sign-out"></i> Đóng cửa sổ</span></div>';
    /// phu phi
    $phuphi         .= '<div class="pp-vat"><table cellpadding="0" cellspacing="0" border="0" class="vat">';
    $phuphi         .= 
    '<tr>
        <td>Phụ phí:</td>
        <td><b>'.$phu_phi.'%</b></td>
        <td>=</td>
        <td class="text-right"><b>'.number_format($pp).'</b></td>
    </tr>';
    $phuphi         .= 
    '<tr>
        <td>Giảm giá:</td>
        <td><b>'.$giam_gia.'%</b></td>
        <td>=</td>
        <td class="text-right"><b>'.number_format($sale).'</b></td>
    </tr>';
    $phuphi         .= 
    '<tr>
        <td>VAT:</td>
        <td><b>'.$vat_.'%</b></td>
        <td>=</td>
        <td class="text-right"><b>'.number_format($vat).'</b></td>
    </tr></table>';
    $phuphi         .= '<table cellpadding="0" cellspacing="0" border="0" class="total-tr">'; 
    $phuphi         .= 
    '<tr>
        <td>Tổng tiền:</td>
        <td class="text-right"><b>'.number_format($totalAll_).'</b></td>
    </tr>';
    $phuphi         .= 
    '<tr>
        <td class="border-bot">Thanh toán:</td>
        <td class="text-right border-bot total-bill-money"><b>'.number_format($tong_thanhtoan).' '.DEFAULT_MONEY_UNIT.'</b></td>
    </tr></table></div>';
}
//
$ngay_nhap          = '';
$store              = '';
$totalAll           = 0;
$thanhtoan          = '';
$avata              = '';
$nhacc              = '';
$opacity            = '';
$da_thanhtoan       = 0;
$con_lai            = 0;
if(trim($position)  === 'right' || trim($bill) == 'out'){
    $title          = 'Hóa đơn nhập hàng';
    $title_left     = 'Danh sách mặt hàng';
    $title_right    = 'Thông tin hóa đơn';
    
    $list = new dataGrid('bid_pro_id',30);
    $list->add('bid_pro_id', 'Mã hàng');
    $list->add('pro_name', 'Tên sản phẩm');
    $list->add('uni_name', 'ĐVT');
    $list->add('bid_pro_number', 'SL');
    $list->add('bid_pro_price', 'Đơn giá');
    $list->add('', 'Thành tiền');
    // thong tin chi tiet hoa don nhap 
    if(trim($position)  === 'right'){
        $db_count = new db_count('SELECT count(*) as count
                                FROM bill_out_detail
                                WHERE 1 '.$list->sqlSearch().' AND bid_bill_id = ' . $data_record_id . '
                                ');
        $total = $db_count->total;unset($db_count);
        // danh sach san pham nhap trong hoa don
        $menu_listing   = new db_query('SELECT * FROM bill_out_detail 
                                        INNER JOIN products ON bid_pro_id = pro_id
                                        INNER JOIN units ON pro_unit_id = uni_id
                                        WHERE bid_bill_id = ' . $data_record_id .' '. $list->limit($total));
        $total          = mysqli_num_rows($menu_listing->result);
        $tr             .= $list->showHeader($total);                                
        $i              = 0;
        while ($row     = mysqli_fetch_assoc($menu_listing->result)){
            $total      = $row['bid_pro_price'] * $row['bid_pro_number'];
            $tr         .= $list->start_tr($i,$row['bid_pro_id'],'class="menu-normal record-item" onclick="active_record('.$row['bid_pro_id'].',\'left\')" data-record_id="'.$row['bid_pro_id'].'"');
            $i++;
            $tr         .= 
                '<td>'.format_codenumber($row['bid_pro_id'],6,'').'</td>
                <td style="text-align: left !important;">'.$row['pro_name'].'</td>
                <td>'.$row['uni_name'].'</td>
                <td>'.$row['bid_pro_number'].'</td>
                <td style="text-align: right !important;">'.number_format($row['bid_pro_price']).'</td>
                <td style="text-align: right !important;">'.number_format($total).'</td>';
            $tr         .= $list->end_tr();
            $totalAll += $total;
        }
        $tr             .= $list->showFooter();
        unset($menu_listing);
    // lay ra thong tin cua nha cug cap, trang thai thanh toan, 
        $sql                    = new db_query('SELECT * FROM bill_out
                                                 INNER JOIN categories_multi ON bio_store_id = cat_id
                                                 INNER JOIN suppliers ON bio_supplier_id = sup_id
                                                 WHERE bio_id = ' . $data_record_id);
        $row                    = mysqli_fetch_assoc($sql->result);unset($sql);
        $ngay_nhap              = date('d-m-Y',$row['bio_start_time']);
        $store                  = $row['cat_name'];
        $nhacc                  = $row['sup_name'];
        $da_thanhtoan           = $row['bio_total_money'];
        if($row['bio_status']   === 0){
            $thanhtoan          = 'Còn nợ lại';
            $con_lai            = $totalAll - $row['bio_total_money'];
        }else{
            $thanhtoan          = 'Thanh toán đủ';
            $opacity            = 'style="opacity: .4;"';
        }
        if($row['sup_image']    === ''){
            $avata              = '<i class="fa fa-camera-retro fa-2x ava-cus"></i><p>Không có hình</p>';
        }else{
            $avata              = '<img src="'.get_picture_path($row['sup_image']).'"/>';
        }
    }
    // chi tiet hoa don nhap trong thung rac
    if(trim($bill) == 'out'){
        $db_count = new db_count('SELECT count(*) as count
                                FROM trash
                                WHERE 1 '.$list->sqlSearch().' AND tra_record_id = ' . $data_record_id . ' AND tra_table = \'bill_' . $bill . '_detail\'
                                ');
        $total = $db_count->total;unset($db_count);
        
        $list_trash_bill_out = new db_query('SELECT * FROM trash 
                                            WHERE tra_record_id = ' . $data_record_id . ' 
                                            AND tra_table = \'bill_' . $bill . '_detail\'' . ' ' . $list->limit($total));
        $total      = mysqli_num_rows($list_trash_bill_out->result);
        $tr         .= $list->showHeader($total);
        $i          = 0;
        // loc ra danh sach san pham nhap trong hoa don
        while ($row     = mysqli_fetch_assoc($list_trash_bill_out->result)){
            $data       = json_decode(base64_decode($row['tra_data']),1);
            $total      = $data['bid_pro_price'] * $data['bid_pro_number'];
            $tr         .= $list->start_tr($i,$data['bid_pro_id'],'class="menu-normal record-item" onclick="active_record('.$data['bid_pro_id'].',\'left\')" data-record_id="'.$data['bid_pro_id'].'"');
            $i++;
            // ten san pham
            $sql_product = new db_query('SELECT * FROM products INNER JOIN units ON pro_unit_id = uni_id WHERE pro_id = ' . $data['bid_pro_id']);
            $pro         = mysqli_fetch_assoc($sql_product->result);unset($sql_product);
            //
            $tr         .= 
                '<td>'.format_codenumber($data['bid_pro_id'],6,'').'</td>
                <td style="text-align: left !important;">'.$pro['pro_name'].'</td>
                <td>'.$pro['uni_name'].'</td>
                <td>'.$data['bid_pro_number'].'</td>
                <td style="text-align: right !important;">'.number_format($data['bid_pro_price']).'</td>
                <td style="text-align: right !important;">'.number_format($total).'</td>';
            $tr         .= $list->end_tr();
            $totalAll += $total;
        }
        $tr             .= $list->showFooter();unset($list_trash_bill_out);
        // thong tin nha cung cap, trang thai thanh toans
        $menu_listing   = new db_query('SELECT * FROM trash WHERE tra_record_id = ' . $data_record_id . ' AND tra_table = \'bill_' . $bill . '\'');
        $row            = mysqli_fetch_assoc($menu_listing->result);unset($menu_listing);
        $data           = json_decode(base64_decode($row['tra_data']),1);
        $ngay_nhap      = date('d-m-Y',$data['bio_start_time']);
        $menu_listing   = new db_query('SELECT cat_name FROM categories_multi WHERE cat_id = ' . $data['bio_store_id']);
        $row_store      = mysqli_fetch_assoc($menu_listing->result);unset($menu_listing);
        $store          = $row_store['cat_name'];
        $menu_listing   = new db_query('SELECT sup_image, sup_name FROM suppliers WHERE sup_id = ' . $data['bio_supplier_id']);
        $row_brand      = mysqli_fetch_assoc($menu_listing->result);unset($menu_listing);
        $nhacc          = $row_brand['sup_name'];
        
        $da_thanhtoan   = $data['bio_total_money'];
        
        if($data['bio_status']   === 0){
            $thanhtoan          = 'Còn nợ lại';
            $con_lai            = $totalAll - $da_thanhtoan;
        }else{
            $thanhtoan          = 'Thanh toán đủ';
            $opacity            = 'style="opacity: .4;"';
        }
        if($row_brand['sup_image']    === ''){
            $avata              = '<i class="fa fa-camera-retro fa-2x ava-cus"></i><p>Không có hình</p>';
        }else{
            $avata              = '<img src="'.get_picture_path($row_brand['sup_image']).'"/>';
        }
    } 
    /// HTML
    $right_content  .= '<div class="box-content-inf-bill">';
    $right_content  .= '<div class="box-content-inf-bill-left"><form method="" action="">';
    $right_content  .= '<p>Ngày nhập:</p><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.$ngay_nhap.'"/>';
    $right_content  .= '<p>Nhập vào kho:</p><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.$store.'"/>';
    $right_content  .= '<p>Tổng thanh toán:</p><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.number_format($totalAll).'"/>';
    $right_content  .= '<p>Trạng thái thanh toán:</p><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.$thanhtoan.'"/>';
    $right_content  .= '</form></div>';
    $right_content  .= '<div class="box-content-inf-bill-right">';
    $right_content  .= '<p>Nhà cung cấp</p>';
    $right_content  .= '<div class="box-ava-cus">'.$avata.'</div>';                             
    $right_content  .= '<p class="brand-name">'.$nhacc.'</p></div>';
    $right_content  .= '<div class="clear_"></div></div>';
    
    $right_content  .= '<div class="cn-kh" '.$opacity.'>';
    $right_content  .= '<span class="cn-n-kh">Công nợ nhà cung cấp</span>'; 
    $right_content  .= '<form action="" method=""><table cellpadding="0" cellspacing="0" border="0" class="tb-cnkh">';
    $right_content  .= '<tr><td>Đã thanh toán:</td><td class="text-right"><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.number_format($da_thanhtoan).'"/></td></tr>';
    $right_content  .= '<tr><td>Còn lại:</td><td class="text-right"><input name="" class="inp-cnkh text-right" readonly="readonly" value="'.number_format($con_lai).'"/></td></tr>';
    $right_content  .= '<tr><td>Ngày hẹn:</td><td class="text-right"><input name="" class="inp-cnkh text-right" readonly="readonly" value="0"/></td></tr>';
    $right_content  .= '</table></form></div>';
    $right_content  .= '<input name="" class="cn-gh-ch" value=""/>';
    $right_content  .= '<div class="print-close">';
    $right_content  .= '<span class="bill-print"><i class="fa fa-print"></i> In hóa đơn</span>';
    $right_content  .= '<span class="bill-close" onclick="detail_close(\'#bill_detail\')"><i class="fa fa-sign-out"></i> Đóng cửa sổ</span></div>';
    
}
?>
<meta charset="utf-8"/>
<span class="detail-tit-bill"><?=$title?></span>
<i class="fa fa-times detail-close" onclick="detail_close('#bill_detail')"></i>
<div class="detail_content">
    <div class="detail_content_lft">
        <span class="detail-tit-bill"><?=$title_left?></span>
        <table cellpadding="0" cellspacing="0" border="0">
            <?=$tr?>
        </table>
    </div>
    <div class="detail_content_rgh">
        <span class="detail-tit-bill"><?=$title_right?></span>
        <?=$right_content?>
    </div>
    <div class="clear_"></div>
</div>
<?=$phuphi?>