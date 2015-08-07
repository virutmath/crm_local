<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo $load_header;?>
</head>
<body>
<div id="wrapper-1column">
    <?php echo $error_msg;?>

    <div class="left-column fl col-sm-12">
        <div class="section-title"><?php echo $left_column_title;?></div>
        <div class="section-control"><?php echo $left_control;?></div>
        <div class="section-content column-wrapper">
            <?php echo $left_column;?>
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