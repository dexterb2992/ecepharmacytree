<?php use Carbon\Carbon; ?>
@extends('admin.layouts.template')
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<div class="box-header">
					<h3>Inventory Management</h3> <br/>
	                <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-inventory" data-target="#form_edit_inventory" data-action="create" data-title="inventory"><i class="fa-plus fa"></i> Add New</button>
				</div>
				<div class="box-body">
					<table class="table table-bordered table-hover datatable">
						<thead>
							<tr>
								<th>SKU</th>
								<th>Product name</th>
								<th>Quantity</th>
								<th>Stock Expiration</th>
								<th>Date Added</th>
							</tr>
						</thead>
						<tbody>
							@foreach($inventories as $inventory)
								<tr>
									<td>
										<span> {{ $inventory->product->sku }}</span>
									</td>
									<td>{{ $inventory->product->name }}</td>
									<td>
										<?php $total = $inventory->quantity * $inventory->product->qty_per_packing; ?>
										{!! $total." ".str_auto_plural($inventory->product->unit, $total)." "
											."( ".$inventory->quantity." ".str_auto_plural($inventory->product->packing, $inventory->quantity)." )" !!}
									</td>
									<td>
										{{ Carbon::parse($inventory->expiration_date)->diffForHumans() }}
									</td>
									<td>
										<span>{{ Carbon::parse($inventory->created_at)->diffForHumans() }}</span>
										<span class="action-icon remove-product pull-right" data-action="remove" data-title="inventory" data-urlmain="/inventory/" data-id="{{ $inventory->id }}" title="Remove"><i class="fa fa-trash-o"></i></span>
										<a href="javascript:void(0);" class="add-edit-btn pull-right" data-action="edit" data-modal-target="#modal-add-edit-inventory" data-title="inventory" data-target="#form_edit_inventory" data-id="{{ $inventory->id }}" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>

			<!-- Modal for Create/Edit Branch -->
	        <div class="modal" id="modal-add-edit-inventory">
	            <div class="modal-dialog">
	                <div class="modal-content">
	                    <!-- form start -->
	                    <form role="form" id="form_edit_inventory" data-mode="create" method="post" action="/inventory/create" data-urlmain="/inventory/">
	                        <div class="modal-header">
	                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                            <h4 class="modal-title">Add new inventory</h4>
	                        </div>
	                        <div class="modal-body">
	                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
	                            <div class="form-group">
	                            	<label for="product_id">Product</label>
	                            	<select class="form-control" name="product_id" id="inventories_product_id">
	                            		@foreach($products as $product)
	                            			<option value="{{ $product->id }}" data-packing="{{ $product->packing }}" data-unit="{{ $product->unit }}" data-qty-per-packing="{{ $product->qty_per_packing }}">{{ $product->name }}</option>
	                            		@endforeach
	                            	</select>
	                            </div>
	                            <div class="form-group">
	                            	<label for="quantity" title="Add quantity by product's packing">Quantity (<i>By Packing</i>)</label>
	                            	<div class="input-group">
		                            	<input type="text" id="inventory_quantity" name="quantity" class="number form-control" title="Add quantity by product's packing" required>
		                            	<div class="input-group-addon">
		                            		<span class="add-on-product-packing" name="packing">{{ head( $products->toArray() )["packing"] }}</span>
		                            	</div>
	                            	</div>
	                            	<span id="total_quantity_in_unit"></span>
	                            </div>
	                            <div class="form-group">
	                            	<label for="expiration">Expiration Date</label>
	                            	<div class="input-group">
	                            		<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" name="expiration_date" class="form-control datemask3" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required/>
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