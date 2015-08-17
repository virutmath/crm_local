var i = 0;
var trigger_keyup = '';
function MenuItem(data) {
    var _default = {
        men_id: 0,
        men_name: '',
        men_price: 0,
        men_price1: 0,
        men_price2: 0,
        men_unit: 0,
        men_image: '',
        men_editable: false
    };
    $.extend(_default, data);
    this.men_id = _default.men_id;
    this.men_name = _default.men_name;
    this.men_price = parseFloat(_default.men_price);
    this.men_price1 = parseFloat(_default.men_price1);
    this.men_price2 = parseFloat(_default.men_price2);
    this.men_unit = _default.men_unit;
    this.men_image = _default.men_image;
    this.men_editable = _default.men_editable;
    return this;
}
function BillInfo(data) {
    var _default = {
        customerDiscount: 0,
        customerID: null,
        customerCode: null,
        customerName: '',
        debit: false,
        debitMoney : 0,
        debitTime : null,
        extraFee: 0,
        note: '',
        payType: 0,
        startTime: Date.now(),
        startTimeStr: '',
        VAT: 0,
        staffCode: null,
        staffID: 0,
        staffName: '',
        totalMoney: 0,
        finalMoney: 0
    };
    $.extend(_default, data);
    this.customerDiscount = _default.customerDiscount;
    this.customerID = _default.customerID;
    this.customerCode = _default.customerCode;
    this.customerName = _default.customerName;
    this.debit = _default.debit;
    this.debitMoney = _default.debitMoney;
    this.debitTime = _default.debitTime;
    this.extraFee = _default.extraFee;
    this.note = _default.note;
    this.payType = _default.payType;
    this.startTime = _default.startTime;
    this.startTimeStr = _default.startTimeStr;
    this.VAT = _default.VAT;
    this.staffCode = _default.staffCode;
    this.staffID = _default.staffID;
    this.staffName = _default.staffName;
    this.totalMoney = _default.totalMoney;
    this.finalMoney = _default.finalMoney;
    return this;
}
//inherit from MenuItem
function MenuInDesk(data) {
    MenuItem.call(this, data);
    var _default = {
        cdm_price: 0,
        cdm_price_type: 'men_price',
        cdm_number: 0,
        cdm_desk_id: 0,
        cdm_menu_discount: 0
    };
    $.extend(_default, data);
    this.cdm_price = parseFloat(_default.cdm_price);
    this.cdm_price_type = _default.cdm_price_type;
    this.cdm_number = parseFloat(_default.cdm_number);
    this.cdm_desk_id = _default.cdm_desk_id;
    this.cdm_menu_discount = parseFloat(_default.cdm_menu_discount);
    return this;
}
MenuInDesk.prototype = new MenuItem();
MenuInDesk.prototype.constructor = MenuInDesk;


function DeskItem(data) {
    var _default = {
        des_id: 0,
        des_name: '',
        full_name: '',
        is_active: false
    };
    $.extend(_default, data);
    this.des_id = _default.des_id;
    this.des_name = _default.des_name;
    this.full_name = _default.full_name;
    this.is_active = _default.is_active;
    return this;
}

var HomeScript = {
    flagSetDebit : false,
    ajaxExtendUrl: {
        loadModalSelectCustomer: '/admin/core/customers/index_modal.php',
        loadModalSelectStaff: '/admin/core/users/index_modal.php'
    },
    domElement: {
        mainSale: $('#main-sale'),
        sectionContent: $('.section-content'),
        centerListing: $('#center-listing'),
        listingMenu: $('.list-menu'),
        listingDesk: $('.list-desk-bound'),
        currentDeskName: $('#current_desk_name'),
        startTimeString: $('#start_time_string'),
        billNote: $('#cud_note'),
        extraFee: $('#cud_extra_fee'),
        extraFeeText: $('#extra_fee_text'),
        vat: $('#cud_vat'),
        vatExt: $('#vat-ext'),
        customerCash: $('#cud_customer_cash'),
        customerCashText: $('#customer_cash_text'),
        customerDiscount: $('#cud_customer_discount'),
        customerDiscountText: $('#discount-text'),
        customerCode: $('#sale_customer_code'),
        searchCustomer: $('#search_customer'),
        staffCode: $('#sale_staff_code'),
        searchStaff: $('#search_staff'),
        menuNumber: $('#cdm_number'),
        menuDiscount: $('#cdm_menu_discount'),
        menuPrice: $('#men_price'),
        menuPrice1: $('#men_price1'),
        menuPrice2: $('#men_price2'),
        menuName: $('#men_name'),
        menuImage: $('#men_image'),
        totalMoney: $('#total-money'),
        finalMoney: $('#final-money'),
        debitCheckbox : $('#is-debit')
    },
    react: {
        tableMenu: {}
    },
    listMenu: [],
    listDesk: [],
    currentMenu: {
        domElement: null,
        menuItem: new MenuInDesk()
    },
    currentDesk: {
        deskItem: new DeskItem(),
        domElement: null,
        menuList: [],
        billInfo: new BillInfo()
    },
    infoRestaurant: {},
    beforeData : {
        currentDesk : {},
        currentMenu : {}
    }
};

