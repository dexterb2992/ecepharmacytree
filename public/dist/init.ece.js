jQuery(function($) {
    cache_list_of_products();
    init_products_list();
    init_specific_product_list( $(".products-multiple-select2") );

    cache_doctors();
    init_select2_items($("#doctor_ids"), window.select2_all_doctors);
});