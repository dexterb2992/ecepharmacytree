/*! fn.ece.js | (c) 2015 ECE Marketing */

var customAlertResponse = false;

/**
 * returns random string
 */
function randomString(length){
 	var text = "";
 	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

 	for( var i=0; i < length; i++ )
 		text += possible.charAt(Math.floor(Math.random() * possible.length));

 	return text;
}

$.fn.hasParent = function(a) {
    return this.filter(function() {
        return !!$(this).closest(a).length;
    });
};


/**
 * type = info, success, warning, danger (modal's background color varies depending on the value of this parameter)
 * alertType = prompt, confirm, alert, notify (some buttons will show depending on this parameter)
 * Note: when alertType = notify, the OK button will just hide the modal, no further functions will be executed
 */
 function showAlert(title, msg, type, alertType){
 	var icon = "", alert = "", buttons = "";
 	var alertId = randomString();

 	buttons = '<button class="btn btn-outline btn-custom-alert" data-value="true">OK</button>'+
 	'<button class="btn btn-outline btn-custom-alert pull-left" data-value="false" data-dismiss="modal">Cancel</button>';

 	if( alertType == "prompt" ){
 		msg = 	'<div class="form-group">'+
 		'<label>Text</label>'+
 		'<input type="text" class="form-control input-custom-alert">'+
 		'</div>';
 	} else if( alertType == "mark_as_paid") {
 		buttons = '<button class="btn btn-outline btn-custom-alert" data-value="true">Yes</button>'+
 		'<button class="btn btn-outline btn-custom-alert pull-left" data-value="false" data-dismiss="modal">No</button>';
 	}else if( alertType == "alert" ){
 		buttons = '<button class="btn btn-outline btn-custom-alert" data-value="true">OK</button>';
 	}else if( alertType == "notify" ){
 		buttons = '<button class="btn btn-outline btn-custom-alert" data-value="false" data-dismiss="modal">OK</button>';
 	}

 	switch(type){
 		case "info" :
 		icon = "fa-info";
 		break;
 		case "success" : 
 		icon = "fa-check";
 		break;
 		case "warning" : 
 		icon = "fa-warning";
 		break;
 		case "danger" : 
 		icon = "fa-ban";
 		break;
 	}

 	var modal = $(".modal-alert");
 	modal.removeClass('modal-info').removeClass('modal-danger').removeClass('modal-success')
     	.removeClass('modal-warning').addClass("modal-"+type).addClass('fade');
 	modal.find(".modal-title").html(title);
 	modal.find(".modal-body").html(msg);
 	modal.find(".modal-footer").html(buttons);
 	modal.modal('show');
 }


 function updateInventoryProductQty(){
 	var unit = "", packing = "", qtyPerPacking = 1, totalQty = 0, qty = 0;
 	var el = $("#inventory_quantity");
 	var selectedOption = $("#inventories_product_id").children('option:selected');
 	
 	qty = el.val() == "" ? 0 : el.val();

 	qtyPerPacking = selectedOption.data("qty-per-packing");

 	totalQty = qty*qtyPerPacking;

 	unit = str_auto_plural( selectedOption.data("unit"), totalQty );
 	packing = str_auto_plural( selectedOption.data("packing"),  qty);

 	$("#total_quantity_in_unit").html( totalQty+" "+unit+" ( "+qty+" "+packing+" )" );
 	$(".add-on-product-packing").html(packing);
 	$("#outer_packing").html(packing);
 }


 function allowNumericOnly(element){
 	element.keydown(function (e) {

	    // Allow: backspace, delete, tab, escape, enter and .
	    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
	         // Allow: Ctrl+A, Command+A
	         (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
	         // Allow: home, end, left, right, down, up
	         (e.keyCode >= 35 && e.keyCode <= 40)) {
	             // let it happen, don't do anything
	         return;
	    }
        
	    // Ensure that it is a number and stop the keypress
	    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	    	e.preventDefault();
	    }
	});

    element.change(function (e){
        var val = parseFloat($(this).val());
        var max = $(this).attr("data-max");
        var min = $(this).attr("data-min");

        if( typeof max !== 'undefined' ){
            if( val >  parseFloat(max) ){
                $(this).val(max);
            }
        }

        if( typeof min !== 'undefined' ){
            if( val <  parseFloat(min) ){
                $(this).val(min);
            }
        }
    });
}

function readURL(input, target) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            target.attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function _clear_form_errors(form){
	form.find(".label-danger").html("");
}

