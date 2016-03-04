var cache_products = function (callback){
    $.ajax({
        url: '/products-json',
        type: 'get',
        dataType: 'json',
        assync: false
    }).done(function (data){
        localStorage.clear();

        localStorage.products = JSON.stringify(data);
        window.products = data;
        window.select2_all_products = [];
        $.each(window.products, function (i, row){
            window.select2_all_products.push({id: row.id, text: row.name});
        });
        localStorage.select2_all_products = JSON.stringify(window.select2_all_products);

        if( typeof callback === 'function' && callback() ){
            callback();
        }
    });
};

var cache_list_of_products = function (){
	// for caching list of products to improve performance
    if( typeof(localStorage.select2_all_products) === 'undefined' ){
        cache_products();
    }

	if( typeof(localStorage.products) ===  'undefined' || (typeof(localStorage.select2_all_products) === 'undefined' ) ){
		cache_products();
	}else{
		window.products = JSON.parse(localStorage.products);
        window.select2_all_products = JSON.parse(localStorage.select2_all_products);

		// check current product count
		$.ajax({
			url: '/products-json',
			type: 'get',
			data: {action: 'get_count', cached_count: window.products.length},
			dataType: 'json'
		}).done(function (data){
			if(data.status == 200){
				// do nothing
			}else{
				// re cache the list
				localStorage.products = JSON.stringify(data);
		        window.products = data;
                window.select2_all_products = [];
                $.each(window.products, function (i, row){
                    window.select2_all_products.push({id: row.id, text: row.name});
                });
			}
		});
	}
};


var init_products_list = function (){
	// to improve select2 performance when handling large amount of data
    if( typeof(localStorage.select2_all_products) === 'undefined' ){
        cache_products('init_products_list');
    }else{
        var data = window.select2_all_products;

        // set initial value
        if( data.length > 0 ){
            $("#inventories_product_id").val(data[0].id).attr('data-packing', data[0].packing).attr('data-unit', data[0].unit).attr('data-qty-per-packing', data[0].qty_per_packing);
        }

        $("#inventories_product_id").select2({
            initSelection: function(element, callback) {
                var selection = _.find(data, function(metric){
                    return metric.id === element.val();
                });
                callback(selection);
            },
            query: function(options){
                var pageSize = 100;
                var startIndex  = (options.page - 1) * pageSize;
                var filteredData = data;

                if( options.term && options.term.length > 0 ){
                    if( !options.context ){
                        var term = options.term.toLowerCase();
                        options.context = data.filter( function(metric){
                            return ( metric.text.toLowerCase().indexOf(term) !== -1 );
                        });
                    }
                    filteredData = options.context;
                }

                options.callback({
                    context: filteredData,
                    results: filteredData.slice(startIndex, startIndex + pageSize),
                    more: (startIndex + pageSize) < filteredData.length
                });
            },
            placeholder: "Select a product"
        });

        $('#inventories_product_id').removeClass("select2-offscreen");
    }
};

var init_specific_product_list = function ($_element){
    
    // to improve select2 performance when handling large amount of data
    var shuffle = function(str) {
        return str.split('').sort(function() {
            return 0.5 - Math.random();
        }).join('');
    };

    // For demonstration purposes we first make
    // a huge array of demo data (20 000 items)
    // HEADS UP; for the _.map function i use underscore (actually lo-dash) here

    // create demo data
    var dummyData = window.select2_all_products;    

    // init select 2
    // $('#specific_promo_product_ids').select2({
    $_element.select2({
        data: dummyData,
        // init selected from elements value
        initSelection: function(element, callback) {
            var initialData = [];
            $(element.val().split(",")).each(function(i, row) {
                var res = dummyData.filter(function (n){
                    return n.id == row;
                });
                if( res.length == 1 ){
                    initialData.push({
                        id: res[0].id,
                        text: res[0].text
                    });
                }

            });
            callback(initialData);
        },
        // initSelection: initialData,

        // NOT NEEDED: These are just css for the demo data
        dropdownCssClass: 'capitalize',
        containerCssClass: 'capitalize',

        // configure as multiple select
        multiple: true,

        // NOT NEEDED: text for loading more results
        formatLoadMore: 'Loading more...',

        // query with pagination
        query: function(q) {
            var pageSize,
                results;
            pageSize = 20; // or whatever pagesize
            results = [];
            if (q.term && q.term !== "") {
                // HEADS UP; for the _.filter function i use underscore (actually lo-dash) here
                results = _.filter(this.data, function(e) {
                    return (e.text.toUpperCase().indexOf(q.term.toUpperCase()) >= 0);
                });
            } else if (q.term === "") {
                results = this.data;
            }
            q.callback({
                results: results.slice((q.page - 1) * pageSize, q.page * pageSize),
                more: results.length >= q.page * pageSize
            });
        }
    });
};


var find_product_object_from_cache = function(id){
    var result = window.products.filter(function (n){
        return n.id == id;
    });

    if( result.length == 1 ){
        return result;  // use result[0] when getting data
    }else if( result > 1 ){
        return result;
    }else if( result < 0 ){
        return false;
    }
};



