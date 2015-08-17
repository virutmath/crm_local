<?
require_once 'inc_security.php';
$import_menu        = isset($_FILES['import_menu']) ? $_FILES['import_menu'] : '';
if($import_menu)
{
    $filename       = $import_menu['tmp_name'];
    $arrMenuPie     = analyzeExcel_Pie($filename);
    foreach ( $arrMenuPie as $value )
    {
        $men_name   = $value['ten_thucdon'];
        $menu1      = $value['menu_cap_1'];
        $menu2      = $value['menu_cap_2'];
        if ( $menu1     == '' ) $menu1 = $menu2;
        if ( $menu1 == '' && $menu2 == '' || $menu2 == '' ) continue;
        $unit_id = 0; 
        $donvi_tinh = $value['donvi_tinh'];
        $uni_note = '';
        // kiem tra xem don vi tinh da ton tai trong bang units chua
        $db_unit    = new db_query("SELECT uni_id FROM units 
                                    WHERE uni_name = '" . trim($donvi_tinh) . "'");
         //neu co roi thi lay ra id cua don vi tinh do
        if( mysqli_num_rows($db_unit->result) >= 1 )
        {
            $data_units = mysqli_fetch_assoc($db_unit->result);
             //id don vi tinh
            $unit_id    = $data_units['uni_id'];
        } //neu chua co thi insert don vi tinh vao bang unit sau do lai lay ra id cua don vi tinh vua insert vao
        else{
            $db_insert_unit = new db_execute_return;
            $db_units_id    = $db_insert_unit->db_execute("INSERT INTO units
                                                            (
                                                            uni_name, 
                                                            uni_note
                                                            ) 
                                                            VALUES 
                                                            (
                                                            '" . trim($donvi_tinh) . "',
                                                            '".$uni_note."'
                                                            )");
            unset($db_insert_unit);
            $unit_id = $db_units_id;
        }unset($db_unit);
        //
        $db_categories_1 = new db_query("SELECT cat_id FROM categories_multi
                                        WHERE cat_name = '" .trim($menu1) . "' 
                                        AND cat_type = '" . MENU_CAT_TYPE . "'");
        if ( mysqli_num_rows($db_categories_1->result) >= 1 )
        {
            $data_cat   = mysqli_fetch_assoc($db_categories_1->result);
            $cat_id = $data_cat['cat_id'];
        }
        else
        {
            $cat_desc = '';
            $cat_picture = '';
            $cat_parent_id = 0;
            $cat_has_child = 0;
            $cat_note = '';
            $db_insert_categories = new db_execute_return;
            $db_categories_id = $db_insert_categories->db_execute("INSERT INTO categories_multi
                                                                (
                                                                cat_name, 
                                                                cat_type, 
                                                                cat_desc, 
                                                                cat_picture, 
                                                                cat_parent_id, 
                                                                cat_has_child, cat_note
                                                                ) VALUES (
                                                                '" .trim($menu1) . "',
                                                                '" . MENU_CAT_TYPE . "',
                                                                '".$cat_desc."',
                                                                '".$cat_picture."',
                                                                ".$cat_parent_id.",
                                                                ".$cat_has_child.",
                                                                '".$cat_note."'
                                                                )");
            unset($db_insert_categories);
            $cat_id = $db_categories_id;
        }
        $men_cat_id = $cat_id;
        unset($db_categories_1); 
        //
        // kiem tra xem menu cap 2 da ton tai hay chua  neu chua thi inset thang ban ghi moi
        $db_categories_2 = new db_query("SELECT cat_id FROM categories_multi
                                        WHERE cat_name = '" .trim($menu2) . "' 
                                        AND cat_parent_id = " . $cat_id . "
                                        AND cat_type = '" . MENU_CAT_TYPE . "'");
        if ( mysqli_num_rows($db_categories_2->result) >= 1 )
        {
            $data_cat_2   = mysqli_fetch_assoc($db_categories_2->result);
            $men_cat_id = $data_cat_2['cat_id'];
        }
        else
        {
            $cat_desc = '';
            $cat_picture = '';
            $cat_parent_id = $cat_id;
            $cat_has_child = 0;
            $cat_note = '';
            $db_insert_categories = new db_execute_return;
            $db_categories_id = $db_insert_categories->db_execute("INSERT INTO categories_multi
                                                                (
                                                                cat_name, 
                                                                cat_type, 
                                                                cat_desc, 
                                                                cat_picture, 
                                                                cat_parent_id, 
                                                                cat_has_child, cat_note
                                                                ) VALUES (
                                                                '" .trim($menu2) . "',
                                                                '" . MENU_CAT_TYPE . "',
                                                                '".$cat_desc."',
                                                                '".$cat_picture."',
                                                                ".$cat_parent_id.",
                                                                ".$cat_has_child.",
                                                                '".$cat_note."'
                                                                )");
            unset($db_insert_categories);
            $men_cat_id = $db_categories_id;
        }unset($db_categories_2);
        //
        //kiem tra xem menu co trong csdl k 
        //neu co thì update lai thong tin menu dong thoi lay ra id cua menu do
        //chua co thì insert moi
        $menu_id = 0;
        $men_price = '' ? 0 : floatval($value['gia_ban']);
        $men_price1 = 0;
        $men_price2 = 0;
        $men_image = '';
        $men_note = '';
        $men_editable = 0;
        $db_menu        = new db_query("SELECT * FROM menus 
                                        WHERE men_name = '" . trim($men_name) . "'");
        if ( mysqli_num_rows($db_menu->result) >= 1)
        {
            $db_update_menu = new db_execute("UPDATE menus
                                            men_unit_id=".$unit_id.",
                                            men_cat_id=".$men_cat_id.",
                                            men_price=".$men_price.",
                                            men_price1=".$men_price1.",
                                            men_price2=".$men_price2.",
                                            men_image='".$men_image."',
                                            men_note='".$men_note."',
                                            men_editable=".$men_editable." 
                                            WHERE men_name = '" . trim($men_name) . "'"
                                            );
            unset($db_update_menu);
            $data_menu = mysqli_fetch_assoc($db_menu->result);
            $menu_id = $data_menu['men_id'];
        }else
        {
            $db_insert_menu = new db_execute_return;
            $db_menu_id     = $db_insert_menu->db_execute("INSERT INTO menus
                                                            (
                                                            men_name, 
                                                            men_unit_id, 
                                                            men_cat_id, 
                                                            men_price, 
                                                            men_price1, 
                                                            men_price2, 
                                                            men_image, 
                                                            men_note, 
                                                            men_editable
                                                            ) 
                                                            VALUES(
                                                            '".trim($men_name)."',
                                                            ".$unit_id.",
                                                            ".$men_cat_id.",
                                                            ".$men_price.",
                                                            ".$men_price1.",
                                                            ".$men_price2.",
                                                            '".$men_image."',
                                                            '".$men_note."',
                                                            ".$men_editable."
                                                            )");
            unset($db_insert_menu);
            $menu_id = $db_menu_id;
        }
        //
        $pro_name = $value['nguyen_lieu']; 
        $pro_image = '';
        $pro_note = '';
        $pro_cat_id = 0;
        $pro_unit_id = 0;
        $pro_code = '';
        $pro_instock = 0;
        $pro_status = 0;
        $idPro = 0;
        $mep_quantity = 1; 
        // kiem tra xem ten nguyen lieu da ton tai trong csdl chua
        // roi thi update so luong
        // chua thi insert ban ghi moi
        $dbPro  = new db_query("SELECT pro_id FROM products 
                                WHERE pro_name = '" . trim($pro_name) . "'");
        if( mysqli_num_rows($dbPro->result) >= 1 )
        {
            $dataPro = mysqli_fetch_assoc($dbPro->result);
             //lay ra id cua nguyen lieu
            $idPro   = $dataPro['pro_id'];
        }
        else{   
            $db_insert_product  = new db_execute_return;
            $db_product_id      = $db_insert_product->db_execute("INSERT INTO products
                                                                (
                                                                pro_name, 
                                                                pro_image, 
                                                                pro_note, 
                                                                pro_cat_id,
                                                                pro_unit_id, 
                                                                pro_code, 
                                                                pro_instock, 
                                                                pro_status
                                                                ) 
                                                                VALUES 
                                                                (
                                                                '" . trim($pro_name) . "',
                                                                '" . $pro_image . "',
                                                                '" . $pro_note . "',
                                                                " . $pro_cat_id . ",
                                                                " . $pro_unit_id . ",
                                                                '" . $pro_code . "',
                                                                " . $pro_instock . ",
                                                                " . $pro_status . "
                                                                )");
            
            unset($db_insert_product);
            $idPro = $db_product_id;
        }unset($dbPro);
        //
        $db_menu_quantity = new db_query ("SELECT * FROM menu_products 
                                           WHERE mep_menu_id = " . $menu_id . "
                                           AND mep_product_id = " . $idPro . "");
        if ( mysqli_num_rows($db_menu_quantity->result) == 0 )
        {
            $sql_menu_products = new db_execute("INSERT INTO menu_products
                                                (
                                                mep_menu_id, 
                                                mep_product_id, 
                                                mep_quantity
                                                ) 
                                                VALUES(
                                                ".$menu_id.",
                                                ".$idPro.",
                                                ".floatval($mep_quantity)."
                                                )");
            unset($sql_menu_products);
        }
    }
}
?>
<form action="" method="post" enctype="multipart/form-data" id="update-menu" name="update_menu">
<input id="file-menu" type="file" name="import_menu" class="file_menu" onchange="updateMenu()"/>
<button type="submit" id="submit_form"> abc </button>
</form>