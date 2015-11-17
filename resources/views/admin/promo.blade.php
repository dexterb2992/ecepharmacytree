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
                                <th>Validitiy</th>
                                <th>Applicability</th>
                                <th>Producs</th>
                                <th>Offer Type</th>
                                <th>Min. Purchase Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($promos as $promo)
                                <tr data-id="{{ $promo->id }}">
                                    <td>{{ ucfirst($promo->name) }}</td>
                                    <td>
                                        <span class="label-primary label"><i class="fa-clock-o fa"></i> 
                                            {{ Carbon\Carbon::parse($promo->start_date)->format('F d, Y')." to ".
                                                Carbon\Carbon::parse($promo->end_date)->format('F d, Y') }}
                                        </span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
                            	<input class="form-control" type="text" name="name" required>
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
                                <select class="form-control" name="product_applicability" required>
                                    <option value="ALL_PRODUCTS">All Products</option>
                                    <option value="SPECIFIC_PRODUCTS">Specific Products</option>
                                </select>
                            </div>

                            <div class="form-group" style="display:none;">
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
                                <select class="form-control" name="offer_type">
                                    <option value="NO_CODE">NO CODE</option>
                                    <option value="GENERIC_CODE">GENERIC CODE</option>
                                </select>
                            </div>
                            
                            <div class="form-group" style="display:none;">
                                <label>Generic Redemption Code (<small><i>The text code that customers can use online to redeem the promotion.</i></small>)</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa-tag fa"></i>
                                    </div>
                                    <input class="form-control" name="generic_redemption_code" />
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

    </div><!-- /.col -->
</div><!-- /.row -->
@stop

