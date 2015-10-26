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
 	modal.removeClass('modal-info')
 	.removeClass('modal-danger')
 	.removeClass('modal-success')
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
	form.find('input').not('input[name="_token"]').val("");
	form.find('textarea').not('input[name="_token"]').val("");
}

function _error(element, error_msg){
	if( element.next('.label-danger').length ){
		element.next('.label-danger').html(error_msg);
	}else{
		element.after('<div class="label label-danger">'+error_msg+'</label>');
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