function _clear_form_data(form){
    // to retain default value on form element, use data-default-value attribute
    form.find('input, textarea').not('input[name="_token"]').each(function(i, row){
        $(row).val( $(row).data("default-value") != "" ? $(row).data("default-value") : '' )
    });
}

function _error(element, error_msg){
    if( element.parents('.input-group').length ){ // has parent
        if( element.parent('.input-group').next('.label-danger').length ){
            element.parent('.input-group').next('.label-danger').html(error_msg);
        }else{
            element.parent('.input-group').after('<div class="label label-danger">'+error_msg+'</label>');
        }
    }else{
       if( element.next('.label-danger').length ){
            element.next('.label-danger').html(error_msg);
        }else{
            element.after('<div class="label label-danger">'+error_msg+'</label>');
        } 
    }

	
}

function formfinder(form, name, fieldType){
	if(fieldType !== "textarea")
		return form.find(fieldType+'[name="'+name+'"]').val();

	return form.find(fieldType+'[name="'+name+'"]').html();
}

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function limitStr(filename, max){
	if( filename.length <= max )
		return filename;
	return filename.substring(0, 35)+"...";
}

function getActiveSidebarMenu(){
	$(".sidebar-menu li").removeClass("active");
	$(".sidebar-menu li a[href='"+window.location.href+"']").parent("li").addClass("active");
}

function refreshProductPrimaryPhoto(id){
    var filename = "";
    $.ajax({
        url: '/products/gallery/primary/'+id,
        type: 'get',
        dataType: 'json'
    }).done(function (data){
        filename = data.filename;
        $(".products-table tbody tr[data-id='"+id+"'] td:first a img").attr("src", "/images/50x50/"+filename);
    });

}

function sendFileToServer(formData,status){
    var uploadURL ="/products/gallery/upload"; //Upload URL
    var extraData ={}; //Extra Data.
    var jqXHR = $.ajax(
    {
        xhr: function() {
        var xhrobj = $.ajaxSettings.xhr();
        if (xhrobj.upload) {
                xhrobj.upload.addEventListener('progress', function(event) {
                    var percent = 0;
                    var position = event.loaded || event.position;
                    var total = event.total;
                    if (event.lengthComputable) {
                        percent = Math.ceil(position / total * 100);
                    }
                    //Set progress
                    status.setProgress(percent);
                }, false);
            }
        return xhrobj;
    	},
	    url: uploadURL,
	    type: "POST",
        dataType: 'json',
	    contentType:false,
	    processData: false,
	        cache: false,
	        data: formData,
	        success: function(data){
                console.log(data);
	            status.setProgress(100);   
                if( data.status_code == "200" ){
                    refreshProductPrimaryPhoto(data.product_id);
                    $("#status1").append('<label class="label label-success">'+data.msg+'</label>');
                } 
	        }
    }); 
 
    status.setAbort(jqXHR);
}
 
var rowCount=0;
function createStatusbar(obj){
	rowCount++;
	var row="odd";
	if(rowCount %2 ==0) row ="even";
	this.statusbar = $("<div class='statusbar "+row+"'></div>");
	this.filename = $("<div class='filename'></div>").appendTo(this.statusbar);
	this.size = $("<div class='filesize'></div>").appendTo(this.statusbar);
	this.progressBar = $("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
	this.abort = $("<div class='abort'>Abort</div>").appendTo(this.statusbar);
	obj.after(this.statusbar);
 
    this.setFileNameSize = function(name,size){
        var sizeStr="";
        var sizeKB = size/1024;
        if(parseInt(sizeKB) > 1024)
        {
            var sizeMB = sizeKB/1024;
            sizeStr = sizeMB.toFixed(2)+" MB";
        }
        else
        {
            sizeStr = sizeKB.toFixed(2)+" KB";
        }
 
        this.filename.html(name);
        this.size.html(sizeStr);
    }

    this.setProgress = function(progress){       
        var progressBarWidth =progress*this.progressBar.width()/ 100;  
        this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "% ");
        if(parseInt(progress) >= 100)
        {
            this.abort.hide();
        }
    }

    this.setAbort = function(jqxhr){
        var sb = this.statusbar;
        this.abort.click(function()
        {
            jqxhr.abort();
            sb.hide();
        });
    }
}

function handleFileUpload(files,obj){
   for (var i = 0; i < files.length; i++) 
   {
        var fd = new FormData();
        fd.append('file', files[i]);
        fd.append('_token', $('input[name="_token"').val());
        fd.append("product_id", obj.data("id"));
 
        var status = new createStatusbar(obj); //Using this we can set progress.
        status.setFileNameSize(files[i].name, files[i].size);
        sendFileToServer(fd,status);
 
   }
}

