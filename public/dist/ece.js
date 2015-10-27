$(document).ready(function (){
    var old_photo_src = $('#user_photo').attr('src');
    getActiveSidebarMenu();


    /* Morris.js Charts */
    var line = new Morris.Line({
        element: 'line-chart',
        resize: true,
        data: [
            {y: '2011 Q1', item1: 2666},
            {y: '2011 Q2', item1: 2778},
            {y: '2011 Q3', item1: 4912},
            {y: '2011 Q4', item1: 3767},
            {y: '2012 Q1', item1: 6810},
            {y: '2012 Q2', item1: 5670},
            {y: '2012 Q3', item1: 4820},
            {y: '2012 Q4', item1: 15073},
            {y: '2013 Q1', item1: 10687},
            {y: '2013 Q2', item1: 8432}
        ],
        xkey: 'y',
        ykeys: ['item1'],
        labels: ['Item 1'],
        lineColors: ['#efefef'],
        lineWidth: 2,
        hideHover: 'auto',
        gridTextColor: "#fff",
        gridStrokeWidth: 0.4,
        pointSize: 4,
        pointStrokeColors: ["#efefef"],
        gridLineColor: "#efefef",
        gridTextFamily: "Open Sans",
        gridTextSize: 10
    });

    //Donut Chart
    var donut = new Morris.Donut({
        element: 'sales-chart',
        resize: true,
        colors: ["#3c8dbc", "#f56954", "#00a65a"],
        data: [
            {label: "Download Sales", value: 12},
            {label: "In-Store Sales", value: 30},
            {label: "Mail-Order Sales", value: 20}
        ],
        hideHover: 'auto'
    });

    //Fix for charts under tabs
    $('.box ul.nav a').on('shown.bs.tab', function (e) {
        area.redraw();
        donut.redraw();
    });

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

    // make sure to add 'fade' class only once
    $('div.modal').removeClass('fade').addClass('fade');

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

        $(".products-gallery-toggler").click(function (){
            var productId = $(this).parent("td").parent("tr").data("id");
            var targetModal = $(this).data("target");
            $("#droppable_div").attr("data-id", productId);
            $.ajax({
                url: '/products/gallery/'+productId,
                type: 'get',
                dataType: 'json'
            }).done(function (data){
                if( data.length > 0 ){
                    $('#product-gallery-carousel').show();
                    $(".add-new-gallery-outer").hide();

                    $('#product-gallery-carousel .carousel-indicators, #product-gallery-carousel .carousel-inner').html("");
                    var isActive = "active";
                    $.each(data, function (i, row){
                        if( i != 0 ) isActive = '';
                        $('#product-gallery-carousel .carousel-indicators').append('<li data-target="#product-gallery-carousel" data-slide-to="'+i+'" class="'+isActive+'"></li>')
                        $('#product-gallery-carousel .carousel-inner').append('<div class="item '+isActive+'">'+
                            '<img src="/images/original/'+row.filename+'" class="product-gallery-item" data-id="'+row.id+'">'+
                        '</div>');
                    });
                    $('#product-gallery-carousel').carousel();
                }else{
                    $('#product-gallery-carousel').hide();
                    $(".add-new-gallery-outer").show();
                }

                $(targetModal).modal('show');
            });
        });

        $("#add_gallery").click(function (){
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



        // $.ajax({
        //     url: '/products/gallery/delete/'+$(this).children('.product-gallery-item').data("id"),
        //     type: "post",
        //     dataType: "json",
        //     data: { _token: $('input[name="_token"]').val() }
        // }).done(function (data){
        //     if( data.status == "success" ){
        //         $this.fadeOut(function (){
        //             $this.remove();
        //             $("body").trigger("click");
        //         });
        //     }else{
        //         alert(data.msg);
        //     }
        // }); 


        
});