<?php if(!class_exists('raintpl')){exit;}?><div class="modal-wrapper">
    <div class="modal-header">
        <span class="modal-label fl">Hệ thống quản lý nhà hàng, quán cafe</span>
        <span class="modal-close">&times;</span>
    </div>
    <div class="modal-body">
        <iframe src="<?php echo $frame_src;?>" frameborder="0" id="frame-module"></iframe>
    </div>
    <div class="modal-footer">
        <span class="module-name fl"><?php echo $module_name;?></span>
        <span class="module-rightname fl"><?php echo $module_rightname;?></span>
    <span class="modal-close fr">
        <i class="fa fa-sign-out"></i> Đóng cửa sổ
    </span>
    </div>
</div>
