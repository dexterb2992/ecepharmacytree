/*! ece.js | (c) 2015 ECE Marketing */

$(document).ready(function (){
    var old_photo_src = $('#user_photo').attr('src');
    window.global_free_gift_product_ids = [];
    window.global_free_gift_quantities = []; // quantities from database

    window.global_per_transaction_free_gift_product_ids = [];
    window.global_per_transaction_quantities = [];

    /* Morris.js Charts */
    // Sales chart
    try{
        $.ajax({
            url: '/get-sales',
            type: 'get',
            assync: false,
            dataType: 'json'
        }).done(function (data){
            console.log(data);
            var a = data;
            console.log(a[""]);
            var area = new Morris.Area({
                element: 'revenue-chart',
                resize: true,
                /*data: [
                    {y: '2011 Q1', item1: 2666, item2: 2666},
                    {y: '2011 Q2', item1: 2778, item2: 2294},
                    {y: '2011 Q3', item1: 4912, item2: 1969},
                    {y: '2011 Q4', item1: 3767, item2: 3597},
                    {y: '2012 Q1', item1: 6810, item2: 1914},
                    {y: '2012 Q2', item1: 5670, item2: 4293},
                    {y: '2012 Q3', item1: 4820, item2: 3795},
                    {y: '2012 Q4', item1: 15073, item2: 5967},
                    {y: '2013 Q1', item1: 10687, item2: 4460},
                    {y: '2013 Q2', item1: 8432, item2: 5713}
                ],*/
                data: a[""],
                xkey: 'y',
                ykeys: ['Sales'],
                labels: ['Sales'],
                lineColors: ['#a0d0e0', '#3c8dbc'],
                hideHover: 'auto'
            });
        });

       
    }catch(Exception){
        console.log(Exception);   
    }

    //Fix for charts under tabs
    $('.box ul.nav a').on('shown.bs.tab', function (e) {
        area.redraw();
        donut.redraw();
    });

    $('.datatable').DataTable({
        "responsive": true,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });

    $('.table-orders').DataTable({
        "responsive": true,
        "iDisplayLength": -1,
        "aaSorting": [[ 0, "desc" ]]
    });

    $('.table-referrals, #tbl_stock_returns').DataTable({
        "responsive": true,
        "iDisplayLength": -1,
        "aaSorting": [[ 2, "desc" ]]
    });

    $('.table-points-log, #tbl_inventory_logs').DataTable({
        "responsive": true,
        "aaSorting": [[ 0, "desc" ]]
    });


    $('.btn').addClass("btn-flat");

    $('.select2').select2();
    $('.icheck').iCheck({
        checkboxClass: 'icheckbox_square-red',
        radioClass: 'iradio_square-red',
        increaseArea: '20%' // optional
    });

    $('.icheck').on('ifChecked', function(event){
        var cbox = $(this);
        cbox.val( cbox.attr('data-check-value') ).trigger('change');
    }).on('ifUnchecked', function(event){
        var cbox = $(this);
        cbox.val( cbox.attr('data-uncheck-value') ).trigger('change');
    });

    $(".datemask").inputmask("dd-mm-yyyy", {"placeholder": "dd-mm-yyyy"});
    //Datemask2 mm/dd/yyyy
    $(".datemask2").inputmask("mm-dd-yyyy", {"placeholder": "mm-dd-yyyy"});
    //Date format for MySQL date
    $(".datemask3").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});

    $('.daterange').daterangepicker({format: 'YYYY-MM-DD'});

    $('[data-toggle="popover"]').popover();   

    // make sure to add 'fade' class only once
    $('div.modal').removeClass('fade').addClass('fade');

    $("#org").jOrgChart({
        chartElement : '#chart',
        dragAndDrop  : false
    });

    var search_keyword = $.getUrlParam('q');
    if( search_keyword != "" ){
        $('.dataTables_filter').find('input[type="search"]:visible').val(search_keyword).trigger('keyup');
    }

    // filter input to numeric characters only
    allowNumericOnly( $('.number') );


    /**
      to use: add "add-edit-btn" class to element
        - define the modal by adding an attribute "data-modal-target" (use the modal id for its value)
        - define the form by adding an attribute "data-target" (use the form id for its value)
        - define what action if it's add or edit by adding an attribute "data-action" (add/edit)
        - define the modal title by adding "data-title" attribute (any title you want to appear on modal's header)
        - define the id by adding "data-id" attribute (only if data-action is edit)

      note: make sure to add a "data-urlmain" attribute to the target form

      to display a div when a specific option is selected on a dropdown, add a "data-show-target"
        attribute to your dropdown element where its value is the id/class of the div to show. ex: #show_this_div
      
      P.S. Add your own codes below to achieve the results you wanted
    */
    // $(document).on("click", ".just-show-the-modal-no-ajax", function(){
    //     alert('fuck you');
    //         // $this.attr('#modal-fulfill-items').modal('show');
    // });

    $('.just-show-the-modal-no-ajax').click(function(){
        var modal = $(this).attr('data-modal-target');
            $(modal).modal('show');
        });
    $(document).on("click", ".add-edit-btn", function (){
    	var $this = $(this), 
            target = $this.attr("data-target");
        var form = $(target), 
            id = $this.attr('data-id'), 
            modal = $this.attr('data-modal-target');
        var title = "", 
            action = $this.attr("data-action"), 
            url = "", 
            mainurl = form.attr('data-urlmain'), 
            dataTitle = $this.attr('data-title'),
            rawdata = [];

        console.log('target: '+target+" id: "+target+" mainurl: "+mainurl);
        _clear_form_data(form);
        url = mainurl+action;
        window.hasAdditionalAddress = false;
        window.responseBarangayId = 0;
        form.attr("data-mode", action);
        form.attr("action", mainurl+action);

        form.show(); // make sure form is not hidden
        $('.modal-backdrop').remove();
        if( action == "edit" || action == "view"){
            title = action.ucfirst()+" "+dataTitle;
            url = $this.data("url");
            $.ajax({
                url : mainurl+id,
                type : 'get',
                dataType : 'json'
            }).done(function (data){
                console.log(data);
                rawdata = data;

                try{
                    if( form.find("input[name='id']").length < 1 ){
                        form.append('<input type="hidden" name="id" value="'+data.id+'">');
                    }else{
                        form.find("input[name='id']").val(data.id);
                    }

                    var start_date = "", end_date;

                    $.each(data, function (i, row){ // loop through all object elements 
                        // just add your custom conditions here 
                        if( i == "barangay_id" ){
                            window.responseBarangayId = row;
                            // let's populate address here
                            $.ajax({
                                url: '/populate-address/'+window.responseBarangayId,
                                type: 'get',
                                dataType: 'json'
                            }).done(function (data){
                                console.log(data);
                                if( data.hasOwnProperty('provinces') && data.hasOwnProperty('municipalities') 
                                    && data.hasOwnProperty('barangays') && data.hasOwnProperty('selected') ){

                                    var provinces = address_populator_helper(data.provinces, data.selected.province_id);
                                    var municipalities = address_populator_helper(data.municipalities, data.selected.municipality_id);
                                    var barangays = address_populator_helper(data.barangays, data.selected.barangay_id);


                                    $("select[name='province_id'], select[name='municipality_id'], select[name='barangay_id'], select[name='region_id']").select2('destroy');

                                    $("select[name='region_id'] option[value='"+data.selected.region_id+"']").attr("selected", "selected");

                                    $("select[name='province_id']").html(provinces).select2();
                                    $("select[name='municipality_id']").html(municipalities).select2();
                                    $("select[name='barangay_id']").html(barangays).select2();
                                    $("select[name='region_id']").select2();
                                }
                            });
                        }


                        form.find("textarea[name='"+i+"']").val(row);

                        if( i == "description" ){
                            var str = row+"";
                            var reg = /\\r?\\n/g; // remove extra slashes
                            var newRow = str.replace(reg, '\n');

                            form.find("textarea[name='"+i+"']").val(newRow);
                        }

                        if( i == "additional_address" ){
                            window.hasAdditionalAddress = true;
                        }

                        form.find("input[name='"+i+"']").val(row);
                        
                        var cbox = form.find('input[name="'+i+'"][type="checkbox"]');
                        if( cbox.length > 0 ){
                            if( row == 1 ){
                                cbox.attr("checked", "checked");
                                cbox.iCheck('uncheck');
                                cbox.iCheck('check');
                            }else{
                                cbox.removeAttr("checked");
                                cbox.iCheck('check');
                                cbox.iCheck('uncheck');
                            }
                        }

                        var radio = form.find('input[name="'+i+'"][type="radio"][data-check-value="'+row+'"]');
                        if( radio.length > 0 ){
                            radio.attr("checked", "checked");
                            radio.iCheck('uncheck');
                            radio.iCheck('check');
                            radio.trigger("change");
                        }

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

                    // form.find('select').find('option:selected').removeAttr("selected");
                    $.each(form.find('select'), function (i, row){
                        var name = $(row).attr("name");

                        $.each(data, function (i, col){
                            if( i == name ){
                                // $(row).find('option[value="'+col+'"]').attr("selected", "selected");
                                $(row).val(col); // works only with non-multiple select
                                console.log("yeayeayea");
                                dataShowTarget(row, col);
                            }
                        });

                    });

                    // search for select dropdown with array names ex. names[]
                        $.each(data, function (i, row){
                            form.find("input[name='"+i+"[]']").val(row); 
                            var does_array_select_exists = form.find("select[name='"+i+"[]']");

                            if( does_array_select_exists.length > 0 ){
                                $.each(row, function (a, b){
                                    var lets_check = form.find("select[name='"+i+"[]'] option[value='"+b.id+"']");
                                    if( lets_check.length > 0 ){
                                        $(lets_check).attr("selected", "selected");
                                    }
                                });

                                if( does_array_select_exists.hasClass('select2') ){
                                    does_array_select_exists.select2();
                                }
                            }
                        });

                    
                }catch(Exception){
                    console.log(Exception);
                }

                if( data.hasOwnProperty('product') ){
                    form.find('input#inventory_quantity').attr("unit", data.product.unit).attr("data-qty-per-packing", data.product.qty_per_packing);
                }else{
                    form.find('input#inventory_quantity').attr("unit", data.unit).attr("data-qty-per-packing", data.qty_per_packing);
                }
                
                // form.find('#inventories_product_id, #inventory_quantity').attr("disabled", "disabled");
                form.find('#inventory_quantity').attr("disabled", "disabled");
                
                // hide select2 and replace with label
                $("#inventories_product_id").prev(".select2-container").fadeOut(function (){
                    $("#inventories_product_id").next('.temp_name').html(data.product.name).show();
                });

                updateInventoryProductQty();

                // add your conditions & magic codes here when form fields has been filled 
                    if( data.hasOwnProperty('has_free_gifts') ){
                        if( data.has_free_gifts == 0 ){
                            $(".selected-products-qty-div").html("");
                            $("#promo_details_gifts").val("");
                            init_specific_product_list( $("#promo_details_gifts") );
                        }
                    }

                    if( $this.hasClass('promo-product-details') ){
                        window.global_free_gift_product_ids = [];
                        window.global_free_gift_quantities = [];

                        console.log("promo details");
                        if( data.has_free_gifts == 1 ){
                            console.log("fetch for free gifts now.");
                            $.ajax({
                                url: '/promos/details/gifts',
                                type: 'post',
                                dataType: 'json',
                                data: { id: data.id, _token: _token }
                            }).done(function (data){
                                console.log(data);
                                var products_list = $("#promo_details_gifts");
                                var htmls = "";
                                var _details_gifts = [];
                                $.each(data, function (i, row){
                                    _details_gifts.push(row.product.id);
                                    // $("#promo_details_gifts option[value='"+row.product.id+"']").attr("selected", "selected");
                                    htmls+= generate_gift_qty_form(row.product.id, row.product.name, row.quantity_free, 'gift_quantities');
                                    window.global_free_gift_product_ids.push(row.product.id);
                                    window.global_free_gift_quantities.push({"id" : row.product.id, "quantity" : row.quantity_free});
                                });
                                $(".selected-products-qty-div").html(htmls);
                                // $("#promo_details_gifts").select2();
                                $("#promo_details_gifts").val( _details_gifts.join(',') );

                                init_specific_product_list( $("#promo_details_gifts") );
                                
                                $("#form_promo_product_info").find('input.gift_quantities').each(function (i, row){
                                    $(row).attr("data-min", 1);
                                });
                                allowNumericOnly( $('.number') );
                            });
                        }
                    }

                    if( data.hasOwnProperty('per_transaction_has_free_gifts') ){
                        var products_list = $("#promo_details_per_transaction_gifts");
                        var htmls = "";
                        var _free_gifts = [];
                        $.each(data.free_gifts, function (i, row){
                            // $("#promo_details_per_transaction_gifts option[value='"+row.id+"']").attr("selected", "selected");
                            _free_gifts.push(row.id);
                            htmls+= generate_gift_qty_form(row.id, row.name, row.quantity_free, 'per_transaction_gift_quantities');
                            window.global_per_transaction_quantities.push(row.id);
                            window.global_per_transaction_free_gift_product_ids.push({"id" : row.id, "quantity" : row.quantity_free});
                        });
                        $(".per-transaction-selected-products-qty-div").html(htmls);
                        // $("#promo_details_per_transaction_gifts").select2();
                        $("#promo_details_per_transaction_gifts").val(_free_gifts.join(','));

                        init_specific_product_list( $("#promo_details_per_transaction_gifts") );
                        
                        $("#form_promo_product_info").find('input.per_transaction_gift_quantities').each(function (i, row){
                            $(row).attr("data-min", 1);
                        });
                    }

                    if( data.hasOwnProperty("specific_promo_product_ids") ){
                        init_specific_product_list( $("#specific_promo_product_ids") );
                    }

                    if( data.hasOwnProperty('products_involved') ){
                         init_specific_product_list( $("#product_groups_products_involved") );
                    }

                    $(modal).modal('show');
            });
        
        } else if(action == "preview_image"){
            var img_source = $(this).children('img').attr('src');
            $('#image_holder').attr('src', img_source);               
            console.log(img_source); 
            $(modal).modal('show');

        } else if(action == "fulfill_items") {
            
        } else {
            title = "Add new "+dataTitle;
            $("#inventories_product_id").next('.temp_name').html("").fadeOut(function (){
                $("#inventories_product_id").prev(".select2-container").show();
            });
            // clear select2 values 
            $('input.products-multiple-select2').val("");
            init_specific_product_list( $('input.products-multiple-select2') );

            _clear_form_data(form);
            form.find('#inventories_product_id, #inventory_quantity').removeAttr("disabled").trigger("change");
            updateInventoryProductQty();

            $(modal).modal('show');
        }
        
        form.find(".modal-title").html(title);
        $("select.select2").select2();
       
        $('.data-show').trigger('change click');
        
        setTimeout(function(){
            console.log("checking if hasAdditionalAddress ");

            if( window.hasAdditionalAddress == true && $("#map").length > 0){
                console.log("hasAdditionalAddress: yes");
                initMap();
            }

            if( rawdata.hasOwnProperty('lot_number') && action == "edit" ){
                $("#inventory_lot_number").trigger('change');
            }

        },1000);
        

    });


    $(document).on("click", ".btn-custom-alert", function (){
        customAlertResponse = $(this).data("value");

    });

    $(document).on("click", ".notify_customer", function () {
        console.log('i am here');
        var mainurl = $(this).data('mainurl');
        var id = $(this).data('orderid');
        console.log(mainurl);
        console.log(id);

        $.ajax({
                url : "/notify_customer",
                type : 'get',
                dataType : 'json',
                data : { id : id }
            }).done(function (data){
                console.log(data);
                alert('Customer Notified');
            });
    });

    /**
     * For alert/confirmation dialogs
     * usage: add class 'action-icon' to any element with a data attributes of
     * 1. data-id  => the id of the specific database entry
     * 2. data-url => the url where the next functions should take place (ex.: http://mydomain.com/products/)
     * 3. data-action => the type of the alert dialog
     * 4. data-urlmain => the url where the next functions should take place (ex.: http://mydomain.com/products/)
     */
     $(document).on("click", ".action-icon", function (){
        var $this = $(this), url = "", alertType = "";
        var id = $this.data("id"), action = $this.data('action'), mainurl = $this.data('urlmain'), 
        dataTitle = $this.data('title');

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
                url : mainurl+'deactivate',
                type : 'post',
                dataType : 'json',
                data : { id : id, _token : _token }
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
            data : { id : id, _token :  _token }
        }).done(function (data){
            console.log(data);
            if( data.status == "success" ){
                $(".modal-alert").modal('hide');
                    window.location = window.location; // reload page
                }
            });
    }
});



