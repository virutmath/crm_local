<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CRM Restaurant</title>
    <link rel="stylesheet" type="text/css" href="resources/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="resources/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="resources/js/enscroll.css"/>
    <link rel="stylesheet" type="text/css" href="resources/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="resources/css/home.css"/>
    <link rel="stylesheet" type="text/css" href="resources/css/config.css"/>
    <script src="resources/js/jquery.js" type="text/javascript"></script>
    <script src="resources/js/enscroll-0.5.5.min.js" type="text/javascript"></script>
    <script src="resources/js/home.js" type="text/javascript"></script>
    <script src="resources/js/crm.js" type="text/javascript"></script>
</head>
<body>
<header>
</header>
<form action="user_config.php" method="post">
    <div id="main-content container" class="ov">
        <div class="configuration-wrapper">
            <h2 class="text-center">Cài đặt thông tin cửa hàng, thông tin in ấn</h2>
            <p class="text-center">Các thông tin nhà hàng này được lưu lại để hiển thị trên phần mềm, trên tiêu đề các bản in, tiêu đề các bản Excel được xuất ra</p>
            <hr/>
            <div class="form-horizontal col-xs-7">
                <div class="form-group">
                    <label for="con_restaurant_name" class="col-xs-4 control-label">Tên nhà hàng</label>
                    <div class="col-xs-8">
                        <input type="text" class="form-control" name="con_restaurant_name" id="con_restaurant_name" value="<?php echo $config_data['con_restaurant_name'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="con_restaurant_address" class="col-xs-4 control-label">Địa chỉ</label>
                    <div class="col-xs-8">
                        <input type="text" name="con_restaurant_address" id="con_restaurant_address" class="form-control" value="<?php echo $config_data['con_restaurant_address'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="con_restaurant_phone" class="col-xs-4 control-label">Điện thoại</label>
                    <div class="col-xs-8">
                        <input name="con_restaurant_phone" id="con_restaurant_phone" type="text" class="form-control" value="<?php echo $config_data['con_restaurant_phone'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-xs-4 control-label">Logo</label>
                    <div class="col-xs-5">
                        <div class="img-thumb">
                            <img src="" alt="" name=""/>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="row">
                            <label for="logo" class="col-xs-12"><button class="btn btn-success col-xs-12 button-logo">Thay đổi ảnh</button></label>
                            <input type="file" id="logo" class="hidden"/>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                Kích thước tiêu chuẩn
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                150x120
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-horizontal col-xs-5">
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="con_default_svdesk">Chi nhánh</label>
                    <div class="col-xs-8">
                        <div class="input-group">
                            <select name="con_default_agency" id="con_default_agency" class="form-control" onchange="changeAgency()">
                                <?php $counter1=-1; if( isset($list_agencies) && is_array($list_agencies) && sizeof($list_agencies) ) foreach( $list_agencies as $key1 => $value1 ){ $counter1++; ?>
                                <option value="<?php echo $value1["age_id"];?>" <?php echo $value1["selected"];?> ><?php echo $value1["age_name"];?></option>
                                <?php } ?>
                            </select>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-cog"></i></button>
                        </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="con_default_svdesk">Quầy tính tiền</label>
                    <div class="col-xs-8">
                        <div class="input-group">
                            <select name="con_default_svdesk" id="con_default_svdesk" class="form-control" >
                                <?php $counter1=-1; if( isset($list_service_desk) && is_array($list_service_desk) && sizeof($list_service_desk) ) foreach( $list_service_desk as $key1 => $value1 ){ $counter1++; ?>
                                <option value="<?php echo $value1["sed_id"];?>" <?php echo $value1["selected"];?> ><?php echo $value1["sed_name"];?></option>
                                <?php } ?>
                            </select>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-cog"></i></button>
                        </span>
                        </div>
                    </div>
                </div>
                <div class="help-block">Lưu ý: khi sử dụng nhiều quầy thanh toán, bạn tạo ra các quầy thanh toán tương ứng và lựa chọn cài đặt ở đây</div>
                <hr/>
                <div class="form-group">
                    <label class="control-label col-xs-4" for="con_default_store">Kho hàng</label>
                    <div class="col-xs-8">
                        <div class="input-group">
                            <select name="con_default_store" id="con_default_store" class="form-control">
                                <?php $counter1=-1; if( isset($list_store) && is_array($list_store) && sizeof($list_store) ) foreach( $list_store as $key1 => $value1 ){ $counter1++; ?>
                                <option value="<?php echo $value1["cat_id"];?>" <?php echo $value1["selected"];?> ><?php echo $value1["cat_name"];?></option>
                                <?php } ?>
                            </select>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-cog"></i></button>
                        </span>
                        </div>
                    </div>
                </div>
                <div class="help-block">Lưu ý: Bạn cần lựa chọn kho hàng mặc định cho quầy thanh toán này. Nếu bạn sử dụng chung 1 kho hàng thì tất cả các quầy bạn đều lựa chọn kho hàng chung</div>
                <hr/>
            </div>
            <div class="col-xs-12 text-center">
                <div class="hidden">
                    <input type="hidden" name="action" value="execute"/>
                </div>
                <button class="btn btn-primary" style="width : 10%" type="submit">Lưu lại</button>
                <button class="btn btn-default" style="width : 10%" onclick="window.location.href='index.php'">Tiếp tục</button>
            </div>
        </div>
    </div>
</form>

<footer id="footer">
    <span>Cơ sở chính</span>
    <a id="AppOffline" href="http://localhost:8027/gotoapp">Ngoại tuyến</a>
</footer>
<div id="overlay"></div>
<div id="modal"></div>
<div id="navigator-online">
    <div class="navigator-header">
        <h1>Hệ thống đang ngoại tuyến</h1>
    </div>
    <div class="navigator-control">
        <button class="gotoapp">
            <a href="http://localhost:8027/gotoapp">
                Đi đến ứng dụng ngoại tuyến
            </a>
        </button>
        <button onclick="closeNavigator()">Tiếp tục</button>
    </div>
</div>
<script type="text/javascript">
    var main_iframe = $('#main_frame');
    $(window).resize(function () {
        //window.location.reload();
    });

    isOnline(function () {
        $('#AppOffline').hide();
        $('#navigator-online').hide();
        $('#overlay').hide();
    }, function () {
        $('#AppOffline').show();
        $('#overlay').show();
        $('#navigator-online').show();
    });

    function closeNavigator() {
        $('#overlay').hide();
        $('#navigator-online').hide();
    }

    //load quầy phục vụ ở các chi nhánh khác nhau
    function changeAgency() {
        $.ajax({
            type : 'post',
            data : {age_id : $('#con_default_agency').val(), action : 'changeAgency'},
            success : function (html) {
                $('#con_default_svdesk').html(html).removeAttr('disabled');
            }
        })
    }
</script>
</body>
</html>