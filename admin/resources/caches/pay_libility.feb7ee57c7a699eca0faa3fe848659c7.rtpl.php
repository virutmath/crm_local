<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo $load_header;?>
</head>
<body>
<div id="wrapper-full">
    <?php echo $error_msg;?>
    <div class="left-column column-half fl rlt col-xs-6">
        <div class="left-column-top">
            <div class="section-title"><?php echo $left_column_title;?></div>
            <div class="section-control"><?php echo $left_control;?></div>
            <div class="section-content column-wrapper" id="section_content"><?php echo $left_column;?></div>
            <?php echo $total_left_top;?>
        </div>
        <div class="left-column-bottom">
            <div class="bottom">
                <div class="section-control"><?php echo $bottom_left_control;?></div>
                <div class="section-content section-content-bottom column-wrapper"><?php echo $bottom_left_column;?></div>
                <?php echo $total_bottom_left;?> 
            </div>           
        </div>
    </div>

    <div class="right-column column-half fl col-xs-6">
        <div class="left-column-top">
            <div class="section-title"><?php echo $right_column_title;?></div>
            <div class="section-control"><?php echo $right_control;?></div>
            <div class="section-content column-wrapper"><?php echo $right_column;?></div>
            <?php echo $total_right_top;?>
        </div>
        <div class="left-column-bottom">
            <div class="bottom">
                <div class="section-control"><?php echo $bottom_right_control;?></div>
                <div class="section-content section-content-bottom column-wrapper"><?php echo $bottom_right_column;?></div>
                <?php echo $total_bottom_right;?>
            </div>
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