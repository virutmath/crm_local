<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo $load_header;?>
    <script type="text/javascript">var total_money = <?php echo $total_money;?>;</script>
</head>
<body>
    <?php echo $content_column;?>
    <script type="text/javascript" src="script_debit.js"></script>
</body>
</html>