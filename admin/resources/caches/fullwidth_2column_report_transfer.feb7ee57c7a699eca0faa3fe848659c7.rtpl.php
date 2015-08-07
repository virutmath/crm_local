<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo $load_header;?>
</head>
<body>
<div id="wrapper-full">
    <div class="top-control"><?php echo $top_control;?></div>
    <div class="left-column session-left fl rlt col-sm-3">
        <div class="section-content column-wrapper">
            <?php echo $left_column;?>
        </div>
    </div>

    <div class="right-column session-right fl col-sm-3" id="right-column">
        <div class="section-content column-wrapper">
            <?php echo $right_column;?>
        </div>
    </div>
    <div class="footer-control col-xs-9"><?php echo $footer_control;?></div>
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