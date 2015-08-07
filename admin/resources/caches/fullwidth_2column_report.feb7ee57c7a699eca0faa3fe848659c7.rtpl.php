<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo $load_header;?>
</head>
<body>
<div id="wrapper-full">
    <div class="left-column fl rlt col-sm-3">
        <div class="section-title"><?php echo $left_column_title;?></div>
        <div class="section-control"><?php echo $left_control;?></div>
        <div class="section-content column-wrapper">
            <?php echo $left_column;?>
        </div>
    </div>

    <div class="right-column fl col-sm-3">
        <div class="section-title"><?php echo $right_column_title;?></div>
        <div class="section-control"><?php echo $right_control;?></div>
        <div class="section-content column-wrapper">
            <iframe src="<?php echo $right_column;?>" width="100%" id="report" name="report" style="height: 100%; display: inline-block;"></iframe>
        </div>
    </div>
    <div class="footer-control col-xs-12"><?php echo $footer_control;?></div>
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