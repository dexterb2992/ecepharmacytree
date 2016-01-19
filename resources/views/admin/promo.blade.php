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
                                <th>Offer Type</th>
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
                                        <span data-toggle="tooltip" data-original-title="{!! clean($promo->offer_type) !!}">
                                            <i class="fa fa-circle text-{{$promo->offer_type == 'NO_CODE' ? 'orange' : 'olive'}}"></i>
                                        </span>
                                    </td>
                                    <td>
                                        <span data-toggle="tooltip" data-original-title="{!! clean($promo->product_applicability) !!}">
                                            <i class="fa fa-circle text-{{$promo->product_applicability == 'SPECIFIC_PRODUCTS' ? 'yellow' : 'green'}}"></i>
                                        </span>
                                    </td>
                                    <td>
                                        @if($promo->product_applicability == 'SPECIFIC_PRODUCTS')
                                            <?php $x = 0; ?>
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
                                            {!! count($promo->discounts) < 1 ? '<span class="label label-default">No product selected</span>' : '' !!}
                                        @else
                                            <span class="label label-primary">All Products</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ peso().' '.to_money($promo->minimum_purchase_amount, 2) }}
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
									<input type="text" class="form-control pull-right daterange" id="date_range" required/>
									<input type="hidden" name="start_date">
									<input type="hidden" name="end_date">
								</div><!-- /.input group -->
							</div><!-- /.form group -->

                            <div class="form-group">
                                <label>Product Applicability<red>*</red>(<small><i>Whether the promotion is applicable to all products or only specific products.</i></small>)</label>
                                <select class="form-control" name="product_applicability" data-show-target="#specific_products_outer_div" data-show-target-when="SPECIFIC_PRODUCTS" required>
                                    <option value="ALL_PRODUCTS">All Products</option>
                                    <option value="SPECIFIC_PRODUCTS">Specific Products</option>
                                </select>
                            </div>

                            <div class="form-group" style="display:none;" id="specific_products_outer_div">
                                <label>Products<red>*</red>(<small><i>Select those products that will be applied with this promo.</i></small>)</label>
                                <select class="form-control select2" name="product_id[]" multiple>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>

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

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="submit">Save changes</button>
                        </div>
                    </div><!-- /.modal-content -->
                </form><!-- /form -->
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
                                <label>Minimum Quantity</label>
                                <input type="text" class="form-control number" name="quantity_required">
                            </div>

                            <div class="form-group">
                                <label>Offers</label>
                            </div>

                            <div class="form-group">
                                <input type="checkbox" name="has_free_gifts" id="has_free_gifts" value="1" 
                                    data-check-value="1" data-uncheck-value="0" class="form-control icheck data-show" 
                                    data-show-target="#gift_products_outer_div" data-show-target-when="1"/>
                                <label for="has_free_gifts">Has Free Gifts</label>
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
                                <label>Peso-based Discount</label>
                                <div class="input-group">
                                    <span class="input-group-addon">{!! peso() !!}</span>
                                    <input class="form-control number" name="peso_discount" id="peso_discount" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Percentage-based Discount</label>
                                <div class="input-group">
                                    <input class="form-control number" name="percentage_discount" id="percentage_discount" />
                                    <span class="input-group-addon">%</span>
                                </div>
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

