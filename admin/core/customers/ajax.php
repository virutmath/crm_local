<?
require_once 'inc_security.php';
//viết theo class Ajax
class CustomerAjax extends AjaxCommon {
    function _loadFormAddCategory()
    {
        parent::_loadFormAddCategory(); // TODO: Change the autogenerated stub
        $this->add(
            $this->form->text(array(
                'label'=>'Nhập tên',
                'name'=>'cus_cat_name',
                'id'=>'cus_cat_name',
                'require'=>1,
                'errorMsg'=>'Bạn chưa nhập tên nhóm khách hàng'
            ))
        );
        $this->add(
            $this->form->number(array(
                'label'=>'Tỉ lệ giảm giá',
                'name'=>'cus_cat_discount',
                'id'=>'cus_cat_discount',
                'addon'=> '%'
            ))
        );
        $this->add(
            $this->form->number(array(
                'label'=>'Doanh số',
                'name'=>'cus_cat_sales',
                'id'=>'cus_cat_sales',
                'addon'=> 'vnđ'
            ))
        );
        $this->add(
            $this->form->ajaxUploadFile(array(
                'label'=>'Ảnh đại diện',
                'name'=>'cus_cat_picture',
                'id'=>'cus_cat_picture',
                'browse_id'=>'browse_img',
                'viewer_id'=>'viewer_img'
            ))
        );
        $this->add(
            $this->form->textarea(array(
                'label'=>'Ghi chú',
                'name'=>'cus_cat_note',
                'id'=>'cus_cat_note'
            ))
        );
    }
    function _loadFormEditCategory()
    {
        parent::_loadFormEditCategory(); // TODO: Change the autogenerated stub
        $this->add(
            $this->form->text(array(
                'label'=>'Nhập tên',
                'name'=>'cus_cat_name',
                'id'=>'cus_cat_name',
                'require'=>1,
                'errorMsg'=>'Bạn chưa nhập tên nhóm khách hàng',
                'value'=>$this->f['cus_cat_name']
            ))
        );
        $this->add(
            $this->form->number(array(
                'label'=>'Tỉ lệ giảm giá',
                'name'=>'cus_cat_discount',
                'id'=>'cus_cat_discount',
                'addon'=> '%',
                'value'=>$this->f['cus_cat_discount']
            ))
        );
        $this->add(
            $this->form->number(array(
                'label'=>'Doanh số',
                'name'=>'cus_cat_sales',
                'id'=>'cus_cat_sales',
                'addon'=> 'vnđ',
                'value'=>$this->f['cus_cat_sales']
            ))
        );
        $this->add(
            $this->form->ajaxUploadFile(array(
                'label'=>'Ảnh đại diện',
                'name'=>'cus_cat_picture',
                'id'=>'cus_cat_picture',
                'browse_id'=>'browse_img',
                'viewer_id'=>'viewer_img',
                'value'=>get_picture_path($this->f['cus_cat_picture'])
            ))
        );
        $this->add(
            $this->form->textarea(array(
                'label'=>'Ghi chú',
                'name'=>'cus_cat_note',
                'id'=>'cus_cat_note',
                'value'=>$this->f['cus_cat_note']
            ))
        );
    }
    function deleteCategory(){
        //xóa danh muc khach hang
        $cat_id = getValue('cat_id','int','POST',0);
        //check quyền xóa
        checkPermission('trash');
        $array_return = array();
        $db_data = new db_query('SELECT * FROM customer_cat WHERE cus_cat_id = '.$cat_id.' LIMIT 1');
        $cuscat_data = mysqli_fetch_assoc($db_data->result);unset($db_data);
        move2trash('cus_cat_id',$cat_id,'customer_cat',$cuscat_data);
        $array_return = array('success'=>1);
        die(json_encode($array_return));
    }

