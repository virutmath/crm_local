<?
require_once 'inc_security.php';
//class Ajax - version 1.0
class ReportAjax extends AjaxCommon {
    function reportInventory(){
        //lấy các giá trị bắn ajax về để xuất báo cáo
        $array_product = getValue('products','arr','POST','');
        $start_date = convertDateTime(getValue('start_date','str','POST',''),'0:0:0');
        $end_date = convertDateTime(getValue('end_date','str','POST',''),'0:0:0');
        $store_id = getValue('store_id','int','POST',0);
        // select ra báo cáo với các thông tin trên
        //lấy số lượng nhập hàng
        $arr_pro = array();
        foreach($array_product as $product){
            $arr_pro[] = $product;
        }
        $arr_pro = implode(',',$arr_pro);
        $total_import = 0;
        $db_query = new db_query('SELECT bio_id FROM bill_out
                                  WHERE bio_store_id = '.$store_id.'
                                  AND bio_start_time >= '.$start_date.' AND bio_start_time <= '.$end_date.'');
        while($row = mysqli_fetch_assoc($db_query->result)){

            $db_query_import = new db_query('SELECT SUM(bid_pro_number) as sum_pro FROM bill_out_detail
                                             WHERE bid_bill_id = '.$row['bio_id'].' AND bid_pro_id IN('.$arr_pro.')');
            $row_total = mysqli_fetch_assoc($db_query_import->result);
            $total_import += $row_total['sum_pro'];
        }
        //echo $total_import;
        unset($db_query);

        //lấy số lượng bán hàng
        $total_menu = 0;
        $db_query_export = new db_query('SELECT bii_id FROM bill_in
                                  WHERE bii_store_id = '.$store_id.'
                                  AND bii_start_time >= '.$start_date.' AND bii_start_time <= '.$end_date.'');
        while($row_export = mysqli_fetch_assoc($db_query_export->result)){
            $db_menu_export = new db_query('SELECT bid_menu_number,sum_menu,bid_menu_id FROM bill_in_detail
                                            WHERE bid_bill_id = '.$row_export['bii_id'].'');
            while($row_menu = mysqli_fetch_assoc($db_menu_export->result)){
                //echo $total_menu = $row_menu['bid_menu_number'];
                //echo $row_menu['bid_menu_id']."<br/>";
//                $db_menu_pro = new db_query('SELECT mep_quantity,mep_product_id FROM menu_products
//                                         WHERE bid_menu_id = '.$row_menu['bid_menu_id'].'');
//                while($row_product = mysqli_fetch_assoc($db_menu_pro->result)){
//                    //echo $row_product['mep_product_id'];
//                    //echo "<br>";
//                    //echo $row_product['mep_quantity'];
//                }
            }

        }unset($db_menu_pro);
        unset($db_query_export);
    }

    // báo cáo giá trị tồn kho
    function reportStock(){
        $array_return = array();
        //lấy các giá trị bắn ajax về để xuất báo cáo
        $array_product  = getValue('products','arr','POST','');
        if(!$array_product){
            $array_return['content']    = 'Chưa chọn sản phẩm';
            die(json_encode($array_return));
        }
        $store_id       = getValue('store_id','int','POST',0);
        if(!$store_id){
            $array_return['content']    = 'Chưa chọn kho hàng';
            die(json_encode($array_return));
        }
        // select ra báo cáo với các thông tin trên
        //lấy số lượng nhập hàng
        $arr_pro = array();
        foreach($array_product as $product){
            $arr_pro[] = $product;
        }
        $arr_pro = implode(',',$arr_pro);

        $left_column = '';
        //Hiển thị danh sách phiếu thu bên trái
        #Bắt đầu với datagird
        $list = new dataGrid('pro_id', 30);
        $list->add('', 'Tên mặt hàng');
        $list->add('', 'ĐVT');
        $list->add('', 'SL tồn');
        $list->add('', 'Giá nhập TB');
        $list->add('', 'Tổng tiền tồn');

        $sql_search = 'AND product_id IN('.$arr_pro.') AND store_id = '.$store_id.'';
        // select list danh
        $db_count = new db_count('SELECT count(*) as count
                            FROM product_quantity
                            WHERE 1 ' . $list->sqlSearch() .$sql_search. '
                            ');
        $total = $db_count->total;
        unset($db_count);

        $sql_query = 'SELECT * FROM product_quantity
                            WHERE 1 ' . $list->sqlSearch() . $sql_search . '
                            ORDER BY ' . $list->sqlSort() . ' product_id ASC
                            ' . $list->limit($total);
        $db_listing = new db_query($sql_query);

        $total_row = mysqli_num_rows($db_listing->result);

        //tao mang hien thi ten product
        $array_pro_name = array();
        $db_product = new db_query('SELECT pro_id,pro_name FROM products');
        while($row_pro = mysqli_fetch_assoc($db_product->result)){
            $array_pro_name[$row_pro['pro_id']] = $row_pro['pro_name'];
        }


        //Vì đây là module cần 2 table listing nên khai báo thêm table_extra id=table-listing-left
        $left_column .= $list->showHeader($total_row, '', 'id="table-listing-right"');
        $i = 0;
        $total_all  = 0;
        while ($row = mysqli_fetch_assoc($db_listing->result)) {
            $i++;
            // lấy ra pro_unit_id để
            $db_query_unit  = new db_query('SELECT pro_unit_id FROM products WHERE pro_id = '.$row['product_id'].' ');
            $row_pro_unit   = mysqli_fetch_assoc($db_query_unit->result);

            // lấy ra đơn vị tính của sản phẩm
            $db_unit_name   = new db_query('SELECT uni_name FROM units WHERE uni_id = ' . $row_pro_unit['pro_unit_id'] . '');
            $row_unit       = mysqli_fetch_assoc($db_unit_name->result);

            // tính giá nhập trung bình
            $db_price_ave   = new db_query('SELECT SUM(bid_pro_price) as total_price FROM bill_out_detail
                                          WHERE bid_pro_id = '.$row['product_id'].'');

            $row_price      = mysqli_fetch_assoc($db_price_ave->result);
            //đếm có bao nhiêu sản phẩm và lấy tổng số bản ghi để tính giá trung bình
            $db_count_price = new db_query('SELECT count(*) as count FROM bill_out_detail
                                            WHERE bid_pro_id = '.$row['product_id'].'');

            $count          = mysqli_fetch_assoc($db_count_price->result);
            // tính công thức giá nhập trung bình
            if($count['count'] > 0){
                $price_average = $row_price['total_price']/$count['count'];
            } else {
                $price_average = 0;
            }


            $left_column .= $list->start_tr($i, $row['product_id'], 'class="menu-normal record-item" data-record_id="' . $row['product_id'] . '"');
            /* code something */
            $left_column .= '<td class="text-left">' . $array_pro_name[$row['product_id']] . '</td>';

            $left_column .= '<td width="100" class="center">' . $row_unit['uni_name'] . '</td>';

            $left_column .= '<td width="120" class="text-right">' . $row['pro_quantity'] . '</td>';

            $left_column .= '<td width="120"  class="text-right">'.number_format($price_average).'</td>';

            $left_column .= '<td width="120"  class="text-right">'.number_format($price_average * $row['pro_quantity']).'</td>';

            // tổng tiền tất cả mặt hàng đã chọn
            $total_all += ($price_average * $row['pro_quantity']);

            $left_column .= $list->end_tr();
        }unset($db_count_price);unset($db_price_ave);unset($db_listing);unset($db_unit_name);unset($db_query_unit);
        $left_column .= $list->showFooter();
        $total_money = number_format($total_all);
        $array_return['content']    = $left_column;
        $array_return['total']      = $total_money;
        die(json_encode($array_return));
    }

    // Thống kê bán hàng theo thực đơn
    function revenueMenus(){
        /* Phần số lượng in bếp chưa có để tính số lượng chênh lệnh so với số lượng bán hàng&*/
        $array_return = array();

        //lấy các giá trị bắn ajax về để xuất báo cáo
        $array_product  = getValue('products','arr','POST','');
        if(!$array_product){
            $array_return['content'] = 'Chưa chọn sản phẩm';
            die(json_encode($array_return));
        }
        $start_date     = convertDateTime(getValue('start_date','str','POST',''),'0:0:0');
        $end_date       = convertDateTime(getValue('end_date','str','POST',''),'0:0:0');
        $store_id       = getValue('store_id','int','POST',0);
        if(!$store_id){
            $array_return['content'] = 'Chưa chọn kho hàng';
            die(json_encode($array_return));
        }
        // select ra báo cáo với các thông tin trên
        //lấy số lượng nhập hàng
        $arr_pro = array();
        foreach($array_product as $product){
            $arr_pro[] = $product;
        }
        $arr_pro = implode(',',$arr_pro);

        $left_column = '';
        //Hiển thị danh sách phiếu thu bên trái
        #Bắt đầu với datagird
        $list = new dataGrid('men_id', 30);
        $list->add('', 'Tên thực đơn');
        $list->add('', 'ĐVT');
        $list->add('', 'SL Bán');
        $list->add('', 'In bếp');
        $list->add('', 'Chênh lệch');
        $list->add('', 'Tổng tiền');

        //lấy ra những hóa đơn có store_id đã chọn
        $array_bii = array();
        $db_bill = new db_query('SELECT bii_id FROM bill_in
                                 WHERE  bii_store_id  = '.$store_id.'
                                 AND bii_start_time <= '.$end_date.' AND bii_start_time >= '.$start_date.'
                                 ');
        while($row_bill = mysqli_fetch_assoc($db_bill->result)){
            $array_bii[] = $row_bill['bii_id'];
        } unset($db_bill);
        $array_bii = implode(',',$array_bii);
        // kiểm tra mảng rỗng điều kiện bid_bill_id sẽ là mảng array(0)
        if($array_bii == null){
            $sql_search = 'AND bid_menu_id IN(' . $arr_pro . ') AND bid_bill_id IN(0)';
        } else {
            $sql_search = 'AND bid_menu_id IN(' . $arr_pro . ') AND bid_bill_id IN(' . $array_bii . ')';
        }


        // lấy ra bản ghi để thực hiển thị ajax
        $db_count = new db_count('SELECT count(*) as count
                                  FROM bill_in_detail
                                  WHERE 1 ' . $list->sqlSearch() .$sql_search. '
                                  GROUP BY bid_menu_id
                            ');
        $total = $db_count->total;
        unset($db_count);

        $sql_query = 'SELECT * FROM bill_in_detail
                      WHERE 1 ' . $list->sqlSearch() . $sql_search . '
                      GROUP BY bid_menu_id
                      ORDER BY ' . $list->sqlSort() . 'bid_menu_id ASC
                      ' . $list->limit($total);
        $db_listing = new db_query($sql_query);

        $total_row = mysqli_num_rows($db_listing->result);

        //tao mang hien thi ten product
        $array_menu_name = array();
        $db_menus = new db_query('SELECT men_id,men_name FROM menus');
        while($row_menus = mysqli_fetch_assoc($db_menus->result)){
            $array_menu_name[$row_menus['men_id']] = $row_menus['men_name'];
        }unset($db_menus);


        //Vì đây là module cần 2 table listing nên khai báo thêm table_extra id=table-listing-left
        $left_column .= $list->showHeader($total_row, '', 'id="table-listing-right"');
        $i = 0;
        $total_all  = 0; /* Tổng của tất cả hóa đơn bán hàng theo menu */
        while ($row = mysqli_fetch_assoc($db_listing->result)) {
            $i++;
            // lấy ra pro_unit_id để
            $db_query_unit  = new db_query('SELECT men_unit_id FROM menus WHERE men_id = '.$row['bid_menu_id'].' ');
            $row_pro_unit   = mysqli_fetch_assoc($db_query_unit->result);
            // lấy ra đơn vị tính của sản phẩm
            $db_unit_name   = new db_query('SELECT uni_name FROM units WHERE uni_id = ' . $row_pro_unit['men_unit_id'] . '');
            $row_unit       = mysqli_fetch_assoc($db_unit_name->result);

            // Tổng số lượng của menu cùng id trong bảng bill_in_detail
            $db_total_number = new db_query('SELECT SUM(bid_menu_number) AS total_number
                                             FROM bill_in_detail
                                             WHERE 1 ' . $list->sqlSearch() . '
                                             AND bid_menu_id = '.$row['bid_menu_id'].'
                                             GROUP BY bid_menu_id');
            $row_total_number = mysqli_fetch_assoc($db_total_number->result);

            $left_column .= $list->start_tr($i, $row['bid_menu_id'], 'class="menu-normal record-item" data-record_id="' . $row['bid_menu_id'] . '"');
            /* code something */
            $left_column .= '<td class="text-left">' . $array_menu_name[$row['bid_menu_id']] . '</td>';

            $left_column .= '<td width="10%" class="center">' . $row_unit['uni_name'] . '</td>';

            $left_column .= '<td width="10%" class="text-right">'.number_format($row_total_number['total_number']).'</td>';

            $left_column .= '<td width="10%"  class="text-right"> 0 </td>';

            $left_column .= '<td width="10%"  class="text-right"> 0 </td>';
            $left_column .= '<td width="15%"  class="text-right">'.number_format($row_total_number['total_number']*$row['bid_menu_price']).'</td>';


            // tổng tiền tất cả mặt hàng đã chọn
            $total_all += ($row_total_number['total_number']*$row['bid_menu_price']);

            $left_column .= $list->end_tr();
        }unset($db_count_price);unset($db_price_ave);unset($db_listing);unset($db_unit_name);unset($db_query_unit);

        $left_column .= $list->showFooter();
        $total_money  = number_format($total_all);


        $array_return['content']    = $left_column;
        $array_return['total']      = $total_money;
        die(json_encode($array_return));
    }
}
$ajax = new ReportAjax();
$ajax->execute();
