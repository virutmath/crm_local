<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="vi" lang="vi" xmlns:og="http://ogp.me/ns#"
      xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo $load_header;?>
</head>
<body>
<div class="page-wrap">
    <div class="clearfix"></div>
    <div class="content">
        <div class="print-header col-xs-12">
            <div class="print-control pull-left">
                <span id="edit-print-number">
                    <i class="fa fa-edit"></i>
                    Sửa số lượng
                </span>
                <span id="delete-menu">
                    <i class="fa fa-trash"></i>
                    Xóa
                </span>
                <span id="reload-menu">
                    <i class="fa fa-refresh"></i>
                    Tải lại phiếu in bếp
                </span>
            </div>
            <div class="pull-right">
                <b><?php echo $desk_detail['desk_name'];?></b>
                <input type="hidden" id="desk_id" value="<?php echo $desk_detail['des_id'];?>">
            </div>
        </div>


        <div class="col-xs-12">
            <div class="print-list-menu">
                <table class="table table-bordered table-listing" id="table-listing">
                    <thead>
                    <tr>
                        <th class="center" width="26px;"><strong>STT</strong></th>
                        <th class="text-left" width="40%"><strong>Tên thực đơn</strong></th>
                        <th class="center"><strong>ĐVT</strong></th>
                        <th class="center"><strong>In thêm</strong></th>
                        <th class="center"><strong>Đã order</strong></th>
                        <th class="center"><strong>Đã in</strong></th>
                    </tr>
                    </thead>
                    <?php $counter1=-1; if( isset($list_menu) && is_array($list_menu) && sizeof($list_menu) ) foreach( $list_menu as $key1 => $value1 ){ $counter1++; ?>
                    <tbody>
                    <tr>
                        <td class="center"><?php echo $value1["stt"];?></td>
                        <td class="text-left"><?php echo $value1["men_name"];?></td>
                        <td class="center"><?php echo $value1["uni_name"];?></td>
                        <td class="text-right"><?php echo $value1["print_number"];?></td>
                        <td class="text-right"><?php echo $value1["cdm_number"];?></td>
                        <td class="text-right"><?php echo $value1["cdm_printed_number"];?></td>
                    </tr>
                    </tbody>
                    <?php } ?>
                </table>
            </div>

        </div>
        <div class="print-note col-xs-12">
            <input type="text" id="print-note-input" class="col-xs-12" placeholder="Ghi chú...">
        </div>
        <div class="col-xs-12">
            <p>
                <b>Mặc định:</b> Hệ thống sẽ tự động thêm thực đơn chưa được in xuống bếp
                vào danh sách theo số lượng tương ứng. Nếu một thực đơn được gọi nhiều lần
                thì danh sách sẽ hiển thị số lượng gọi thêm tính từ lúc in bếp gần nhất
            </p>
        </div>
        <div class="print-footer col-xs-12">
            <div class="pull-right control-footer">
                <span id="print-order">
                    <i class="fa fa-print"></i>
                    In phiếu
                </span>
                <span id="close-window">
                    <i class="fa fa-sign-out"></i>
                    Đóng cửa sổ
                </span>
            </div>
        </div>
    </div>
</div>
<div id="print-area" class="hidden">
    <div class="center col-xs-12">
        <b><?php echo $configuration['con_restaurant_name'];?></b>
    </div>
    <div class="center col-xs-12">
        <label>Phiếu in bếp</label>
    </div>
    <div class="col-xs-12">
        <div class="pull-left"><?php echo $print_date;?></div>
        <div class="pull-right"><?php echo $desk_detail['desk_name'];?></div>
    </div>
    <table class="table table-bordered" id="list-menu-print">
        <thead>
        <tr>
            <th>STT</th>
            <th>Tên thực đơn</th>
            <th>Đơn vị tính</th>
            <th>Số lượng chế biến</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
<script>
    var ListMenu = <?php echo $list_menu_json;?>;
</script>
<script type="text/javascript" src="js/print_order.js"></script>
</body>
</html>