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
 * type = info, success, warning, danger
 * alertType = prompt, confirm, alert
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
	}else if( alertType == "alert" ){
		buttons = '<button class="btn btn-outline btn-custom-alert" data-value="true">OK</button>';
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
		.removeClass('modal-warning').addClass("modal-"+type);
	modal.find(".modal-title").html(title);
	modal.find(".modal-body").html(msg);
	modal.find(".modal-footer").html(buttons);
	modal.modal('show');
}



