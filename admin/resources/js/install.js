"use strict";
var listDownload = {
    commonConfig : {
        title : 'cài đặt chung',
        link : 'install.php?step=commonConfig'
    },
    menu: {
        title: 'dữ liệu thực đơn',
        link: 'install.php?step=menu'
    },
    desk: {
        title: 'danh sách bàn',
        link: 'install.php?step=desk'
    },
    product : {
        title : 'danh sách nguyên liệu',
        link : 'install.php?step=product'
    },
    staff : {
        title : 'dữ liệu nhân viên',
        link : 'install.php?step=staff'
    },
    promotion : {
        title : 'thông tin khuyến mại',
        link : 'install.php?step=promotion'
    },
    bill_in : {
        title : 'dữ liệu hóa đơn bán hàng',
        link : 'install.php?step=bill_in'
    },
    bill_out : {
        title : 'dữ liệu hóa đơn nhập hàng',
        link : 'install.php?step=bill_out'
    },
    customer : {
        title : 'dữ liệu khách hàng',
        link : 'install.php?step=customer'
    },
    financial : {
        title : 'dữ liệu tài chính',
        link : 'install.php?step=financial'
    },
    supplier : {
        title : 'dữ liệu nhà cung cấp',
        link : 'install.php?step=supplier'
    },
    current_desk : {
        title : 'dữ liệu bàn hiện tại',
        link : 'install.php?step=current_desk'
    },
    countUrl : 'install.php?step=countAll'
};
var installParam = {
    settingDone : false,
    recordCount: 0,
    recordTotal : 0,
    running : false,
    queueProcess : [],
    menu : {
        count : 0,
        total : 0
    },
    desk : {
        count : 0,
        total : 0
    },
    current_desk : {
        total : 1
    },
    product : {
        count : 0,
        total : 0
    },
    bill_in : {
        count : 0,
        total : 0
    },
    bill_out : {
        count : 0,
        total : 0
    },
    financial : {
        count : 0,
        total : 0
    },
    customer : {
        total : 1
    },
    supplier : {
        total : 1
    },
    promotion : {
        total : 1
    },
    staff : {
        total : 1
    },
    commonConfig : {
        total : 1
    }
};
var loadingMsgWrp = $('#loading-message');
var moduleTitleWrp = $('#module-in-progress');
var progressStep = $('#step');
var progressTotal = $('#total');

function countAll() {
    return $.ajax({
        type : 'post',
        url : listDownload.countUrl,
        dataType : 'json',
        data : {age_id : $('#age_id').val()},
        success : function(resp) {
            for(var i in resp) {
                installParam[i].total = resp[i];
                installParam.recordTotal += resp[i];
            }
        }
    })
}

var ajaxStepByStep = function () {
    var next = function () {
            installParam.running = false;
            if(installParam.queueProcess.length) {
                ajaxStepByStep(installParam.queueProcess.shift());
            }
        };
    return function(data) {
        if(installParam.running) return installParam.queueProcess.push(data);
        installParam.running = true;
        data.age_id = $('#age_id').val();
        if(data.ajax_type == 'end_all_step') {
            endSetting();
            return true;
        }
        return $.ajax({
            type : 'post',
            data : data,
            url : listDownload[data.type].link,
            dataType : 'json',
            success : function (resp) {
                switch (data.ajax_type) {
                    case 'record_load':
                        moduleTitleWrp.html(listDownload[data.type].title);
                        progressTotal.html(installParam[data.type].total);
                        progressStep.html(resp.step);
                        drawProgressBar();
                        break;
                }
            },
            complete : next
        })
    }
}();

function drawProgressBar() {
    if(installParam.recordCount < installParam.recordTotal) {
        installParam.recordCount++;
    }
    var step_width = installParam.recordCount / installParam.recordTotal * 100;
    $('#loading-progress-bar-step').css('width',step_width + '%');
}

function endSetting() {
    $('#loading-message').addClass('hidden');
    $('#success-message').removeClass('hidden');
    installParam.settingDone = true;
    $('#after-setting').removeClass('hidden');
}

function download() {
    if(installParam.settingDone) {
        return false;
    }
    if(!$('#age_id').val()) {
        alert('Chưa chọn chi nhánh');
        return false;
    }
    loadingMsgWrp.removeClass('hidden');
    countAll().done(function () {
        //download dữ liệu cài đặt chung
        ajaxStepByStep({type : 'commonConfig',ajax_type : 'record_load'});
        //download dữ liệu thực đơn
        while(installParam.menu.count < installParam.menu.total) {
            ajaxStepByStep({record : installParam.menu.count, type : 'menu', ajax_type : 'record_load'});
            installParam.menu.count++;
        }
        //download dữ liệu bàn
        while(installParam.desk.count < installParam.desk.total) {
            ajaxStepByStep({record : installParam.desk.count, type : 'desk', ajax_type : 'record_load'});
            installParam.desk.count++;
        }
        //download dữ liệu bàn đang mở
        ajaxStepByStep({type : 'current_desk', ajax_type : 'record_load'});
        //download dữ liệu nguyên liệu
        while(installParam.product.count < installParam.product.total) {
            ajaxStepByStep({record : installParam.product.count, type : 'product', ajax_type : 'record_load'});
            installParam.product.count++;
        }
        //download dữ liệu nhân viên
        ajaxStepByStep({type : 'staff', ajax_type : 'record_load'});
        //download dữ liệu khuyến mại
        ajaxStepByStep({type : 'promotion', ajax_type : 'record_load'});
        //download dữ liệu hóa đơn bán hàng
        while(installParam.bill_in.count < installParam.bill_in.total) {
            ajaxStepByStep({record : installParam.bill_in.count, type : 'bill_in', ajax_type : 'record_load'});
            installParam.bill_in.count++;
        }
        //download dữ liệu hóa đơn nhập hàng
        while(installParam.bill_out.count < installParam.bill_out.total) {
            ajaxStepByStep({record : installParam.bill_out.count, type : 'bill_out', ajax_type : 'record_load'});
            installParam.bill_out.count++;
        }
        //download dữ liệu khách hàng
        ajaxStepByStep({type : 'customer', ajax_type : 'record_load'});
        //download dữ liệu tài chính
        while(installParam.financial.count < installParam.financial.total) {
            ajaxStepByStep({record : installParam.financial.count, type : 'financial', ajax_type : 'record_load'});
            installParam.financial.count++;
        }
        //download dữ liệu nhà cung cấp
        ajaxStepByStep({type : 'supplier', ajax_type : 'record_load'});

        //kết thúc download dữ liệu
        ajaxStepByStep({ajax_type : 'end_all_step'});
    })
}