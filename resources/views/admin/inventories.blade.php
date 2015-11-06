<?php 
	use Carbon\Carbon; 
	$is_critical = false;
?>

@extends('admin.layouts.template')

@section('content')

	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<div class="box-header">
					<h4>Stock Items List</h4> <br/>
	                <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-inventory" data-target="#form_edit_inventory" data-action="create" data-title="inventory"><i class="fa-plus fa"></i> Add New</button>
				</div>
				<div class="box-body">
					<table class="table table-bordered table-hover datatable">
						<thead>
							<tr>
								<th>SKU</th>
								<th>Product name</th>
								<th>Quantity</th>
								<th>Lot #</th>
								<th>Stock Expiration</th>
								<th>Date Added</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($inventories as $inventory)
								@if(!is_null($inventory->product))
								<tr data-pid="{{ $inventory->product_id }}" data-id="{{ $inventory->id }}">
									<td>
										<?php $is_critical = $inventory->quantity <= $recent_settings->critical_stock ? true : false; ?>
										{!! $inventory->quantity <= $recent_settings->critical_stock ? 
											'<i class="fa-warning fa" style="color:#dd4b39;" data-toggle="tooltip" title="" data-original-title="Critical Stock" title="Critical Stock"></i>' 
											: '' 
										!!}
										<span> {{ $inventory->product->sku }}</span>
									</td>
									<td>
										<a href="javascript:void(0);" class="show-product-info" data-id="{{ $inventory->product->id }}">
											{{ $inventory->product->name }}
										</a>
									</td>
									<td>
										<?php 
											$total = $inventory->quantity * $inventory->product->qty_per_packing; 
										?>
										{!! '<b>'.$inventory->quantity." ".str_auto_plural($inventory->product->packing, $inventory->quantity)."</b> "
											."( ".$total." ".str_auto_plural($inventory->product->unit, $total)." )" !!}
									
									</td>
									<td>
										{{ $inventory->lot_number }}
									</td>
									<td>
										<?php $expiration = Carbon::parse($inventory->expiration_date); ?>
										<span class="label label-success" data-toggle="tooltip" data-original-title="{{ $expiration->formatLocalized('%A %d %B %Y') }}">
											<i class="fa-clock-o fa"></i> 
											{{ $expiration->diffForHumans() }}
										</span>
									</td>
									<td>
										<?php $date_added = Carbon::parse($inventory->created_at); ?>
										<span class="label label-primary" data-toggle="tooltip" data-original-title="{{ $date_added->formatLocalized('%A %d %B %Y') }}">
											<i class="fa-clock-o fa"></i> 
											{{ $date_added->diffForHumans() }}
										</span>
									</td>
									<td>
										<div class="btn-group pull-right">
											{!! $is_critical ? 
												'<span class="btn-sm btn-default btn action-icon pull-right" title="Restock" data-action="restock" data-pid="{{ $inventory->product->id }}">
												<i class="fa-refresh fa"></i></span>' 
												: '' 
											!!}
											<span class="btn btn-default btn-sm action-icon remove-product pull-right" data-action="remove" data-title="inventory" data-urlmain="/inventory/"
												 data-id="{{ $inventory->id }}" title="Remove" data-toggle="tooltip" data-original-title="Remove">
												 <i class="fa fa-trash-o"></i>
											</span>
											<span class="btn-default btn btn-sm action-icon pull-right" data-action="adjust-stock" title="Stock Adjustment" data-title="Stock Adjustment" data-urlmain="/inventory/"
												data-id="{{ $inventory->id }}" data-toggle="tooltip" data-original-title="Stock Adjustment">
												<i class="glyphicon glyphicon-list-alt"></i>
											</span>
											<span class="btn btn-default btn-sm add-edit-btn pull-right" data-action="edit" data-modal-target="#modal-add-edit-inventory" 
												data-title="inventory" data-target="#form_edit_inventory" data-id="{{ $inventory->id }}" title="Edit" data-toggle="tooltip" data-original-title="Edit">
	                                            <i class="fa fa-edit"></i>
	                                        </span>
					                    </div>
									</td>
								</tr>
								@endif
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
	                            	<label for="quantity" title="Add quantity by product's packing">Quantity (<i>per <span id="outer_packing">{{ head( $products->toArray() )["packing"] }}</span></i>)</label>
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
	                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
	                        </div>
	                    </div><!-- /.modal-content -->
	                </form><!-- /form -->
	            </div><!-- /.modal-dialog -->
	        </div><!-- /.modal -->
		</div><!-- /.col -->
	</div><!-- /.row -->
@stop