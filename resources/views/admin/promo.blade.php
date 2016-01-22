<?php 
use ECEPharmacyTree\DiscountsFreeProduct;
use Illuminate\Support\Str; 

?>
@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="tab-pane active" id="tab_promos">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">Promos</h3><br/>
                    <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-promo" data-target="#form_edit_promo" data-action="create" data-title="promo"><i class="fa-plus fa"></i> Add New</button>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered table-hover datatable">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Validity</th>
                                <th>Coupon Code</th>
                                <th>Applicability</th>
                                <th>Products</th>
                                <th>Min. Purchase Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($promos as $promo)
                                <tr data-id="{{ $promo->id }}">
                                    <td>{{ ucfirst($promo->long_title) }}</td>
                                    <td>
                                        <span class="label-primary label"><i class="fa-clock-o fa"></i> 
                                            {{ Carbon\Carbon::parse($promo->start_date)->format('M j, Y')." to ".
                                                Carbon\Carbon::parse($promo->end_date)->format('M j, Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        {!! $promo->offer_type == 'NO_CODE' ? '<div class="label label-default">N/A</div>' : $promo->generic_redemption_code !!}
                                    </td>
                                    <td>
                                        {!! $promo->product_applicability == 'SPECIFIC_PRODUCTS' ? 
                                            '<div class="label label-warning">Only Specific products</div>' : 
                                            '<div class="label label-success">Every Transaction</div>' !!}
                                    </td>
                                    <td>
                                        @if($promo->product_applicability == 'SPECIFIC_PRODUCTS')
                                            <?php $x = 0; ?>
                                            @if( isset($promo->discounts) && !empty($promo->discounts) )
                                                @foreach($promo->discounts as $discount)
                                                        @if($x < 2)
                                                        <span data-id="{{ $discount->id }}" data-toggle="tooltip" data-original-title="Click for more details" 
                                                            class="btn-success btn btn-xs promo-product-details add-edit-btn" data-action="edit"
                                                            data-modal-target="#modal-promo-product-info" data-target="#form_promo_product_info"
                                                            data-title="promo details for <a href='{{ route('Products::index').'?q='.$discount->product->name }}'>{{ $discount->product->name }}</a>">
                                                            {!! Str::limit($discount->product->name, 29) !!}
                                                        </span>
                                                        @else
                                                            <div id="more_{{ $discount->id }}" style="display:none;">
                                                                <span data-id="{{ $discount->id }}" data-toggle="tooltip" data-original-title="Click for more details"
                                                                    class="btn-success btn btn-xs promo-product-details add-edit-btn" data-action="edit" 
                                                                    data-modal-target="#modal-promo-product-info" data-target="#form_promo_product_info"
                                                                    data-title="promo details for <a href='{{ route('Products::index').'?q='.$discount->product->name }}'>{{ $discount->product->name }}</a>">
                                                                    {!! Str::limit($discount->product->name, 29) !!}
                                                                </span>
                                                            </div>
                                                            @if($x == count($promo->discounts)-1)
                                                                <span data-toggle="tooltip" data-target="#more_{{ $discount->id }}" data-original-title="Expand to show more products"
                                                                    class="btn btn-xs bg-purple show-hide-more-products" >
                                                                    <i class="fa-eye fa"></i>
                                                                </span>
                                                            @endif
                                                        @endif
                                                    <?php $x++; ?>
                                                @endforeach
                                            @endif
                                            {!! count($promo->discounts) < 1 ? '<span class="label label-default">No product selected</span>' : '' !!}
                                        @else
                                            <?php 
                                                $free_products = [];
                                            ?>
                                            <span class="label label-primary">All Products</span>
                                        @endif
                                    </td>
                                    <td>
                                        {!! $promo->product_applicability == 'SPECIFIC_PRODUCTS' ? 'N/A' : peso().' '.to_money($promo->minimum_purchase_amount, 2) !!}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <span class="btn btn-danger btn-xs action-icon remove-promo"   data-toggle="tooltip" data-original-title="Remove"
                                                data-action="remove" data-title="promo" data-urlmain="/promos/" data-id="{{ $promo->id }}"><i class="fa fa-trash-o"></i></span>
                                            <a href="javascript:void(0);" class="btn btn-info btn-xs add-edit-btn"  data-toggle="tooltip" data-original-title="Edit"
                                                data-action="edit" data-modal-target="#modal-add-edit-promo" data-title="promo info" data-target="#form_edit_promo" data-id="{{ $promo->id }}" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->

           
        </div>


        <!-- Modal for Create/Edit promo -->
        <div class="modal" id="modal-add-edit-promo">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- form start -->
                    <form role="form" id="form_edit_promo" data-mode="create" method="post" action="/promos/create" data-urlmain="/promos/">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add new promo</h4>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                            	<label>Promo title <red>*</red></label>
                            	<input class="form-control" type="text" name="long_title" required>
                            </div>
                            <div class="form-group">
								<label>Validity<red>*</red><small><i>(Date range on which this promo is valid)</i></small></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control pull-right daterange" id="date_range" name="date_range" required/>
									<input type="hidden" name="start_date">
									<input type="hidden" name="end_date">
								</div><!-- /.input group -->
							</div><!-- /.form group -->

                            <div class="form-group">
                                <label>Offer Type<red>*</red> (<small><i>This attribute indicates whether or not a coupon code is required for users to redeem the offer.</i></small>)</label>
                                <select class="form-control" name="offer_type" data-show-target="#redemption_code_outer_div" data-show-target-when="GENERIC_CODE">
                                    <option value="NO_CODE">NO CODE</option>
                                    <option value="GENERIC_CODE">GENERIC CODE</option>
                                </select>
                            </div>

                            <div class="form-group" style="display:none;" id="redemption_code_outer_div">
                                <label>Generic Redemption Code (<small><i>The text code that customers can use online to redeem the promotion.</i></small>)</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa-tag fa"></i>
                                    </div>
                                    <input class="form-control" name="generic_redemption_code" />
                                    <div class="input-group-btn">
                                        <span class="btn btn-default btn-flat btn-gencode">
                                            <i class="fa-random fa"></i> Generate Code
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label id="label_for_product_applicability">Product Applicability<red>*</red>(<small><i>Whether the promotion is applicable to per transaction or only specific products.</i></small>)</label>
                                <div class="label-danger label"></div><br/>

                                
                                <label for="product_applicability">
                                    <input type="radio" name="product_applicability" value="PER_TRANSACTION" 
                                        data-check-value="PER_TRANSACTION" data-uncheck-value="SPECIFIC_PRODUCTS" class="form-control icheck data-show" 
                                        data-show-target="#per_transaction_outer_div" data-show-target-when="PER_TRANSACTION" />
                                    Per Transaction
                                </label>

                                
                                <label for="product_applicability">
                                    <input type="radio" name="product_applicability" value="SPECIFIC_PRODUCTS" 
                                    data-check-value="SPECIFIC_PRODUCTS" data-uncheck-value="PER_TRANSACTION" class="form-control icheck data-show" 
                                    data-show-target="#specific_products_outer_div" data-show-target-when="SPECIFIC_PRODUCTS"/>
                                    Specific Products
                                </label>

                            </div>

                            <!-- the container for a Specific Products Promo -->
                                <div class="form-group" style="display:none;" id="specific_products_outer_div">
                                    <label>Products<red>*</red>(<small><i>Select those products that will be applied with this promo.</i></small>)</label>
                                    <select class="form-control select2" id="specific_promo_product_ids" name="product_id[]" multiple>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            <!-- the container for a Per Transaction Promo -->
                                <div id="per_transaction_outer_div" style="display:none;">
                                    <div class="form-group">
                                        <label>Minimum Purchase Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                {{ peso() }}
                                            </div>
                                            <input type="text" class="form-control number" name="minimum_purchase_amount">
                                        </div>
                                    </div>  

                                    <div class="form-group">
                                        <label id="per_transaction_promo_offers">Offers: </label><div class="label-danger label"></div>
                                        <div class="form-group">
                                            
                                            <label for="per_transaction_has_free_gifts">
                                                <input type="checkbox" name="per_transaction_has_free_gifts" id="per_transaction_has_free_gifts" value="1" 
                                                data-check-value="1" data-uncheck-value="0" class="form-control icheck data-show" 
                                                data-show-target="#per_transaction_gift_products_outer_div" data-show-target-when="1"/>
                                                Has Free Gifts
                                            </label>

                                            <label for="per_transaction_has_free_gifts">
                                                <input type="checkbox" name="is_free_delivery" id="is_free_delivery" value="1" 
                                                data-check-value="1" data-uncheck-value="0" class="form-control icheck data-show" />
                                                Free Delivery
                                            </label>
                                        </div>

                                        <div class="form-group" style="display:none;" id="per_transaction_gift_products_outer_div">
                                            <label>Select product/s to use as free gift</label>
                                            <select class="form-control select2" id="promo_details_per_transaction_gifts" name="per_transaction_product_id[]" multiple>
                                                @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>

                                            <div class="per-transaction-selected-products-qty-div form-horizontal"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Discount Type </label><br/>

                                        <label for="discount_type">
                                            <input type="radio" name="discount_type" value="peso_discount" 
                                                data-check-value="peso_discount" data-uncheck-value="percentage_discount" class="form-control icheck data-show" 
                                                data-show-target="#peso_discount_outer_div" data-show-target-when="peso_discount" />
                                            Peso-based Discount
                                        </label>

                                        
                                        <label for="discount_type">
                                            <input type="radio" name="discount_type" value="percentage_discount" 
                                            data-check-value="percentage_discount" data-uncheck-value="peso_discount" class="form-control icheck data-show" 
                                            data-show-target="#percentage_discount_outer_div" data-show-target-when="percentage_discount" />
                                            Percentage-based Discount
                                        </label>
                                    </div>

                                    <div class="form-group" id="peso_discount_outer_div" style="display:none;">
                                        <label>Peso-based Discount</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">{!! peso() !!}</span>
                                            <input class="form-control number" name="peso_discount" id="peso_discount" placeholder="(Optional)" />
                                        </div>
                                    </div>

                                    <div class="form-group" style="display:none;" id="percentage_discount_outer_div">
                                        <label>Percentage-based Discount</label>
                                        <div class="input-group">
                                            <input class="form-control number" name="percentage_discount" id="percentage_discount" placeholder="(Optional)"/>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" name="submit" id="btn_create_edit_promo">Save changes</button>
                        </div>
                    </form><!-- /form -->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Modal for Promo's individual product -->
        <div class="modal" id="modal-promo-product-info">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" id="form_promo_product_info" data-urlmain="/promos/details/">
                        {!! Form::token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Promo details for <a href="javascript:void(0);" target="_blank" id="modal_product_name">{Product name}</a></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Choose a minimum type </label><br/>

                                <label for="discount_detail_minimum_type">
                                    <input type="radio" name="discount_detail_minimum_type" value="quantity_required" 
                                        data-check-value="quantity_required" data-uncheck-value="minimum_purchase" class="form-control icheck data-show" 
                                        data-show-target="#discount_detail_minimum_quantity_div" data-show-target-when="quantity_required" />
                                    Minimum Quantity
                                </label>

                                
                                <label for="discount_detail_minimum_type">
                                    <input type="radio" name="discount_detail_minimum_type" value="minimum_purchase" 
                                    data-check-value="minimum_purchase" data-uncheck-value="quantity_required" class="form-control icheck data-show" 
                                    data-show-target="#discount_detail_minimum_purchase_div" data-show-target-when="minimum_purchase" />
                                    Minimum Purchase Amount
                                </label>
                            </div>

                            <div class="form-group" id="discount_detail_minimum_quantity_div" style="display:none;">
                                <label>Minimum Quantity</label>
                                <input type="text" class="form-control number" name="quantity_required">
                            </div>

                            <div class="form-group"  id="discount_detail_minimum_purchase_div" style="display:none;">
                                <label>Minimum Purchase Amount</label>
                                <input type="text" class="form-control number" name="minimum_purchase">
                            </div>

                            <div class="form-group">
                                <label id="specific_product_offers">Offers</label><div class="label-danger label"></div>
                            </div>

                            <div class="form-group">
                                <label for="has_free_gifts">
                                    <input type="checkbox" name="has_free_gifts" id="has_free_gifts" value="1" 
                                        data-check-value="1" data-uncheck-value="0" class="form-control icheck data-show" 
                                        data-show-target="#gift_products_outer_div" data-show-target-when="1"/>
                                    Has Free Gifts
                                </label>
                            </div>

                            <div class="form-group" style="display:none;" id="gift_products_outer_div">
                                <label>Select product/s to use as free gift</label>
                                <select class="form-control select2" id="promo_details_gifts" name="product_id[]" multiple>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>

                                <div class="selected-products-qty-div form-horizontal"></div>
                            </div>

                            <div class="form-group">
                                <label>Discount Type </label><br/>

                                <label for="discount_type">
                                    <input type="radio" name="discount_detail_discount_type" value="peso_discount" 
                                        data-check-value="peso_discount" data-uncheck-value="percentage_discount" class="form-control icheck data-show" 
                                        data-show-target="#discount_detail_peso_discount_div" data-show-target-when="peso_discount" />
                                    Peso-based Discount
                                </label>

                                
                                <label for="discount_type">
                                    <input type="radio" name="discount_detail_discount_type" value="percentage_discount" 
                                    data-check-value="percentage_discount" data-uncheck-value="peso_discount" class="form-control icheck data-show" 
                                    data-show-target="#discount_detail_percentage_discount_div" data-show-target-when="percentage_discount" />
                                    Percentage-based Discount
                                </label>
                            </div>

                            <div class="form-group" id="discount_detail_peso_discount_div" style="display:none;">
                                <label>Peso-based Discount</label>
                                <div class="input-group">
                                    <span class="input-group-addon">{!! peso() !!}</span>
                                    <input class="form-control number" name="peso_discount" id="peso_discount" />
                                </div>
                            </div>

                            <div class="form-group" id="discount_detail_percentage_discount_div" style="display:none;">
                                <label>Percentage-based Discount</label>
                                <div class="input-group">
                                    <input class="form-control number" name="percentage_discount" id="percentage_discount" />
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>For every minimum quantity/purchase, this promo will be applied.</label><br/>
                                <label for="is_every">
                                    <input type="radio" name="is_every" value="0" 
                                        data-check-value="1" data-uncheck-value="0" class="form-control icheck" />
                                    Yes
                                </label>

                                
                                <label for="is_every">
                                    <input type="radio" name="is_every" value="0" 
                                    data-check-value="0" data-uncheck-value="1" class="form-control icheck" checked />
                                    No, apply just once.
                                </label>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="submit">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

