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

    $('.daterange').daterangepicker({format: 'YYYY-MM-DD'});

    $("#org").jOrgChart({
        chartElement : '#chart',
        dragAndDrop  : false
    });

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

                var start_date = "", end_date;

                $.each(data, function (i, row){
                    
                    if( i == "description" ){
                        var str = row+"";
                        // var reg = /\\r\\n|\\n|\\r/g;
                        var reg = /\\r?\\n/g;
                        // var newRow = str.replace(reg, '&#13;&#10;');
                        var newRow = str.replace(reg, '\n');
                        console.log("row: "+String(row));
                        console.log("newRow: "+newRow);
                    }

                    form.find("input[name='"+i+"']").val(row);
                    form.find("textarea[name='"+i+"']").val(newRow);
                    if(row == "") {
                        form.find("img[name='"+i+"']").attr('src', 'img/nophoto.jpg');
                    } else {
                        form.find("img[name='"+i+"']").attr('src', 'db/uploads/user_'+data.id+'/'+row);
                    }

                    if( i == "start_date" ) 
                        start_date = row;

                    if( i == "end_date" )
                        end_date = row;
                });

                form.find("input#date_range").val(start_date+" - "  +end_date);

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
                updateInventoryProductQty();
            });
        } else if(action == "preview_image"){
            var img_source = $(this).children('img').attr('src');
            $('#image_holder').attr('src', img_source);               
            console.log(img_source); 
            $(modal).modal('show');
            
        } else if(action == "fulfill_items") {

        }else{
            title = "Add new "+dataTitle;
            
            form.find("input").not("input[name='_token']").val("");
            updateInventoryProductQty();
        }

        form.attr("data-mode", action);
        form.attr("action", mainurl+action);

        form.find(".modal-title").html(title);
        
        $(modal).modal('show');
    });


    $(document).on("click", ".btn-custom-alert", function (){
        customAlertResponse = $(this).data("value");

    });

    /**
     * For alert/confirmation dialogs
     */
    $(document).on("click", ".action-icon", function (){
        var $this = $(this), url = "", alertType = "";
        var id = $this.data("id"), action = $this.data('action'), mainurl = $this.data('urlmain'), dataTitle = $this.data('title');
        if( action == "deactivate" ){
            alertType = "warning";
            type="confirm";
            msg = "Are you sure you want to deactivate this "+dataTitle+"?";
            title = "Warning!";
            url = mainurl+"deactivate";
        } else if( action == "remove" ){
            url = mainurl+"deactivate";
            type="confirm";
            alertType = "danger";
            url = mainurl+"delete";
            msg = "Are you really sure you want to remove this "+dataTitle+"?";
            title = "Confirmation";
        } else if(action == "unblock") {
            url = mainurl+"unblock";
            alertType = "warning";
            type = "confirm";
            msg = "Are you really sure you want to unblock this "+dataTitle+"?";
            title = "Confirmation"; 
        } else if( action == "disapprove" ){
            alertType = "danger";
            type = "confirm";
            msg = "Are you sure you want to disapprove this "+dataTitle+"?";
            title = "Warning!";
            url = mainurl+"disapprove";
        } else if( action == "approve" ){
            alertType = "success";
            type = "confirm";
            msg = "Are you sure you want to approve this "+dataTitle+"?";
            title = "Warning!";
            url = mainurl+"approve";
        } else if(action == "mark_as_paid"){
            alertType = "primary";
            type="mark_as_paid";
            msg = "Processed by <b>"+dataTitle+"</b>.<br/> Are you sure you received the payment for this order? ";
            title = "Mark as paid";
            url = "mark_as_paid/"+mainurl;
        }

        if( action !== "reactivate" ){
            showAlert(title, msg, alertType, type);
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
        $("#outer_packing").html(packing);
        $(".add-on-product-packing").html( str_plural(packing) );
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

    $("#inventories_product_id, #inventory_quantity").change(function (){
        updateInventoryProductQty();
    });

    $("#inventory_quantity").keyup(function (){
        updateInventoryProductQty();
    });

    $("select.sort-by").change(function (){
       $("input[type='search']:visible").val( $(this).val() ).trigger("keyup");
    })

    $("#date_range").change(function (){
        var dates = $(this).val();
        console.log(dates);
        $("input[name='start_date']").val( $("input[name='daterangepicker_start']").val() );
        $("input[name='end_date']").val( $("input[name='daterangepicker_end']").val() );
    });

    $(document).on("click", ".show-downlines", function(){
        $("#chart").html("");
        $("ul.referral-chart[data-id='"+$(this).data("id")+"']").jOrgChart({
            chartElement : '#chart',
            dragAndDrop  : false
        });

        $(".show-downlines").removeClass("selected");
        $(this).addClass("selected");
    });
		
});