function getOriginalCarouselItems(){
    return '<ol class="carousel-indicators">'+
        '<li data-target="#product-gallery-carousel" data-slide-to="0" class="active"></li>'+
        '<li data-target="#product-gallery-carousel" data-slide-to="1" class=""></li>'+
        '<li data-target="#product-gallery-carousel" data-slide-to="2" class=""></li>'+
    '</ol>'+
    '<div class="carousel-inner disable-contextmenu">'+
        '<div class="item active">'+
            '<img src="http://placehold.it/900x500/39CCCC/ffffff&text=1. Add photos for this product" alt="First slide">'+
        '</div>'+
        '<div class="item">'+
            '<img src="http://placehold.it/900x500/3c8dbc/ffffff&text=2. Click the button \'Add new\'" alt="Second slide">'+
        '</div>'+
        '<div class="item">'+
            '<img src="http://placehold.it/900x500/f39c12/ffffff&text=3. Drop photos on the dotted space" alt="Third slide">'+
        '</div>'+
    '</div>'+
    '<a class="left carousel-control" href="#product-gallery-carousel" data-slide="prev">'+
        '<span class="fa fa-angle-left"></span>'+
    '</a>'+
    '<a class="right carousel-control" href="#product-gallery-carousel" data-slide="next">'+
        '<span class="fa fa-angle-right"></span>'+
    '</a>';
}

// Note that we have a static variables for promo's offer type
// @param int type 
function get_promo_offer_type(type){
    switch(type){
        case 0: 
            return "Percentage base discount";
        case 1: 
            return "Peso value discount";
        case 2: 
            return "Free Gift";
        case 3: 
            return "Free Delivery";
    }
}

//
function generate_gift_qty_form(productId, productName, quantity, inputname){
    console.log("i am generating gift div now");
    quantity = (typeof quantity === 'undefined') ? 1 : quantity;

    return '<div class="form-group">'+
        '<div class="control-label col-sm-8 selected-product-qty-label">'+
            '<label>'+productName+'</label>'+
        '</div>'+
        '<div class="col-sm-4">'+
            // '<input type="text" class="form-control number" name="gift_quantities['+productId+']" value="'+quantity+'" placeholder="Quantity">'+
            '<input type="text" data-id="'+productId+'" class="form-control number '+inputname+'" name="'+inputname+'['+productId+']" value="'+quantity+'" placeholder="Quantity" required>'+
        '</div>'+
    '</div>';
}

function dataShowTarget(element, showWhen){
    // let's check if this element has a data-show-target attribute
    var showTarget = $(element).attr('data-show-target');

    // For some browsers, `showTarget` is undefined; for others,
    // `showTarget` is false.  Check for both.
    if (typeof showTarget !== typeof undefined && showTarget !== false) {
        var showTargetWhen = $(element).attr("data-show-target-when");

        if (typeof showTargetWhen !== typeof undefined && showTargetWhen !== false) {
            if( showWhen == $(element).attr("data-show-target-when") ){
                $(showTarget).fadeIn();
            }else{
                $(showTarget).fadeOut();
            }
        }else{
            // By default, if we didn't find a "data-show-target-when" attribute,
            // we automatically show the showTarget
            $(showTarget).fadeIn();
        }
    }
}

function getArrayIndexForKey(arr, key, val){
    for(var i = 0; i < arr.length; i++){
        if(arr[i][key] == val)
            return i;
    }
    return -1;
}

var _token = $('input[name="_token"]').val();

function ajaxCalls(param){
    if( param == "orders" ){
        return $.ajax({
            url: '/orders/all',
            type: 'post',
            dataType: 'json',
            data: {_token: _token}
        });
    }else if( param == "products" ){
        return $.ajax({
            url: '/products/all',
            type: 'post',
            dataType: 'json',
            data: {_token: _token}
        });
    }else if( param == "stockReturnCodes" ){
        return $.ajax({
            url: '/stock-return-codes/all',
            type: 'post',
            dataType: 'json',
            data: {_token: _token}
        });
    }
}

function calculateStockReturnAmount(){
    var total_amount = 0;
    $("#form_return_n_refund").find('input.products_return_qtys').each(function (i, row){
        total_amount+= parseFloat(window.maxReturnQty[$(row).data("id")].price) * parseFloat($(row).val());
    });

    $("#amount_refunded").val(total_amount);
    $("#refund_amount").html(total_amount);
}