$(document).on("click", "#disapprove_seniorId", function(){
   var $this = $(this);
   var redirectUrl = $this.data("redirect"), id = $this.data("id"), status = $this.data("status");
   console.log("redirectUrl: "+redirectUrl+" id: "+id+" status: "+status);
   if( redirectUrl !== "" ){
        $.ajax({
            url : '/members/edit',
            type : 'post',
            dataType : 'json',
            data : { id : id, status :  status, _token : _token}
        }).done(function (data){
            console.log(data);
            location.reload(true);
            $('#modal-view-seniorID').modal('toggle');
        });
    }
});

$(document).on("click", "#approve_Beneficiary_seniorId", function(){
   var $this = $(this);
   var redirectUrl = $this.data("redirect"), id = $this.data("id"), status = $this.data("status");
   console.log("redirectUrl: "+redirectUrl+" id: "+id+" status: "+status);
   if( redirectUrl !== "" ){
        $.ajax({
            url : redirectUrl,
            type : 'post',
            dataType : 'json',
            data : { id : id, status :  status, _token : _token}
        }).done(function (data){
            console.log(data);
            location.reload(true);
            $('#modal-view-seniorID').modal('toggle');
        });
    }
});

$(document).on("click", "#disapprove_Beneficiary_seniorId", function(){
   var $this = $(this);
   var redirectUrl = $this.data("redirect"), id = $this.data("id"), status = $this.data("status");
   console.log("redirectUrl: "+redirectUrl+" id: "+id+" status: "+status);
   if( redirectUrl !== "" ){
        $.ajax({
            url : redirectUrl,
            type : 'post',
            dataType : 'json',
            data : { id : id, status :  status, _token : _token}
        }).done(function (data){
            console.log(data);
            location.reload(true);
            $('#modal-view-seniorID').modal('toggle');
        });
    }
});

