$(document).ready(function (){

	$('.datatable').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });

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

                form.find("select[name='address_region']").children('option:selected').removeAttr("selected");
                form.find("select[name='address_region']").children('option[value="'+data.address_region+'"]').attr("selected", "selected");

                $.each(data, function (i, row){
                    form.find("input[name='"+i+"']").val(row);
                });
                $(modal).modal('show');
            });
        }else{
            title = "Add new "+dataTitle;
            $(modal).modal('show');
        }

        form.attr("data-mode", action);

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
                    $(".modal-alert").modal('hide');
                    window.location = window.location;
                }
            });
        }

        $(document).find(".btn-custom-alert[data-value='true']").attr("data-redirect", url).attr("data-id", id);
    });

    $(document).on("click", ".btn-custom-alert[data-value='true']", function (){
        var $this = $(this);
        var redirectUrl = $this.data("redirect"), id = $this.data("id");
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

    /*$("#form_edit_product_category").submit(function (){
        var mode = $(this).data("mode");
        if( mode == "create" ){
            $(this).attr("action", "/products/categories/create");
        }else if( mode == "edit" ){
            $(this).attr("action", "/branches/edit");
        }
    });*/
		
});
