<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo $load_header;?>
</head>
<body>
<div id="wrapper-full">
    <?php echo $error_msg;?>
    <div class="left-column fl rlt">
        <div class="section-content column-wrapper">
            <div id="listing-menu" class="scrollable-area">
                <?php echo $listing_menu;?>
            </div>
            <div id="restaurant-info">
                <div class="row col-xs-12 text-center info-label">Thông tin nhà hàng</div>
                <div class="row">
                    <span class="row-title pull-left">Tên:</span>

                    <div class="row-content"><?php echo $restaurant_info['res_name'];?></div>
                </div>
                <div class="row">
                    <span class="row-title pull-left">Địa chỉ:</span>

                    <div class="row-content"><?php echo $restaurant_info['res_address'];?></div>
                </div>
                <div class="row">
                    <span class="row-title pull-left">ĐT:</span>

                    <div class="row-content"><?php echo $restaurant_info['res_phone'];?></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-center info-promo">
                        <i class="fa fa-bullhorn"></i>
                        Không có khuyến mại
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="right-column fl">
        <div class="section-content column-wrapper">
            <div id="main-sale">
                <div class="fl" id="center-sale">
                    <div id="center-top-sale" class="col-xs-12">
                        <div class="col-xs-5">
                            <label class="color-24BDE2">Hóa đơn bán hàng</label>

                            <div class="row">
                                <div class="col-xs-4 row-title">Giờ vào</div>
                                <div class="col-xs-8">
                                    <input type="text" id="start_time_string" disabled/>
                                    <input type="hidden" id="cud_start_time" name="cud_start_time"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4 row-title">Ghi chú</div>
                                <div class="col-xs-8"><input type="text" name="cud_note" id="cud_note"
                                                             onchange="HomeScript.inputChangeFunction('note')"/></div>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <label class="color-E21600" id="current_desk_name">Chưa chọn bàn</label>
                            <input type="hidden" id="current_desk_id"/>

                            <div class="row">
                                <div class="col-xs-3 row-title">Khách hàng</div>
                                <div class="col-xs-3">
                                    <input type="text" name="sale_customer_code"
                                           id="sale_customer_code" disabled/>
                                </div>
                                <div class="col-xs-6">
                                    <input type="text" id="search_customer"
                                           onclick="HomeScript.view.changeCustomer()"
                                           disabled/>
                                    <span><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3 row-title">Nhân viên</div>
                                <div class="col-xs-3">
                                    <input type="text" name="sale_staff_code" id="sale_staff_code" disabled/>
                                </div>
                                <div class="col-xs-6">
                                    <input type="text" id="search_staff"
                                           onclick="HomeScript.view.changeStaff()"
                                           disabled/>
                                    <span><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="center-listing" class="col-xs-12 scrollable-area">

                    </div>
                    <div id="center-control" class="col-xs-12">
                        <table>
                            <tr>
                                <?php if( $permission_print_order ){ ?>
                                <td><span id="print-order" onclick="HomeScript.view.printOrder()" class="btn"><i
                                        class="fa fa-print"></i> in chế biến</span>
                                </td>
                                <?php } ?>
                                <td><span id="preview-bill" onclick="HomeScript.view.printBills()" class="btn"><i
                                        class="fa fa-print"></i> In tạm tính</span></td>
                                <td><span id="move-desk" onclick="HomeScript.view.moveDesk()" class="btn"><i
                                        class="fa fa-retweet"></i> chuyển bàn</span></td>
                                <td><span id="join-bill" onclick="HomeScript.view.joinDesk()" class="btn"><i
                                        class="fa fa-copy"></i> ghép hóa đơn</span></td>
                                <td><span id="split-bill" onclick="HomeScript.view.splitDesk()" class="btn"><i
                                        class="fa fa-clipboard"></i> tách hóa đơn</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="list-desk">
                    <div class="list-desk-bound scrollable-area">
                        <?php $counter1=-1; if( isset($list_desk) && is_array($list_desk) && sizeof($list_desk) ) foreach( $list_desk as $key1 => $value1 ){ $counter1++; ?>
                        <fieldset>
                            <legend class="section-name bold">
                                <?php echo $value1["sec_name"];?> (<?php echo $value1["count"];?>)
                            </legend>
                            <div class="section-list-desk">
                                <?php $counter2=-1; if( isset($value1["list_desk"]) && is_array($value1["list_desk"]) && sizeof($value1["list_desk"]) ) foreach( $value1["list_desk"] as $key2 => $value2 ){ $counter2++; ?>
                                <?php if( $value2["active"] ){ ?>
                                <div class="desk-item active" onclick="HomeScript.selectDesk(this)"
                                     ondblclick="HomeScript.openDesk(this)"
                                     data-des_id="<?php echo $value2["des_id"];?>" data-des_name="<?php echo $value2["full_name"];?>"
                                     data-full_name="<?php echo $value2["full_name"];?>"
                                     data-is_active="1">
                                    <span class="desk-name"><?php echo $value2["des_name"];?></span>
                                </div>
                                <?php }else{ ?>
                                <div class="desk-item" onclick="HomeScript.selectDesk(this)"
                                     ondblclick="HomeScript.openDesk(this)"
                                     data-des_id="<?php echo $value2["des_id"];?>" data-des_name="<?php echo $value2["full_name"];?>"
                                     data-full_name="<?php echo $value2["full_name"];?>"
                                     data-is_active="1">
                                    <span class="desk-name"><?php echo $value2["des_name"];?></span>
                                </div>
                                <?php } ?>
                                <?php } ?>
                            </div>
                        </fieldset>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div id="sale-control">
                <div id="sale-price" class="col-xs-3">
                    <div class="row">
                        <label for="cdm_number" class="row-title pull-left">Số lượng</label>
                        <input type="text" class="pull-left"
                               onchange="HomeScript.inputChangeFunction('menu_number')"
                               onkeyup="HomeScript.keyUpFunction('menuNumber')" id="cdm_number" name="cdm_number"
                               disabled/>
                        <label for="cdm_menu_discount" class="row-title row-title-short pull-left">Giảm</label>

                        <div class="cdm_discount_bound">
                            <input type="text" class="col-xs-8"
                                   onchange="HomeScript.inputChangeFunction('menu_discount')"
                                   onkeyup="HomeScript.keyUpFunction('menuDiscount')" id="cdm_menu_discount"
                                   name="cdm_menu_discount"
                                   placeholder="%" disabled/>
                            <span id="setting-menu-discount" onclick="HomeScript.view.settingMenuDiscount()"
                                  class="col-xs-4"><i
                                    class="fa fa-cog"></i></span>
                        </div>
                    </div>
                    <div class="row">
                        <label class="row-title pull-left">Giá bán</label>

                        <div class="men_price_bound">
                            <span class="men_price_span pull-left" id="men_price">0</span>
                        </div>
                    </div>
                    <div class="row">
                        <label class="row-title pull-left">Giá bán 1</label>

                        <div class="men_price_bound">
                            <span class="men_price_span pull-left" id="men_price1">0</span>
                        </div>
                    </div>
                    <div class="row">
                        <label class="row-title pull-left">Giá bán 2</label>

                        <div class="men_price_bound">
                            <span class="men_price_span pull-left" id="men_price2">0</span>
                        </div>
                    </div>
                </div>
                <div id="info-bill" class="col-xs-1-5">
                    <img src="" alt="" id="men_image"/>
                    <span id="men_name"></span>
                </div>
                <div id="info-money" class="col-xs-7-5">
                    <div class="info-money-extra">
                        <div class="row">
                            <label for="cud_extra_fee" class="row-title pull-left">Phụ phí</label>
                            <input type="text" placeholder="%"
                                   onchange="HomeScript.inputChangeFunction('extra_fee')"
                                   onkeyup="HomeScript.keyUpFunction('extraFee')" class="pull-left info-money-percent"
                                   name="cud_extra_fee"
                                   id="cud_extra_fee" disabled/>

                            <div class="info-money-bound">
                                <span class="info-money-text" id="extra_fee_text">0</span>
                                <span id="setting-extra-fee"><i class="fa fa-cog"></i></span>
                            </div>
                        </div>
                        <div class="row">
                            <label for="cud_customer_discount" class="pull-left row-title">Giảm giá</label>
                            <input type="text" placeholder="%"
                                   onchange="HomeScript.inputChangeFunction('customer_discount')"
                                   onkeyup="HomeScript.keyUpFunction('customerDiscount')"
                                   class="pull-left info-money-percent"
                                   name="cud_customer_discount" id="cud_customer_discount" disabled/>

                            <div class="info-money-bound">
                                <span class="info-money-text" id="discount-text">0</span>
                                <span id="setting-customer-discount"><i class="fa fa-cog"></i></span>
                            </div>
                        </div>
                        <div class="row">
                            <label for="cud_vat" class="pull-left row-title">VAT</label>
                            <input type="text" placeholder="%"
                                   onchange="HomeScript.inputChangeFunction('vat')"
                                   onkeyup="HomeScript.keyUpFunction('vat')" class="pull-left info-money-percent"
                                   name="cud_vat"
                                   id="cud_vat" disabled/>

                            <div class="info-money-bound">
                                <span class="info-money-text" id="vat-ext">0</span>
                                <span class="info-money-space"></span>
                            </div>
                        </div>
                        <div class="row">
                            <label for="cud_customer_cash" class="row-title pull-left">Khách trả</label>
                            <input type="text" onkeyup="HomeScript.keyUpFunction('customerCash')"
                                   class="pull-left info-money-percent"
                                   name="cud_customer_cash"
                                   id="cud_customer_cash" disabled/>

                            <div class="info-money-bound">
                                <span class="info-money-text" id="customer_cash_text">0</span>
                                <span class="info-money-space"></span>
                            </div>
                        </div>
                    </div>
                    <div class="total-sum-money">
                        <div class="col-xs-12">
                            <label>Tổng tiền:</label>
                            <span id="total-money">0</span>
                        </div>
                        <div class="col-xs-12">
                            <label>Thanh toán:</label>
                            <span id="final-money">0 VNĐ</span>
                        </div>
                        <div class="col-xs-12">
                            <div class="col-xs-5">
                                <label>Ghi nợ <input type="checkbox" id="is-debit" value="1"
                                                     onclick="HomeScript.setDebit()"/></label>
                            </div>
                            <div class="col-xs-7 text-right">
                                <label class="pull-left">
                                    <input type="radio" onclick="HomeScript.view.changePayType(<?php echo $pay_type_cash;?>)" name="pay_type" value="<?php echo $pay_type_cash;?>" checked/> Tiền
                                    mặt</label>
                                <label class="pull-right">
                                    <input type="radio" onclick="HomeScript.view.changePayType(<?php echo $pay_type_card;?>)" name="pay_type" value="<?php echo $pay_type_card;?>"/> Thẻ</label>
                            </div>
                        </div>
                        <div id="bill-finish" class="col-xs-12" onclick="HomeScript.billSubmit()">
                            <i class="fa fa-tags"></i>
                            Thanh toán hóa đơn
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal"></div>
    <div id="overlay"></div>
    <div id="m-window"></div>
    <div id="loading">
        <i class="fa fa-spin fa-cog"></i>
        <span id="loading-text">Đang tải dữ liệu...</span>
    </div>
    <div id="convertPercentTemplate" class="hidden">
        <div class="mwindow-wrapper">
            <div class="mwindow-header">
                <label>Chuyển đổi từ tiền (VNĐ) sang phần trăm (%)</label>
                <span class="mwindow-close">&times;</span>
            </div>
            <div class="content-mini-window convert-percent">
                <div class="row">
                    <label class="row-title col-xs-6">Số tiền chuyển đổi</label>
                    <input type="text" class="col-xs-6" id="convert_money" value="0"/>
                </div>
                <div class="row">
                    <label class="row-title col-xs-6">Tính theo số</label>
                    <input type="text" class="col-xs-6" id="total_money"/>
                </div>
                <div class="row">
                    <label class="row-title col-xs-6">Phần trăm tương ứng</label>
                    <input type="text" class="col-xs-6" id="convert_result" value="0" disabled/>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <button class="btn btn-primary" id="acceptConvert">Đồng ý</button>
                        <button class="btn btn-default" id="cancelConvert">Hủy bỏ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if( $tpl_constants['DEVELOPER_ENVIRONMENT'] ){ ?>
<script type="text/jsx" src="js/src/home.js"></script>
<?php }else{ ?>
<script type="text/javascript" src="js/build/home.js"></script>
<?php } ?>
</body>
</html>
