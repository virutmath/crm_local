<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <?php echo $load_header;?>
</head>
<body>
<div id="wrapper-full">
    <div class="col-xs-12" id="content-column">
        <?php echo $content_column;?>
    </div>
    <div class="clearfix"></div>
    <div class="col-xs-12 footer-button">
        <?php echo $footer_control;?>
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