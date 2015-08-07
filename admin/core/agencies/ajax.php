<?
require_once 'inc_security.php';
$action = getValue('action','str','POST','',3);
switch($action){
    case 'loadFormAddCategory':
        //kiểm tra quyền add
        checkPermission('add');
        $html = '';
        $form = new form();
        $html .= mini_modal_open($modal_title[$action],'style="height:236px;width:480px;"');
        $html .= $form->form_open();
        /* code something etc... */
        $html .= $form->text(array(
            'label'=>'Tên cửa hàng',
            'name'=>'age_name',
            'id'=>'age_name',
            'require'=>1,
            'errorMsg'=>'Bạn chưa nhập tên cửa hàng'
        ));
        $html .= $form->text(array(
            'label'=>'Địa chỉ',
            'name'=>'age_address',
            'id'=>'age_address',
            'require'=>1,
            'errorMsg'=>'Bạn chưa nhập địa chỉ'
        ));
        $html .= $form->text(array(
            'label'=>'Số điện thoại',
            'name'=>'age_phone',
            'id'=>'age_phone',
            'require'=>1,
            'errorMsg'=>'Bạn chưa nhập điện thoại cửa hàng'
        ));
        $html .= $form->ajaxUploadFile(array(
            'label'=>'Ảnh đại diện',
            'name'=>'age_image',
            'id'=>'age_image',
            'browse_id'=>'browse_id',
            'viewer_id'=>'viewer_id'
        ));
        $html .= $form->textarea(array(
            'label'=>'Ghi chú',
            'name'=>'age_note',
            'id'=>'age_note'
        ));
        $html .= $form->form_action(array(
            'label'=>array('Đồng ý','Hủy bỏ'),
            'type'=>array('submit','reset'),
            'extra'=>array('','modal-control="modal-close"')
        ));
        $html .= $form->hidden(array(
            'name'=>'action_modal',
            'id'=>'action_modal',
            'value'=>'add_category'
        ));
        $html .= $form->form_close();
        $html .= mini_modal_close();
        echo $html;
        break;
    /*******************************************************************************************************************/
    case 'loadFormEditCategory':
        //kiểm tra quyền edit
        checkPermission('edit');
        //lấy ra cat_id cần chỉnh sửa
        $cat_id = getValue('cat_id','int','POST',0);
        //lấy ra data cat id
        $db_data = new db_query('SELECT * FROM '.$cat_table.' WHERE age_id = '.$cat_id.' LIMIT 1');
        if($row = mysqli_fetch_assoc($db_data->result)){
            extract($row);
        }else{
            exit();
        }

        $html = '';
        $form = new form();
        $html .= mini_modal_open($modal_title[$action],'style="height:236px;width:480px;"');
        $html .= $form->form_open();
        /* code something etc... */
        $html .= $form->text(array(
            'label'=>'Tên cửa hàng',
            'name'=>'age_name',
            'id'=>'age_name',
            'require'=>1,
            'errorMsg'=>'Bạn chưa nhập tên cửa hàng',
            'value'=>$age_name
        ));
        $html .= $form->text(array(
            'label'=>'Địa chỉ',
            'name'=>'age_address',
            'id'=>'age_address',
            'require'=>1,
            'errorMsg'=>'Bạn chưa nhập địa chỉ',
            'value'=>$age_address
        ));
        $html .= $form->text(array(
            'label'=>'Số điện thoại',
            'name'=>'age_phone',
            'id'=>'age_phone',
            'require'=>1,
            'errorMsg'=>'Bạn chưa nhập điện thoại cửa hàng',
            'value'=>$age_phone
        ));
        $html .= $form->ajaxUploadFile(array(
            'label'=>'Ảnh đại diện',
            'name'=>'age_image',
            'id'=>'age_image',
            'browse_id'=>'browse_id',
            'viewer_id'=>'viewer_id',
            'value'=>get_picture_path($age_image)
        ));
        $html .= $form->textarea(array(
            'label'=>'Ghi chú',
            'name'=>'age_note',
            'id'=>'age_note',
            'value'=>$age_note
        ));
        $html .= $form->form_action(array(
            'label'=>array('Đồng ý','Hủy bỏ'),
            'type'=>array('submit','reset'),
            'extra'=>array('','modal-control="modal-close"')
        ));
        $html .= $form->hidden(array(
            'name'=>'action_modal',
            'id'=>'action_modal',
            'value'=>'edit_category'
        ));
        $html .= $form->hidden(array(
            'name'=>'record_id',
            'id'=>'record_id',
            'value'=>$cat_id
        ));
        $html .= $form->form_close();
        $html .= mini_modal_close();
        echo $html;
        break;
    /*******************************************************************************************************************/
    case 'deleteCategory':
        //kiểm tra quyền xóa
        checkPermission('trash');
        $cat_id = getValue('cat_id','int','POST',0);
        $db_data = new db_query('SELECT * FROM '.$cat_table.' WHERE cat_id = '.$cat_id .' LIMIT 1');
        $array_data = mysqli_fetch_assoc($db_data->result);unset($db_data);
        if($array_data){
            move2trash('cat_id',$cat_id,$cat_table,$array_data);
            $array_return = array('success'=>1);
        }else{
            exit();
        }
        die(json_encode($array_return));
        break;
    /*******************************************************************************************************************/
    case 'loadFormAddRecord':
        //kiểm tra quyền add
        checkPermission('add');
        //lấy ra danh sách các cửa hàng
        $list_agencies = array(''=> ' -- Chọn cửa hàng -- ');
        $db_agen = new db_query('SELECT * FROM '.$cat_table);
        while($row = mysqli_fetch_assoc($db_agen->result)){
            $list_agencies[$row['age_id']] = $row['age_name'];
        }
        unset($db_agen);
        $html = '';
        $form = new form();
        $html .= mini_modal_open($modal_title[$action]);
        $html .= $form->form_open();
        /* code something etc... */
        $html .= $form->text(array(
            'label'=>'Tên quầy phục vụ',
            'name'=>'sed_name',
            'id'=>'sed_name',
            'require'=>1,
            'errorMsg'=>'Bạn chưa nhập tên quầy phục vụ',
            'value'=>''
        ));
        $html .= $form->text(array(
            'label'=>'Điện thoại',
            'name'=>'sed_phone',
            'id'=>'sed_phone',
            'value'=>''
        ));
        $html .= $form->select(array(
            'label'=>'Cửa hàng',
            'name'=>'sed_agency_id',
            'id'=>'sed_agency_id',
            'selected'=>0,
            'require'=>1,
            'errorMsg'=>'Bạn chưa chọn cửa hàng',
            'option'=>$list_agencies
        ));
        $html .= $form->textarea(array(
            'label'=>'Ghi chú',
            'name'=>'sed_note',
            'id'=>'sed_note'
        ));
        $html .= $form->form_action(array(
            'label'=>array('Đồng ý','Hủy bỏ'),
            'type'=>array('submit','reset'),
            'extra'=>array('','modal-control="modal-close"')
        ));
        $html .= $form->hidden(array(
            'name'=>'action_modal',
            'id'=>'action_modal',
            'value'=>'add_record'
        ));
        $html .= $form->form_close();
        $html .= mini_modal_close();
        echo $html;
        break;
    /*******************************************************************************************************************/
    case 'loadFormEditRecord':
        //kiểm tra quyền edit
        checkPermission('edit');
        $record_id = getValue('record_id','int','POST',0);
        //lấy ra danh sách các cửa hàng
        $list_agencies = array(''=> ' -- Chọn cửa hàng -- ');
        $db_agen = new db_query('SELECT * FROM '.$cat_table);
        while($row = mysqli_fetch_assoc($db_agen->result)){
            $list_agencies[$row['age_id']] = $row['age_name'];
        }
        unset($db_agen);
        //lấy ra data cat id
        $db_data = new db_query('SELECT * FROM '.$bg_table.' WHERE '.$id_field.' = '.$record_id.' LIMIT 1');
        if($row = mysqli_fetch_assoc($db_data->result)){
            extract($row);
        }else{
            exit();
        }
        $html = '';
        $form = new form();
        $html .= mini_modal_open($modal_title[$action]);
        $html .= $form->form_open();
        /* code something etc... */
        $html .= $form->text(array(
            'label'=>'Tên quầy phục vụ',
            'name'=>'sed_name',
            'id'=>'sed_name',
            'require'=>1,
            'errorMsg'=>'Bạn chưa nhập tên quầy phục vụ',
            'value'=>$sed_name
        ));
        $html .= $form->text(array(
            'label'=>'Điện thoại',
            'name'=>'sed_phone',
            'id'=>'sed_phone',
            'value'=>$sed_phone
        ));
        $html .= $form->select(array(
            'label'=>'Cửa hàng',
            'name'=>'sed_agency_id',
            'id'=>'sed_agency_id',
            'selected'=>$sed_agency_id,
            'require'=>1,
            'errorMsg'=>'Bạn chưa chọn cửa hàng',
            'option'=>$list_agencies
        ));
        $html .= $form->textarea(array(
            'label'=>'Ghi chú',
            'name'=>'sed_note',
            'id'=>'sed_note',
            'value'=>$sed_note
        ));
        $html .= $form->form_action(array(
            'label'=>array('Đồng ý','Hủy bỏ'),
            'type'=>array('submit','reset'),
            'extra'=>array('','modal-control="modal-close"')
        ));
        $html .= $form->hidden(array(
            'name'=>'action_modal',
            'id'=>'action_modal',
            'value'=>'edit_record'
        ));
        $html .= $form->hidden(array(
            'name'=>'record_id',
            'id'=>'record_id',
            'value'=>$record_id
        ));
        $html .= $form->form_close();
        $html .= mini_modal_close();
        echo $html;
        break;
    /*******************************************************************************************************************/
    case 'deleteRecord':
        //kiểm tra quyền xóa
        checkPermission('trash');
        $record_id = getValue('record_id','int','POST',0);
        $db_data = new db_query('SELECT * FROM '.$bg_table.' WHERE '.$id_field.' = '.$record_id .' LIMIT 1');
        $array_data = mysqli_fetch_assoc($db_data->result);unset($db_data);
        if($array_data){
            move2trash($id_field,$record_id,$bg_table,$array_data);
            $array_return = array('success'=>1);
        }else{
            exit();
        }
        die(json_encode($array_return));
        break;
    /*******************************************************************************************************************/
    case 'recoveryRecord':
        //kiểm tra quyền khôi phục
        checkPermission('recovery');
        $record_id = getValue('record_id','int','POST',0);
        //phục hồi dữ liệu
        $result = trash_recovery($record_id,$bg_table);
        if($result){
            $array_return = array('success'=>1);
        }else{
            $array_return = array('success'=>0,'error'=>'Khôi phục không thành công');
        }
        die(json_encode($array_return));
        break;
    /*******************************************************************************************************************/
    case 'terminalDeleteRecord':
        //kiểm tra quyền xóa vĩnh viễn
        checkPermission('delete');
        $record_id = getValue('record_id','int','POST',0);
        //xóa hoàn toàn
        terminal_delete($record_id,$bg_table);
        $array_return = array('success'=>1);
        die(json_encode($array_return));
        break;
    /*******************************************************************************************************************/
    case 'listRecord':
        $cat_id = getValue('cat_id','str','POST',0);
        $html = '';
        $class_context_menu = 'menu-normal';
        #Bắt đầu với datagrid
        $list = new dataGrid($id_field,30);
        /*code something*/
        $list->add('','Tên cửa hàng');
        $list->add('','Điện thoại');
        switch($cat_id){
            case 'all':
                $db_count = new db_count('SELECT count(*) as count
                                          FROM '.$bg_table.'
                                          WHERE 1 '.$list->sqlSearch());
                $total = $db_count->total;unset($db_count);
                $db_listing = new db_query('SELECT *
                            FROM '.$bg_table.'
                            WHERE 1 '.$list->sqlSearch().'
                            ORDER BY '.$list->sqlSort().' '.$id_field.' ASC
                            '.$list->limit($total));
                $array_row = $db_listing->resultArray();unset($db_listing);
                break;
            case 'trash':
                $class_context_menu = 'menu-trash';
                $db_count = new db_count('SELECT count(*) as count
                            FROM trash
                            WHERE tra_table = "'.$bg_table.'"');
                $total = $db_count->total;unset($db_count);
                $array_row = trash_list($bg_table);
                $list->limit($total);
                break;
            default :
                $cat_id = (int)$cat_id;
                $db_count = new db_count('SELECT count(*) as count
                                          FROM '.$bg_table.'
                                          WHERE 1 '.$list->sqlSearch() .'
                                          AND '.$cat_field.' = '. $cat_id);
                $total = $db_count->total;unset($db_count);
                $db_listing = new db_query('SELECT *
                                            FROM '.$bg_table.'
                                            WHERE 1 '.$list->sqlSearch().'
                                            AND '.$cat_field.' = '. $cat_id.'
                                            ORDER BY '.$list->sqlSort().' '.$id_field.' ASC
                                            '.$list->limit($total));
                $array_row = $db_listing->resultArray();unset($db_listing);
                break;
        }
        $total_row = count($array_row);
        $html .= $list->showHeader($total_row);
        $i = 0;
        foreach($array_row as $row){
            $i++;
            $html .= $list->start_tr($i,$row[$id_field],'class="'.$class_context_menu.' record-item" onclick="active_record('.$row[$id_field].')" data-record_id="'.$row[$id_field].'"');
            /*code something */
            $html .= '<td>'.$row['sed_name'].'</td>';
            $html .= '<td class="right">'.$row['sed_phone'].'</td>';
            $html .= $list->end_tr();
        }
        $html .= $list->showFooter();
        echo $html;
        break;
}