HomeScript.addMenuToDesk = function (menu_id) {
    if (!HomeScript.currentDesk.domElement) {
        alert('Không thể thêm thực đơn! Chọn một bàn bắt đầu để thêm');
        return false;
    }
    if (!HomeScript.currentDesk.domElement.hasClass('active')) {
        alert('Bàn chưa được mở, cần mở bàn để bắt đầu thêm thực đơn');
        return false;
    }

    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: {action: 'addMenuToDesk', desk_id: HomeScript.currentDesk.deskItem.des_id, menu_id: menu_id},
        dataType: 'json',
        success: function (resp) {
            loadingProgress('hide');
            if (resp.array_menu) {
                HomeScript.parseResponseAddMenuToDesk(resp);
                //tính tiền
                HomeScript.cashCurrentBill();
                HomeScript.view.buildCurrentDesk();
                HomeScript.selectMenuInDesk(menu_id);
            }
        },
        beforeSend: function () {
            loadingProgress('show');
        }
    })
};
HomeScript.billSubmit = function () {
    if(HomeScript.currentDesk.deskItem.des_id <= 0) {
        alert('Bạn cần chọn một bàn để thanh toán');
        return false;
    }
    if(HomeScript.currentDesk.billInfo.totalMoney <= 0 || HomeScript.currentDesk.menuList.length <= 0) {
        alert('Không thể thanh toán hóa đơn trống');
        return false;
    }
    if(HomeScript.currentDesk.billInfo.debit && !HomeScript.flagSetDebit) {
        this.checkDebitSubmit();
        return false;
    }
    //thanh toán hóa đơn
    if(confirm('Bạn chắc chắn muốn thanh toán hóa đơn này? Lưu ý: sau khi thanh toán bạn sẽ không thể chỉnh sửa được hóa đơn.')){
        $.ajax({
            type : 'post',
            url : 'ajax.php',
            data : {
                action : 'billSubmit',
                desk_id : HomeScript.currentDesk.deskItem.des_id,
                debit : HomeScript.currentDesk.billInfo.debitMoney,
                date : HomeScript.currentDesk.billInfo.debitTime
            },
            dataType : 'json',
            success : function (resp) {
                if(resp.error) {
                    alert(resp.error);
                    HomeScript.flagSetDebit = false;
                    return false;
                }
                if(resp.success == 1) {
                    //xóa bàn
                    HomeScript.currentDesk.domElement.removeClass('active');
                    HomeScript.view.resetInput();
                    //Hiển thị in hóa đơn
                    if(confirm('Thanh toán thành công! Bạn có muốn in hóa đơn?')){
                        var mwindow = new Mindows();
                        mwindow.width = 600;
                        mwindow.height = 600;
                        mwindow.resize = true;
                        mwindow.iframe('../printer/print_bill.php', 'In hóa đơn', {
                            action : 'PRINT_SUCCESS_BILL',
                            billID : resp.bii_id
                        })
                    }
                }
            }
        })
    }else {
        HomeScript.flagSetDebit = false;
    }

};
HomeScript.checkDebitSubmit = function () {
    //nếu chọn ghi nợ thì check xem có chọn khách hàng không
    if(HomeScript.currentDesk.billInfo.debit && HomeScript.currentDesk.billInfo.customerID == 0) {
        //không cho thanh toán
        alert('Bạn cần chọn khách hàng để ghi nợ');
        return false;
    }
    if(HomeScript.currentDesk.billInfo.debit) {
        //show form ghi nợ
        var mindow = new Mindows();
        mindow.width = 400;
        mindow.height = 230;
        mindow.resize = true;
        mindow.iframe('debit_v2.php', 'Cài đặt công nợ khách hàng' ,{total: HomeScript.currentDesk.billInfo.finalMoney});
    }
};
HomeScript.deleteDesk = function(elem) {
    //xóa bàn

};
HomeScript.init = function (data) {
    if (data) {
        for (i in data.listMenu) {
            this.listMenu.list[i] = new MenuItem(data.listMenu[i]);
        }
        for (i in data.listDesk) {
            this.listDesk.list[i] = new DeskItem(data.listDesk[i]);
        }
    }
    CrmUtilities.adjustScreen();
    this.view.adjustScreen();
    $('.scrollable-area').enscroll({
        showOnHover: true,
        minScrollbarLength: 28,
        addPaddingToPane: false
    });
    //cấu hình numeric
    this.domElement.menuNumber.autoNumeric({
        lZero: 'deny',
        mDec: 1
    });
    this.domElement.menuDiscount.autoNumeric({
        lZero: 'deny',
        vMax: 100,
        mDec: 1
    });
    this.domElement.customerDiscount.autoNumeric({
        lZero: 'deny',
        vMax: 100,
        mDec: 1
    });
    this.domElement.extraFee.autoNumeric({
        lZero: 'deny',
        vMax: 100,
        mDec: 1
    });
    this.domElement.vat.autoNumeric({
        lZero: 'deny',
        vMax: 100,
        mDec: 1
    });
    this.domElement.customerCash.autoNumeric({
        lZero: 'deny',
        mDec: 0
    });
    this.contextMenu();
};
HomeScript.inputChangeFunction = function (type) {
    var action = '';
    var data = {};
    switch (type) {
        case 'menu_number':
            action = 'updateMenuNumber';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                menu_id: HomeScript.currentMenu.menuItem.men_id,
                number: HomeScript.currentMenu.menuItem.cdm_number
            };
            break;
        case 'menu_discount':
            action = 'updateMenuDiscount';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                menu_id: HomeScript.currentMenu.menuItem.men_id,
                discount: HomeScript.currentMenu.menuItem.cdm_menu_discount
            };
            break;
        case 'customer':
            action = 'updateCustomer';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                cus_id: HomeScript.currentDesk.billInfo.customerID
            };
            break;
        case 'staff':
            action = 'updateStaff';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                staff_id: HomeScript.currentDesk.billInfo.staffID
            };
            break;
        case 'note':
            action = 'updateNote';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                note: HomeScript.currentDesk.billInfo.note
            };
            break;
        case 'vat':
            action = 'updateVAT';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                vat: HomeScript.currentDesk.billInfo.VAT
            };
            break;
        case 'extra_fee':
            action = 'updateExtraFee';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                extra_fee: HomeScript.currentDesk.billInfo.extraFee
            };
            break;
        case 'customer_discount':
            action = 'updateCustomerDiscount';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                discount: HomeScript.currentDesk.billInfo.customerDiscount
            };
            break;
    }
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: data,
        dataType: 'json',
        success: function (resp) {
            loadingProgress('hide');
            if (resp.error) {
                //đặt lại dữ liệu trước khi thay đổi
                switch (data.action) {
                    case 'updateMenuNumber':
                        HomeScript.currentMenu.menuItem.cdm_number = HomeScript.beforeData.currentMenu.menuItem.cdm_number;
                        for(var i in HomeScript.currentDesk.menuList) {
                            var tmpMenu = HomeScript.currentDesk.menuList[i];
                            if(tmpMenu.men_id == data.menu_id) {
                                tmpMenu.cdm_number = HomeScript.beforeData.currentMenu.menuItem.cdm_number;
                            }
                        }
                        HomeScript.view.buildCurrentDesk();
                        break;
                }
                alert(resp.error);
            }
        },
        beforeSend: function () {
            loadingProgress('show');
        }
    })
};
HomeScript.keyUpFunction = function (type) {
    trigger_keyup = type;
    //kiểm tra xem có bàn hiện tại và thực đơn hiện tại ko
    if (!this.currentDesk.deskItem || $.isEmptyObject(this.currentDesk.deskItem)) {
        alert('Bàn chưa được mở. Vui lòng mở bàn');
        return false;
    }
    if (!this.currentMenu.menuItem || $.isEmptyObject(this.currentMenu.menuItem)) {
        alert('Vui lòng chọn thực đơn cho bàn!');
        return false;
    }
    //lưu lại giá trị cũ
    this.beforeData.currentDesk =  $.extend(true,{}, this.currentDesk);
    this.beforeData.currentMenu =  $.extend(true,{}, this.currentMenu);

    //cập nhật số lượng, phụ phí, VAT...
    var curMenu = this.currentMenu.menuItem,
        billInfo = this.currentDesk.billInfo,
        domElement = this.domElement;
    curMenu.cdm_number = parseFloat(domElement.menuNumber.autoNumeric('get'));
    curMenu.cdm_menu_discount = parseFloat(domElement.menuDiscount.autoNumeric('get'));
    billInfo.extraFee = parseFloat(domElement.extraFee.autoNumeric('get'));
    billInfo.VAT = parseFloat(domElement.vat.autoNumeric('get'));
    billInfo.customerDiscount = parseFloat(domElement.customerDiscount.autoNumeric('get'));
    this.currentDesk.menuList.map(function (menuItem) {
        if (menuItem.men_id == curMenu.men_id) {
            menuItem.cdm_number = curMenu.cdm_number;
        }
    });
    this.cashCurrentBill();
    this.view.buildCurrentDesk();
};

