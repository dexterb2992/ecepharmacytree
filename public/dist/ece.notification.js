jQuery(function($){
	function get_new_notifications(){
		$.ajax({
			url: '/notify',
			type: 'post',
			dataType: 'json',
			data: {_token: $('input[name="_token"]').val()}
		}).done(function(data){
			console.log(data);
			$.each(data, function(i, row){
				if( row > 0 ){

					if( $('#'+i).next('small').length < 1 ){
						$("#"+i).parent('a').attr("href", $("#"+i).parent('a').attr("href")+"?sc=noti&s="+i );
						$('#'+i).after('<small class="label pull-right bg-green">'+row+'</small>');
					}else{
						$('#'+i).next('small').html(row);
					}
				}else{
					$('#'+i).next('small').remove();
				}
			});
		});
	}

	get_new_notifications();

	if( $.getUrlParam('sc') == 'noti' ){
		$.ajax({
			url: '/read-notification',
			type: 'post',
			dataType: 'json',
			data: {
					_token: $('input[name="_token"]').val(),
					source: $.getUrlParam('s')
				}
		}).done(function (data){
			if( data.status == 500 ){
				console.log("Something, went wrong. Cannot read notifications right now.");
			}
		});
	}


	window.setInterval(function(){
		get_new_notifications();
	}, 5000);
});