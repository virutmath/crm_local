<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo $load_header;?>
</head>
<body>
<div id="wrapper">
    <?php echo $error_msg;?>
    <div class="left-column fl rlt">
        <?php echo $left_control;?>
        <div class="column-content">
            <?php echo $left_column;?>
        </div>
    </div>
    <div class="right-column fl">
        <?php echo $right_control;?>
        <div class="column-wrapper modal-column">
            <?php echo $right_column;?>
        </div>
    </div>
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