HomeScript.openDesk = function (elem) {
    var _this = $(elem);
    var deskData = _this.data();
    //nếu bàn đang mở rồi thì chỉ select bình thường
    if (_this.hasClass('active')) {
        HomeScript.selectDesk(elem);
        return true;
    }
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: {action: 'openDesk', desk_id: deskData.des_id},
        dataType: 'json',
        success: function (resp) {
            if (resp.error) {
                alert(resp.error);
                return false;
            } else {
                if (resp.array_menu) {
                    //active bàn
                    HomeScript.view.activeDesk(deskData.des_id);
                    HomeScript.parseResponseCurrentData(resp);
                    //tính tiền
                    HomeScript.cashCurrentBill();
                    HomeScript.view.buildCurrentDesk();
                    HomeScript.view.selectedCurrentMenu();
                }
            }
        }
    })
};
HomeScript.setDebit = function () {
    if(!HomeScript.currentDesk.deskItem.des_id) {
        $('#is-debit').removeAttr('checked');
        alert('Bạn cần chọn bàn để ghi nợ!');
        return false;
    }
    HomeScript.currentDesk.billInfo.debit = $('#is-debit').is(':checked');
    HomeScript.flagSetDebit = false;
};
HomeScript.selectDesk = function (elem) {
    var _this = $(elem);
    //nếu bàn đang được chọn thì không làm gì cả
    if (_this.hasClass('selected')) {
        //return false;
    }
    this.resetCurrentData();
    //nếu bàn này đang active thì không cần resetInput
    if (!_this.hasClass('active')) {
        this.view.resetInput();
    }
    var deskData = _this.data();
    this.currentDesk.domElement = _this;
    this.currentDesk.deskItem = new DeskItem(deskData);
    this.domElement.currentDeskName.html(deskData.full_name);

    //select bàn này
    this.view.selectedCurrentDesk();
    //nếu bàn đang active thì load chi tiết bàn
    if (_this.hasClass('active')) {
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {action: 'getCurrentDeskDetail', desk_id: HomeScript.currentDesk.deskItem.des_id},
            dataType: 'json',
            success: function (resp) {
                HomeScript.parseResponseCurrentData(resp);
                //tính tiền
                HomeScript.cashCurrentBill();
                HomeScript.view.buildCurrentDesk();
                HomeScript.view.selectedCurrentMenu();
            }
        })
    }
};
//Chọn thực đơn trong bàn
HomeScript.selectMenuInDesk = function (menu_id) {
    //gán thực đơn hiện tại
    HomeScript.currentMenu.domElement = $('#record_' + menu_id);
    var tmpMn;
    HomeScript.currentDesk.menuList.map(function (menuItem) {
        if (menuItem.men_id == menu_id) {
            tmpMn = menuItem;
        }
    });
    HomeScript.currentMenu.menuItem = new MenuInDesk(tmpMn);
    //đổi màu active cho thực đơn
    HomeScript.view.selectedCurrentMenu();
    HomeScript.view.fillDataToInput();
};
HomeScript.parseResponseCurrentData = function (resp) {
    //load thông tin hóa đơn
    this.currentDesk.billInfo.customerCode = resp.customer_code;
    this.currentDesk.billInfo.customerDiscount = parseFloat(resp.cud_customer_discount);
    this.currentDesk.billInfo.customerID = resp.cud_customer_id;
    this.currentDesk.billInfo.customerName = resp.customer_name;
    this.currentDesk.billInfo.debit = Boolean(parseInt(resp.cud_debit));
    this.currentDesk.billInfo.extraFee = parseFloat(resp.cud_extra_fee);
    this.currentDesk.billInfo.note = resp.cud_note;
    this.currentDesk.billInfo.payType = resp.cud_pay_type;
    this.currentDesk.billInfo.staffCode = resp.staff_code;
    this.currentDesk.billInfo.staffName = resp.staff_name;
    this.currentDesk.billInfo.staffID = resp.cud_staff_id;
    this.currentDesk.billInfo.startTime = resp.cud_start_time;
    this.currentDesk.billInfo.startTimeStr = resp.start_time_string;
    this.currentDesk.billInfo.VAT = parseFloat(resp.cud_vat);
    //load thông tin thực đơn
    if (resp.hasOwnProperty('array_menu')) {
        //reset lại menuList
        this.currentDesk.menuList = [];
        for (i in resp.array_menu) {
            var menuTmp = new MenuInDesk(resp.array_menu[i]);
//                console.log(menuTmp);
            this.currentDesk.menuList.push(menuTmp);
        }
    }
    //gán thực đơn đầu tiên trong list làm currentMenu
    this.currentMenu.menuItem = this.currentDesk.menuList[0];
};
HomeScript.parseResponseAddMenuToDesk = function (resp) {
    if (resp.hasOwnProperty('array_menu')) {
        //reset lại menuList
        this.currentDesk.menuList = [];
        for (i in resp.array_menu) {
            var menuTmp = new MenuInDesk(resp.array_menu[i]);
            this.currentDesk.menuList.push(menuTmp);
        }
    }
};
HomeScript.resetCurrentData = function () {
    this.flagSetDebit = false;
    this.currentDesk.domElement = null;
    this.currentDesk.menuList = [];
    this.currentDesk.deskItem = new DeskItem();
    this.currentDesk.billInfo = new BillInfo();
    this.currentMenu.domElement = null;
    this.currentMenu.menuItem = new MenuInDesk();
};

