$(document).ready(function (){
	$('.datatable').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });

    $(document).on("click", ".edit-branch", function (){
    	var $this = $(this);
    	var inputAddress = $this.next("span");
    	var address = inputAddress.text();

    	inputAddress.attr("data-oldvalue", address);

    	$this.fadeOut(function (){
    		inputAddress.html('<div class="input-group" data-id="'+$this.data("id")+'">'+
	            '<input type="text" class="form-control" value="'+address+'">'+
	            '<span class="input-group-btn"  title="Save">'+
	              '<button class="btn btn-info btn-flat save-edit-branch" data-id="'+$this.data("id")+'" type="button"><i class="fa fa-check"></i></button>'+
	            '</span>'+
	            '<span class="input-group-btn" title="Cancel">'+
	              '<button class="btn btn-info btn-flat cancel-edit-branch" data-id="'+$this.data("id")+'" type="button"><i class="fa fa-close"></i></button>'+
	            '</span>'+
	        '</div>');
    	});
    });

    $(document).on("click", ".save-edit-branch", function (){
    	var $this = $(this);
    	var id = $this.data("id"), address = $this.parent("span").prev("input").val();
    	var token = $("input[name='_token']").val();
    	$.ajax({
    		url : 'branches/edit',
    		type : 'post',
    		data : { id : id, address : address, _token : token }
    	}).done(function (data){
    		if( data.status == "success" ){
    			$(".edit-branch[data-id='"+id+"']").next("span").html( address );
		    	$(".edit-branch[data-id='"+id+"']").fadeIn();
    		}else{
    			$("div.input-group[data-id='"+id+"']").addClass("hasError").append('<label class="control-label" for="inputError"><i class="fa fa-close"></i> '+data.error+'</label>');
    		}
    	});
    	
    });

    $(document).on("click", ".cancel-edit-branch", function (){
    	var $this = $(this);
    	var id = $this.data("id"), span = $(".edit-branch[data-id='"+id+"']").next("span");
    	span.html( span.data("oldvalue") );
    	$(".edit-branch[data-id='"+id+"']").fadeIn();
    });
		
});
