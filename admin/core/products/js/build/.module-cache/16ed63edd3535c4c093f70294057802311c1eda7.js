function ProductItem(data) {
    var _default = {
        pro_id : 0,
        pro_name : '',
        pro_unit : '',
        pro_image : '',
        pro_code : '',
        pro_number : 0,
        pro_price : 0,
        pro_total : 0
    };
    $.extend(_default,data);
    this.pro_id = _default.pro_id;
    this.pro_name = _default.pro_name;
    this.pro_unit = _default.pro_unit;
    this.pro_image = _default.pro_image;
    this.pro_price = parseFloat(_default.pro_price);
    this.pro_number = parseFloat(_default.pro_number);
    this.pro_total = this.pro_price * this.pro_number;
    this.getInstances = function(){
        return new ProductItem(_default);
    }
}
var ImportScript = ImportScript || {};
ImportScript.productActive = new ProductItem();
ImportScript.productList = ImportScript.productList || [];
ImportScript.importList = [];
ImportScript.domElement = {
    listingProduct : $('#table-listing'),
    listingImport : $('#listing-import').find('.table-listing-bound'),
    productID : $('#product-id'),
    productNumber : $('#product-number'),
    productPrice : $('#product-price'),
    productName : $('#product-name')
};
ImportScript.billInfo = {
    startDate : 0,
        note : '',
        supplier : 0,
        storeId : 0,
        debit : false,
        payType : 0
};
ImportScript.react = {};
ImportScript.react.TableHead = React.createClass({displayName: "TableHead",
    render: function () {
        return React.createElement("thead", null, 
        React.createElement("tr", null, 
            React.createElement("th", {width: "32px;"}, "STT"), 
            React.createElement("th", {width: "40%"}, 
                React.createElement("strong", null, "Mã hàng")
            ), 
            React.createElement("th", null, 
                React.createElement("strong", null, "Tên hàng")
            ), 
            React.createElement("th", null, 
                React.createElement("strong", null, "ĐVT")
            ), 
            React.createElement("th", null, 
                React.createElement("strong", null, "Số lượng")
            ), 
            React.createElement("th", null, 
                React.createElement("strong", null, "Giá nhập")
            ), 
            React.createElement("th", null, 
                React.createElement("strong", null, "Thành tiền")
            )
        )
        )
    }
});

ImportScript.react.TableRow = React.createClass({displayName: "TableRow",
    onClickFn : function () {
        ImportScript.activeProductImport()
    },
    render : function () {
        var id = 'record_' + this.props.id;
        return (React.createElement("tbody", null, 
                    React.createElement("tr", {id: id, className: "record-item import-item", onclick: this.onClickFn}, 
                        React.createElement("td", null, this.props.stt), 
                        React.createElement("td", null, this.props.code), 
                        React.createElement("td", null, this.props.name), 
                        React.createElement("td", null, this.props.unit), 
                        React.createElement("td", null, this.props.number), 
                        React.createElement("td", null, this.props.price), 
                        React.createElement("td", null, this.props.total)
                    )
                ))
    }
});
ImportScript.react.TableImport = React.createClass({displayName: "TableImport",
    render : function () {
        var rowsProduct = [];
        for(var i in ImportScript.importList) {
            var productItem = ImportScript.importList[i];
            rowsProduct.push(React.createElement(ImportScript.react.TableRow, {
                        stt: i+1, 
                        id: productItem.pro_id, 
                        code: productItem.pro_code, 
                        name: productItem.pro_name, 
                        unit: productItem.pro_unit, 
                        number: productItem.pro_number, 
                        price: productItem.pro_price, 
                        total: productItem.pro_total}
                ))
        }
        return React.createElement("table", {className: "table table-bordered table-hover table-listing"}, 
                    React.createElement(ImportScript.react.TableHead, null), 
                    rowsProduct
               )
    }
});
ImportScript.react.renderTableImport = function(){
    //console.log(ImportScript.react.TableImport);
    React.render(React.createElement(ImportScript.react.TableImport, null), ImportScript.domElement.listingImport[0])
};
ImportScript.init = function () {
    this.react.renderTableImport();
};
ImportScript.activeProductImport = function (pro_id) {
    this.productActive = this.getProductFromImport(pro_id);
    this.domElement.listingImport.find('.import-item').removeClass('active');
    this.domElement.listingImport.find('#record_' + this.productActive.pro_id).addClass('active');
    this.domElement.productID.val(this.productActive.pro_code);
    this.domElement.productName.val(this.productActive.pro_name);
    this.domElement.productPrice.val(this.productActive.pro_price);
    //console.log(this.productActive);
};
ImportScript.activeProductListing = function (pro_id) {

};
ImportScript.addProduct = function (pro_id) {

    ImportScript.importList.push(ImportScript.getProductFromList(pro_id));

};
ImportScript.getProductFromList = function (pro_id) {
    for(var i in ImportScript.productList){
        if(ImportScript.productList[i].pro_id == pro_id){
            return ImportScript.productList[i].getInstances();
        }
    }
    return false;
};
ImportScript.getProductFromImport = function (pro_id) {
    for(var i in ImportScript.importList){
        if(ImportScript.importList[i].pro_id == pro_id){
            return ImportScript.importList[i].getInstances();
        }
    }
    return false;
};
ImportScript.issetProduct = function (list, pro_id) {
    
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
});