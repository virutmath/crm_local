function ProductItem(data) {
    var _default = {
        pro_id : 0,
        pro_name : '',
        pro_unit : '',
        pro_image : '',
        pro_code : ''
    };
    $.extend(_default,data);
    this.pro_id = _default.pro_id;
    this.pro_name = _default.pro_name;
    this.pro_unit = _default.pro_unit;
    this.pro_image = _default.pro_image;
}

var ImportScript = {
    productActive : new ProductItem(),
    productList : [],
    domElement : {
        listingProduct : $('#table-listing')
    },
    billInfo : {
        startDate : 0,
        note : '',
        supplier : 0,
        storeId : 0,
        debit : false,
        payType : 0
    }
};
ImportScript.activeProductImport = function (product) {

};

$.contextMenu({
    selector: '.import-item',
    items: {
        delete: {
            name: '<i class="fa fa-trash"></i> Xóa mặt hàng này',
            callback : function () {

            }
        }
    }
})