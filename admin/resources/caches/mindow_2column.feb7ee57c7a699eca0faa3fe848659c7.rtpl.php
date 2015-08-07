<?php if(!class_exists('raintpl')){exit;}?><div class="mwindow-wrapper">
    <div class="mwindow-header">
        <label><?php echo $mindow_title;?></label>
        <span class="mwindow-close">&times;</span>
    </div>
    <div class="content-mini-window">
        <div class="col-xs-4">
            <?php echo $left_column;?>
        </div>
        <div class="col-xs-8 pull-left">
            <?php echo $right_column;?>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12 footer-button">
            <?php echo $footer_button;?>
        </div>
    </div>
</div>
<?php echo $custom_script;?>