$(document).on("click", "#approve_seniorId", function(){
   var $this = $(this);
   var redirectUrl = $this.data("redirect"), id = $this.data("id"), status = $this.data("status");
   console.log("redirectUrl: "+redirectUrl+" id: "+id+" status: "+status);
   if( redirectUrl !== "" ){
        $.ajax({
            url : '/members/edit',
            type : 'post',
            dataType : 'json',
            data : { id : id, status :  status, _token : _token}
        }).done(function (data){
            console.log(data);
            location.reload(true);
            $('#modal-view-seniorID').modal('toggle');
        });
    }
});


$("#inventories_product_id").on("change", function (){
    var $this = $(this);
    console.log("change is triggered "+$this.val());
        
    products_trigger_change($this);

    // var packing = $(this).children('option:selected').attr("data-packing");
    // $(document).find("#outer_packing").html(packing);
    // $(document).find(".add-on-product-packing").html( str_plural(packing) );
    // console.log("packing: "+packing);
});


$("#inventory_quantity").change(function (){
    updateInventoryProductQty();
});

$("#inventory_quantity").keyup(function (){
    updateInventoryProductQty();
});

$("select.sort-by").change(function (){
   $("input[type='search']:visible").val( $(this).val() ).trigger("keyup");
});

$("#date_range").change(function (){
    var dates = $(this).val();
    console.log(dates);
    $("input[name='start_date']").val( $("input[name='daterangepicker_start']").val() );
    $("input[name='end_date']").val( $("input[name='daterangepicker_end']").val() );
});

$(document).on("click", ".show-downlines", function(){
    $(".table-referrals tr").removeClass("selected");
    $(".show-downlines").removeClass("selected");
    $(this).addClass("selected");
    $(this).parent("td").parent("tr").addClass("selected");

    $('html, body').animate({
        scrollTop: $(".referral-chart-row").offset().top
    }, 1500);

    $("#chart").html("");
    $("ul.referral-chart[data-id='"+$(this).data("id")+"']").jOrgChart({
        chartElement : '#chart',
        dragAndDrop  : false
    });
    $(".scroll-to-top").fadeIn();
});



$(".scroll-to-top").click(function (){
    $('html, body').animate({
        scrollTop: $(".content-header").offset().top
    }, 500);
});

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
            $("div.user-panel .info p, li.user.user-menu a.dropdown-toggle span").html(formdata.fname+" "+formdata.lname);
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

$('select#address_city_municipality').change(function (){
    if( $(this).val() != '0' ){
        $.ajax({
            url: '/locations/get/barangays/where-municipalities/'+$(this).val(),
            type: 'get',
            dataType: 'json'
        }).done(function (data){
            console.log(typeof(data));
            if( typeof(data) == 'object' ){
                var barangays = '<option value="0">- Select Barangay -</option>';
                $.each(data, function (i, row){
                    barangays += '<option value="'+row.id+'">'+row.name+'</option>';
                });

                $("#address_barangay").html(barangays).select2();
            }
        });
    }
});

$('#address_brangay, #address_city_municipality, #address_province, #address_region').change(function(){
    if($(this).val() != "" && $("#address_barangay").val() != '0')
        initMap();
    else
        $('#map').text("Please fill up the address first"); 
});

$("#additional_address").on('input', function(){
    if($(this).val() != "" && $("#address_barangay").val() != '0')
        initMap();
    else
        $('#map').text("Please fill up the address first");     
});
 
$(document).on("click",".products-gallery-toggler",function (){
    var productId = $(this).parent("td").parent("tr").data("id");
    var targetModal = $(this).data("target");
    $("#droppable_div").attr("data-id", productId);
    $.ajax({
        url: '/products/gallery/'+productId,
        type: 'get',
        dataType: 'json'
    }).done(function (data){
        console.log(data);
        console.log("length: "+data.length);
        if( data.length > 0 ){
            $(".gallery-empty").html("");
            $("#add_gallery").html("Add new").removeClass("btn-warning").addClass("btn-info");
            $('#product-gallery-carousel').show();
            $(".add-new-gallery-outer").hide();

            $('#product-gallery-carousel .carousel-indicators, #product-gallery-carousel .carousel-inner').html("");
            var isActive = "active";
            $.each(data, function (i, row){
                if( i != 0 ) isActive = '';
                $('#product-gallery-carousel .carousel-indicators').append('<li data-target="#product-gallery-carousel" data-slide-to="'+i+'" class="'+isActive+'"  data-id="'+row.id+'"></li>')
                $('#product-gallery-carousel .carousel-inner').append('<div class="item '+isActive+'">'+
                    '<img src="/images/original/'+row.filename+'" class="product-gallery-item" data-product_id="'+productId+'" data-id="'+row.id+'">'+
                    '</div>');
            });
            $(".carousel-inner").removeClass("disable-contextmenu");
            $('#product-gallery-carousel').carousel();
        }else{
            $('#product-gallery-carousel').carousel('pause').html(getOriginalCarouselItems()).carousel();
            $("#add_gallery").html("Cancel").removeClass("btn-info").addClass("btn-warning");
            $('#product-gallery-carousel').hide();
            $(".add-new-gallery-outer").removeClass("hidden").show();
            $(".gallery-empty").html('<code> No available photo for this product yet. Drop some photos here.</code>');
            $(".carousel-inner").addClass("disable-contextmenu");
        }

        $(targetModal).modal('show');
    });
});

