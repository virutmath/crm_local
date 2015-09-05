<?php if(!class_exists('raintpl')){exit;}?><div class="mwindow-wrapper">
    <div class="mwindow-header">
        <label><?php echo $mindow_title;?></label>
        <span class="mwindow-close">&times;</span>
    </div>
    <div class="content-mini-window">
        <iframe src="<?php echo $frame_src;?>" frameborder="0" id="frame-mindow"></iframe>
    </div>
</div>