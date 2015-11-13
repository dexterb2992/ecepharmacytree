$(document).ready(function (){
    var old_photo_src = $('#user_photo').attr('src');
    getActiveSidebarMenu();


    /* Morris.js Charts */
    // Sales chart
    try{
        var area = new Morris.Area({
            element: 'revenue-chart',
            resize: true,
            data: [
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
            ],
            xkey: 'y',
            ykeys: ['item1', 'item2'],
            labels: ['Item 1', 'Item 2'],
            lineColors: ['#a0d0e0', '#3c8dbc'],
            hideHover: 'auto'
        });
    }catch(Exception){
        console.log(Exception);   
    }

    try{
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
    }catch(Exception){
        console.log(Exception);   
    }

    //Fix for charts under tabs
    $('.box ul.nav a').on('shown.bs.tab', function (e) {
        area.redraw();
        donut.redraw();
    });

	$('.datatable:not(.table-referrals)').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });

    $('.table-referrals').DataTable({
        "iDisplayLength": -1,
        "aaSorting": [[ 2, "desc" ]]
    });

    $('.btn').addClass("btn-flat");

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

    var search_keyword = $.getUrlParam('q');
    if( search_keyword != "" ){
        $('.dataTables_filter').find('input[type="search"]:visible').val(search_keyword).trigger('keyup');
    }

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
        _clear_form_data(form);
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

                $.each(data, function (i, row){
                    // search for select dropdown with array names ex. names[]
                    form.find("input[name='"+i+"[]']").val(row); 
                    var does_it_exists = form.find("select[name='"+i+"[]']");

                    if( does_it_exists.length > 0 ){
                        $.each(row, function (a, b){
                            var lets_check = form.find("select[name='"+i+"[]'] option[value='"+b.id+"']");
                            if( lets_check.length > 0 ){
                                $(lets_check).attr("selected", "selected");
                            }
                        });

                        if( does_it_exists.hasClass('select2') ){
                            does_it_exists.select2();
                        }
                    }
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
            
            _clear_form_data(form);
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

    $('#additional_address').change(function(){
        if($(this).val() != "" && $("#address_barangay").val() != '0')
            initMap();
        else
            $('#map').text("Please fill up the address first");
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
                            data: { _token: $('input[name="_token"]').val() }
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
                            data: { _token: $('input[name="_token"]').val() },
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
            $('#old_quantity').val(data.quantity);
            $('#modal-add-adjustments').find('#sid').val(data.id);
        });
    });

});