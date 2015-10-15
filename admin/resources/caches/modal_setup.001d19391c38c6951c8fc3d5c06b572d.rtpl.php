<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo $load_header;?>
</head>
<body>
<div id="wrapper-full">
    <div class="tabs-setup">
        <ul>
            <li id-tab="#setup" class="active"><i class="fa fa-cog"></i> Cài đặt chung</li>
            <li id-tab="#setup-system"><i class="fa fa-cogs"></i> Cài đặt hệ thống</li>

        </ul>
    </div>
    <div class="clearfix"></div>
    <form name="setup-form" method="post" action="index.php">
        <div class="hidden">
            <input type="hidden" name="action" value="setup">
        </div>
        <div class="content" id="setup">
        <div class="left_content">
            <h4>Thông tin cửa hàng trên bản in</h4>

            <div class="group-input">
                <label>Tên cửa hàng:</label>
                <input name="res_name" id="res_name" class="form-control" type="text" value="<?php echo $con_restaurant_name;?>">
            </div>
            <div class="group-input">
                <label>Địa chỉ:</label>
                <input name="res_address" id="res_address" value="<?php echo $con_restaurant_address;?>" class="form-control">
            </div>
            <div class="group-input">
                <label>Điện thoại:</label>
                <input name="res_phone" id="res_phone"  value="<?php echo $con_restaurant_phone;?>" class="form-control">
            </div>
            <div class="group-input">
                <label class="control-label">Logo cửa hàng</label>
                <input type="file" id="browse_img" style="position: relative; z-index: 0;">
                <input type="hidden" name="con_restaurant_image" id="con_restaurant_image" value="">
                <div class="img">
                    <img src="<?php echo $con_restaurant_image;?>" alt="" id="viewer_img" style="max-width: 150px">
                </div>
                <script>
                    UploaderScript.init({
                        browse_button : "browse_img",
                        image_wrapper : "viewer_img",
                        loading : "viewer_img"
                    },function(){
                        $("#con_restaurant_image").val(UploaderScript.config.file_name);
                    });

                </script>
            </div>
        </div>
        <div class="right_content">
            <h4>Quầy phục vụ</h4>
            <div class="group-input">
                <label>Quầy tính tiền:</label>
                <select name="store_place" id="store_place" class="textbox form-control">
                    <?php echo $array_option_desk;?>
                </select>
                <span id="setting-system"><i class="fa fa-cog"></i></span>
                <div class="clearfix"></div>
                <p><strong>Lưu ý :</strong> Lưu ý khi sử dụng nhiều quầy thanh toán. bạn tạo ra các quầy thanh toán tương ứng và lựa chọn cài đặt ở đây</p>
            </div>
            <div class="group-input">
                <label>Kho hàng:</label>
                <select name="store_list" id="store_list" class="textbox form-control">
                    <?php echo $array_store;?>
                </select>
                <div class="clearfix"></div>
                <p><strong>Lưu ý :</strong> Bạn cần lựa chọn kho hàng mặc định cho quầy thanh toán này. Nếu bạn sử dụng một kho hàng thì tất cả các quầy bạn đều lựa chọn kho hàng chung</p>
            </div>
            <div class="clearfix"></div>
            <h4>Thực đơn mặc định khi bắt đầu bàn</h4>
            <div class="clearfix"></div>
            <strong>Có (0) thực đơn trong danh sách</strong>
            <button class="button" type="button" id="list-menu-default" onclick="listmenu_default()"> Quản lý </button>
        </div>
        <div class="footer-control col-xs-12">
            <label class="control-btn pull-right" onclick="config_submit()">
                <i class="fa fa-save"></i>
                Lưu lại
            </label>
        </div>
    </div>
    </form>

    <form name="setup-system" method="post" action="">
        <div class="hidden">
            <input type="hidden" name="action" value="setup-system">
        </div>
        <div class="content" id="setup-system">
        <div class="left_content">
            <h4>Bán hàng, nhập hàng</h4>
            <div class="group_checkbox">
                <label>
                    <span>Cho phép tự nhập ngày giờ bán hàng</span>
                    <input type="checkbox" name="checkbox" value="c1">
                </label>
                <label>
                    <span>Cho phép tự nhập ngày giờ nhập hàng</span>
                    <input type="checkbox" name="checkbox" value="c2">
                </label>
                <label>
                    <span>Cho phép xuất âm kho hàng</span>
                    <input type="checkbox" name="checkbox" value="c3">
                </label>
                <label>
                    <span>Cho phép nhập giá tùy chọn</span>
                    <input type="checkbox" name="checkbox" value="c4">
                </label>

            </div>
            <div class="clearfix"></div>
            <div class="box_bottom">
                <h6>Khi bán hàng không chọn nhân viên</h6>
                <div class="row">
                    <div class="col-xs-6">
                        <label><input type="radio" name="staff" value="staff_choose"> Yêu cầu chọn</label>
                    </div>
                    <div class="col-xs-6">
                        <label><input type="radio" name="staff" value="staff_default"> Lấy nhân viên mặc định</label>
                    </div>
                </div>
                <h6>Khi bán hàng không chọn khách hàng</h6>
                <div class="row">
                    <div class="col-xs-6">
                        <label><input type="radio" name="customer" value="cus_choose"> Yêu cầu chọn</label>
                    </div>
                    <div class="col-xs-6">
                        <label><input type="radio" name="customer" value="cus_default"> Khách hàng là lẻ</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="right_content">
            <h4>Khuyến mãi - Thành viên</h4>
            <span>Khi có 2 kiểu khuyến mãi tại cùng một thời điểm thì áp dụng:</span>
            <div class="group_radio_right">
                <label><input type="radio" name="radio-sale" id="sale1"> <span>Khuyến mãi có giá trị cao hơn</span></label>
                <label><input type="radio" name="radio-sale" id="sale2"> <span>Khuyến mãi theo chiến dịch hiện đang chạy</span></label>
                <label><input type="radio" name="radio-sale" id="sale3"> <span>Khuyến mãi theo thành viên (vàng, bạc, đồng...)</span></label>
            </div>
            <div class="clearfix"></div>
            <div class="group_checkbox">
                 <label>
                     Tự động nâng cấp cấp độ cho khách hàng khi đủ doanh số mua hàng
                     <input type="checkbox" name="update_cus" value="1">
                 </label>
            </div>
            <div class="clearfix"></div>
            <h4>Cảnh báo hệ thống</h4>
            <div class="group_checkbox">
                <label>
                    Hiển thị cảnh báo từ hệ thống
                    <input type="checkbox" name="notification" id="notification" value="notice">
                </label>
            </div>
            <div class="clearfix"></div>
            <div class="box-notice">
                Cảnh báo thời gian công nợ trước thời hạn <input type="text" id="congno" disabled="disabled" name="congno" style="width: 40px"> ngày
                <p>Đây là mức thời hạn tối thiểu để cảnh báo công nợ sắp đến thời hạn phải thanh toán. Thời gian được tính bằng ngày</p>
            </div>
        </div>
            <div class="footer-control col-xs-12">
                <label class="control-btn pull-right" onclick="config_submit()">
                    <i class="fa fa-save"></i>
                    Lưu lại
                </label>
            </div>
    </div>
    </form>

    <?php echo $custom_script;?>
    <div id="modal"></div>
    <div id="overlay"></div>
    <div id="m-window"></div>
    <div id="loading">
        <i class="fa fa-spin fa-cog"></i>
        <span id="loading-text">Đang tải dữ liệu...</span>
    </div>
</div>
</body>
</html>
