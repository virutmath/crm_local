<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <?php echo $load_header;?>
</head>
<body>
<div id="wrapper-full">
    <div class="col-xs-12" id="content-column">
        <div class="detail_content_left">
            <div class="title_detail_bill col-xs-12"><?php echo $title_detail_left;?></div>
            <div class="col-xs-12 section-content"><?php echo $left_column;?></div>
            <?php echo $phuphi;?>
        </div>
        <div class="detail_content_righ">
            <div class="title_detail_bill col-xs-12"><?php echo $title_detail_right;?></div>
            <div class="col-xs-12"><?php echo $right_content;?></div>
        </div>
        <div class="clear_"></div>
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