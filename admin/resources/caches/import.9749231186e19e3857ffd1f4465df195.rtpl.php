<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo $load_header;?>
</head>
<body>
<div id="wrapper-full">
    <div class="col-xs-4">
        <?php echo $left_column;?>
    </div>
    <div class="col-xs-8 pull-left">
        <?php echo $right_column;?>
    </div>
    <div class="clearfix"></div>
    <div class="col-xs-12 footer-button">
        <?php echo $footer_control;?>
    </div>
    <div id="modal"></div>
    <div id="overlay"></div>
    <div id="m-window"></div>
    <div id="loading">
        <i class="fa fa-spin fa-cog"></i>
        <span id="loading-text">Đang tải dữ liệu...</span>
    </div>
    <script type="text/javascript">
        ajax_paging = true;
    </script>
    
    <?php if( $tpl_constants['DEVELOPER_ENVIRONMENT'] ){ ?>
    <script type="text/jsx" src="js/src/import.js"></script>
    <?php }else{ ?>
    <script type="text/javascript" src="js/build/import.js"></script>
    <?php } ?>
</div>
</body>
</html>