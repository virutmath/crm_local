<?
require_once 'inc_security.php';
$id             = getValue('id','str','POST','',3);
$tabel          = getValue('tabel','str','POST','',3);
function isset_($elm){
    $cont = isset($elm) ? $elm : '';
    return $cont;
}
// khai bao bien
$ma_kh  = '';
$name   = '';
$avata = '';
$addrss = '';
$phone  = '';
$email  = '';
$team_kh= '';
$note   = '';
//
    if(trim($tabel) == 'customers'){
        $title      = 'Thông tin khách hàng';
        $t_ma_      = 'Mã KH';
        $t_team_    = 'Nhóm khách hàng';
        if($id      == 0) {
            $ma_kh  = '';
            $name   = 'Khách lẻ';
            $addrss = '';
            $phone  = '';
            $email  = '';
            $team_kh= '';
            $note   = '';
        }
        if($id      != 0) {
            $sql    = new db_query('SELECT * FROM ' . $tabel . ' 
                                    INNER JOIN customer_cat ON customers.cus_cat_id = customer_cat.cus_cat_id
                                    WHERE cus_id = ' . intval($id));
            $row    = mysqli_fetch_assoc($sql->result);unset($sql);
            
            $ma_kh  = format_codenumber($row['cus_id'],6,'KH');
            $avata  = $row['cus_picture'];
            $name   = $row['cus_name'];
            $addrss = $row['cus_address'];
            $phone  = $row['cus_phone'];
            $email  = $row['cus_email'];
            $team_kh= $row['cus_cat_name'];
            $note   = $row['cus_note'];    
        }
    }
    if(trim($tabel) == 'users'){
        $title      = 'Thông tin nhân viên';
        $t_ma_      = 'Mã NV';
        $t_team_    = 'Nhóm nhân viên';
        if($id      != '') {
            $sql    = new db_query('SELECT * FROM ' . $tabel . ' 
                                    INNER JOIN categories_multi ON use_group_id = cat_id
                                    WHERE use_id = ' . intval($id));
            $row    = mysqli_fetch_assoc($sql->result);unset($sql);
            
            $ma_kh  = format_codenumber($row['use_id'],6,'NV');
            $avata  = $row['use_image'];
            $name   = $row['use_name'];
            $addrss = $row['use_address'];
            $phone  = $row['use_phone'];
            $note   = $row['use_note'];
            $team_kh= $row['cat_name'];    
        }
    }
    ?>
    <meta charset="utf-8"/>
    <span class="detail-tit-bill"><?=$title?></span>
    <i class="fa fa-times detail-close" onclick="detail_close('#cus-form')"></i>
    <div class="detail_content">
        <form action="" method="">
            <table cellpadding="0" cellspacing="0" border="0" class="form-box">
                <tr>
                    <td class="col-30">Mã hệ thống:</td>
                    <td><input class="inp1-2" readonly="readonly" value="<?= isset_($ma_kh);?>"/> <?=$t_ma_?>: <input class="inp1-2" readonly="readonly"/></td>
                    <td rowspan="6" class="col-30" >
                        <div class="box-ava-cus">
                            <?
                                if($avata){
                            ?>
                                <img src="<?=get_picture_path($avata);?>"/>
                            <?
                                }else{
                            ?>    
                                <i class="fa fa-camera-retro fa-2x ava-cus"></i>
                                <p>Không có hình</p>
                            <?
                                }
                            ?>
                            
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Tên khách hàng:</td>
                    <td><input class="inp1" readonly="readonly" value="<?=isset_($name);?>"/></td>
                </tr>
                <tr>
                    <td>Địa chỉ:</td>
                    <td><input class="inp1" readonly="readonly" value="<?=isset_($addrss);?>"/></td>
                </tr>
                <tr>
                    <td>Điện thoại:</td>
                    <td><input class="inp1" readonly="readonly" value="<?=isset_($phone);?>"/></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input class="inp1" readonly="readonly" value="<?=isset_($email)?>"/></td>
                </tr>
                <tr>
                    <td><?=$t_team_?>:</td>
                    <td><select class="inp1 inp-sel" disabled="disabled"><option> <?=isset_($team_kh)?></option></select></td>
                </tr>
                <tr>
                    <td>Ghi chú:</td>
                    <td colspan="2"><textarea class="inp-tarea" readonly="readonly"><?=isset_($note)?></textarea></td>
                </tr>
            </table>
        </form>
        <div>
            <span class="bill-close" onclick="detail_close('#cus-form')"><i class="fa fa-sign-out"></i> Đóng cửa sổ</span>
            <div class="clear_"></div>
        </div>
    </div>



























