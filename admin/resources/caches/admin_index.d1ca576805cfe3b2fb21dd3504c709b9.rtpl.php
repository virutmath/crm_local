<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CRM Restaurant</title>
    <link rel="stylesheet" type="text/css" href="resources/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="resources/js/enscroll.css"/>
    <link rel="stylesheet" type="text/css" href="resources/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="resources/css/home.css"/>
    <link rel="stylesheet" type="text/css" href="resources/css/template2.css"/>
    <link rel="stylesheet" type="text/css" href="resources/css/template.css"/>

    <script src="resources/js/jquery.js" type="text/javascript"></script>
    <script src="resources/js/jqueryui/ui/core.js"></script>
    <script src="resources/js/jqueryui/ui/widget.js"></script>
    <script src="resources/js/jqueryui/ui/mouse.js"></script>
    <script src="resources/js/jqueryui/ui/position.js"></script>
    <script src="resources/js/jqueryui/ui/draggable.js"></script>
    <script src="resources/js/enscroll-0.5.5.min.js" type="text/javascript"></script>
    <script src="resources/js/home.js" type="text/javascript"></script>
    <script src="resources/js/crm.js" type="text/javascript"></script>

</head>
<body>
<header>
    <div id="menu">
        <ul>
            <li class="menu-function rlt">
                <i class="fa fa-bars"></i>
                <div class="list-function-menu abs">
                    <div class="function-item">
                        <a href="<?php echo $link_settings;?>" rel="mwindow" mtype="small" title="Cài đặt hệ thống">
                            <i class="fa fa-cog fa-fw"></i>
                            <span class="function-name">
                                Hệ thống
                            </span>
                        </a>
                    </div>
                    <div class="function-item">
                        <a href="<?php echo $link_manager_user;?>" rel="mwindow" mtype="medium" title="Quản lý tài khoản đăng nhập">
                            <i class="fa fa-user-md fa-fw"></i>
                            <span class="function-name">
                                Quản lý người dùng
                            </span>
                        </a>
                    </div>
                    <div class="function-item">
                        <a href="<?php echo $link_manager_desk;?>" rel="mwindow" mtype="medium" title="Quản lý khu vực, bàn ăn">
                            <i class="fa fa-cutlery fa-fw"></i>
                            <span class="function-name">
                                Quản lý khu vực, bàn ăn
                            </span>
                        </a>
                    </div>
                    <div class="function-item">
                        <a href="<?php echo $link_manager_supplier;?>" rel="mwindow" mtype="medium" title="Danh sách nhà cung cấp">
                            <i class="fa fa-truck fa-fw"></i>
                            <span class="function-name">
                                Quản lý nhà cung cấp
                            </span>
                        </a>
                    </div>
                    <div class="function-item">
                        <a href="<?php echo $link_manager_agencies;?>" rel="mwindow" mtype="medium" title="Danh sách các cửa hàng" >
                            <i class="fa fa-home fa-fw"></i>
                            <span class="function-name">
                                Quản lý cửa hàng
                            </span>
                        </a>
                    </div>
                    <div class="function-item">
                        <a href="<?php echo $link_manager_menus;?>" rel="iframe">
                            <i class="fa fa-bars fa-fw"></i>
                            <span class="function-name">
                                Quản lý thực đơn
                            </span>
                        </a>
                    </div>
                    <div class="function-item">
                        <div class="function-multi">
                            <i class="fa fa-users fa-fw"></i>
                            <span class="function-name">Trung tâm khách hàng<i class="fa fa-caret-right"></i></span>
                            <ul class="function-list-child">
                                <li>
                                    <a href="<?php echo $link_manager_customers;?>" rel="iframe">
                                        <i class="fa fa-list fa-fw"></i>
                                        <span class="function-name">
                                            Quản lý khách hàng
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $link_promotions;?>" rel="iframe">
                                        <i class="fa fa-table fa-fw"></i>
                                        <span class="function-name">
                                            Chiến dịch khuyến mãi
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="function-item">
                        <a href="<?php echo $link_manager_users;?>" rel="iframe">
                            <i class="fa fa-user fa-fw"></i>
                            <span class="function-name">
                                Quản lý nhân sự
                            </span>
                        </a>
                    </div>
                    <div class="function-item">
                        <div class="function-multi">
                            <i class="fa fa-home fa-fw"></i>
                            <span class="function-name">Quản lý kho hàng <i class="fa fa-caret-right"></i></span>
                            <ul class="function-list-child">
                                <li>
                                    <a href="<?php echo $link_manager_products;?>" rel="iframe">
                                        <i class="fa fa-table fa-fw"></i>
                                        <span class="function-name">
                                            Quản lý kho hàng
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $link_listing_stores;?>" rel="modal" modal-type="small" modal-name="Danh sách kho hàng">
                                        <i class="fa fa-list fa-fw"></i>
                                        <span class="function-name">
                                            Danh sách kho hàng
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $link_import_stores;?>" rel="mwindow" mtype="large" title="Nhập hàng vào kho" >
                                        <i class="fa fa-plus-circle fa-fw"></i>
                                        <span class="function-name">
                                            Nhập hàng vào kho
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $link_inventory_transfer;?>" rel="iframe">
                                        <i class="fa fa-exchange fa-fw"></i>
                                        <span class="function-name">
                                            Chuyển kho
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $link_inventory_transfer_product;?>" rel="iframe">
                                        <i class="fa fa-check fa-fw"></i>
                                        <span class="function-name">
                                            Kiểm kê kho hàng
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $link_listing_stores;?>" rel="iframe">
                                        <i class="fa fa-cog fa-fw"></i>
                                        <span class="function-name">
                                            Cài đặt giá bán theo cửa hàng
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="function-item">
                        <div class="function-multi">
                            <i class="fa fa-money fa-fw"></i>
                            <span class="function-name">Quản lý quỹ tiền<i class="fa fa-caret-right"></i></span>
                            <ul class="function-list-child">
                                <li>
                                    <a href="<?php echo $link_cat_fins;?>" rel="modal" modal-type="small" modal-name="Danh sách lý do thu chi">
                                        <i class="fa fa-list fa-fw"></i>
                                        <span class="function-name">
                                            Danh sách lý do thu chi
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $link_manager_fins;?>" rel="iframe">
                                        <i class="fa fa-table fa-fw"></i>
                                        <span class="function-name">
                                            Quản lý phiếu thu, chi
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
            <?php $counter1=-1; if( isset($list_nav) && is_array($list_nav) && sizeof($list_nav) ) foreach( $list_nav as $key1 => $value1 ){ $counter1++; ?>
            <li class="navigate-top" data-module-id="<?php echo $value1["mod_id"];?>">
                <?php if( $value1["active"] ){ ?>
                <a href="<?php echo $value1["link"];?>" rel="iframe" class="active"><?php echo $value1["label"];?></a>
                <?php }else{ ?>
                <a href="<?php echo $value1["link"];?>" rel="iframe"><?php echo $value1["label"];?></a>
                <?php } ?>
            </li>
            <?php } ?>
        </ul>
        <ul class="ul-right" style="float: right">
            <li>
                <a href="logout.php">
                    <span class="user-name" title="<?php echo $user_note;?>">
                        <?php echo $user_name;?>
                    </span>
                    <i class="fa fa-sign-out fa-fw"></i>
                </a>
            </li>
        </ul>
    </div>
</header>
<div id="main-content" class="ov fl">
    <iframe id="main_frame" src="<?php echo $link_home;?>" style="width : 100%;height : 100%;border : none;"></iframe>
</div>
<footer id="footer">
    <span><?php echo $configuration['age_name'];?> - <?php echo $configuration['sed_name'];?> | <?php echo $configuration['cat_name'];?></span>
    <a id="AppOffline" href="http://localhost:8027/gotoapp">Ngoại tuyến</a>
</footer>
<div id="overlay"></div>
<div id="modal"></div>
<div id="m-window"></div>
<div id="loading">
    <i class="fa fa-spin fa-cog"></i>
    <span id="loading-text">Đang tải dữ liệu...</span>
</div>
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
    synchronize_data();
    isOnline(function () {
        $('#AppOffline').hide();
        $('#navigator-online').hide();
        $('#overlay').hide().css({
            background : 'transparent'
        });
    }, function () {
        $('#AppOffline').show();
        $('#overlay').show().css({
            background : 'rgba(0, 0, 0, 0.3)'
        });
        $('#navigator-online').show();
    });

    function closeNavigator() {
        $('#overlay').hide();
        $('#navigator-online').hide();
    }
</script>
</body>
</html>