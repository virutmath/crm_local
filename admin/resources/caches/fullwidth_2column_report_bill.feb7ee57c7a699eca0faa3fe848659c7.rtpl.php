<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <?php echo $load_header;?>
</head>
<body>
<div id="wrapper-full">
    <div class="left-column fl rlt col-sm-3">
        <div class="section-content column-wrapper">
            <form action="" method="" class="form-report">
                <table cellspacing="0" cellpadding="0" border="0" width="100%" class="table-find-report">
                    <tr>
                        <td width="35%">Từ ngày:</td>
                        <td width="65%"><input value="<?php echo $formDate;?>" class="bill-date form-control datetime-local from-date"/></td>
                    </tr>
                    <tr>
                        <td>Đến ngày:</td>
                        <td><input value="<?php echo $toDate;?>" class="bill-date form-control datetime-local to-date"/></td>
                    </tr>
                </table>
                <?php echo $left_column;?>
                <p class="select-title" data-module="<?php echo $data_module;?>">
                    <i class="fa fa-check-circle-o filter-data"> Lọc dữ liệu</i>
                    <i class="fa fa-file-excel-o export-excel"> Xuất Excel</i>
                    <div class="clear"></div>
                </p>
                <?php echo $total_report;?>
            </form>
        </div>
    </div>
    <div class="right-column fl col-sm-3">
        <div class="section-content column-wrapper">
             <?php echo $right_column;?>
        </div>
    </div>
    <div class="section-title footer-control col-xs-12"><?php echo $footer_control;?></div>
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