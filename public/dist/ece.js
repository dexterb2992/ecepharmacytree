$(document).ready(function (){

	$('.datatable').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
  });

    $('.select2').select2();
    $(".datemask").inputmask("dd-mm-yyyy", {"placeholder": "dd-mm-yyyy"});
    //Datemask2 mm/dd/yyyy
    $(".datemask2").inputmask("mm-dd-yyyy", {"placeholder": "mm-dd-yyyy"});
    //Date format for MySQL date
    $(".datemask3").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});

    // filter input to numeric characters only
    allowNumericOnly( $('.number'));

    $(document).on("click", ".add-edit-btn", function (){
    	var $this = $(this), 
        target = $(this).attr("data-target");
        var form = $(target), 
        id = $(this).attr('data-id'), 
        modal = $(this).data('modal-target');
        var title = "", 
        action = $this.data("action"), 
        url = "", 
        mainurl = form.attr('data-urlmain'), 
        dataTitle = $this.data('title');

        console.log('target: '+target+" id: "+target+" mainurl: "+mainurl);
        form.find ("input[type='text']").val("");
        url = mainurl+action;
        if( action == "edit" ){
            title = "Edit "+dataTitle;
            url = $this.data("url");
            $.ajax({
                url : mainurl+id,
                type : 'get',
                dataType : 'json'
            }).done(function (data){
                console.log(data);

                form.append('<input type="hidden" name="id" value="'+data.id+'">');

                $.each(data, function (i, row){
                    form.find("input[name='"+i+"']").val(row);
                    form.find("textarea[name='"+i+"']").val(row);
                    if(row == "") {
                        form.find("img[name='"+i+"']").attr('src', 'img/nophoto.jpg');
                    } else {
                        form.find("img[name='"+i+"']").attr('src', 'db/uploads/user_'+data.id+'/'+row);
                    }
                });

                form.find('select').find('option:selected').removeAttr("selected");
                $.each(form.find('select'), function (i, row){
                    var name = $(row).attr("name");
                    
                    $.each(data, function (i, col){
                        if( i == name ){
                            $(row).find('option[value="'+col+'"]').attr("selected", "selected");
                        }
                    });
                    
                });

                form.find('input#inventory_quantity').attr("unit", data.unit).attr("data-qty-per-packing")

                $(modal).modal('show');
            });
}else{
    title = "Add new "+dataTitle;
    $(modal).modal('show');
}

form.attr("data-mode", action);
form.attr("action", mainurl+action);

form.find(".modal-title").html(title);


});


$(document).on("click", ".btn-custom-alert", function (){
    customAlertResponse = $(this).data("value");

});

$(document).on("click", ".action-icon", function (){
    var $this = $(this), url = "", alertType = "";
    var id = $this.data("id"), action = $this.data('action'), mainurl = $this.data('urlmain'), dataTitle = $this.data('title');
    if( action == "deactivate" ){
        alertType = "warning";
        msg = "Are you sure you want to deactivate this "+dataTitle+"?";
        title = "Warning!";
        url = mainurl+"deactivate";
    }else if( action == "remove" ){
        url = mainurl+"deactivate";
        alertType = "danger";
        url = mainurl+"delete";
        msg = "Are you really sure you want to remove this "+dataTitle+"?";
        title = "Confirmation";
    } else if(action == "unblock") {
        url = mainurl+"unblock";
        alertType = "warning";
        msg = "Are you really sure you want to unblock this "+dataTitle+"?";
        title = "Confirmation"; 
    }

    if( action !== "reactivate" ){
        showAlert("Warning!", msg, alertType, "confirm");
    }else{
        $.ajax({
            url : '/branches/deactivate',
            type : 'post',
            dataType : 'json',
            data : { id : id, _token : $("input[name='_token']").val() }
        }).done(function (data){
            console.log(data);
            if( data.status == "success" ){
                $(document).find(".modal-alert").modal('hide');
                window.location = window.location;
            }
        });
    }

    $(document).find(".btn-custom-alert[data-value='true']").attr("data-redirect", url).attr("data-id", id);
});

$(document).on("click", ".btn-custom-alert[data-value='true']", function (){
    var $this = $(this);
    var redirectUrl = $this.data("redirect"), id = $this.data("id");
    console.log("redirectUrl: "+redirectUrl+" id: "+id);
    if( redirectUrl !== "" ){
        $.ajax({
            url : redirectUrl,
            type : 'post',
            dataType : 'json',
            data : { id : id, _token :  $("input[name='_token']").val() }
        }).done(function (data){
            console.log(data);
            if( data.status == "success" ){
                $(".modal-alert").modal('hide');
                window.location = window.location;
            }
        });
    }
});

$("#form_edit_branch, #form_edit_product_category, #form_edit_product_subcategory").submit(function (){
    var mode = $(this).data("mode"), mainurl = $(this).data('urlmain');
    $(this).attr("action", mainurl+mode);
});

$("#inventories_product_id").change(function (){
    var packing = $(this).children('option:selected').data("packing");
    $(".add-on-product-packing").html( str_plural(packing) );
});

$("#inventory_quantity").keyup(function (){

});

    $(document).on("click", ".btn-custom-alert[data-value='true']", function (){
        var $this = $(this);
        var redirectUrl = $this.data("redirect"), id = $this.data("id");
        console.log("redirectUrl: "+redirectUrl+" id: "+id);
        if( redirectUrl !== "" ){
            $.ajax({
                url : redirectUrl,
                type : 'post',
                dataType : 'json',
                data : { id : id, _token :  $("input[name='_token']").val() }
            }).done(function (data){
                console.log(data);
                if( data.status == "success" ){
                    $(".modal-alert").modal('hide');
                    window.location = window.location;
                }
            });
        }
    });

    $("#form_edit_branch, #form_edit_product_category, #form_edit_product_subcategory").submit(function (){
        var mode = $(this).data("mode"), mainurl = $(this).data('urlmain');
        $(this).attr("action", mainurl+mode);
    });

    $("#inventories_product_id").change(function (){
        updateInventoryProductQty();
    });

    $("#inventory_quantity").keyup(function (){
        updateInventoryProductQty();
    });
		
});