$("#add_gallery").click(function (){
    $("#status1").html("");

    if( $(this).html() == "Add new" ){
        $("#product-gallery-carousel").fadeOut(function (){
            $(".add-new-gallery-outer").fadeIn(function(){
                $(this).removeClass('hidden').show(function(){
                    $("#add_gallery").html("Cancel").removeClass("btn-info").addClass("btn-warning");
                });
            });
        });
    }else{
        $(".add-new-gallery-outer").fadeOut(function (){
            $("#product-gallery-carousel").fadeIn(function(){
                $("#add_gallery").html("Add new").removeClass("btn-warning").addClass("btn-info");
                $("#droppable_div").next(".statusbar").remove();
            });
        });
    }
});

    // let's create our drag and drop file uploader
    var obj = $("#droppable_div");
    obj.on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).css('border', '2px solid #0B85A1');
    });
    obj.on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });

    obj.on('drop', function (e) {

        $(this).css('border', '2px dotted #0B85A1');
        e.preventDefault();
        var files = e.originalEvent.dataTransfer.files;

        //We need to send dropped files to Server
        handleFileUpload(files,obj);
    });

    $(document).on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });

    $(document).on('dragover', function (e) {
      e.stopPropagation();
      e.preventDefault();
      obj.css('border', '2px dotted #0B85A1');
    });

    $(document).on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });


    // Let's enable the right click context menu to delete product photo
    $(document).on("mousedown", ".carousel-inner:not(.disable-contextmenu)", function(e){
        if(e.which == 3 ){  // if mouse's right button is clicked
            $("#product-gallery-carousel").carousel("pause").removeData();

                var activeItem = $(this).children("div.item.active");
                var pid = activeItem.children("img").data("id");
                var activeIndicator = $('#product-gallery-carousel .carousel-indicators li[data-id="'+pid+'"]');
                var product_id = activeItem.children("img").data("product_id");
                console.log("activeIndicator: ");
                console.log(activeIndicator);

                console.log("activeItem: ");
                console.log(activeItem);

                var $this = $(this);

                if( !$this.is(":focus") && !$("div.context-menu").parent("td").parent("tr").parent("tbody")
                    .parent("table").is(":focus") ){
                    $(document).find("div.context-menu").parent("td").parent("tr").parent("tbody").parent("table").remove();
                $(document).find('div.context-menu-shadow').remove();
            }

            var menu = [
            {
                'Make Primary': function(menuItem, menu){
                    $.ajax({
                        url: '/products/gallery/change-primary/'+pid,
                        type: "post",
                        dataType: "json",
                        data: { _token: _token }
                    }).done(function (data){
                        console.log(data);
                        if( data.status_code == "200" ){
                            refreshProductPrimaryPhoto(product_id);
                            $("#modal-products-gallery").modal('hide');
                        }
                    });
                }
            },
            {
                'Delete': function(menuItem, menu) { 
                    $.ajax({
                        url: '/products/gallery/delete/'+pid,
                        type: "post",
                        dataType: "json",
                        data: { _token: _token },
                        beforeSend: function(){
                            activeItem.prepend('<div class="deleting-photo">We are deleting, please wait...</div>');
                        }
                    }).done(function (data){
                        console.log(data);
                        $(".deleting-photo").remove();

                        if( data.status_code == "200" ){
                            $("#product-gallery-carousel").carousel("next");
                            activeItem.removeClass("item").addClass("hidden");
                            activeIndicator.remove();
                            $(".carousel-indicators li").removeClass("active");
                            $(".carousel-indicators li:first").addClass("active");

                            $("#product-gallery-carousel").carousel();
                            refreshProductPrimaryPhoto(product_id);
                        }else{
                            alert(data.msg);
                        }
                    }); 
                } 
            }];

            $('.carousel-inner').contextMenu(menu,{theme:'vista'});
        }
    });

    // let's validate the expiration date set on adding an inventory
    $("input[name='expiration_date']").change(function (){
        var CurrentDate = new Date();
        var ExpirationDate = new Date( $(this).val() );
        if(CurrentDate >= ExpirationDate){
            _error($("input[name='expiration_date']"), "Expiration date is invalid");
        }else{
            _clear_form_errors($("#form_edit_inventory"));
        }
    });

    $("#form_edit_inventory").submit(function (){
        if( $.trim( $(this).find('div.label-danger').html() ) != "" )
            return false;
    });

    $('.btn-adjustment').click(function (){
        $.ajax({
            url : '/inventory/'+$(this).data('id'),
            type: 'get',
            dataType: 'json'
        }).done(function (data){
            if( data.hasOwnProperty('product') ){
                $('#inventory_adjustment_details').html('Lot# '+data.lot_number+
                    '<br/>Product:  <b><a href="/search/products?q='+data.product.name+'" target="_blank">'+data.product.name+'</a></b>'+
                    '<br/>Total sold items: <b title="Total sold products for this inventory">'+data.sold_products_count+'</b>'+
                    '<br/><small>Date added: '+data.date_added+'</small>');
                $('#old_quantity').val(data.quantity);
                $('#modal-add-adjustments').find('#sid').val(data.id);
            }
        });
    });


    // jQuery events used on promos & discounts page
    $("select[name='offer_type']").change(function (){
        var nextDiv = $(this).parent("div.form-group").next("div.form-group");
        if( $(this).val() == "GENERIC_CODE" ){
            nextDiv.fadeIn();
            nextDiv.find('input').attr("required", "required");
        }else{
            nextDiv.fadeOut();
            nextDiv.find('input').removeAttr("required");
        }
    });

    $("select[name='product_applicability']").change(function (){
        var nextDiv = $(this).parent("div.form-group").next("div.form-group");
        if( $(this).val() == "SPECIFIC_PRODUCTS" ){
            nextDiv.fadeIn();
            nextDiv.find('select').attr("required", "required");
        }else{
            nextDiv.fadeOut();
            nextDiv.find('select').removeAttr("required");
        }
    });

    $(document).on("click", '.show-hide-more-products', function (){
        var target = $(this).data("target");
        if( $(target).is(":visible") ){
            $(this).attr("data-original-title", "Expand to show more products")
                .removeClass("bg-maroon").addClass("bg-purple")
                .find('i').removeClass("fa-eye-slash").addClass("fa-eye");
            $(target).fadeOut();
        }else{
            $(this).attr("data-original-title", "Compress to minimize view")
                .addClass("bg-maroon").removeClass("bg-purple")
                .find('i').removeClass("fa-eye").addClass("fa-eye-slash");
            $(target).fadeIn();
        }
    });

    $('.btn-gencode').click(function (){
        var code = generate_random_string(6).toUpperCase();
        $(this).parent('div').prev('input[name="generic_redemption_code"]').val(code);
    });

    $('.btn-gensku').click(function (){
        $this = $(this);
        $.ajax({
            url: '/api/generate/sku',
            type: 'get',
            dataType: 'text'
        }).done(function (data){
            $this.parent('div').prev('input[name="sku"]').val(data.toUpperCase()).trigger('change');
        });
        
    });

    $('input[name="sku"]').bind("change keyup", function (){
        // check if sku exists
        var $this = $(this), 
            sku = $this.val(),
            form = $this.attr("data-form_edit_product");

        var product_id = form.find('input[name="id"]').val();
        if( typeof(product_id) == 'undefined' ){
            product_id = 0;
        }

        if( sku.length < 6 ){
            _error($this, "SKU should be atleast 6 characters long.");
        }else{
            _clear_form_errors($this.parent("div.input-group"));
            $.ajax({
                url: '/api/check/sku/',
                type: 'get',
                dataType: 'text',
                data: {sku: sku, product_id: product_id}
            }).done(function (data){
                if( data == 'true' ){
                    _error($this, "Opps...that SKU already exists.");
                }else{
                    _clear_form_errors($this.parent("div.input-group"));
                }
            }); 
        }
    });

    $('#sku_popover').on('hide.bs.tooltip', function () {
       $('input[name="sku"]').val("");
    });

    $('#promo_details_type').change(function (){
        var productsDiv = $("#promo_details_gifts").parent("div");
        var discountsDiv = $("#promo_details_discount").parent("div").parent("div");
        var detailsDiscount = $("#promo_details_discount");
        var val = $(this).val();

        if( val == 0 ){
            detailsDiscount.prev("span.input-group-addon").fadeOut(function (){
                detailsDiscount.next("span.input-group-addon").fadeIn();
            });
            productsDiv.fadeOut(function(){
                discountsDiv.fadeIn();
            });
            
        }else if( val == 1 ){
            detailsDiscount.next("span.input-group-addon").fadeOut(function (){
                detailsDiscount.prev("span.input-group-addon").fadeIn();
            });
            productsDiv.fadeOut(function (){
                discountsDiv.fadeIn();
            });
            
        }else if( val == 2 ){
            discountsDiv.fadeOut(function (){
                productsDiv.fadeIn();
            });
            
        }else{
            productsDiv.fadeOut();
            discountsDiv.fadeOut();
        }
    });


    $('.data-show').bind('change click', function(){
        dataShowTarget($(this), $(this).val());
    });

    $('.btn-stock-return').click(function (){

        var ordersHtml = "", productsHtml = "", returnCodesHtml = "";
        $.when(
            // ajaxCalls("orders"), ajaxCalls("products"), ajaxCalls("stockReturnCodes") 
            ajaxCalls("orders"), ajaxCalls("stockReturnCodes") 
        ).done(function(orders, returnCodes){
            window.orders = orders[0];
            $.each(orders[0], function (i, row){
                if( row.billing.payment_status == 'paid' ){
                    ordersHtml+= '<option value="'+row.id+'" data-pname="'+row.patient.fname+' '+row.patient.lname+'">#'+row.id+'</option>';
                }else{
                    ordersHtml+= '<option value="'+row.id+'" data-pname="'+row.patient.fname+' '+row.patient.lname+'">#'+row.id+'('+row.billing.payment_status+')</option>';

                }
            });

            // $.each(products[0], function (i, row){
            $.each(window.products, function (i, row){
                productsHtml+= '<option value="'+row.id+'">'+row.name+'</option>';
            });

            $.each(returnCodes[0], function (i, row){
                returnCodesHtml+= '<option value="'+row.id+'">'+row.name+'</option>';
            });

            $("#order_id").html(ordersHtml);
            $("#exchange_product_id").html(productsHtml);
            $("#return_code").html(returnCodesHtml);
            $("#order_id, #exchange_product_id, #return_product_id, #return_code").select2();
            $('.selected-products-qty-div').html('');

            if( orders[0].length > 0 ){
                $("#order_id").trigger("change");
            }
        });
        
    });

    $(document).on('change', '#order_id', function (){
        $(".selected-products-qty-div").html("");
        var val = $(this).val(), productsHtml = "", productNames = "";
        console.log("order_id has changed. value: "+val);
        window.maxReturnQty = [];
        window.maxReturnQtyProductIDs = [];
        $.each(window.orders, function (col, row){
            if( row.id == val ){
                window.selectedOrder = row;

                // make sure that the order is already paid
                // if( row.billing.payment_status == 'paid' ){
                    $.each(row.order_details, function (index, order_detail){
                        var pId = parseInt(order_detail.product.id), return_status = "",
                            pOrderedQty = parseFloat(order_detail.quantity), 
                            pQtyReturned = parseFloat(order_detail.quantity_returned);
                        console.log('order_detail_id: '+order_detail.id);
                        console.log(order_detail);
                        if( pOrderedQty > pQtyReturned ){
                           
                            console.log("nisulod sa if");
                            var old_max_qty = 0;
                            if( $.inArray(pId, maxReturnQtyProductIDs) !== -1 ){
                                console.log("id: "+pId+" -> yes, naa");
                                old_max_qty = window.maxReturnQty[pId].qty;
                                window.maxReturnQty[pId].qty =  old_max_qty + (pOrderedQty - pQtyReturned);
                            }else{
                                productsHtml+= '<option value="'+pId+'" data-id="'+order_detail.id+'">'+order_detail.product.name+'</option>';
                                maxReturnQtyProductIDs.push(pId);
                                console.log("id: "+pId+" -> no, wala");
                                window.maxReturnQty[pId] = {
                                    pId: pId, 
                                    qty: pOrderedQty - pQtyReturned, 
                                    name: order_detail.product.name, 
                                    price: order_detail.price
                                };
                            }

                            // productsHtml+= '<option value="'+pId+'" data-id="'+order_detail.id+'">'+order_detail.product.name+'</option>';
                            // window.maxReturnQty[pId] = {
                            //     id: order_detail.id,
                            //     pId: pId, 
                            //     qty: pOrderedQty - pQtyReturned, 
                            //     name: order_detail.product.name, 
                            //     price: order_detail.price
                            // };
                        }else{
                            console.log("wa nisulod sa if");
                        }

                        if( pOrderedQty == pQtyReturned ){
                            return_status = '<span class="label label-info" style="margin-left: 4px">Returned</span>';
                        }else if( pOrderedQty > pQtyReturned ){
                            var def = order_detail.quantity - order_detail.quantity_returned;
                            return_status = '<span class="label label-warning" style="margin-left: 4px">'+def+' remaining</span>';
                        }

                        productNames+= "<i class='fa fa-caret-right'></i> ("+peso()+order_detail.price+" x "+
                                    pOrderedQty+" "+
                                    str_auto_plural(order_detail.product.packing, pOrderedQty)+
                                    ') <a href="/products?q='+order_detail.product.name+'" target="_blank">'+
                                    order_detail.product.name+"</a>"+return_status+"<br/>";
                    });

                    // let's show the discounts that the user has availed
                    var discounts_html = "";
                    if( row.billing.coupon_discount > 0 ){
                        discounts_html+= peso()+" "+row.billing.coupon_discount+' (Coupon discount) ';
                    }

                    if( row.billing.points_discount > 0 ){
                        discounts_html+= '<br/>'+peso()+" "+row.billing.points_discount+' (Points discount) ';
                    }

                    discounts_html+= '<br/><a href="/orders/'+row.id+'" target="_blank" class="glow">View details</a>';

                    $("#customer_name").html(row.patient.fname+" "+row.patient.lname);
                    $("#total_amount").html(peso()+' '+row.billing.total);
                    $("#gross_total").html(peso()+' '+row.billing.gross_total);
                    $("#all_less").html(discounts_html);
                    $("#refund_amount").html(window.selectedOrder.billing.total);
                    $('#amount_refunded').val(window.selectedOrder.billing.total);
                // }
                
            }
        });
        $("#product_name").html(productNames);
        $("#return_product_id").html(productsHtml).select2();
    });

    $("#return_quantity, #return_product_id").bind("change keyup", function (){
        try{
            if ( $("#return_product_id").val() !== null && typeof $("#return_product_id").val() != 'undefined'){
                var $this = $("#return_quantity"),  selectedProduct = parseInt($("#return_product_id").val()), 
                val = parseInt($this.val()); 

                console.log('fucking selectedProduct: '+selectedProduct);
            
                // var row = window.maxReturnQty[selectedProduct];
                // console.log(row);
                $.each(window.maxReturnQty, function (i, row){
                    if( row.pId == selectedProduct ){
                        var refundableAmount = val * row.price;

                        if( val > parseInt(row.qty) || val == 0 ){
                            $this.val(row.qty).trigger('change');
                        }else{
                            $('#refund_amount').html(refundableAmount);
                            $('#amount_refunded').val(refundableAmount).trigger('change');
                            
                        }
                        
                    }
                });
            }
        }catch(Exception){
            console.log(Exception);
        }


    });

    $("#return_product_id").change(function (){
        $(".selected-products-qty-div").html("");
        // try{
            if( $(this).val() !== null && typeof $(this).val() != 'undefined' ){
              console.log($(this).val());
                var htmls = "";
                $.each($(this).val(), function (i, row){
                    // $.each(window.maxReturnQty,)
                    htmls+= generate_gift_qty_form(row, window.maxReturnQty[row].name, window.maxReturnQty[row].qty, 'products_return_qtys');
                });
                $(".selected-products-qty-div").html(htmls);
                allowNumericOnly( $('.number') );

                // add max qty to the newly appended textviews
                $("#form_return_n_refund").find('input.products_return_qtys').each(function (i, row){
                    $(row).attr("data-max", window.maxReturnQty[$(row).data("id")].qty).attr("data-min", 1);
                    $(row).on("keyup change", function (){
                        calculateStockReturnAmount();
                    });
                });  
            }else{
                $(".selected-products-qty-div").html("");
            }
            

        // }catch(Exception){
            // console.log(Exception);
        // }

    });

    $('#return_product_id').change(function (){
        calculateStockReturnAmount();
    });

    $('#amount_refunded').change(function (){
        if( $(this).val() == "NaN" ){
            $(this).val(0);
        }
    });

    $("input[name='all_product_is_returned']").bind("click change", function (){
        if( $(this).val() == 1 ){
            $("#refund_amount").html(window.selectedOrder.billing.total);
            $('#amount_refunded').val(window.selectedOrder.billing.total);
        }else{
            $("#return_quantity").trigger('change');
        }
    });


    // Adding promo validation & codes here
        $('#promo_details_gifts').change(function (){
            var $this = $(this);
            var htmls = "";
            var dropdown_values = $this.val().split(",");
            
            if( (dropdown_values !== null) && (dropdown_values.length > 0) ){
                $.each(dropdown_values, function (i, row){
                    dropdown_values[i] = parseInt(row);
                });

                $.each(dropdown_values, function (i, row){
                    if( $.inArray( row, window.global_free_gift_product_ids) === -1 ){
                        window.global_free_gift_product_ids.push(row);

                        var check = getArrayIndexForKey(window.global_free_gift_quantities, "id", row);
                        if( check === -1 ){ // make sure the product doesn't exist yet
                            window.global_free_gift_quantities.push({"id" : row, "quantity" : 1});
                        }

                        // htmls+= generate_gift_qty_form(row, $this.children("option[value='"+row+"']").text());
                    }

                });
            }

            // if wala sa select tas naa sa global_free_gift_qty, remove
            $.each(window.global_free_gift_product_ids, function (i, row){
                if( $.inArray(row, dropdown_values) === -1 ){ // means value is found in global_free_gift_qty and not in select
                    console.log("means value is found in global_free_gift_qty and not in select");
                    console.log("global_free_gift_quantities:");
                    console.log(global_free_gift_product_ids);
                    console.log("dropdown_values:");
                    console.log(dropdown_values);
                    var index =  window.global_free_gift_product_ids.indexOf(row);
                    console.log("index: "+index+" row: "+row);
                    if (index > -1) {
                        window.global_free_gift_product_ids.splice(index, 1); // 
                    }
                }
            });


            $.each(window.global_free_gift_quantities, function (i, row){
                if( $.inArray(row.id, global_free_gift_product_ids) !== -1 ){
                    var res = window.select2_all_products.filter(function (n){
                        return n.id == row.id;
                    });
                    if( res.length > 0 ){
                        // htmls+= generate_gift_qty_form(row.id, $this.children("option[value='"+row.id+"']").text(), row.quantity, 'gift_quantities');
                        htmls+= generate_gift_qty_form(row.id, res[0].text, row.quantity, 'gift_quantities');
                    }
                }
            
            });

            $(".selected-products-qty-div").html(htmls);
            allowNumericOnly( $('.number') );
        });

        $('#promo_details_per_transaction_gifts').change(function (){
            var $this = $(this);
            var htmls = "";
            var dropdown_values = $this.val().split(",");
            
            if( (dropdown_values !== null) && (dropdown_values.length > 0) ){
                $.each(dropdown_values, function (i, row){
                    dropdown_values[i] = parseInt(row);
                });

                $.each(dropdown_values, function (i, row){
                    // if( $.inArray( row, window.global_free_gift_product_ids) === -1 ){
                    if( $.inArray( row, window.global_per_transaction_free_gift_product_ids) === -1 ){
                        window.global_per_transaction_free_gift_product_ids.push(row);

                        var check = getArrayIndexForKey(window.global_per_transaction_quantities, "id", row);
                        if( check === -1 ){ // make sure the product doesn't exist yet
                            window.global_per_transaction_quantities.push({"id" : row, "quantity" : 1}); // set default to 1
                        }

                        // htmls+= generate_gift_qty_form(row, $this.children("option[value='"+row+"']").text());
                    }

                });
            }

            // if wala sa select tas naa sa global_free_gift_qty, remove
            $.each(window.global_per_transaction_free_gift_product_ids, function (i, row){
                if( $.inArray(row, dropdown_values) === -1 ){ // means value is found in global_free_gift_qty and not in select
                    console.log("means value is found in global_free_gift_qty and not in select");
                    console.log("global_per_transaction_free_gift_product_ids:");
                    console.log(global_per_transaction_free_gift_product_ids);
                    console.log("dropdown_values:");
                    console.log(dropdown_values);
                    var index =  window.global_per_transaction_free_gift_product_ids.indexOf(row);
                    console.log("index: "+index+" row: "+row);
                    if (index > -1) {
                        window.global_per_transaction_free_gift_product_ids.splice(index, 1); // 
                    }
                }
            });


            $.each(window.global_per_transaction_quantities, function (i, row){
                if( $.inArray(row.id, global_per_transaction_free_gift_product_ids) !== -1 ){
                    var res = window.select2_all_products.filter(function (n){
                        return n.id == row.id;
                    });
                    if( res.length > 0 ){
                        htmls+= generate_gift_qty_form(row.id, res[0].text, row.quantity, 'per_transaction_gift_quantities');
                    }
                }
            
            });

            $(".per-transaction-selected-products-qty-div").html(htmls);
            allowNumericOnly( $('.number') );
        });
        
        // validation on promo details of each product
            $(document).on("submit", "#form_promo_product_info", function (e){
                var $this = $(this);
                var hasError = false;

                var minimum_purchase = $this.find("input[name='minimum_purchase']"),
                    quantity_required = $this.find("input[name='quantity_required']"),
                    has_free_gifts = $this.find("input[name='has_free_gifts']"),
                    promo_details_gifts = $this.find('#promo_details_gifts'),
                    percentage_discount = $this.find('input[name="percentage_discount"]'),
                    peso_discount = $this.find('input[name="peso_discount"]');

                if( minimum_purchase.is(":visible") === true && (minimum_purchase.val() == "" ||  minimum_purchase.val() == "0" ) ){
                    hasError = true;
                    _error(minimum_purchase, "This field is required.");
                    console.log("error 1");
                }

                if( quantity_required.is(":visible") === true && (quantity_required.val() == "" ||  quantity_required.val() == "0" ) ){
                    hasError = true;
                    _error(quantity_required, "This field is required.");
                    console.log("error 2");
                }

                if( quantity_required.is(":visible") === false &&  minimum_purchase.is(":visible") === false ){
                    hasError = true;
                    _error($this.find('label[for="discount_detail_minimum_type"]:last'), "This field is required.");
                    console.log("error 3");
                }

                if(  quantity_required.is(":visible") === true && (has_free_gifts.val() == 1 || has_free_gifts.val() == 0) &&  
                    (promo_details_gifts.val() == "" || promo_details_gifts.val() === null) ){
                    hasError = true;
                    _error(promo_details_gifts, "Please select atleast one product.");
                    console.log("error 4");
                }

                // if(  quantity_required.is(":visible") === true && ( has_free_gifts.val() == 0 || has_free_gifts.val() == 1 ) ){
                //     hasError = true;
                //     _error(has_free_gifts, "This field is required.");
                //     console.log("error 5");
                // }

                // when no offer is selected
                if( quantity_required.is(":visible") === false && (promo_details_gifts.val() == "" || promo_details_gifts.val() === null) &&
                    (peso_discount.val() == "" || peso_discount.val() == 0) && (percentage_discount.val() == "" || percentage_discount.val() == 0)
                ){
                    hasError = true;
                    $("#specific_product_offers").next('div.label-danger').html("Please specify the discount to continue.").fadeIn().delay(5000).fadeOut(400);
                    console.log("error 5");
                }

                $.each($(document).find('#form_promo_product_info input.gift_quantities'), function (i, row){
                    if( $(row).val() == "" || $(row).val() == 0 ){
                        hasError = true;
                        _error($(row), "This field is required.");
                    }
                });

                if( hasError === true ){
                    e.preventDefault();
                    return false;
                }
            });
    
        // validation for Add new Promo
            $(document).on("click", "#btn_create_edit_promo", function (e){
                
                var $this = $("#form_edit_promo");
                var hasError = false;

                var date_range = $this.find("input[name='date_range']"), 
                    long_title = $this.find("input[name='long_title']"),
                    start_date = $this.find("input[name='start_date']"),
                    end_date = $this.find("input[name='end_date']"),
                    offer_type = $this.find('select[name="offer_type"]'),
                    generic_redemption_code = $this.find('input[name="generic_redemption_code"]');

                if( long_title.val() == "" ){
                    hasError = true;
                    _error(long_title, "This field is required.");
                }

                if( date_range.val() == "" ){
                    hasError = true;
                    _error(date_range, "This field is required.");
                }

                if( start_date.val() == "Invalid date" || end_date.val() == "Invalid date" ){
                    hasError = true;
                    _error(date_range, "Invalid date.");
                }

                if( offer_type.val() == "GENERIC_CODE" && generic_redemption_code.val() == ""){
                    hasError = true;
                    _error(generic_redemption_code, "This field is required.");
                }



                var product_applicability = "";
                console.log("product_applicability: " +product_applicability);
                if( $("#per_transaction_outer_div").is(":visible") === true ){
                    product_applicability = 'PER_TRANSACTION';
                }else if( $("#specific_products_outer_div").is(":visible") === true ){
                    product_applicability = 'SPECIFIC_PRODUCTS';
                }

                
                
                if( product_applicability === "PER_TRANSACTION" ){
                    // if Per Transaction
                    var minimum_purchase_amount = $this.find("input[name='minimum_purchase_amount']"),
                        is_free_delivery = $this.find("input[name='is_free_delivery']:checked"),
                        peso_discount = $this.find('input[name="peso_discount"]'),
                        percentage_discount = $this.find('input[name="percentage_discount"]'),
                        promo_details_per_transaction_gifts = $this.find("#promo_details_per_transaction_gifts"),
                        per_transaction_has_free_gifts = $this.find('input[name="per_transaction_has_free_gifts"]:checked');

                        console.log("minimum_purchase_amount: "+minimum_purchase_amount.val());
                        console.log("is_free_delivery: "+is_free_delivery.val());
                        console.log("peso_discount: "+peso_discount.val());
                        console.log("percentage_discount: "+percentage_discount.val());
                        console.log("promo_details_per_transaction_gifts: "+promo_details_per_transaction_gifts.val());
                        console.log("per_transaction_has_free_gifts: "+per_transaction_has_free_gifts.val());

                    if( minimum_purchase_amount.val() == 0 || minimum_purchase_amount.val() == "" ){
                        hasError = true;
                        _error(minimum_purchase_amount, "Please provide a minimum purchase amount.");
                    }

                    if( promo_details_per_transaction_gifts.is(":visible") === true && (promo_details_per_transaction_gifts.val() === null 
                            || promo_details_per_transaction_gifts == "") ){
                        hasError = true;
                        _error(promo_details_per_transaction_gifts, "Please select atleast one product.");
                    }

                    if( per_transaction_has_free_gifts.val() != 1 && is_free_delivery.val() != 1 && (
                        peso_discount.val() == "" || percentage_discount == "" 
                        )){
                            hasError = true;
                        $("#per_transaction_promo_offers").next('div.label-danger').html("Please add atleast one offer (Free Gifts, Free Delivery, Discount) to continue.").fadeIn().delay(5000).fadeOut(400);
                    }


                }else if(product_applicability === "SPECIFIC_PRODUCTS"){
                    // If Specific Products
                    var specific_promo_product_ids = $this.find("#specific_promo_product_ids");
                    console.log("specific_promo_product_ids: ");
                    console.log(specific_promo_product_ids);

                    if( specific_promo_product_ids.val() === null || specific_promo_product_ids.val() == "" ){
                        hasError = true;
                        _error(specific_promo_product_ids, "Please select atleast one product which will be included on this promo.");
                    }
                }else{
                    hasError = true;
                    _error($this.find("label#label_for_product_applicability"), "This field is required.");
                }

                if( hasError === true ){
                    $(this).attr("type", "button");
                    e.preventDefault();
                    return false;
                }else{
                    console.log("submitting form now...");
                    $(this).attr("type", "submit");
                    $("#form_edit_promo").submit();
                }
            });

    getSessionBranch();

    // get lot numbers
    if( $("#inventory_lot_number").length > 0 ){
        getLotNumbers();
    }


    $("#inventory_lot_number").bind("change keyup",function(){
        checkLotNumber( $(this) );
    });

    $(".classify-returned-products").click(function (){
        var $this = $(this), id = $this.attr("data-id");

        $.ajax({
            url: '/get-stock-returns/'+id,
            type: 'post',
            data: {_token: $("input[name='_token']").val() }
        }).done(function (data){
            if( !data.error ){
                var html = "";

                if( data.length > 0 ){
                    $.each(data, function (i, row){
                        var option = "", max_removable = 0;
                        max_removable = row.quantity - row.defective_quantity;
                        var status = "";
                        if( row.quantity > row.defective_quantity ){
                            option = '<input type="text" class="number returned-item-qty" value="'+max_removable+'" data-max="'+max_removable+'" data-min="1" placeholder="Defective quantity" name="damaged_qty">'+
                                    '<a href="javascript:void(0);" class="btn-xxs btn-danger btn btn-flat btn-defective-qty" data-id="'+row.id+'">Mark as defective</a> ';
                        }
                            
                        /*if( row.defective_quantity > 0 ){
                            status += '<span class="">('+row.quantity+' '+str_auto_plural(row.product.packing, row.quantity)+' returned, '+
                                row.defective_quantity+' '+str_auto_plural(row.product.packing, row.defective_quantity)+
                                ' marked as defective)</span> ';
                        }*/
                        

                        var total_replacement = row.total_replacement;
                        // $.each(row.replacement, function (c, r){
                        //     total_replacement+= r.quantity;
                        // });

                        // var max_replaceable = row.quantity - total_replacement;
                        var max_replaceable = row.max_replaceable;

                        if( row.quantity > total_replacement || total_replacement == 0 ){
                            option+= '<br/><input type="text" class="number returned-item-qty" readonly value="'+max_replaceable+'" data-max="'+max_replaceable+'" data-min="1" placeholder="Replaced quantity" name="replaced_qty">'+
                                    '<a href="javascript:void(0);" data-max-replaceable="'+max_replaceable+'" class="btn-xxs btn-primary btn btn-flat btn-replace-qty" data-product-id="'+row.product.id+'" data-id="'+row.id+'">Replace</a><br/>';

                        }

                        /*if( total_replacement > 0 ){
                            status += ' <br/><span class="">('+row.quantity+' '+str_auto_plural(row.product.packing, row.quantity)+
                                ' returned, '+
                                total_replacement+' '+str_auto_plural(row.product.packing, total_replacement)+
                                ' replaced)</span>';
                        }*/
                        if( row.defective_quantity > 0 || total_replacement > 0 ){
                            status = ' <span class="msg-status">'+row.msg_status+'</span>';
                        }else{
                            status = ' <span class="msg-status"></span>';
                        }

                        html+= '<li>'+
                                '<div class="row" style="margin-right: 0px;">'+
                                    '<div class="col-md-6">'+
                                        '<span class="handle">'+
                                            '<i class="fa fa-arrows"></i>'+
                                        '</span>'+
                                        '<span class="product-list-items" title="'+row.product.name+'">'+row.product.name+'</span>'+
                                    '</div>'+
                                    '<div class="col-md-6">'+option+'<br/>'+status+'</div>'+
                                '</div>'+
                            '</li>';
                    });
                }else{
                    html = "Sorry, we can't find anything.";
                }
                if( data.error ){
                    html = data.error;
                }
                
                $("#returned_stocks_lists").html(html);
                $("#modal-stock-return-details").modal("show");
                allowNumericOnly($(".number"));
            }

        });

    });

    $(document).on('click', '.refresh-inventory-logs', function (){
        var $this = $(this);
        $this.children('i').addClass("fa-spin");

        $.ajax({
            url: '/inventory/logs',
            type: 'post',
            data: { _token: $('input[name="_token"]').val() }
        }).done(function (data){
            $this.parent("div").parent("div.box-body").html(data);
            $("#tbl_inventory_logs").DataTable({
                "responsive": true,
                "aaSorting": [[ 0, "desc" ]]
            });
            $('[data-toggle="tooltip"]').tooltip();

            $this.children('i').removeClass("fa-spin");
        });
    });

    $(document).on('click', '.refresh-inventory-items', function (){
        var $this = $(this);
        $this.children('i').addClass("fa-spin");

        var prevHtmls = '';
        

        $.ajax({
            url: '/inventory/items',
            type: 'post',
            data: { _token: $('input[name="_token"]').val() }
        }).done(function (data){
            $this.children('i').removeClass("fa-spin");

            prevHtmls+= '<small>'+$this.parent("div").prev('small').html()+'</small>';
            prevHtmls+= '<div style="margin-bottom: 20px;">'+$this.parent('div').html()+'</div>';

            $this.parent("div").parent("div.box-body").html(prevHtmls+data);
            $("#tbl_inventory_items").DataTable({
                "responsive": true,
                "aaSorting": [[ 0, "desc" ]]
            });
            $('[data-toggle="tooltip"]').tooltip();

            
        });
    });

    $(document).on("click", ".btn-replace-qty", function (){
        var $this = $(this),
            product_id = $this.attr("data-product-id"),
            product_stock_return_id = $this.attr("data-id"),
            replaceable_qty = $this.attr("data-max-replaceable"),
            product_name = "";

        // get all lot numbers associated with the productID
        $.ajax({
            url: '/get-product-lotnumbers',
            type: 'post',
            data: { _token: $('input[name="_token"]').val(), product_id: product_id },
            dataType: 'json'
        }).done(function (data){
            console.log(data);
            if( !data.error && data.data.length < 1 ){
                showAlert("Heads up!", data.msg, "danger", "notify")
            }else if( !data.error && data.data.length > 0 ){
                var product_name = data.data[0].product_name;
                var parentRow = $this.parent("div").parent("div.row");

                // $this.fadeOut();
                $this.addClass("disabled");

                if( parentRow.next(".replacement-form").length < 1 ){
                    // create a new element, we use $(document.createElement('element')) for fastest performance
                    // refer here http://jsperf.com/jquery-vs-createelement

                    var options = [];
                    var lotnumbers = [];

                    $.each(data.data, function (i, row){
                        options.push( 
                            $(document.createElement("option")).val(row.id)
                                .text(row.lot_number+"("+row.available_quantity+" available)")
                                .attr("data-available-quantity", row.available_quantity) 
                        );

                        lotnumbers[row.id] = row.available_quantity;
                    });

                    var select = $(document.createElement('select')).attr("multiple", "multiple")
                                    .attr("id", 's_'+product_stock_return_id+"_p_"+product_id)
                                    .addClass("stock-return-replacement").attr("data-id", product_stock_return_id)
                                    .append(options);               
                    var newRow = $(document.createElement('span')).addClass("replacement-form").addClass("row");
                    
                    var column1 = $(document.createElement('span')).addClass("col-md-10 replacement-lotnumber-div"),
                        column2 = $(document.createElement('span')).addClass("col-md-2 replacement-submit-div"),
                        label = $(document.createElement("label")).text("Select Lot number for the replacement"),
                        small = $(document.createElement("small")).text("Note: These lot numbers are exclusive for "+product_name+" only. If selected lot numbers are more than 1, the system will prioritize the lot number with lesser available quantity."),
                        btn_submit = $(document.createElement("button")).text("Submit").attr("data-target", '#s_'+product_stock_return_id+"_p_"+product_id)
                                .addClass("btn btn-xxs btn-flat btn-success submit-replacement").attr("data-id", product_stock_return_id),
                        btn_cancel =  $(document.createElement("button")).text("Cancel ")
                                .addClass("btn btn-xxs btn-flat btn-warning submit-replacement").attr("data-id", product_stock_return_id),
                        hr = $(document.createElement("hr")),
                        br = $(document.createElement("br"));

                        btn_cancel.click(function (){
                            parentRow.next(".replacement-form").fadeOut(function(){
                                // $this.fadeIn();
                                $this.removeClass("disabled");
                            });
                        });

                        btn_submit.click(function (){
                            var lot_num = select.val();
                            if( lot_num != "" && lot_num != null ){
                                $.ajax({
                                    url: '/replace-returned-product',
                                    type: 'post',
                                    dataType: 'json',
                                    data: { 
                                        'inventory_ids': lot_num, 
                                        'product_stock_return_id': product_stock_return_id, 
                                        _token: $('input[name="_token"]').val() 
                                    }
                                }).done(function (data){
                                    console.log(data);
                                    if( !data.error && data.status == 200 ){
                                        parentRow.next(".replacement-form").fadeOut().html("");
                                        
                                        var html_output = '<span class="msg-status">'+data.msg_status+'</span>';

                                        if( data.max_replaceable == 0 ){
                                            /*if( $this.prev("input").next('span').length ){
                                                $this.prev("input").next('span').replaceWith(html_output);
                                                $this.fadeOut();
                                            }else{
                                                $this.fadeOut().prev("input").fadeOut().replaceWith(html_output);
                                            }*/
                                            if( parentRow.find('span.msg-status').length ){
                                                parentRow.find('span.msg-status').html(data.msg_status);
                                                $this.fadeOut();
                                            }else{
                                                $this.prev("input").after(html_output);
                                                $this.prev('input').fadeOut();
                                                $this.fadeOut();
                                            }
                                            
                                        }else{

                                            /*if( $this.prev("input").next('span').length ){
                                                $this.prev("input").next('span').replaceWith(html_output);
                                            }else{
                                                $this.prev("input").after(html_output)
                                            }*/
                                            if( parentRow.find('span.msg-status').length ){
                                                parentRow.find('span.msg-status').html(data.msg_status);
                                            }else{
                                                $this.prev("input").after(html_output);
                                            }
                                            $this.removeClass('disabled').removeAttr("disabled");
                                        }

                                        

                                        $this.prev("input").attr("data-max", data.max_replaceable).val(data.max_replaceable);

                                    }else{
                                        showAlert("Opps! Something's not good.", "Sorry, something went wrong. Please refresh the page and try again. If problem persists, please contact your programmer.", "danger", "notify");
                                    }
                                });
                            }
                        });

                    column1.append(label,br,small,select);
                    column2.append(btn_submit, btn_cancel)
                    newRow.append(column1, column2);
                    parentRow.after(newRow,hr);
                    select.select2();


                    select.on("change", function (){
                        // console.log($(this).val());
                        if( $(this).val() != null ){
                            var total_max = 0,
                                selectVals = [];
                            if( is_array( $(this).val() ) ){
                                selectVals = $(this).val();
                            }else{
                                selectVals.push( $(this).val() );
                            }

                            $.each( selectVals, function (i, row){
                                console.log(row);
                                // var max = select.children("option[value='"+row+"']").attr("data-max-replaceable");
                                var max = lotnumbers[row]; // available quantity of this lot number
                                
                                console.log("max: "+max+"\nreplaceable_qty: "+replaceable_qty);

                                if( replaceable_qty <=  max){
                                    // prevent selecting another option
                                    var attr = select.attr('multiple');

                                    if (typeof attr !== typeof undefined && attr !== false) {
                                       
                                        select.select2('destroy');   
                                        select.children('option').removeAttr("selected");
                                        select.children('option[value="'+row+'"]').attr("selected", "selected");                                   
                                        select.removeAttr("multiple").select2();
                                    }
                                    console.log("yes");
                                    
                                }else{
                                    console.log("no");

                                    if( total_max > replaceable_qty ){
                                        // return false;
                                        console.log("opps! too much!");
                                        select.select2('destroy').children('option[value="'+row+'"]').removeAttr("selected");   
                                        select.select2();
                                    }else{
                                        total_max+= max;

                                        console.log("addmore..\ntotal_max: "+total_max);

                                        var attr = select.attr('multiple');
                                       

                                        if (typeof attr !== typeof undefined && attr !== false) {
                                            // do nothing
                                        }else{                                        
                                            select.select2('destroy');
                                            select.attr("multiple", "multiple").select2();
                                        }
                                    }

                                    
                                }
                            });

                            if( total_max >= replaceable_qty ){
                                console.log("yeah, weve reach the max");
                                select.select2("destroy");
                                $.each(select.children('option'), function (i, row){
                                    var rowVal = $(row).val();
                                    if( !$.inArray( selectVals, rowVal ) ){
                                        select.children('option[value="'+rowVal+'"]').attr("disabled", "disabled");
                                    }
                                });

                                select.select2();
                            }else{
                                console.log("go on, we need more");
                                select.select2("destroy");
                                select.children('option').removeAttr("disabled");
                                select.select2();
                            }
                        }
                    });
                }else{
                    parentRow.next(".replacement-form").fadeIn();
                }
            }
        });
    });

    $(document).on("click", ".btn-defective-qty", function (){
        var $this = $(this), txtbox = $this.prev("input"), qty = txtbox.val();
        $.ajax({
            url: "/update-defective-stocks",
            type: "post",
            dataType: "json",
            data: {
                _token: $("input[name='_token']").val(),
                id: $this.attr("data-id"),
                defective_quantity: qty
            },
            beforeSend: function (){
                $this.addClass("disabled").html("Loading..");
            }
        }).done(function (data){
            $this.removeClass("disabled").html("Mark as defective");
            var alertMsg = '';
            if( data.status == 200 ){
                var option = "", row = data.data;

                alertMsg = '<span class="label label-success">Successfully removed '+qty+' '+
                        str_auto_plural(row.product.packing, qty)+' of '+row.product.name+' from the inventory</span><br/>';
                   
                $("#returned_stocks_lists_request_status").append(alertMsg);
                $("#returned_stocks_lists_request_status span.label").fadeIn().delay(4000).fadeOut(function(){
                    $(this).prev('br').remove();
                    $(this).remove();
                });

                /*if( row.defective_quantity == row.quantity ){
                    txtbox.fadeOut();
                    $this.fadeOut(function (){
                        option = '<span class="">('+row.quantity+' '+str_auto_plural(row.product.packing, row.quantity)+' returned, '+
                                row.defective_quantity+' '+str_auto_plural(row.product.packing, row.defective_quantity)+
                                ' marked as defective)</span>';
                        if( txtbox.next('span').length > 0 ){
                            txtbox.next('span').remove().after(option);
                        }else{
                            txtbox.after(option);
                        }
                    });
                }else{
                    var max_removable = row.quantity - row.defective_quantity;
                    txtbox.attr("data-max", max_removable).val(max_removable);
                    option = '<span class="">('+row.quantity+' '+str_auto_plural(row.product.packing, row.quantity)+' returned, '+
                                qty+' '+str_auto_plural(row.product.packing, qty)+
                                ' marked as defective)</span>';
                }*/
                var parentRow = $this.parent("div").parent("div.row");

                parentRow.next(".replacement-form").fadeOut().html("");
                    
                var html_output = '<span class="msg-status">'+row.msg_status+'</span>';

                if( row.defective_quantity == row.quantity ){
                    if( parentRow.find('span.msg-status').length ){
                        parentRow.find('span.msg-status').html(row.msg_status);
                        $this.prev("input").fadeOut();
                        $this.fadeOut();
                    }else{
                        $this.fadeOut().prev("input").fadeOut().after(html_output);
                    }
                }else{
                    // var max_removable = row.quantity - row.defective_quantity;
                    txtbox.attr("data-max", row.max_removable).val(row.max_removable);

                    if( parentRow.find('span.msg-status').length ){
                        parentRow.find('span.msg-status').html(row.msg_status);
                    }else{
                        $this.prev("input").after(html_output);
                    }

                    $this.removeClass('disabled').removeAttr("disabled");
                }

                txtbox.attr("data-max", row.max_removable).val(row.max_removable);
                
            }else if(data.error){
                
                showAlert("Opps! Something's not good.", data.error, "danger", "notify");

            }
        });
    });
    

    // For Doctor - Clinic Tagging
    $(".edit-clinic-doctors").click(function (){
        var $this = $(this);

        $("#btn_doctor_clinic_save_changes").attr("data-c-id", $this.attr("data-id"));

        $.ajax({
            url: '/clinic-doctor',
            type: 'post',
            dataType: 'json',
            data: {
                action: 'get_doctors',
                doctor_ids: $('#doctor_ids').val(),
                clinic_id: $this.attr("data-id"),
                _token: $('input[name="_token"]').val()
            }
        }).done(function (data){
            console.log(data);
            if( data.length >= 0 ){
                var selectedDoctors = [];
                $.each(data, function (i, row){
                    selectedDoctors.push(row.id);
                });

                $("#doctor_ids").val(selectedDoctors.join(","));

                $("#modal_tag_clinic_doctor").modal('show');
                init_select2_items($("#doctor_ids"), window.select2_all_doctors);
            }
        });
    });

    $("#btn_doctor_clinic_save_changes").click(function (){
        $this = $(this);
        clinicId = $this.attr("data-c-id");
        $.ajax({
            url: "/clinic-doctor",
            type: 'post',
            dataType: 'json',
            data: {
                action: 'apply_changes',
                clinic_id: clinicId,
                doctor_ids: $("#doctor_ids").val(),
                _token: $('input[name="_token"]').val()
            },
            beforeSend: function (){
                $this.addClass("disabled").html('<i class="fa fa-refresh fa-spin"></i> Please wait...');
            }
        }).done(function (data){
            console.log(data);
            if( data.status != 'failed' ){
                /*
                <span class="badge bg-yellow" data-toggle="tooltip" data-original-title="{{ count($clinic->doctors) }} total doctors">{{ count($clinic->doctors) }}</span>
                @foreach ( limit($clinic->doctors, 3) as $doctor)
                    <span class="badge bg-aqua">{{ get_person_fullname($doctor) }}</span>
                @endforeach
                */
                span1 = '<span class="badge bg-yellow" data-toggle="tooltip" data-original-title="'+data.count+' total doctors">'+data.count+'</span> ';
                span2 = '';
                for (var i = 0; i < data.doctors.length; i++) {
                    if( i < 3 ){
                        span2+= '<span class="badge bg-aqua">'+data.doctors[i].lname+', '+data.doctors[i].fname+'</span>';
                    }
                }

                $('.clinic-doctors-div[data-id="'+clinicId+'"]').html(span1+span2);
            }

            $this.removeClass("disabled").html('Apply changes');
        });
    });
});