HomeScript.view = {};
HomeScript.view.activeDesk = function (des_id) {
    HomeScript.domElement.listingDesk.find('[data-des_id=' + des_id + ']').addClass('active');
};
HomeScript.view.adjustScreen = function () {
    HomeScript.domElement.mainSale.height($('.section-content').height() - 160);
    HomeScript.domElement.centerListing.height(HomeScript.domElement.mainSale.height() - 140);
    HomeScript.domElement.listingMenu.height(HomeScript.domElement.sectionContent.height() - 180);
    HomeScript.domElement.listingDesk.height(HomeScript.domElement.listingMenu.height());
};

HomeScript.view.buildCurrentDesk = function () {
    //build danh sách thực đơn ở bàn được chọn từ currentDesk
    var TableHead = React.createClass({
        render: function () {
            return <thead>
            <tr>
                <th width="32px;">STT</th>
                <th width="40%">
                    <strong>Tên thực đơn</strong>
                </th>
                <th>
                    <strong>ĐVT</strong>
                </th>
                <th>
                    <strong>SL</strong>
                </th>
                <th>
                    <strong>Đơn giá</strong>
                </th>
                <th>
                    <strong>Giảm</strong>
                </th>
                <th>
                    <strong>Thành tiền</strong>
                </th>
            </tr>
            </thead>
        }
    });
    HomeScript.react.tableMenu = (
        <table className="table table-bordered table-hover table-listing" id="table-listing">
            <TableHead/>
            {
                HomeScript.currentDesk.menuList.map(function (menuItem, index) {
                    var rowData = {
                        id: 'record_' + menuItem.men_id,
                        name: menuItem.men_name,
                        unit: menuItem.men_unit,
                        number: number_format(menuItem.cdm_number),
                        price: number_format(menuItem.cdm_price),
                        discount: number_format(menuItem.cdm_menu_discount, 1),
                        total: number_format(HomeScript.cashMenuItem(menuItem))
                    };
                    var onClickFn = function () {
                        HomeScript.selectMenuInDesk(menuItem.men_id);
                    };
                    return <tbody>
                    <tr id={rowData.id} className="menu-desk-menu record-item" onClick={onClickFn}>
                        <td className="center">{index + 1}</td>
                        <td>{rowData.name}</td>
                        <td className="center">{rowData.unit}</td>
                        <td className="center">{rowData.number}</td>
                        <td className="text-right">{rowData.price}</td>
                        <td className="center">{rowData.discount}</td>
                        <td className="text-right">{rowData.total}</td>
                    </tr>
                    </tbody>
                })
            }
            <tbody>
            <tr className="footer">
                <td colSpan="9">
                    <span class="fl nowrap">Tổng cộng {HomeScript.currentDesk.menuList.length} món</span>
                </td>
            </tr>
            </tbody>
        </table>
    );
    //render vào center_listing
    React.render(HomeScript.react.tableMenu, HomeScript.domElement.centerListing[0]);
    //fill dữ liệu vào các input
    this.fillDataToInput();
    //bỏ disable ở các input
    this.switchDisableInput(false);
    //cấp phát thanh cuộn
    this.reInitScroll();
};
HomeScript.view.collapse = function (collapse_id) {
    var ul = $('ul[data-collapse-id=' + collapse_id + ']');
    if (ul.hasClass('list-cat-child')) {
        ul.siblings('label').find('.fa').toggleClass('fa-minus-square-o').toggleClass('fa-plus-square-o');
    }
    if (ul.hasClass('list-menu-child')) {
        ul.siblings('label').find('.fa').toggleClass('fa-caret-right').toggleClass('fa-caret-down');
    }
    ul.slideToggle();
};
HomeScript.view.changeCustomer = function () {
    if (!HomeScript.currentDesk.deskItem.des_id) {
        return false;
    }
    var mindow = new Mindows;
    mindow.width = 930;
    mindow.height = 450;
    mindow.resize = true;
    mindow.iframe(HomeScript.ajaxExtendUrl.loadModalSelectCustomer, 'Quản lý thông tin khách hàng');
};
HomeScript.view.changeStaff = function () {
    if (!HomeScript.currentDesk.deskItem.des_id) {
        return false;
    }
    var mindow = new Mindows;
    mindow.width = 930;
    mindow.height = 450;
    mindow.resize = true;
    mindow.iframe(HomeScript.ajaxExtendUrl.loadModalSelectStaff, 'Quản lý thông tin nhân viên');
};
HomeScript.view.fillDataToInput = function () {
    var billInfo = HomeScript.currentDesk.billInfo,
        domElement = HomeScript.domElement,
        extraFeeText = number_format(billInfo.totalMoney * billInfo.extraFee / 100),
        customerDiscountText = number_format(billInfo.totalMoney * billInfo.customerDiscount / 100),
        vatText = number_format(billInfo.totalMoney * (1 + billInfo.extraFee / 100 - billInfo.customerDiscount / 100) * billInfo.VAT / 100);
    domElement.billNote.val(billInfo.note);
    domElement.startTimeString.val(billInfo.startTimeStr);
    domElement.customerCode.val(billInfo.customerCode);
    domElement.searchCustomer.val(billInfo.customerName);
    domElement.staffCode.val(billInfo.staffCode);
    domElement.searchStaff.val(billInfo.staffName);
    //nếu đang keyup ở các input thì không set value ở input đó
    if(trigger_keyup != 'extraFee') {
        domElement.extraFee.autoNumeric('set', billInfo.extraFee);
    }
    domElement.extraFeeText.html(extraFeeText);
    if(trigger_keyup != 'customerDiscount') {
        domElement.customerDiscount.autoNumeric('set', billInfo.customerDiscount);
    }
    domElement.customerDiscountText.html(customerDiscountText);
    if(trigger_keyup != 'vat') {
        domElement.vat.autoNumeric('set', billInfo.VAT);
    }
    domElement.vatExt.html(vatText);

    domElement.totalMoney.html(number_format(billInfo.totalMoney));
    domElement.finalMoney.html(number_format(billInfo.finalMoney) + ' VNĐ');
    //cập nhật các input chứa dữ liệu của thực đơn
    if (HomeScript.currentMenu.menuItem) {
        var menuItem = HomeScript.currentMenu.menuItem;
        domElement.menuImage.attr('src', menuItem.men_image);
        if(trigger_keyup != 'menuDiscount') {
            domElement.menuDiscount.autoNumeric('set', menuItem.cdm_menu_discount);
        }
        domElement.menuName.html(menuItem.men_name);
        if(trigger_keyup != 'menuNumber') {
            domElement.menuNumber.autoNumeric('set', number_format(menuItem.cdm_number));
        }
        domElement.menuPrice.removeClass('active').html(number_format(menuItem.men_price));
        domElement.menuPrice1.removeClass('active').html(number_format(menuItem.men_price1));
        domElement.menuPrice2.removeClass('active').html(number_format(menuItem.men_price2));
        switch (menuItem.cdm_price_type) {
            case 'men_price':
                HomeScript.domElement.menuPrice.addClass('active');
                break;
            case 'men_price1':
                HomeScript.domElement.menuPrice1.addClass('active');
                break;
            case 'men_price2':
                HomeScript.domElement.menuPrice2.addClass('active');
                break;
        }
    }
    //reset trigger keyup
    trigger_keyup = '';
};
HomeScript.view.joinDesk = function () {
    if (!HomeScript.currentDesk.deskItem.des_id) {
        alert('Phải chọn bàn để ghép');
        return false;
    }
    var desk_id = HomeScript.currentDesk.deskItem.des_id;
    var mwindow = new Mindows();
    mwindow.width = 450;
    mwindow.height = 250;
    mwindow.resize = true;
    mwindow.iframe('join_desk.php', 'Ghép bàn ăn', {desk_id: desk_id});
};
HomeScript.view.moveDesk = function () {
    if (!HomeScript.currentDesk.deskItem.des_id) {
        alert('Phải chọn bàn ăn để chuyển');
        return false;
    }
    var desk_id = HomeScript.currentDesk.deskItem.des_id;
    var mwindow = new Mindows();
    mwindow.width = 450;
    mwindow.height = 250;
    mwindow.resize = true;
    mwindow.iframe('move_desk.php', 'Chuyển bàn ăn', {desk_id: desk_id});
};
HomeScript.view.printBills = function () {
    if (HomeScript.currentDesk.deskItem.des_id && HomeScript.currentDesk.menuList.length) {
        // lấy id bàn và menu để in hóa đơn
        var desk_id = HomeScript.currentDesk.deskItem.des_id;
        var mwindow = new Mindows();
        mwindow.width = 600;
        mwindow.height = 600;
        mwindow.resize = true;
        mwindow.iframe('../printer/index.php', 'In hóa đơn tạm tính', {
            action: "PRINT_BEFORE",
            desk_id: desk_id
        });
    } else {
        alert('Chọn bàn để in. Lưu ý bàn không có thực đơn không thể in');
    }
};
HomeScript.view.printOrder = function() {
    if (HomeScript.currentDesk.deskItem.des_id && HomeScript.currentDesk.menuList.length) {
        // lấy id bàn và menu để in hóa đơn
        var desk_id = HomeScript.currentDesk.deskItem.des_id;
        var mwindow = new Mindows();
        mwindow.width = 600;
        mwindow.height = 400;
        mwindow.resize = true;
        mwindow.iframe('../printer/order.php', 'In chế biến', {
            desk_id: desk_id
        });
    } else {
        alert('Chọn bàn để in');
    }
};
HomeScript.view.selectedDesk = function (des_id) {
    //bỏ select ở các bàn khác
    HomeScript.domElement.listingDesk.find('.desk-item').removeClass('selected');
    //gán class select
    $('[data-des_id=' + des_id + ']').addClass('selected');
};
HomeScript.view.splitDesk = function () {
    if (!HomeScript.currentDesk.deskItem.des_id) {
        alert('Phải chọn bàn để tách hóa đơn');
        return false;
    }
    var desk_id = HomeScript.currentDesk.deskItem.des_id;
    var mwindow = new Mindows();
    mwindow.width = 900;
    mwindow.height = 400;
    mwindow.resize = true;
    mwindow.iframe('split_desk.php', 'Tách hóa đơn', {desk_id: desk_id});
};
HomeScript.view.selectedCurrentDesk = function () {
    if (!HomeScript.currentDesk.deskItem) {
        return false;
    }
    HomeScript.domElement.listingDesk.find('.desk-item').removeClass('selected');
    //gán class select
    HomeScript.currentDesk.domElement.addClass('selected');
};
HomeScript.view.selectedMenu = function (menu_id) {
    //bỏ active ở các menu khác
    HomeScript.domElement.centerListing.find('.record-item').removeClass('active');
    HomeScript.domElement.centerListing.find('#record_' + menu_id).addClass('active');
};
HomeScript.view.selectedCurrentMenu = function () {
    if (!HomeScript.currentMenu.menuItem) {
        return false;
    }
    HomeScript.currentMenu.domElement = $('.record-item#record_' + HomeScript.currentMenu.menuItem.men_id);
    HomeScript.domElement.centerListing.find('.record-item').removeClass('active');
    HomeScript.currentMenu.domElement.addClass('active');
    this.selectedPriceMenu(HomeScript.currentMenu.menuItem.cdm_price);
};
HomeScript.view.selectedPriceMenu = function (price_type) {
    HomeScript.domElement.menuPrice.removeClass('active');
    HomeScript.domElement.menuPrice1.removeClass('active');
    HomeScript.domElement.menuPrice2.removeClass('active');
    switch (price_type) {
        case 'men_price':
        default :
            HomeScript.domElement.menuPrice.addClass('active');
            break;
        case 'men_price1':
            HomeScript.domElement.menuPrice1.addClass('active');
            break;
        case 'men_price2':
            HomeScript.domElement.menuPrice2.addClass('active');
            break;
    }
};
HomeScript.view.settingMenuDiscount = function () {
    var result_percent = 0, result_money = 0;
    //nếu current_menu không tồn tại thì return luôn
    if (!HomeScript.currentMenu.menuItem.men_id) {
        return false;
    }
    var mindow = new Mindows();
    mindow.width = 450;
    mindow.height = 250;
    mindow.resize = true;
    var content = $('#convertPercentTemplate').html();
    mindow.openStatic(content, function () {
        var sl = HomeScript.currentMenu.menuItem.cdm_number;
        var dg = HomeScript.currentMenu.menuItem.cdm_price;
        var total_money = number_format(sl * dg);
        mindow.container.find('#total_money').val(total_money);
        callbackConvertPercent(mindow);
        //bắt sự kiện click đồng ý chuyển đổi
        mindow.container.find('#acceptConvert').unbind('click').click(function () {
            result_percent = $('#convert_result').val();
            result_money = $('#total_money').autoNumeric('get') - $('#convert_money').autoNumeric('get');
            HomeScript.currentMenu.menuItem.cdm_menu_discount = result_percent;
            HomeScript.currentDesk.menuList.map(function (item) {
                if (item.men_id == HomeScript.currentMenu.menuItem.men_id) {
                    item.cdm_menu_discount = parseFloat(result_percent);
                }
            });
            HomeScript.domElement.menuDiscount.trigger('change');
            //tính lại tiền
            HomeScript.cashCurrentBill();
            HomeScript.view.buildCurrentDesk();
            //đóng mindow
            mindow.close();
        })
    });
};
HomeScript.view.switchDisableInput = function (disabled) {
    HomeScript.domElement.menuPrice.removeClass('active');
    HomeScript.domElement.menuPrice1.removeClass('active');
    HomeScript.domElement.menuPrice2.removeClass('active');
    HomeScript.domElement.debitCheckbox.removeAttr('checked');
    if (disabled) {
        HomeScript.domElement.menuNumber.attr('disabled', 'disabled');
        HomeScript.domElement.menuDiscount.attr('disabled', 'disabled');
        HomeScript.domElement.extraFee.attr('disabled', 'disabled');
        HomeScript.domElement.staffCode.attr('disabled', 'disabled');
        HomeScript.domElement.customerCode.attr('disabled', 'disabled');
        HomeScript.domElement.searchStaff.attr('disabled', 'disabled');
        HomeScript.domElement.searchCustomer.attr('disabled', 'disabled');
        HomeScript.domElement.customerDiscount.attr('disabled', 'disabled');
        HomeScript.domElement.vat.attr('disabled', 'disabled');
        HomeScript.domElement.customerCash.attr('disabled', 'disabled');
    } else {
        HomeScript.domElement.menuNumber.removeAttr('disabled');
        HomeScript.domElement.menuDiscount.removeAttr('disabled');
        HomeScript.domElement.extraFee.removeAttr('disabled');
        HomeScript.domElement.staffCode.removeAttr('disabled');
        HomeScript.domElement.customerCode.removeAttr('disabled');
        HomeScript.domElement.searchStaff.removeAttr('disabled');
        HomeScript.domElement.searchCustomer.removeAttr('disabled');
        HomeScript.domElement.customerDiscount.removeAttr('disabled');
        HomeScript.domElement.vat.removeAttr('disabled');
        HomeScript.domElement.customerCash.removeAttr('disabled');
    }
};
//reset input
HomeScript.view.resetInput = function () {
    this.switchDisableInput(true);
    //dữ liệu đã được reset
    HomeScript.resetCurrentData();
    this.fillDataToInput();
    //reset dữ liệu trong bàn
    HomeScript.react.tableMenu = React.createElement("table", null);
    React.render(HomeScript.react.tableMenu, HomeScript.domElement.centerListing[0]);
};
HomeScript.view.reInitScroll = function () {
    $('.scrollable-area').each(function () {
        if (!$(this).parent().find('.enscroll-track').length) {
            console.log($(this));
            $(this).enscroll({
                showOnHover: true,
                minScrollbarLength: 28,
                addPaddingToPane: false
            })
        }
    });
};

