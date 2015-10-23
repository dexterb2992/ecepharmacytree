$(document).ready(function (){
    var old_photo_src = $('#user_photo').attr('src');
    getActiveSidebarMenu();

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

                form.find('input#inventory_quantity').attr("unit", data.unit).attr("data-qty-per-packing");
                form.find('#inventories_product_id').attr("disabled", "disabled");
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
            form.find('#inventories_product_id').removeAttr("disabled");
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

    /** Add your forms here */
    $("#form_edit_branch, #form_edit_product_category, #form_edit_product_subcategory, #form_edit_inventory").submit(function (){
        var mode = $(this).data("mode"), mainurl = $(this).data('urlmain');
        $(this).attr("action", mainurl+mode);
        $(this).find("select[disabled='disabled']").removeAttr("disabled");
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
                    window.location = window.location; // reload page
                }
            });
        }
    });

    $("#inventories_product_id").change(function (){
        var packing = $(this).children('option:selected').attr("data-packing");
        $(document).find("#outer_packing").html(packing);
        $(document).find(".add-on-product-packing").html( str_plural(packing) );
        console.log("packing: "+packing);
    });


    $("#inventory_quantity").change(function (){
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

        $(".table-referrals tr").removeClass("selected");
        $(".show-downlines").removeClass("selected");
        $(this).addClass("selected");
        $(this).parent("td").parent("tr").addClass("selected");
    });

    // added codes below on October 16, 2015 11:30 AM
        $("#browse_photo").change(function (){
            readURL(this, $('#user_photo'));
            var filename = $(this).val().replace(/C:\\fakepath\\/i, '');
            $("#photo_filename").attr("title", filename).html(limitStr(filename, 35)+'<span id="cancel_update_photo" class="cancel-update-photo" data-toggle="tooltip" data-original-title="Cancel"><i class="fa fa-close"></i></span>').show();
        });
            
        $(document).on("click", "#cancel_update_photo", function(){
            $("#photo_filename").slideUp(function(){ $(this).html(""); });
            $("#user_photo").attr("src", old_photo_src);
            var browse = $("#browse_photo");
            browse.replaceWith( browse = browse.clone( true ) );
        });

        $("#btn_submit_form_update_password").click(function (){
            var form = $("#form_update_password");
            var data = {
                old_password: formfinder(form, 'old_password', 'input'),
                new_password: formfinder(form, 'new_password', 'input'),
                new_password_confirmation: formfinder(form, 'new_password_confirmation', 'input'),
                _token: formfinder(form, '_token', 'input')
            };

            var $this = $(this);
            $this.attr("disabled", "disabled").addClass("disabled").html("Please wait...");

            $.ajax({
                url: '/admin/update-password',
                type: 'post',
                dataType: 'json',    
                data: {
                    old_password: formfinder(form, 'old_password', 'input'),
                    new_password: formfinder(form, 'new_password', 'input'),
                    new_password_confirmation: formfinder(form, 'new_password_confirmation', 'input'),
                    _token: formfinder(form, '_token', 'input')
                }
            }).done(function(data){
                $this.removeClass("disabled").removeAttr("disabled").html("Update password");

                _clear_form_errors(form);
                _clear_form_data(form);

                $.each(data.errors, function (i, row){
                    _error($('input[name="'+i+'"]'), row[0]);
                });

                // if successful, hide the modal
                if( data.status_code == "200" ){
                    $("#modal_update_password").modal('hide');
                    showAlert("Password notification", "Your password has been changed successfully.", 'success', 'notify');
                }

            }).fail(function (){
                $this.removeClass("disabled").removeAttr("disabled").html("Update password");
            });
        });

        $('#btn_update_info').click(function (){
            var form = $("#form_update_info");
            var $this = $(this);

            $this.attr("disabled", "disabled").addClass("disabled").html("Please wait...");

            var formdata = {
                fname: formfinder(form, 'fname', 'input'),
                mname: formfinder(form, 'mname', 'input'),
                lname: formfinder(form, 'lname', 'input'),
                email: formfinder(form, 'email', 'input'),
                _token: formfinder(form, '_token', 'input')
            };

            $.ajax({
                url: '/profile/update',
                type: 'post',
                dataType: 'json',
                data: formdata
            }).done(function (data){
                console.log(data);
                _clear_form_errors(form);

                $.each(data.errors, function (i, row){
                    _error($('input[name="'+i+'"]'), row[0]);
                });


                if( data.status_code == '200' ){
                    showAlert("Password notification", "Your profile has been updated.", 'info', 'notify');

                    $this.removeClass("disabled").removeAttr("disabled").html("Update Info");

                    $.each(formdata, function (i, row){
                        if( i !== "_token" ){
                            form.find('input[name="'+i+'"]').val(row);
                        }
                    });
                }else{

                }

                $this.removeClass("disabled").removeAttr("disabled").html("Update Info");

            }).fail(function(data){
                console.log(data);
                $this.removeClass("disabled").removeAttr("disabled").html("Update Info");
            });
        });

        $('select#address_region').change(function(){
            var provinces = '<option value="0">- Select Province -</option>';
            $("#address_province").html(provinces).select2();

            if( $(this).val() !='0' ){
                $.ajax({
                    url: '/locations/get/provinces/where-regions/'+$(this).val(),
                    type: 'get',
                    dataType: 'json'
                }).done(function (data){
                    console.log(typeof(data));
                    if( typeof(data) == 'object' ){
                        
                        $("#address_city_municipality").html('<option value="0">- Select Municipality -</option>');

                        $.each(data, function (i, row){
                            provinces += '<option value="'+row.id+'">'+row.name+'</option>';
                        });

                        $("#address_province").html(provinces).select2();
                        $('#address_city_municipality').select2();
                    }
                });
            }
        });

        $('select#address_province').change(function(){
            if( $(this).val() !='0' ){
                $.ajax({
                    url: '/locations/get/municipalities/where-provinces/'+$(this).val(),
                    type: 'get',
                    dataType: 'json'
                }).done(function (data){
                    console.log(typeof(data));
                    if( typeof(data) == 'object' ){
                        var municipalities = '<option value="0">- Select Municipality -</option>';
                        $.each(data, function (i, row){
                            municipalities += '<option value="'+row.id+'">'+row.name+'</option>';
                        });

                        $("#address_city_municipality").html(municipalities).select2();
                    }
                });
            }
        });
		
});