    function _loadFormAddRecord()
    {
        parent::_loadFormAddRecord(); // TODO: Change the autogenerated stub
        $db_query = new db_query('SELECT * FROM customer_cat');
        $array_cus_cat = array('' =>' - Chọn nhóm khách hàng - ');
        while($row = mysqli_fetch_assoc($db_query->result)){
            $array_cus_cat[$row['cus_cat_id']] = $row['cus_cat_name'];
        }
        unset($db_query);
       $this->add(
            $this->form->text(array(
                'label'=>'Tên khách hàng',
                'name'=>'cus_name',
                'id'=>'cus_name',
                'require'=>1,
                'errorMsg'=>'Bạn chưa nhập tên khách hàng'
            ))
        );
       $this->add(
            $this->form->text(array(
                'label'=>'Địa chỉ',
                'name'=>'cus_address',
                'id' => 'cus_address',
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập địa chỉ khách hàng'
            ))
        );
       $this->add(
            $this->form->text(array(
                'label'=>'Điện thoại',
                'name'=>'cus_phone',
                'id'=>'cus_phone'
            ))
        );
       $this->add(
           $this->form->text(array(
                'label'=>'Email',
                'name'=>'cus_email',
                'id'=>'cus_email'
           ))
       );
       $this->add(
           $this->form->select(array(
                'label'=>'Nhóm khách hàng',
                'name'=>'cus_cat_id',
                'id'=>'cus_cat_id',
                'option'=>$array_cus_cat,
                'selected'=>0
            ))
       );
       $this->add(
           $this->form->text(array(
                'label'=>'Mã khách hàng tự nhập',
                'name'=>'cus_code',
                'id'=>'cus_code'
           ))
       );
       $this->add(
           $this->form->textarea(array(
                'label'=>'Ghi chú',
                'name'=>'cus_note',
                'id'=>'cus_note'
            ))
       );
       $this->add(
           $this->form->ajaxUploadFile(array(
                'label'=>'Ảnh đại diện',
                'name'=>'cus_picture',
                'id'=>'cus_picture',
                'browse_id'=>'browse_img',
                'viewer_id'=>'viewer_img'
            ))
       );
    }
    function _loadFormEditRecord()
    {
        parent::_loadFormEditRecord(); // TODO: Change the autogenerated stub
        $db_query = new db_query('SELECT * FROM customer_cat');
        $array_cus_cat = array('' => ' - Chọn nhóm khách hàng - ');
        while ($row = mysqli_fetch_assoc($db_query->result)) {
            $array_cus_cat[$row['cus_cat_id']] = $row['cus_cat_name'];
        }
        unset($db_query);
        $this->add(
            $this->form->text(array(
                'label'=>'Tên khách hàng',
                'name'=>'cus_name',
                'id'=>'cus_name',
                'require'=>1,
                'errorMsg'=>'Bạn chưa nhập tên khách hàng',
                'value'=>$this->f['cus_name']
            ))
        );
        $this->add(
            $this->form->text(array(
                'label'=>'Địa chỉ',
                'name'=>'cus_address',
                'id' => 'cus_address',
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập địa chỉ khách hàng',
                'value'=>$this->f['cus_address']
            ))
        );
        $this->add(
            $this->form->text(array(
                'label'=>'Điện thoại',
                'name'=>'cus_phone',
                'id'=>'cus_phone',
                'value'=>$this->f['cus_phone']
            ))
        );
        $this->add(
            $this->form->text(array(
                'label'=>'Email',
                'name'=>'cus_email',
                'id'=>'cus_email',
                'value'=>$this->f['cus_email']
            ))
        );
        $this->add(
            $this->form->select(array(
                'label'=>'Nhóm khách hàng',
                'name'=>'cus_cat_id',
                'id'=>'cus_cat_id',
                'option'=>$array_cus_cat,
                'selected'=>$this->f['cus_cat_id']
            ))
        );
        $this->add(
            $this->form->text(array(
                'label'=>'Mã khách hàng tự nhập',
                'name'=>'cus_code',
                'id'=>'cus_code',
                'value'=>$this->f['cus_code']
            ))
        );
        $this->add(
            $this->form->textarea(array(
                'label'=>'Ghi chú',
                'name'=>'cus_note',
                'id'=>'cus_note',
                'value'=>$this->f['cus_note']
            ))
        );
        $this->add(
            $this->form->ajaxUploadFile(array(
                'label'=>'Ảnh đại diện',
                'name'=>'cus_picture',
                'id'=>'cus_picture',
                'browse_id'=>'browse_img',
                'viewer_id'=>'viewer_img',
                'value'=>get_picture_path($this->f['cus_picture'])
            ))
        );

    }
    function _listAdd()
    {

        $this->list->add('cus_cat_id','Mã hệ thống');
        $this->list->add('cus_code','Mã có sẵn');
        $this->list->add('cus_name','Tên khách hàng','string',1,1);
        $this->list->add('cus_address','Địa chỉ');
        $this->list->add('cus_phone','Điện thoại');
    }

    function _listColumn($row = array())
    {
        $list_column = '';
        $list_column .= '<td class="center">'.$row['cus_cat_id'].'</td>';
        $list_column .= '<td class="center">'.$row['cus_code'].'</td>';
        $list_column .= '<td class="center">'.$row['cus_name'].'</td>';
        $list_column .= '<td class="center">'.$row['cus_address'].'</td>';
        $list_column .= '<td class="center">'.$row['cus_phone'].'</td>';
        return $list_column;
    }

    // load thông tin nhóm khách hàng được giảm giá
    function showInfo(){
        $array_return = array();
        $cat_id = getValue('cat_id','int','POST',0);
        $db_cus_cat = 'SELECT * FROM customer_cat WHERE cus_cat_id = '.$cat_id.'';
        $rs_cus_cat = new db_query($db_cus_cat);
        $list_category = $rs_cus_cat->resultArray();
        foreach($list_category as $cat){
            $array_return  = array('cus_cat_sales' => format_number($cat['cus_cat_sales']),'cus_cat_discount' => $cat['cus_cat_discount']);
        }
        unset($db_cus_cat);
        die(json_encode($array_return));
    }
}
// khoi tao doi tuong object ajax va thuc thi
$customerajax = new CustomerAjax();
$customerajax->execute();
