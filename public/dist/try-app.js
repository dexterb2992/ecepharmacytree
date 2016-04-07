(function($) {
    jQuery(document).ready(function(){

        var gblFileName = "", timezone = "";
        var fbUserId = "", fbAuthResponse = false, fbAccessToken = "";
        var fbScope = 'email,user_about_me,publish_actions,user_posts,user_status,manage_pages,publish_pages,user_photos,user_managed_groups';
        var albumId = 0, imgUploadedUrl = "", imgUploadedLink = "", imgUploadedId = 0, whereToPost = "";
        var albums = "", pages = "", groups = "", albums_array = [], pages_array = [], groups_array = [];
        window.fbScope = fbScope;
        
        var is_instantiated = 0;                
        var scale = 10, font_size = 50;
        var top_y = 0, bottom_y = 0;

        window.hasImageEffects = false;

        var current_font_size = 48;

        window.useRasterizeHtmlRenderer = true; 
        window.pages_access_tokens = [];

        // note: error_fields structure is 'field' => html element(#el or .el tag), 'error' => the error message
        var error_fields = [];

    /* FB init */
        try{
            if( fbAppId !== "" ){
                FB.init({ appId: fbAppId, status: true, cookie: true, xfbml: true, oauth: true, channelUrl: channelUrl });

                fbApiInit = true;
                checkUserFbStatus();
            }
        }catch(Exception){
            console.log(Exception);
        }  

        instantiateCanvas();

    /* START JQUERY FUNCTIONS */
        function getUrlParameter(sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        }

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var imgSize;
                    var w, h;

                    if( $("#image_size").is(":visible") ){
                        imgSize = $("#image_size").val();
                        imgSize = imgSize.split("x");
                        w = imgSize[0]+"px";
                        h = imgSize[1]+"px";
                    }else{
                        w = 500, h = 500;
                    }

                    if( $("canvas#image_preview").is(":visible") ){
                        $("canvas#image_preview").remove();
                        $(".image-holder").append('<img src="'+e.target.result+'" id="image_preview" />');

                    }

                    $('.image-holder').width(w).height(h);
                    $('.image-holder img').attr('src', e.target.result);
                    fitImage('#image_preview');
                };

                reader.readAsDataURL(input.files[0]);
                $("#image_name").text( limitString(input.files[0].name) ).attr("title", input.files[0].name);
                gblFileName = input.files[0].name;
                $("input[type='hidden']#filename").val(gblFileName);
                $("#image_type").val(input.files[0].type);
                // updateFilters();
            }
        }

        function limitString(string){
            if( string.length > 25 ){
                return string.substr(0, 25)+"...";
            }
            return string;
        }

        function allowNumericOnly(element){
            element.keydown(function(e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
              // Allow: Ctrl+A,Ctrl+C,Ctrl+V, Command+A
              ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) ||
              // Allow: home, end, left, right, down, up
              (e.keyCode >= 35 && e.keyCode <= 40)) {
              // let it happen, don't do anything
              return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
              e.preventDefault();
            }
          });
        }

        function activateModal(target) {
            // initialize modal element
            var targetElement = $("#"+target);

            var modalEl = document.createElement('div');
            modalEl.style.width = '400px';
            // modalEl.style.height = '300px';
            modalEl.style.height = targetElement.height;
            modalEl.style.margin = '100px auto';
            modalEl.style.backgroundColor = '#fff';
            modalEl.innerHTML = targetElement.html();

            // show modal
            mui.overlay('on', modalEl);
        }

        function respondCanvas(c, container){ 
            c.attr('width', $(container).width() ); //max width
            c.attr('height', $(container).height() ); //max height

            //Call a function to redraw other content (texts, images etc)
        }


        if( $("canvas#image_preview").length > 0 ){

            // This is where we make the canvas responsive
                var c = $('#image_preview');
                var ct = c.get(0).getContext('2d');
                var container = $(c).parent();

                //Run function when browser resizes
                $(window).resize( respondCanvas );

                //Initial call 
                respondCanvas(c, container);

        }


        function adaptiveheight(a) {
            $(a).height(0);
            var scrollval = $(a)[0].scrollHeight;
            $(a).height(scrollval);
            if (parseInt(a.style.height) > $(window).height() - 30) {
                $(document).scrollTop(parseInt(a.style.height));
            }
        }

        // Convert a data URI to blob
        function dataURItoBlob(dataURI) {
            var byteString = atob(dataURI.split(',')[1]);
            var ab = new ArrayBuffer(byteString.length);
            var ia = new Uint8Array(ab);
            for (var i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new Blob([ab], {
                type: 'image/png'
            });
        }

       
        function get_pages(userId){
            console.log("get_pages is called");

            $.ajax({
                url: "https://graph.facebook.com/"+fbUserId+"/accounts?fields=id,name&access_token="+fbAccessToken,
                type: 'get',
                assync: false
            }).done(function (response1){
                console.log(response1);
                if( response1.hasOwnProperty('data') ){
                    if(response1.data.length < 1){
                        get_groups();
                    }
                }

                if (response1 && !response1.error) {
                    get_graph_api_request_assync(response1, "pages", get_groups);
                }else{
                    get_groups(); // moveon to the next
                }
            });
        }

        function get_groups(){
            console.log("get_groups is called");

            $.ajax({
                url: "https://graph.facebook.com/"+fbUserId+"/groups?fields=id,name,administrator&access_token="+fbAccessToken,
                type: 'get',
                assync: false
            }).done(function (response2){
                console.log(response2);
                if( response2.hasOwnProperty('data') ){
                    if(response2.data.length < 1){
                        show_results_for_where_to_post();
                        get_albums(fbUserId);
                    }
                }

                if (response2 && !response2.error) {
                    get_graph_api_request_assync(response2, "groups", show_results_for_where_to_post);
                }else{
                    console.log("An error has occured while getting groups.");
                    show_results_for_where_to_post();
                    get_albums(fbUserId);
                }
            });
        }

        function get_albums(userId){
            console.log("get_albums is called");
            // FB.api(
            //     "/"+userId+"/albums",
            //     function (response) {
            //         console.log(response);
            //         if( response.hasOwnProperty('data') ){
            //             if(response.data.length < 1){
            //                 show_results_for_albums();
            //             }
            //         }

            //         if (response && !response.error) {
            //             get_graph_api_request_assync(response, "albums", show_results_for_albums);
            //         }
            //     }
            // );

            $.ajax({
                url: 'https://graph.facebook.com/'+fbUserId+'/albums?fields=id,can_upload,name&access_token='+fbAccessToken,
                type: 'get',
                assync: false,
            }).done(function (response){
                console.log(response);
                if( response.hasOwnProperty('data') ){
                    if(response.data.length < 1){
                        show_results_for_albums();
                    }
                }

                if (response && !response.error) {
                    get_graph_api_request_assync(response, "albums", show_results_for_albums);
                }
            });
        }

        function show_results_for_where_to_post(){
            console.log("show_results_for_where_to_post is called");
            $("#where_to_post").html('<option value="me">My Wall</option><optgroup label="Pages">'+pages+"</optgroup>"+'<optgroup label="Groups">'+groups+"</optgroup>");
            $("#where_to_post ").select2();
        }

        function show_results_for_albums(){
            $("select#album").html(albums);
            $("#album").select2();
        }


        function get_graph_api_request_assync(responseFromAjax, type, callback){
            console.log("get_graph_api_request_assync has been called.");
            
            try{

                if( type == "albums" ){
                    $("select#album").html("");
                }

                $.each(responseFromAjax.data, function(i, row){
                    
                    if( type == "pages" ){
                        var found = jQuery.inArray(row.id, pages_array);
                        if (found === -1) {
                            pages_array.push(row.id);
                            pages+= '<option value="'+row.id+'" title="'+row.name+'">'+row.name+'</option>';
                        } 
                    }else if( type == "groups" ){ 
                        // filter groups 
                        if( row.hasOwnProperty('administrator') ){
                            if( row.administrator == true ){
                                var found = jQuery.inArray(row.id, groups_array);
                                if (found === -1) {
                                    groups_array.push(row.id);
                                    groups+= '<option value="'+row.id+'" title="'+row.name+'">'+row.name+'</option>';
                                } 
                            }
                        }else{
                            var found = jQuery.inArray(row.id, groups_array);
                            if (found === -1) {
                                groups_array.push(row.id);
                                groups+= '<option value="'+row.id+'" title="'+row.name+'">'+row.name+'</option>';
                            } 
                        }
                    }else if( type == "albums" ){
                        // $("select#album").append('<option value="'+row.id+'">'+row.name+'</option>');
                        var found = jQuery.inArray(row.id, albums_array);
                        if (found === -1) {
                            albums_array.push(row.id);
                            albums+= '<option value="'+row.id+'" title="'+row.name+'">'+row.name+'</option>';
                        } 
                    }
                        
                });

                if( responseFromAjax.hasOwnProperty('paging') ){
                    if( responseFromAjax.paging.hasOwnProperty("next") ){
                        // get_graph_api_request_assync();
                        $.ajax({
                            url: responseFromAjax.paging.next,
                            type: 'get'
                        }).done(function (newData){
                            get_graph_api_request_assync(newData, type, callback);
                        });
                    }else{
                        if( callback ){
                            callback();
                        }
                    }
                }

            }catch(Exception){
                console.log(Exception);
            }
        }

        function refreshWhereToPost(userId){
                get_pages(userId);  // after getting pages, this will automatically call the get_groups method
                                    // since they are on the same select
                get_albums(userId);
        }

        function postPhoto(albumId){
            if( albumId !== 0 ){

                // let's upload the image

                // let's change html2canvas to rasterizeHTML for better text quality
                var canvas = document.getElementById("image_preview"),
                    context = canvas.getContext('2d');

                rasterizeHTML.drawHTML($(".image-holder").html()).then(function (renderResult) {
                    context.drawImage(renderResult.image, 10, 25);

                    var theCanvas = canvas;

                    var imageData = canvas.toDataURL("image/png");
                    window.globalDataUrl = imageData;

                    var blob = "";
                    try {
                        blob = dataURItoBlob(imageData);
                    } catch (e) {
                        console.log(e);
                    }
                    var fd = new FormData();
                    fd.append("access_token", fbAccessToken);
                    fd.append("source", blob);
                    fd.append("message", $("#post_title").val());

                    try {
                        var btn = $("#post_to_fb");

                        btn.html("Please wait...").addClass("disabled").attr("disabled", "disabled");

                        $.ajax({
                            assync: false,
                            url: "https://graph.facebook.com/"+albumId+"/photos",
                            type: "POST",
                            data: fd,
                            processData: false,
                            contentType: false,
                            cache: false,
                            beforeSend : function (){
                                btn.html("Uploading...").addClass("disabled").attr("disabled", "disabled");
                            },
                            success: function (data) {
                                console.log(data);
                                var imgUploadedId = data.id;
                                var imgPostId = data.post_id;
                                console.log("imgUploadedId: "+imgUploadedId);

                                


                                FB.api(fbUserId+"/picture?id="+imgUploadedId, function(response1){
                                    console.log(" let's get the url of the newly uploaded image...");
                                    console.log(response1);
                                    if( data.hasOwnProperty('error') ){
                                        console.log("ERROR while fetching url of newly uploaded user photo.");
                                        $.snackbar({ content: "ERROR while fetching url of newly uploaded user photo.", timeout: 4000});
                                    }else{
                                        imgUploadedUrl= response1.data.url;
                                        // if (response && !response.error) {
                                            /* handle the result */
                                            // var imgUploadedUrl = response.source;
                                            // var imgUploadedLink = response.link;

                                            // let's choose where we want it to be posted
                                            if( $("#target_url").val() !== "" ){  // this means, the image should be clickable
                                                console.log("/we are posting inside IF when target_url is not empty");
                                                console.log("whereToPost: ");
                                                console.log(whereToPost);
                                                try{
                                                   
                                                    var newImgURL = "";

                                                    window.ajax_requests_for_feed_posting = [];
                                                    $.each(whereToPost, function(i, row){
                                                        var check_if_page = $.inArray(row, pages_array);
                                                        // Make sure not to include page
                                                        if( check_if_page < 0 ){

                                                            var request = $.ajax({
                                                                            url: 'https://graph.facebook.com/'+row+'/feed',
                                                                            type: 'post',
                                                                            assync: false,
                                                                            data: {
                                                                                name: $("#post_title").val(),
                                                                                picture: imgUploadedUrl,
                                                                                link: $("#target_url").val(),
                                                                                description: $("#post_description").val(),
                                                                                message : $("#post_message").val(),
                                                                                access_token: fbAccessToken
                                                                            },
                                                                            beforeSend: function (){
                                                                                console.log("posting to "+row+' feed...');
                                                                            },
                                                                            success: function (data){
                                                                                console.log("response when target_url is not empty:");
                                                                                console.log(data);
                                                                            },
                                                                            error: function (shr, status, data){
                                                                                if( row == "me" ){
                                                                                    $.snackbar({content: "Failed when posting to wall. Please try again later. "+data+" status "+shr.status, timeout: 4000});
                                                                                }else{
                                                                                    $.snackbar({content: "Failed when posting to group. "+data+" status "+shr.status, timeout: 4000});
                                                                                }
                                                                                console.log("Error when posting to "+row+" feed. "+data+" status "+shr.status);
                                                                            }
                                                                        });
                                                            
                                                            window.ajax_requests_for_feed_posting.push(request);
                                                        }
                                                    });
                                                    
                                                    window.user_image_is_deleted = false;
                                                    $(document).ajaxStop(function (){
                                                        // let's delete the image post to prevent duplicate posts
                                                        if( $.trim( $("#target_url").val() ) !== "" ){
                                                            if( window.user_image_is_deleted === false ){
                                                                $.ajax({
                                                                    url: "https://graph.facebook.com/"+imgUploadedId+"?method=DELETE&access_token="+fbAccessToken,
                                                                    type: 'post',
                                                                    processData: false,
                                                                    contentType: false,
                                                                    cache: false,
                                                                    beforeSend: function (){
                                                                        console.log("deleting user image now...");
                                                                    },
                                                                    success: function (data){
                                                                        console.log(data);
                                                                        window.user_image_is_deleted = true;
                                                                    },
                                                                    error: function(shr, status, data){
                                                                        console.log("Error while deleting user image." + data + " Status " + shr.status);
                                                                    },
                                                                    complete: function (data){
                                                                        console.log(data);
                                                                        console.log("completed");
                                                                    }
                                                                });
                                                            }
                                                        }
                                                    });
                                                    
                                                    
                                                }catch(Exception){
                                                    console.log("ERROR: ");
                                                    console.log(Exception);
                                                }
                                            }else{
                                                $.ajax({
                                                    url: 'https://graph.facebook.com/v2.5/'+imgUploadedId+'?fields=link&access_token='+fbAccessToken,
                                                    type: 'get',
                                                    assync: false,
                                                    dataType: 'json',
                                                    beforeSend: function (){
                                                        console.log("fecthing "+imgUploadedId+" photo details...");
                                                    },
                                                    success: function (data){
                                                        console.log(data);
                                                        $.each(whereToPost, function(i, row){
                                                            var check_if_page = $.inArray(row, pages_array);
                                                           

                                                            // Make sure not to include page
                                                            if( check_if_page < 0 ){
                                                                FB.api('/'+row+'/feed', 'post', {
                                                                    message: $("#post_title").val(),
                                                                    // link: imgUploadedLink
                                                                    link: data.link,
                                                                    // picture: imgUploadedUrl
                                                                    }, function (response) {
                                                                        console.log(response);
                                                                        if (response && !response.error) {
                                                                            /* handle the result */
                                                                            console.log("Posted to "+row+" feed.");
                                                                            if( row == "me" ){
                                                                                $.snackbar({content: "Posted to wall successfully.", timeout: 4000});
                                                                            }
                                                                        }else{
                                                                            color.log("Sorry, something went wrong while posting to "+row+" feed");
                                                                            console.log(response);
                                                                        }
                                                                    }
                                                                );
                                                            }
                                                            
                                                        });
                                                    },
                                                    error: function (shr, status, data){
                                                        console.log("Error: "+data+" Status "+shr.status);
                                                    }
                                                });

                                               

                                                
                                            }
                                        // }
                                    }
                                });
                            },
                            error: function (shr, status, data) {
                                console.log("error " + data + " Status " + shr.status);
                                // alert("Failed to post on facebook. Please try again later.");
                                $.snackbar({ content: "Failed to post on facebook. Please try again later.", timeout: 4000});
                                btn.removeAttr("disabled").removeClass("disabled").html("POST");
                            },
                            complete: function () {
                                console.log("Posted to facebook");
                                // alert("Posted to facebook successfully.");
                                // $.snackbar({ content: "Posted to facebook successfully.", timeout: 4000});
                                btn.removeAttr("disabled").removeClass("disabled").html("POST");
                            }
                        });
                        
                        
                        FB.api(fbUserId+'/accounts', function(data){
                            // window.pages_access_tokens = data.data;

                            if( data.hasOwnProperty('data') ){
                                window.pages_access_tokens = data.data;
                                // $.each(data.data, function (i, row){
                                //     window.pages_access_tokens[row.id] = row.access_token;
                                // });
                            }
                            // var page_access_token = data.data[0].access_token;
                            
                            console.log("window.pages_access_tokens: ");
                            console.log(window.pages_access_tokens);

                            // Here we execute if user has a page id on the where_to_post field
                            $.each(whereToPost, function (i, row){
                              
                                var check_if_page = $.inArray(row, pages_array);

                                if( check_if_page !== -1 ){  // it means, the row is a page ID         
                                    console.log("we're starting to upload image as Page Admin");
                                    // let's upload the image directly to the page as admin
                                    //FB.api(fbUserId+'/accounts', function(data){  // getting the page access token
                                        console.log(data);  

                                        // var page_access_token = data.data[0].access_token;
                                        var page_access_token = "";
                                        $.each(window.pages_access_tokens, function (i2, row2){
                                            if( row == row2.id ){
                                                page_access_token = row2.access_token;
                                            }
                                        });

                                        console.log("pageID: "+row+"\n access_token: \n"+page_access_token);
                                        var fd = new FormData();
                                        var url = "";
                                        
                                        fd.append("source", blob);
                                        fd.append("message", $("#post_title").val());
                                        url = "https://graph.facebook.com/"+row+"/photos?access_token="+page_access_token;
                                            
                                        $.ajax({
                                            url: url,
                                            type: "POST",
                                            data: fd,
                                            processData: false,
                                            contentType: false,
                                            cache: false,
                                            assync: false,
                                            beforeSend : function (){
                                                console.log("uploading image to page photos...");
                                            },
                                            success: function (response) {
                                                console.log(response);
                                                var pageImgUploadedId = response.id;
                                                var pageImgUploadedUrl = "";

                                                if( $.trim( $("#target_url").val() ) != "" ){
                                                
                                                    // getting the url of newly uploaded picture
                                                    FB.api(row+"/picture?id="+pageImgUploadedId, function(response1){
                                                        console.log("getting the url of newly uploaded picture..");
                                                        console.log(response1);
                                                        if( data.hasOwnProperty('error') ){
                                                            console.log("ERROR while fetching url of newly uploaded photo on page.");
                                                            $.snackbar({ content: "ERROR while fetching url of newly uploaded photo on page.", timeout: 4000});
                                                        }else{
                                                            pageImgUploadedUrl= response1.data.url;
                                                            console.log('page image uploaded url: '+pageImgUploadedUrl);

                                                            //let's try to publish to page feed
                                                            var fd2 = new FormData();
                                                            fd2.append("access_token", page_access_token);
                                                            fd2.append("name", $("#post_title").val());
                                                            fd2.append("link", $("#target_url").val());
                                                            fd2.append("caption", $("#post_description").val());
                                                            fd2.append("message", $("#post_message").val());
                                                            fd2.append('picture', pageImgUploadedUrl);
                                                            url2 = "https://graph.facebook.com/"+row+"/feed";

                                                            var page_success_flag = false;
                                                            $.ajax({
                                                                url: url2,
                                                                type: 'post',
                                                                data: fd2,
                                                                processData: false,
                                                                contentType: false,
                                                                cache: false,
                                                                beforeSend: function (){
                                                                    btn.html("Posting...");
                                                                    console.log("Posting new image to page feed...");
                                                                },
                                                                success: function (data){
                                                                    console.log(data);
                                                                    // alert("Posted to facebook page successfully.");
                                                                    $.snackbar({ content: "Posted to facebook page successfully.", timeout: 4000});
                                                                    page_success_flag = true;
                                                                    // if success, let's delete the photo post to hide it on the timeline
                                                                    $.ajax({
                                                                        url: "https://graph.facebook.com/"+pageImgUploadedId+"?method=DELETE&access_token="+page_access_token,
                                                                        type: "post",
                                                                        processData: false,
                                                                        contentType: false,
                                                                        cache: false,
                                                                        beforeSend: function (){
                                                                            console.log("deleting image now...");
                                                                        },
                                                                        success: function (data){
                                                                            console.log(data);
                                                                        },
                                                                        error: function (shr, status, data){
                                                                            console.log("error " + data + " Status " + shr.status);
                                                                            btn.removeAttr("disabled").removeClass("disabled").html("POST");
                                                                        }
                                                                    });
                                                                },
                                                                error: function (shr, status, data) {
                                                                    console.log("error " + data + " Status " + shr.status);
                                                                    // alert("Failed to post on a facebook page. Please try again later.");
                                                                    $.snackbar({ content: "Failed to post on a facebook page. Please try again later.", timeout: 4000});
                                                                    btn.removeAttr("disabled").removeClass("disabled").html("POST");
                                                                },
                                                                complete: function () {
                                                                    console.log("Posted to facebook page successfully.");
                                                                    // alert("Posted to facebook successfully.");
                                                                    btn.removeAttr("disabled").removeClass("disabled").html("POST");
                                                                    
                                                                    // if( page_success_flag === true ){
                                                                        // if success, let's delete the photo post to hide it on the timeline
                                                                        $.ajax({
                                                                            url: "https://graph.facebook.com/"+pageImgUploadedId+"?method=DELETE&access_token="+page_access_token,
                                                                            type: "post",
                                                                            processData: false,
                                                                            contentType: false,
                                                                            cache: false,
                                                                            beforeSend: function (){
                                                                                console.log("deleting image now...");
                                                                            },
                                                                            success: function (data){
                                                                                console.log(data);
                                                                            },
                                                                            error: function (shr, status, data){
                                                                                console.log("error " + data + " Status " + shr.status);
                                                                                btn.removeAttr("disabled").removeClass("disabled").html("POST");
                                                                            },
                                                                            complete: function (){
                                                                                // reset the success flag
                                                                                // page_success_flag = false;
                                                                            }
                                                                        });
                                                                    // }

                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                            },
                                            error: function (shr, status, data) {
                                                console.log("error " + data + " Status " + shr.status);
                                                // alert("Failed to post on facebook. Please try again later.");
                                                $.snackbar({ content: "Failed to post on a facebook page. Please try again later.", timeout: 4000});
                                                btn.removeAttr("disabled").removeClass("disabled").html("POST");
                                            },
                                            complete: function () {
                                                console.log("Posted to facebook");
                                                // alert("Posted to facebook successfully.");
                                                btn.removeAttr("disabled").removeClass("disabled").html("POST");
                                            }
                                        });
                                    //});
                                }
                            });

                        });

                    } catch (e) {
                        console.log(e);
                    }
                });    
            }
        }

        function getLongLiveFBToken(){
            $.ajax({
                // url : wpSocialMageAjaxUrl,
                url: ajaxurl,
                type : 'post',
                data : { 
                    'q' : 'get_long_live_token', 
                    'short_life_token' : fbAccessToken ,
                    'action': 'WpSocialMageAjax'
                },
                dataType : 'json',
                beforeSend: function (){
                    console.log("getting long live access token...");
                }
            }).done(function(data){
                console.log(data);
                if( data.longLiveAccessToken !== "" ){
                    $(document).find("input#_fbtoken").val(data.longLiveAccessToken);
                }
            });
        }

        function fitImage(target){
            if( $("#image_size option:selected").attr("data-id") == 2){
                $(".image-holder, .image-holder-outer").css("width", 487).css("height", 255);
            }else{
                $(".image-holder, .image-holder-outer").css("width", 500).css("height", 500);
            }

            
            $(target).each(function(i, item) {
                var img_height = $(item).height();
                var div_height = $(item).parent().height();
                if(img_height<div_height){
                    //INCREASE HEIGHT OF IMAGE TO MATCH CONTAINER
                    $(item).css({'width': 'auto', 'height': div_height });
                    //GET THE NEW WIDTH AFTER RESIZE
                    var img_width = $(item).width();
                    //GET THE PARENT WIDTH
                    var div_width = $(item).parent().width();
                    //GET THE NEW HORIZONTAL MARGIN
                    var newMargin = (div_width-img_width)/2+'px';
                    //SET THE NEW HORIZONTAL MARGIN (EXCESS IMAGE WIDTH IS CROPPED)
                    $(item).css({'margin-left': newMargin });
                }else{
                    //CENTER IT VERTICALLY (EXCESS IMAGE HEIGHT IS CROPPED)
                    var newMargin = (div_height-img_height)/2+'px';
                    $(item).css({'margin-top': newMargin});
                }
            });

        }

        function saveImageUpdates(){
            var $this = $("#update_image"), action = $this.data("action"), filename = $("#image_preview").data("filename");

            $this.html("Saving...");

            if( getUrlParameter('action') == 'add_filters' && ($from_php_width > 550 && $from_php_height > 255) ){
                window.hasImageEffects = true;
            }

            console.log("hasImageEffects: "+window.hasImageEffects);
            
            // if action is 'save', then we'll check if the image don't have text so we don't need to re-render it, same
            // goes if the image is already on 487x255 or 500x500, we should not render the image
            
            if( window.hasImageEffects === false && action == 'update' && $from_php_width < 550){
                $this.html('&#10003; Redirecting...please wait.');
                // save the image without re-rendering
                window.location = '?page=wp-social-mage-dashboard&image='+filename;
            }

            if( getUrlParameter('action') == 'add_filters' ){

                html2canvas( $(".image-holder"), {
                    onrendered: function(canvas) {
                        theCanvas = canvas;
                        var dataURL = canvas.toDataURL();

                        $this.attr("disabled", "disabled").addClass("disabled");
                        ajax_save_image_updates(dataURL, filename, action, $this);
                    }
                });

            }else{

                var dataURL = "";
                if( window.useRasterizeHtmlRenderer === true ){
                    var canvas =  document.getElementById('image_preview'),
                        context = canvas.getContext('2d');

                    rasterizeHTML.drawHTML($(".image-holder").html()).then(function (renderResult) {
                        context.drawImage(renderResult.image, 10, 25);

                        dataURL = canvas.toDataURL();
                        ajax_save_image_updates(dataURL, filename, action, $this);
                    });

                }else{
                    html2canvas($(".image-holder"), {
                        onrendered: function(canvas) {
                            dataURL = canvas.toDataURL();
                            ajax_save_image_updates(dataURL, filename, action, $this);
                        }
                    });
                }
            
            }

        }

        // note: $this is the button
        function ajax_save_image_updates(dataURL, filename, action, $this){
            $this.attr("disabled", "disabled").addClass("disabled");
                    
            $.ajax({
                assync: false,
                type: "POST",
                // url: wpSocialMageAjaxUrl,
                url: ajaxurl,
                dataType : 'json',
                data: { 
                    action: 'WpSocialMageAjax',
                    imgBase64: dataURL,
                    data_action : action,
                    q : "save_canvas",
                    filename : filename
                },
            }).done(function(o) {
                console.log(o);
                (function (el) {
                    setTimeout(function () {
                        $this.removeAttr("disabled").removeClass("disabled");
                        $this.html("Please wait...");
                        if( o.status == "success" && action == "update" ){
                            // window.location = '?page=wp-social-mage-dashboard&image='+filename;
                            var a = document.createElement('a');
                            a.href = '?page=wp-social-mage-dashboard&image='+o.filename;
                            
                            // click event for firefox
                            var clickEvent = new MouseEvent("click", {
                                "view": window,
                                "bubbles": true,
                                "cancelable": false
                            });

                            a.dispatchEvent(clickEvent);

                        }else{
                            if( o.msg ){
                                $.snackbar({content: o.msg, timeout: 10000});
                            }
                            $this.html("Save");
                        }
                    }, 4000);
                }( $this.html('&#10003; Redirecting...please wait.' )) );

              // If you want the file to be visible in the browser 
              // - please modify the callback in javascript. All you
              // need is to return the url to the file, you just saved 
              // and than put the image in your browser.
            }).fail(function(){
                $this.html("Save");
                (function (el) {
                    setTimeout(function () {
                        $this.removeAttr("disabled").removeClass("disabled");
                        el.next('span.saving-status').fadeOut(function(){
                            $(this).remove();
                        });
                    }, 4000);
                }( $this.after('<span class="saving-status" style="margin-left: 5px; color: red;">Error! Please try again later.</span>')) );
            });
        }




        /** END JQUERY FUNCTIONS **/
/******************************************************************************************************************************************************
*******************************************************************************************************************************************************
*******************************************************************************************************************************************************/
        /**START JQUERY EVENTS **/

        $("#select_image").change(function(){
            readURL(this);
            console.log("select_image change fn");
            $("#btn_upload").unbind('click').trigger("click");
        });



        // $('.date-picker').bootstrapMaterialDatePicker({ format : 'DD/MM/YYYY HH:mm', minDate : new Date() });

        var bar = $('.bar');
        var percent = $('.percent');
        var status = $('#status');
        var progress = $('.progress');

        $("#btn_browse").unbind('click').click(function (){
            bar.width('0%'); 
            $('#btn_add_filter, #update_image').addClass("mui-invisible");
            $("#select_image").trigger('click');  
        });


        $('#form_upload_image').ajaxForm({
            url: wpSocialMageUploadUrl, // point to server-side PHP script 
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            assync: false,
            contentType: false,
            processData: false,
            data: $('#form_upload_image').formSerialize(),                         
            type: 'post',
            beforeSend: function() {
                progress.fadeIn(function(){
                    progress.removeClass('mui-invisible');
                });
                status.empty();
                var percentVal = '0%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            complete: function(xhr) {
                progress.fadeOut(2000);
                var data = xhr.responseJSON;
                $(function() {
                    // $.snackbar({ content: data.msg, timeout: 4000});
                    console.log(data.msg);
                    $('.image-preview').attr("src", uploadsUrl+data.filename);

                    var url = location.href;
                    
                    try{
                        if( data.status == "success" ){
                        
                            window.location = url+"&q=imagefilters&action=add_filters&image="+data.filename;
                        }
                    }catch(Exception){
                        console.log(Exception);
                    }
                });
            }
        });

        $(document).on("click", ".mui-modal", function (){
            activateModal( $(this).data("target") );
        }); 


        $(document).on("click", "img.option-effect", function (){
            $(".image-preview").attr("effect", $(this).attr("effect")).addClass("colorup").attr("inverse", "true");
        });

        $(document).on("click", "#filter_types", function(){
            var $this = $(this);
            var val = $this.data("value");
            
            if( val == "preset" ){
                $("#Filters").fadeOut(function (){
                    $("#PresetFilters").fadeIn();
                });
                $("#filter_type_name").html("Default Effects");
                $this.data("value", "interactive");
            }else if( val == "interactive" ){
                $("#PresetFilters").fadeOut(function (){
                    $("#Filters").fadeIn();
                });
                $("#filter_type_name").html("Interactive Effects");
                $this.data("value", "preset");
            }
        });

        $("#update_image").click(function(){
            saveImageUpdates();
        });

        $("#image_size").change(function(e){

            var imgSize = $(this).val();
            var w, h;

            var hasError = false;

            if( $from_php_width != 487 && $from_php_height != 255 ){
                top_y = $(this).children("option:selected").attr("data-id") == 1 ? 40 : 140;
                bottom_y = $(this).children("option:selected").attr("data-id") == 1 ? 460 : 350;
            }

            if( $(this).children("option:selected").attr("data-id") == 1 ){
                if( $from_php_width == 487 && $from_php_height == 255 ){
                    $("#image_size option").removeAttr("selected");
                    e.preventDefault();
                    $.snackbar({ content: "Sorry, you can't select a larger dimension from an image with smaller size.", timeout: 4000});
                    $("#image_size option[data-id='2']").attr("selected", "selected");
                    hasError = true;
                }else{
                    $image_width = 500;
                    $image_height = 500;
                    top_y = 40;
                    bottom_y = 460;
                }
                
            }else{
                $image_width = 487;
                $image_height = 255;
                if( $raw_image_width == 487 ){

                    top_y = 80;
                    bottom_y = 260; 
                }else{
                    top_y = 140;
                    bottom_y = 350; 
                }
            }


            if( hasError === false ){

                if( $(this).children("option:selected").attr("data-id") == 2 ){
                    $(".image-holder, .image-holder-outer").css("width", 487).css("height", 255);

                    if( $from_php_width == 487 && $from_php_height == 255 ){
                        $("#image_preview").removeAttr("style").width('487px').height('255px');
                        window.useRasterizeHtmlRenderer = true;
                    }else{
                        window.useRasterizeHtmlRenderer = false;
                    }

                    console.log('$(".image-holder, .image-holder-outer").css("width", 487).css("height", 255);');
                }else{
                    $(".image-holder, .image-holder-outer").css("width", 500).css("height", 500);
                    console.log('$(".image-holder, .image-holder-outer").css("width", 500).css("height", 500);');
                    window.useRasterizeHtmlRenderer = true;
                }

                fitImage('#image_preview');

                console.log("before instantiateCanvas = top_y: "+top_y+" bottom_y: "+bottom_y);
                instantiateCanvas();

                $('#image_preview').setLayer('top_text', {
                    x: $image_width/2,
                    y: top_y
                }).setLayer('bottom_text', {
                    x: $image_width/2,
                    y: bottom_y
                }).drawLayers();


                console.log("top_y: "+top_y+" bottom_y: "+bottom_y);

                if( getUrlParameter('action') != 'add_filters' ){
                    $("#bottom_watermark").trigger("keyup");
                }

            }

            $("#text_over_image, #bottom_text").trigger("keyup");
            console.log('hasError: '+hasError);
            window.hasImageEffects = true;
            console.log("window.useRasterizeHtmlRenderer: "+window.useRasterizeHtmlRenderer);
        });



        $("textarea").keyup(function (e) {
            adaptiveheight(this);
        });

        $("#font_size").change(function(){
            var size = $(this).val();
            checkCanvasStatus();
            $('#image_preview').setLayer('top_text', {
                fontSize: size,
            }).setLayer('bottom_text', {
                fontSize: size,
            }).drawLayers();
            
            space_text();
        });

        $("#font_family").change(function(){
            var font = $(this).val();
            checkCanvasStatus();
            $('#image_preview').setLayer('top_text', {
                fontFamily: font,
            }).setLayer('bottom_text', {
                fontFamily: font,
            }).drawLayers();
            
            space_text();
        });


        $("#stroke_size").change(function (){
            var size = $(this).val();
            checkCanvasStatus();
            $("#image_preview").setLayer('top_text', {
                strokeWidth: size,
                strokeStyle: $("#text_shadow").val()
            }).setLayer('bottom_text', {
                strokeWidth: size,
                strokeStyle: $("#text_shadow").val()
            }).drawLayers();

            space_text();

        });

        $("#post_to_fb").click(function(){
            var scheduleDate = $.trim($("#schedule").val());
            
            if( validate_inputs(false) ){
                FB.login(function(response) {
                    if (response.authResponse) {
                        // Do all facebook stuffs here

                        whereToPost = $("#where_to_post").val();

                        if( $("input[name='optionsAlbum']:checked").val() == "create" ){
                            // creates a new album
                            var albumName = $("#album_name").val();
                            FB.api('/me/albums', 'post', {
                                name: albumName,
                                message: '', // album description
                                privacy: { "value" : 'ALL_FRIENDS' }
                            }, function(response) {
                                // response.id is the album id
                                albumId = response.id;
                                if( albumId !== undefined ){
                                    postPhoto(albumId);
                                }
                                
                                refreshWhereToPost(fbUserId);
                            });
                        }else{
                            // we'll use the existing selected album
                            albumId = $("select#album").val();
                            postPhoto(albumId);
                        }
                        
                    } else {
                        console.log('User cancelled login or did not fully authorize.');
                    }
                }, {scope: fbScope });

                saveImageUpdates();
            }else{
                $.each(error_fields, function (i, row){
                    console.log(row);
                    $(row.field).addClass("mui-dirty");
                    $.snackbar({ content: row.error, timeout: 6000});
                });
            }
           
            return false;
        });
        
        $("#schedule_post").click(function(){
            $("#schedule").click();
        });

        $("#schedule").click(function(){
            $(this).bootstrapMaterialDatePicker({ format : 'DD/MM/YYYY HH:mm', minDate : new Date() });
        });

        $("#schedule").unbind("change").change(function(){
            if( $.trim(fbUserId) == "" ){
                alert("Please login with facebook first.");
            }else{
                if( validate_inputs(true) ){
                    $.ajax({
                        // url: wpSocialMageAjaxUrl,
                        url: ajaxurl,
                        type: 'post',
                        dataType: 'json',
                        data: { 
                            action: 'WpSocialMageAjax',
                            q : 'get_user_type', 
                            type: 'pro' 
                        }
                    }).done(function (data){
                        if( data.type == "pro" || data.type == "pro_wl" ){

                            whereToPost = $("#where_to_post").val();
                            saveImageUpdates();

                            // try
                            window.whereToPost = whereToPost;
                            window.pagesArray = pages_array;

                            var neoWhereToPost = whereToPost;
                            var whereToPostPage = [];


                            $.each(whereToPost, function(i, row){
                                
                                var check_if_page = $.inArray(row, pages_array);

                                if( check_if_page !== -1 ){  // it means, the row is a page ID   
                                    // save to array
                                    whereToPostPage.push(row);

                                    neoWhereToPost = $.grep(neoWhereToPost, function(value){
                                        return value != row;
                                    });
                                }
                            });

                            window.neoWhereToPost = neoWhereToPost;
                            whereToPost = neoWhereToPost;


                            $("#_fbtoken").val(fbAccessToken);
                            $.ajax({
                                // url : wpSocialMageAjaxUrl,
                                url: ajaxurl,
                                data : "action=wpSocialMageAjax&"+$("#wp_social_mage_form").serialize()+"&fbUserId="+fbUserId+"&whereToPost="+whereToPost+"&whereToPostPage="+whereToPostPage,
                                type : 'post',
                                dataType : 'json'
                            }).done(function (data){
                                console.log(data);
                                if( data.status == "success" || data.status_code == '200' ){
                                    alert("Post scheduled successfully.");
                                }else{
                                    alert("Sorry, we can't process your request right now. Plese check back later.");   
                                }
                            });
                        }else{
                            activateModal('pro_upgrade');
                        }
                    });
                }else{
                    console.log(error_fields);
                    $.each(error_fields, function (i, row){
                        $(row.field).addClass("mui-dirty");
                        $.snackbar({ content: row.error, timeout: 6000});
                    });
                }
            } 

        });

        
        // @param boolean isSchedule  = false if post directly, true if schedule posting
        function validate_inputs(isSchedule){
            var res = true;
            error_fields = [];

            if( $("#where_to_post").val() === null || $("#where_to_post").val() == ""){
                res = false;
                error_fields.push({"field": "#where_to_post", "error": "Please specify where you want it to post."});
            }

            if( $("#target_url").val() !== "" ){
                // validate url
                var targetUrl = $("#target_url").val();
                if( !/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(targetUrl) ){
                    res = false;
                    error_fields.push({"field": "#target_url", "error": "Please enter a valid URL."});
                }

                if( $("#post_title").val() === ""  ){
                    res = false;
                    error_fields.push({"field": "#post_title", "error": "Please fill the Title field."});
                }
                
                if( $("#post_description").val() === "" ){
                    res = false;
                    error_fields.push({"field": "#post_description", "error": "Please fill the Description field."});
                }
                

            }

            if( $("#options_album_create").is(":checked") && $.trim( $("#album_name").val() ) === "" ){

                res = false;
                error_fields.push({"field": "#album_name", "error": "Please provide an Album Name"});
                

            }else if( $("#options_album_choose").is(":checked") && $("#album").val() === "" ){
                res = false;
                error_fields.push({"field": "#album", "error": "Please choose an existing album"});
            }

            if( isSchedule && $("input#schedule").val() === "" ){
                res = false;
                error_fields.push({"field": "#schedule", "error": "Please specify date and time"});
                
            }

            console.log(error_fields);

            return res;
        }

        function space_text() {
            if( $from_php_width == 487 && $from_php_height == 255 ){
                top_y = 20;
            }
        
            $('#image_preview').setLayer('top_text', {
                y: top_y + ( $('#image_preview').measureText('top_text').height / 2 )
            }).setLayer('bottom_text', {
                y: bottom_y - ( $('#image_preview').measureText('bottom_text').height / 2 )
            }).drawLayers();
            
        }
        
        
        $('#text_over_image').keyup( function(){

            top_y = $("#image_size option:selected").data("id") == 1 ? 40 : 140;

            if( $from_php_width == 487 && $from_php_height == 255 ){
                top_y = 20;
            }

            if( is_instantiated === 0 ){                
                instantiateCanvas();
                is_instantiated = 1;
            }else{
               $('#image_preview').setLayer('top_text', {
                    fillStyle: $("#font_color").val(),
                    strokeStyle:  $("#text_shadow").val(),
                    strokeWidth: $("#stroke_size").val(),
                    x: $image_width/2,
                    y: top_y, 
                    draggable: true,  

                    maxWidth:  $(".image-holder").width()-($("#image_size option:selected").data("id") == 1 ? 10 : 28),
                    textAlign: 'center',
                    fontSize: current_font_size,
                    fontFamily: $("#font_family").val(),
                    text: $(this).val()
                }).drawLayers(); 
            }
            

            space_text();
            
        });

        $('#bottom_text').keyup( function(){
            bottom_y = $("#image_size option:selected").data("id") == "1" ? 450 : 340;

            if( $from_php_width == 487 && $from_php_height == 255 ){
                bottom_y = 230; 
            }

            if( is_instantiated === 0 ){
                console.log("x is 0 so lets instantiate the canvas");
                
                instantiateCanvas();
                is_instantiated = 1;
            }else{
                $('#image_preview').setLayer('bottom_text', {
                    fillStyle: $("#font_color").val(),
                    strokeStyle:  $("#text_shadow").val(),
                    strokeWidth: $("#stroke_size").val(),
                    x: $image_width/2,
                    y: bottom_y,
                    draggable: true,
                    // maxWidth:  500,
                    maxWidth: $(".image-holder").width()-($("#image_size option:selected").data("id") == 1 ? 10 : 28),
                    textAlign: 'center',
                    fontSize: current_font_size,
                    fontFamily: $("#font_family").val(),
                    text: $(this).val()
                }).drawLayers(); 
            }
            
            space_text();            
        });

        $('#bottom_watermark').keyup( function(){
            bottom_y = $("#image_size option:selected").attr("data-id") == 1 ? 475 : 365;

            if( $from_php_width == 487 && $from_php_height == 255 ){
                // bottom_y = 245; 
                bottom_y = 240; 
            }

            if( is_instantiated === 0 ){
                console.log("x is 0 so lets instantiate the canvas");
                
                instantiateCanvas();
                // is_instantiated = 1;
            }else{
                $('#image_preview').setLayer('bottom_watermark', {
                    fillStyle: $("#font_color").val(),
                    strokeStyle:  0,
                    strokeWidth: 0,
                    x: $image_width/2,
                    y: bottom_y,
                    draggable: true,
                    // maxWidth:  500,
                    maxWidth: $(".image-holder").width()-($("#image_size option:selected").data("id") == 1 ? 10 : 28),
                    textAlign: 'center',
                    fontSize: 20,
                    fontFamily: 'Times New Roman, Arial',
                    text: $(this).val()
                }).drawLayers(); 
            }
            
            space_text();
            $("#bottom_text").trigger("keyup");            
        });

        function instantiateCanvas(){
            console.log("instantiating canvas");
            
            top_y = $("#image_size option:selected").attr("data-id") == 1 ? 40 : 140;
            bottom_y = $("#image_size option:selected").attr("data-id") == 1 ? 440 : 350;

            if( $("#image_size").is(":visible") && $from_php_width == 487 && $from_php_height == 255 &&  $("#image_size option:selected").attr("data-id") == 1 && is_instantiated === 0 ){
                $("#image_size option[data-id='2']").attr("selected", "selected");
            }

            if( getUrlParameter('action') == 'add_filters' ){
                $("#image_preview").addLayer({
                    layer: true,
                    name: 'background',
                        
                    type: 'image',
                    source: $("#image_preview").data("src"),
                    x: ( $w / 2 ) - ( $w / 2 ), 
                    y: ( $h / 2 ) - ( $h / 2 ),
                    fromCenter: false
                }).drawLayers();
            }else{
                $("#image_preview").addLayer({
                    layer: true,
                    name: 'background',
                        
                    type: 'image',
                    source: $("#image_preview").data("src"),
                    x: ( $w / 2 ) - ( $w / 2 ), 
                    y: ( $h / 2 ) - ( $h / 2 ),
                    fromCenter: false
                }).addLayer({
                    layer: true,
                    name: 'top_text',
                    type: 'text',
                    fillStyle: $("#font_color").val(),
                    strokeStyle:  $("#text_shadow").val(),
                    strokeWidth: $("#stroke_size").val(),
                    x: $image_width/2, 
                    y: top_y,
                    draggable: true,
                    maxWidth: 490,
                    textAlign: 'center',
                    fontSize: 48,
                    fontFamily: $("#font_family").val(),
                    text: $("#text_over_image").val()
                }).addLayer({
                    layer: true,
                    name: 'bottom_text',
                    textBaseline: 'top',
                    type: 'text',
                    fillStyle: $("#font_color").val(),
                    strokeStyle:  $("#text_shadow").val(),
                    strokeWidth: $("#stroke_size").val(),
                    x: $image_width/2, 
                    y: bottom_y,
                    draggable: true,
                    maxWidth: 490,
                    align: 'center',
                    fontSize: 48,
                    fontFamily: $("#font_family").val(),
                    text: $("#bottom_text").val()
                }).addLayer({
                    layer: true,
                    name: 'bottom_watermark',
                    textBaseline: 'top',
                    type: 'text',
                    draggable: true,
                    fillStyle: $("#font_color").val(),
                    strokeStyle: 0,
                    strokeWidth: 0,
                    x: $image_width/2, 
                    y: bottom_y+5,
                    maxWidth: 490,
                    align: 'center',
                    fontSize: 20,
                    fontFamily: 'Times New Roman, Arial',
                    text: $("#bottom_watermark").val()
                }).drawLayers();
            }

            console.log('done instantiating canvas');
        }

        var increasing_bg = false;
    
        $('#image_preview_increase_bg').click( function(){ 
            return false; 
        });
        
        $('#image_preview_increase_bg').unbind('mousedown').mousedown( function(){
            window.hasImageEffects = true;
            instantiateCanvas();

            increase_bg();
            
            increasing_bg = setInterval( function(){
                
                increase_bg();
                
            }, 30 );
            
        }).bind( 'mouseup mouseleave', function(){
            
            clearTimeout(increasing_bg);
            
        });
        
        function increase_bg() {
            
            if ( scale < 30 ) {
                
                scale = scale + 0.2;
                set_scale( scale );
                
            } else {
                
                clearTimeout(increasing_bg);
                
            }
            
            return false;
            
        }
        
        var decreasing_bg = false;
        
        $('#image_preview_decrease_bg').unbind('mousedown').mousedown( function(){
            window.hasImageEffects = true;
            instantiateCanvas();

            decrease_bg();
            
            decreasing_bg = setInterval( function(){
                
                decrease_bg();
                
            }, 30 );
            
        }).bind( 'mouseup mouseleave', function(){
            
            clearTimeout(decreasing_bg);
            
        });
        
        function decrease_bg() {
            
            if ( scale > 2 ) {
                
                scale = scale - 0.2;
                set_scale( scale );
                
            } else {
                
                clearTimeout(decreasing_bg);
                
            }
            
            return false;
            
        }
        
        function set_scale( size ) {
            checkCanvasStatus();
            $('#image_preview').setLayer('background', {
                scale: ( size / 10 )
            }).drawLayers();
            
        }


        var font_size = 48;
    
        var increasing = false;
        
        $('#image_preview_increase_font').click( function(){ return false; });
        
        $('#image_preview_increase_font').unbind("mousedown").mousedown( function(){
            
            increase_text();
            
            increasing = setInterval( function(){
                
                increase_text();
                
            }, 80 );
            
        }).bind( 'mouseup mouseleave', function(){
            
            clearTimeout(increasing);
            
        });
        
        function increase_text() {

            checkCanvasStatus();
            
            font_size = font_size + 2;
            set_font_size( font_size );

            current_font_size = font_size;

            return false;
            
        }
        
        var reducing = false;
        
        $('#image_preview_decrease_font').click( function(){ return false; });
        
        $('#image_preview_decrease_font').unbind("mousedown").mousedown( function(){
            
            reduce_text();
            
            reducing = setInterval( function(){
                
                reduce_text();
                
            }, 80 );
            
        }).bind( 'mouseup mouseleave', function(){
            
            clearTimeout(reducing);
            
        });
        
        function reduce_text() {
            checkCanvasStatus();
            
            if ( font_size != 20 ) {
                
                font_size -= 2;
                set_font_size( font_size );
                current_font_size = font_size;
                
            }
            
            return false;
            
        }
        
        function set_font_size( size ) {
            
            $('#image_preview').setLayer('top_text', {
                fontSize: size,
            }).setLayer('bottom_text', {
                fontSize: size,
            }).drawLayers();
            
            space_text();
            
        }

        function checkCanvasStatus(){
            if( is_instantiated === 0 ){
                instantiateCanvas();
            }
        }

        function checkUserFbStatus(){
            console.log("checkUserFbStatus is called");
            FB.getLoginStatus(function(response) {
              if (response.status === 'connected') {
                // the user is logged in and has authenticated your
                // app, and response.authResponse supplies
                // the user's ID, a valid access token, a signed
                // request, and the time the access token 
                // and signed request each expire
                fbUserId = response.authResponse.userID;
                fbAccessToken = response.authResponse.accessToken;

                $("#connect_to_facebook").parent("div").slideUp();
                getLongLiveFBToken();    
                // let's get all the existing albums of the current user here

                refreshWhereToPost(fbUserId);

              } else if (response.status === 'not_authorized') {
                // the user is logged in to Facebook, 
                // but has not authenticated your app
                // $("#connect_to_facebook").parent("div").removeClass("mui-invisible");
                $("#connect_to_facebook").parent("div").slideDown();
              } else {
                // the user isn't logged in to Facebook.
                $("#connect_to_facebook").parent("div").slideDown();
                // $("#connect_to_facebook").parent("div").removeClass("mui-invisible");
              }
            });
        }

        $("#target_url").bind("change keyup", function (){
            if( $(this).val() !== "" ){
                $("#post_title").next("label").html("Title");
                $("#post_message").parent("div").removeClass("hidden").fadeIn();
                $("#post_description").parent("div").removeClass("hidden").fadeIn();
            }else{
                $("#post_message").parent("div").fadeOut();
                $("#post_title").next("label").html("What's on your mind?");
                $("#post_description").parent("div").fadeOut();
            }
        });

        if( currentTimezone !== "" ){
            $("#timezone_setting").children("option").removeAttr("selected");
            $("#timezone_setting").children("option[value='"+currentTimezone+"']").attr("selected", "selected");
        }

    /**************** FUNCTIONS AFTER ALL JS HAS BEEN LOADED *****************************************************************/
        $(window).unbind("load").load(function (){
                
            $("#connect_to_facebook").click(function(){
                var $this = $(this);
                FB.login(function(response) {
                    if (response.authResponse) {
                        // Do all facebook stuffs here
                        $this.parent("div").addClass("mui-invisible");
                        fbUserId = response.authResponse.userID;
                        fbAccessToken = response.authResponse.accessToken;     
                        refreshWhereToPost(fbUserId);         
                    } else {
                        console.log('User cancelled login or did not fully authorize.');
                        $this.parent("div").removeClass("mui-invisible");   
                    }
                }, {scope: fbScope});
                return false;
            });

            allowNumericOnly( $('.number-light') );

            var j = setInterval(function (){

                $("input[name='optionsAlbum']").click(function(){
                    if( $(this).val() == "choose" ){
                        $("#div_create_new_album").addClass("mui-invisible").fadeOut(function(){
                            $("#div_choose_existing_album").removeClass("mui-invisible").fadeIn();
                        }); 
                    }else{
                        $("#div_choose_existing_album").addClass("mui-invisible").fadeOut(function(){
                            $("#div_create_new_album").removeClass("mui-invisible").fadeIn();
                        }); 
                    } 
                });


                if ($('canvas#image_preview').length){
                    clearInterval(j);
                    // safe to execute your code here

                    $("#font_color").ColorPicker({
                        color: '#fff',
                        onShow: function (colpkr) {
                            $(colpkr).fadeIn(500);
                            return false;
                        },
                        onHide: function (colpkr) {
                            $(colpkr).fadeOut(500);
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            $("#font_color").css('backgroundColor', '#' + hex).val('#'+hex);
                            checkCanvasStatus();
                            $("#image_preview").setLayer('top_text', {
                                fillStyle: '#'+hex,
                            }).setLayer('bottom_text', {
                                fillStyle: '#'+hex,
                            }).setLayer('bottom_watermark', {
                                fillStyle: '#'+hex,
                            }).drawLayers();

                            space_text();

                        }
                    });

                    $("#text_shadow").ColorPicker({
                        color: '#000',
                        onShow: function (colpkr) {
                            $(colpkr).fadeIn(500);
                            return false;
                        },
                        onHide: function (colpkr) {
                            $(colpkr).fadeOut(500);
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            $("#text_shadow").css('backgroundColor', '#' + hex).val('#'+hex);
                            checkCanvasStatus();
                            $("#image_preview").setLayer('top_text', {
                                strokeStyle: '#'+hex,
                            }).setLayer('bottom_text', {
                               strokeStyle: '#'+hex,
                            }).drawLayers();

                            space_text();
                        }
                    });

                    fitImage('#image_preview');
                }
            }, 100);

            $(".select2").select2();
        });


    
        $("#btn_img_search").click(function (){
            var $this = $(this);

            $(".library-images").fadeOut();
            $("#btn_select_library").removeAttr("disabled");
            $("#inner_results").fadeIn(function (){
                $(this).html('<div class="mui-text-center mui-text-subhead"><img src="'+imagesUrl+'ajax-loader.gif"> Please wait...</div>');
            });

            $.ajax({
                // url : wpSocialMageAjaxUrl,
                url: ajaxurl,
                type : 'post',
                dataType : 'json',
                data : { q : 'image_search', 'query' : $("#img_search").val(), action: 'WpSocialMageAjax' },
                beforeSend : function (){
                    $this.text("Searching...").attr("disabled", "disabled");
                }       
            }).done(function(data){

                $this.text("Image Search").removeAttr("disabled");
                $("#inner_results").html("");
                var x = 0;

                var result = '<div><h4 id="results_no">'+data.hits.length+' image search results for "'+$("#img_search").val()+'"</h4></div><div class="rd-gallery">';
                $.each(data.hits, function (i, row){

                    result+= '<div class="rd-gallery-item"><img height="132" width="132" src="'+
                            row.previewURL+'"/><a href="?page=wp-social-mage-dashboard&action=add_filters" data-src="'+row.webformatURL+'" class="select image-from-url">Use this image</a></div>';

                });
                result+= "</div>";
                $("#inner_results").append(result);
            });
        });


        $("#btn_select_library").click(function (){
            $(".library-images").fadeIn(function (){
                $("#btn_show_results").fadeIn();
            });
            $("#btn_select_library").attr("disabled", "disabled");
            $("#inner_results").fadeOut();
            
        });

        $("#btn_show_results").click(function (){
            $(".library-images").fadeOut();
            $("#btn_select_library").removeAttr("disabled", "disabled");
            $("#inner_results").fadeIn(function (){
                $("#btn_show_results").fadeOut();
            });
            
        });

        $(document).on("focus", ".rd-gallery-item", function (){
            var $this = $(this);

            if( !$this.is(":focus") && !$("div.context-menu").parent("td").parent("tr").parent("tbody")
                .parent("table").is(":focus") ){
                $(document).find("div.context-menu").parent("td").parent("tr").parent("tbody").parent("table").remove();
                $(document).find('div.context-menu-shadow').remove();
            }

            var menu = [{ 
                    'Use': function(menuItem, menu) { 
                        window.location = $this.children("a").attr("href");
                    }
                },
                $.contextMenu.separator, {
                    'Delete': function(menuItem, menu) { 
                        $.ajax({
                            // url: wpSocialMageAjaxUrl,
                            url: ajaxurl,
                            type: "post",
                            dataType: "json",
                            data: { q: 'delete_image', filename: $this.data("filename"), action: 'WpSocialMageAjax' }
                        }).done(function (data){
                            if( data.status == "success" ){
                                $this.fadeOut(function (){
                                    $this.remove();
                                    $("body").trigger("click");
                                });
                            }else{
                                alert(data.msg);
                            }
                        });
                } 
            }];

            $('.cmenu').contextMenu(menu,{theme:'vista'});
        });
        
        $(document).on("click", ".image-from-url", function (e){
            e.preventDefault();
            var $this = $(this);
            var src = $this.data("src"), url = $this.attr("href");

            $this.html("Please wait...").css("display", "block").removeClass("image-from-url").attr("href", "javascript:void(0)");

            $.ajax({
                // url: wpSocialMageAjaxUrl,
                url: ajaxurl,
                type: "post",
                dataType: "json",
                data: { q: 'grab_image_from_url', src: src, action: 'WpSocialMageAjax' }
            }).done(function (data){
                $this.html("Use this image").css("display", "none").addClass("image-from-url").attr("href", "javascript:void(0)");
                if( data.status == "success" ){
                    window.location = url+"&image="+data.filename;
                }else{
                    alert(data.msg);
                }
            });
        });

        $("body").on("click", ".rd-icon, .rd-icon-lg", function (){
        
            if( $(this).children("span").find("button")[0] === undefined ){
                $(this).children("span").find("a")[0].click();

            }else{
                $(this).children("span").find("button")[0].click();
            }
        });


        $("#download_image").click(function(){
            var filename = getUrlParameter('image');

            if( window.useRasterizeHtmlRenderer === true ){
                // let's change html2canvas to rasterizeHTML to improve text quality
                var canvas = document.getElementById("image_preview"),
                    context = canvas.getContext('2d');

                rasterizeHTML.drawHTML($(".image-holder").html()).then(function (renderResult) {
                    context.drawImage(renderResult.image, 10, 25);
                    var url = canvas.toDataURL();

                    var link2 = document.createElement('a');
                    link2.href = url;
                    link2.download = filename;
                    link2.click();
                });
            }else{
                html2canvas($('.image-holder'), {
                onrendered: function (canvas){
                    var url = canvas.toDataURL();

                    var link2 = document.createElement('a');
                    link2.href = url;
                    link2.download = filename;
                    link2.click();
                }
            });
            }

        });


        $("#img_search").keypress(function(e) {
            if(e.which == 13) {
                $("#btn_img_search").click();
            }
        });

        function fbEnsureInit(callback) {
            if(!window.fbApiInit) {
                setTimeout(function() {fbEnsureInit(callback);}, 50);
            } else {
                if(callback) {
                    callback();
                }
            }
        }

        function formFbSettingHasError(form){
            var hasError = false;
            if( form.find("input[name='fb_app_id']").val() === "" ){
                hasError = true;
                $.snackbar({content: "Please provide your App ID.", timeout: 4000});
            }

            if( form.find("input[name='fb_app_secret']").val() === "" ){
                hasError = true;
                $.snackbar({content: "Please provide your App Secret.", timeout: 4000});
            }

            if( hasError ){
                return true;
            }
            
            return false;
            
        }


        $("#form_settings").submit(function(e){
        // $("#save_settings").click(function (e){
            if( formFbSettingHasError($(this)) ){
                e.preventDefault();
                return false;
            }else{
                try{
                    $("#save_settings").html("Please wait...");
                    var form = $("#form_settings");
                    // fbAppId = form.find('input[name="fb_app_id"]').val().replace(/[^0-9]/g,''); // ensure that it's only numbers
                    var newfbAppId = form.find('input[name="fb_app_id"]').val().replace(/[^0-9]/g,''); // ensure that it's only numbers
                    
                    FB.init({ appId: newfbAppId, status: true, cookie: true, xfbml: true, oauth: true, channelUrl: channelUrl});

                    window.fbApiInit = true; //init flag
                    
                    
                    fbAppId = newfbAppId;

                    fbEnsureInit(function() {
                        try{
                            // this will be executed if FB is initialized
                            FB.login(function(response) {
                                console.log(response);
                                if (response.authResponse) {
                                    // Do all facebook stuffs here

                                    fbUserId = response.authResponse.userID;
                                    fbAccessToken = response.authResponse.accessToken;     
                                    refreshWhereToPost(fbUserId); 

                                    $.ajax({
                                        assync: false,
                                        // url: wpSocialMageAjaxUrl,
                                        url: ajaxurl,
                                        type: 'post',
                                        dataType: 'json',
                                        beforeSend: function (){
                                            console.log('generating longlive_token');
                                        },
                                        data: {
                                            action: 'WpSocialMageAjax',
                                            q: 'generate_longlive_token',
                                            fb_app_id: fbAppId, 
                                            fb_app_secret: form.find('input[name="fb_app_secret"]').val(),
                                            fb_user_id: fbUserId,
                                            fb_shortlive_auth_token: fbAccessToken,
                                        }
                                    }).done(function (response){
                                        if( response.status == '200' ){
                                            $.snackbar({ content: "Your facebook has been successfully connected.", timeout: 6000});
                                            $.ajax({
                                                // url: wpSocialMageAjaxUrl,
                                                url: ajaxurl,
                                                type: 'post',
                                                dataType: 'json',
                                                data: {
                                                    action: 'WpSocialMageAjax',
                                                    q: 'update_settings', 
                                                    fb_app_id: fbAppId, 
                                                    fb_app_secret: form.find('input[name="fb_app_secret"]').val(),
                                                    fb_user_id: fbUserId,
                                                    fb_auth_token: response.longLiveAccessToken,
                                                    timezone: $('#timezone_setting').val()
                                                }
                                            }).done(function(data){
                                                if( data.status_code == "200" ){
                                                    $("#timezone_setting").html($('#timezone_setting').val());
                                                    $.snackbar({ content: "Your Settings has been saved.", timeout: 4000});
                                                    $("#save_settings").html("Save & Connect to Facebook");
                                                    $(".rd-icon-home span a").click();
                                                }
                                            }).fail(function(data){
                                                console.log(data);
                                                $("#save_settings").html("Save & Connect to Facebook");
                                            });
                                        }else{
                                            if( response.msg ){
                                                $.snackbar({ content: response.msg, timeout: 4000 });
                                                $("#save_settings").html("Save & Connect to Facebook");
                                            }
                                            
                                        }
                                    }).fail(function(data){
                                        console.log(data);
                                        console.log('Error generating long live auth token');
                                        $("#save_settings").html("Save & Connect to Facebook");
                                    });

                                } else {
                                    console.log('User cancelled login or did not fully authorize.'); 
                                    $("#save_settings").html("Save & Connect to Facebook");
                                }
                            }, {scope: fbScope});
                        }catch(Exception){
                            console.log(Exception);
                            $("#save_settings").html("Save & Connect to Facebook");
                        }
                    });
                                        
                    
                    
                }catch(ErrorException){
                    console.log(ErrorException);
                    $("#save_settings").html("Save & Connect to Facebook");
                }
            }
        });

        

        $("#rebrand").click(function (){
            $.ajax({
                // url: wpSocialMageAjaxUrl,
                url: ajaxurl,
                type: 'post',
                dataType: 'json',
                data: { q: 'get_user_type', type: 'standard_wl,pro_wl', action: 'WpSocialMageAjax' },
                beforeSend: function (){
                    $(document).find("a#wl_login_link").remove();
                }
            }).done(function (data){
                console.log(data);
                if( data.type == "standard_wl" || data.type == "pro_wl" ){ 
                    window.location = 'http://topdogimsoftware.com/whitelabel-platform';
                }else{
                    activateModal('wl_upgrade');
                }
            });
        
        });

        // detect if some effects has been added to a current image
        $(document).on("change", ".FilterSetting input", function (){
            window.hasImageEffects = true;
        });

        $(document).on("click", "#PresetFilters", function (){
            window.hasImageEffects = true;
        });

        /* END JQUERY EVENTS */

    });

}(jQuery));