//công thức tính tiền của 1 thực đơn
HomeScript.cashMenu = function (price, number, discount) {
    price = parseFloat(price);
    number = parseFloat(number);
    discount = parseFloat(discount);
    return parseFloat(price * number * (100 - discount) / 100);
};
HomeScript.cashMenuItem = function (menuItem) {
    return this.cashMenu(menuItem.cdm_price, menuItem.cdm_number, menuItem.cdm_menu_discount);
};
HomeScript.cashCurrentBill = function () {
    var total_money = 0, final_money = 0, billInfo = this.currentDesk.billInfo;
    this.currentDesk.menuList.map(function (menuItem) {
        total_money += HomeScript.cashMenuItem(menuItem);
    });
    billInfo.totalMoney = total_money;
    final_money = (total_money * (100 + billInfo.extraFee - billInfo.customerDiscount) / 100) * (100 + billInfo.VAT) / 100;
    billInfo.finalMoney = final_money;
};

//context menu
HomeScript.contextMenu = function () {
    //context menu
    $.contextMenu({
        selector: '.desk-item',
        items: {
            active: {
                name: '<i class="fa fa-play"></i> Sử dụng',
                callback: function (key, opt) {
                    var _this = $(this);
                    if(_this.hasClass('active'))
                        HomeScript.selectDesk(_this);
                    else{
                        HomeScript.selectDesk(_this);
                        HomeScript.openDesk(_this);
                    }

                }
            },
            payment: {
                name: '<i class="fa fa-check"></i> Thanh toán hóa đơn',
                callback: function (key, opt) {
                    var _this = $(this);
                    HomeScript.selectDesk(_this);
                    HomeScript.billSubmit();
                }
            },
            cancel: {
                name: '<i class="fa fa-times"></i> Hủy hóa đơn',
                callback: function (key, opt) {
                    var _this = $(this);
                    if (confirm('Bạn muốn hủy bàn này?')) {

                    }
                }
            },
            print: {
                name: '<i class="fa fa-print"></i> In tạm tính',
                callback: function (key, opt) {

                }
            },
            printmenu: {
                name: '<i class="fa fa-print"></i> In chế biến',
                callback: function (key, opt) {
                    var _this = $(this);
                    HomeScript.selectDesk(_this);

                }
            },
            fowardesk: {
                name: '<i class="fa fa-exchange"></i> Chuyển bàn',
                callback: function (key, opt) {

                }
            },
            split: {
                name: '<i class="fa fa-files-o"></i> Tách hóa đơn',
                callback: function (key, opt) {

                }
            },
            join: {
                name: '<i class="fa fa-file-text"></i> Ghép hóa đơn',
                callback: function (key, opt) {

                }
            },
            listdesk: {
                name: '<i class="fa fa-list"></i> Quản lý danh sách bàn',
                callback: function (key, opt) {

                }
            },
            refresh: {
                name: '<i class="fa fa-refresh"></i> Tải lại danh sách bàn',
                callback: function (key, opt) {

                }
            }
        }
    });
};

