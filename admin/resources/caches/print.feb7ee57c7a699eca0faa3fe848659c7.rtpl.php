<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="vi" lang="vi" xmlns:og="http://ogp.me/ns#"
      xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo $load_header;?>
</head>
<body>
<button class="btn btn-primary btn_print" id="print_button"><i class="fa fa-print"></i> In hóa đơn</button>
    <div class="page-wrap">
        <div class="header">
            <div class="logo"><img src="<?php echo $res_logo;?>" align="<?php echo $res_name;?>"></div>
            <div class="info-top">
                <div class="title-print"> <?php echo $res_name;?></div>
                <span>
                    <strong>Địa chỉ:</strong> <?php echo $res_address;?><br>
                    <strong>Điện thoại:</strong> <?php echo $res_phone;?>
                </span>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="content">
            <div class="title-print text-center">Hóa đơn thanh toán</div>
            <table class="table-info">
                <tr>
                    <td class="text-left"><?php echo $start_time;?></td>
                    <td class="text-left"><?php echo $location;?></td>
                    <td class="text-right">Mã HĐ:<?php echo $bill_id;?></td>
                </tr>
            </table>
            <table class="table-info">
                <tr>
                    <td class="text-left"><label>Khách hàng:</label>  <?php echo $customer_name;?></td>
                    <td class="text-left"><label>Thu ngân: </label> <?php echo $admin_name;?></td>
                </tr>
            </table>
            <div class="table-listmenu">
                <table class="table table-bordered " id="table-listing">
                    <thead>
                        <tr>
                            <th class="center"><strong>STT</strong> </th>
                            <th class="text-left" width="50%"><strong>Tên thực đơn</strong></th>
                            <th class="center" width="15%"><strong>SL</strong> </th>
                            <th class="center" width="15%"><strong>Giá bán</strong></th>
                            <th class="center" width="15%"><strong>TT</strong></th>
                        </tr>
                    </thead>
                    <?php echo $list_menu;?>
                    <tfoot>
                        <tr>
                            <td colspan="4" rowspan="4" class="text-right">
                                <strong>Tổng tiền: </strong><br/>
                                <strong>Phụ phí (<?php echo $desk_extra;?>%) : </strong><br/>
                                <strong>Chiết khấu (<?php echo $desk_discount;?>%) :  </strong><br/>
                                <strong>Thuế VAT(<?php echo $desk_vat;?>%) : </strong><br/>
                                <strong>Tổng tiền thanh toán : </strong>

                            </td>
                            <td class="text-right" rowspan="4">
                                <?php echo $total;?><br/>
                                <?php echo $extra_fee;?><br/>
                                <?php echo $discount_money;?><br/>
                                <?php echo $vat_money;?><br/>
                                <?php echo $pay_money;?>
                            </td>
                        </tr>
                    </tfoot>
                  </table>
            </div>
        </div>
        <div class="footer-text center">Hẹn gặp lại quý khách !</div>
    </div>
    <?php echo $custom_script;?>
</body>
</html>