HomeScript.init();

function callbackConvertPercent(mindow) {
    //cấp phát numeric
    mindow.container.find('#total_money').autoNumeric({
        lZero: 'deny',
        mDec: 0
    });

    mindow.container.find('#convert_money').autoNumeric({
        lZero: 'deny',
        mDec: 0,
        vMin: 0,
        vMax: mindow.container.find('#total_money').autoNumeric('get')
    });

    //bắt sự kiện keypress vào convert_money
    $('#convert_money').keyup(function () {
        var money = $(this).autoNumeric('get');
        var total = $('#total_money').autoNumeric('get');
        $('#convert_result').val(money / total * 100);
    });

    mindow.container.find('#cancelConvert').unbind('click').click(function () {
        mindow.close();
    })
}

function communicateParentWindow(action, data) {
    //nếu không có current_desk thì không cho thực hiện
    if (!HomeScript.currentDesk.deskItem.des_id) {
        return;
    }
    switch (action) {
        case 'selectCustomer':
            //cập nhật customer
            $.ajax({
                type: 'post',
                url: 'ajax.php',
                data: {
                    action: 'updateCustomer',
                    desk_id: HomeScript.currentDesk.deskItem.des_id,
                    cus_id: data.cus_id
                },
                dataType: 'json',
                success: function (resp) {
                    loadingProgress('hide');
                    if (resp.error) {
                        alert(resp.error);
                        return false;
                    }
                    $('.mwindow-close').trigger('click');
                    //cập nhật dữ liệu
                    HomeScript.currentDesk.billInfo.customerID = data.cus_id;
                    HomeScript.currentDesk.billInfo.customerCode = data.customer_code;
                    HomeScript.currentDesk.billInfo.customerName = data.customer_name;
                    HomeScript.currentDesk.billInfo.customerDiscount = data.customer_discount;
                    //tính lại tiền
                    HomeScript.cashCurrentBill();
                    HomeScript.view.fillDataToInput();
                },
                beforeSend: function () {
                    loadingProgress('show');
                }
            });
            break;
        case 'selectStaff':
            //cập nhật nhân viên
            $.ajax({
                type: 'post',
                url: 'ajax.php',
                data: {
                    action: 'updateStaff',
                    desk_id: HomeScript.currentDesk.deskItem.des_id,
                    staff_id: data.use_id
                },
                dataType: 'json',
                success: function (resp) {
                    loadingProgress('hide');
                    if (resp.error) {
                        alert(resp.error);
                    }
                    //cập nhật dữ liệu
                    HomeScript.currentDesk.billInfo.staffID = data.use_id;
                    HomeScript.currentDesk.billInfo.staffCode = data.staff_code;
                    HomeScript.currentDesk.billInfo.staffName = data.staff_name;
                    HomeScript.view.fillDataToInput();
                    $('.mwindow-close').trigger('click');
                },
                beforeSend: function () {
                    loadingProgress('show');
                }
            });
            break;
        case 'moveDesk' :
        case 'joinDesk' :
            $('.mwindow-close').trigger('click');
            $('.desk-item[data-des_id=' + data.from_desk + ']').removeClass('active');
            $('.desk-item[data-des_id=' + data.to_desk + ']').addClass('active').trigger('click');
            break;
        case 'splitDesk':
            //đóng khung mindow
            $('.mwindow-close').trigger('click');
            //cập nhật dữ liệu của bàn
            //với dữ liệu data nhận được ta sẽ thay đổi số lượng thực đơn ở bàn hiện tại
            //đồng thời thêm active vào bàn mới được tách
            if (data.from_desk_id != HomeScript.currentDesk.deskItem.des_id) {
                return false;
            }
            HomeScript.currentDesk.menuList.map(function (itemInDesk, index) {
                if (data.from_list_menu.hasOwnProperty(itemInDesk.men_id)) {
                    itemInDesk.cdm_number = data.from_list_menu[itemInDesk.men_id].men_number;
                } else {
                    HomeScript.currentDesk.menuList.splice(index, 1);
                }
            });
            HomeScript.domElement.listingDesk.find('[data-des_id=' + data.to_desk_id + ']').addClass('active');
            //build lại thực đơn
            HomeScript.cashCurrentBill();
            HomeScript.view.buildCurrentDesk();
            HomeScript.view.selectedCurrentMenu();
            break;
        case 'setDebit':
            //đóng khung mindow
            $('.mwindow-close').trigger('click');
            HomeScript.currentDesk.billInfo.debitMoney = data.money;
            HomeScript.currentDesk.billInfo.debitTime = data.time;
            HomeScript.flagSetDebit = true;
            HomeScript.billSubmit();
            break;
        case 'printOrder':
            $('.mwindow-close').trigger('click');
            break;
        default :
            break;
    }
}