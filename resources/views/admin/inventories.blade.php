<?php 
	use Carbon\Carbon; 
	$is_critical = false;
?>

@extends('admin.layouts.template')

@section('content')

	<div class="row">
		<div class="col-xs-12">
			<div class="nav-tabs-custom">
				<ul class="nav-tabs nav">
					<li class="active">
						<a href="#tab_inventory" data-toggle="tab">Stocks</a>
					</li>
					<li>
						<a href="#tab_logs" data-toggle="tab">Logs</a>
					</li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane fade in active" id="tab_inventory">
						<div class="box box-success">
							<div class="box-header">
								<h4>Stock Items List</h4> <br/>
				                <button class="btn-info btn pull-right add-edit-btn" data-toggle="modal" data-target="#modal-add-edit-inventory" 
				                	data-target="#form_edit_inventory" data-action="create" data-title="inventory">
				                	<i class="fa-plus fa"></i> Add New
				                </button>

				                <button class="btn-success btn pull-right btn-stock-return" data-target="#modal-stock-return" data-toggle="modal">
				                	<i class="fa-refresh fa"></i> Stock Return
				                </button>
							</div>
							<div class="box-body">
								<table class="table table-bordered table-hover datatable">
									<thead>
										<tr>
											<th>Lot #</th>
											<th>SKU</th>
											<th>Product name</th>
											<th>Available Quantity</th>
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
													{{ $inventory->lot_number }}
												</td>
												<td>
													<?php $is_critical = $inventory->quantity <= $recent_settings->critical_stock ? true : false; ?>
													{!! $inventory->quantity <= $recent_settings->critical_stock ? 
														'<i class="fa-warning fa" style="color:#dd4b39;" data-toggle="tooltip" title="" data-original-title="Critical Stock" title="Critical Stock"></i>' 
														: '' 
													!!}
													<span> {{ $inventory->product->sku }}</span>
												</td>
												<td>
													<a href="{{ route('Products::index').'?q='.$inventory->product->name }}" target="_blank" class="show-product-info" data-id="{{ $inventory->product->id }}">
														{{ $inventory->product->name }}
													</a>
												</td>
												<td>
													<?php 
														$total = $inventory->available_quantity * $inventory->product->qty_per_packing; 
													?>
													{!! '<b>'.$inventory->available_quantity." ".str_auto_plural($inventory->product->packing, $inventory->available_quantity)."</b> "
														."( ".$total." ".str_auto_plural($inventory->product->unit, $total)." )" !!}
												
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
															'<span class="btn-xs btn-primary btn action-icon pull-right" title="Restock" data-action="restock" data-pid="{{ $inventory->product->id }}">
															<i class="fa-refresh fa"></i></span>' 
															: '' 
														!!}
														<span class="btn btn-danger btn-xs action-icon remove-product pull-right" data-action="remove" data-title="inventory" data-urlmain="/inventory/"
															 data-id="{{ $inventory->id }}" title="Remove" data-toggle="tooltip" data-original-title="Remove">
															 <i class="fa fa-trash-o"></i>
														</span>
														<span class="btn-warning btn btn-xs pull-right btn-adjustment" data-id="{{ $inventory->id }}" data-toggle="modal" data-target="#modal-add-adjustments">
															<i class="glyphicon glyphicon-list-alt" data-toggle="tooltip" data-original-title="Stock Adjustment"></i>
														</span>
														<span class="btn btn-info btn-xs add-edit-btn pull-right" data-action="edit" data-modal-target="#modal-add-edit-inventory" 
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
					</div>
					<div class="tab-pane fade" id="tab_logs">
						<div class="box box-success">
							<div class="box-header">
								<h4>Stocks Activity Logs</h4> <br/>
							</div>
							<div class="box-body">
								<table class="table table-bordered table-hover datatable" id="tbl_inventory_logs">
									<thead>
										<tr>
											<th>User</th>
											<th>Action</th>
											<th>Date</th>
										</tr>
									</thead>
									<tbody>
										@foreach($logs as $log)
											<tr>
												<td>{{ get_person_fullname($log->user) }}</td>
												<td>{!! $log->action !!}</td>
												<td>
													<?php $date_added = Carbon::parse($log->created_at); ?>
													<span class="label label-primary" data-toggle="tooltip" data-original-title="{{ $date_added->formatLocalized('%A %d %B %Y') }}">
														<i class="fa-clock-o fa"></i> 
														{{ $date_added->diffForHumans() }}
													</span>
												</td>
											</tr>
										@endforeach	
									</tbody>
								</table>
							</div>
						</div>
					</div>
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
	                            	<label for="quantity" title="Add quantity by product's packing">Quantity Received 
	                            		<small>(<i>per <span id="outer_packing">{{ head( $products->toArray() )["packing"] }}</span></i>)</small>
	                            	</label>
	                            	<div class="input-group">
		                            	<input type="text" id="inventory_quantity" name="quantity" class="number form-control" placeholder="Add quantity by product's packing" title="Add quantity by product's packing" required>
		                            	<div class="input-group-addon">
		                            		<span class="add-on-product-packing" name="packing">{{ head( $products->toArray() )["packing"] }}</span>
		                            	</div>
	                            	</div>
	                            	<span id="total_quantity_in_unit"></span>
	                            </div>
	                            <div class="form-group">
	                            	<label for="expiration">Expiration Date <small><i>(Leave empty if this stock doesn't have an expiration)</i></small></label>
	                            	<div class="input-group">
	                            		<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" name="expiration_date" class="form-control datemask3" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask/>
		                            </div>
	                            </div>
	                        </div>
	                        <div class="modal-footer">
	                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
	                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
	                        </div>
	                    </form><!-- /form -->
                    </div><!-- /.modal-content -->
	            </div><!-- /.modal-dialog -->
	        </div><!-- /.modal -->

	        <!-- Modal for Stock Adjustments -->
	        <div class="modal" id="modal-add-adjustments">
	        	<div class="modal-dialog">
	        		<div class="modal-content">
	        			<form method="post" action="/inventory/adjustment">
	        			{!! Form::open(['method' => 'post', 'action' => 'InventoryController@add_adjustments']) !!}
		        			<div class="modal-header">
		        				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                            <h4 class="modal-title">
	                            	Add adjustments
	                            	<h5 id="inventory_adjustment_details"></h5>
	                            </h4>

		        			</div>
		        			<div class="modal-body">
		        				{!! Form::token() !!}
		        				<input type="hidden" id="sid" name="id">
		        				<div class="form-group">
	                            	<label for="old_quantity">Old Quantity Received</label>
	                            	<input class="form-control number" type="text" name="old_quantity" id="old_quantity" value="" readonly>
	                            </div>
	                            <div class="form-group">
	                            	<label for="new_quantity">New Quantity Received</label>
	                            	<input class="form-control number" type="text" name="new_quantity" id="new_quantity">
	                            </div>
	                            <div class="form-group">
	                            	<label for="new_quantity">Reason</label>
	                            	<textarea class="form-control" name="reason"></textarea>
	                            </div>
		        			</div>
		        			<div class="modal-footer">
	                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
	                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
		        			</div>
	        			{!! Form::close() !!}
	        		</div>	
	        	</div>
	        </div>

	        <!-- Modal for Stock Return -->
	        <div class="modal" id="modal-stock-return">
	        	<div class="modal-dialog">
	        		<div class="modal-content">
	        			{!! Form::open(['action' => 'StockReturnController@store', 'method' => 'post']) !!}
		        			<div class="modal-header">
		        				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        				<h4>STOCK RETURNS / EXCHANGE FORM</h4>
		        			</div>
		        			<div class="modal-body">
		        				<div class="form-group">
		        					<label>Order No.</label>
									<select class="form-control select2" name="order_id" id="order_id"></select>	        					
		        				</div>

		        				<div class="oder-details">
		        					<legend class="legend-15">Order Details</legend>
		        					
		        					<dl class="dl-vertical">
		        						<dt>Customer: </dt>
		        						<dd id="customer_name"></dd>
		        					</dl>

		        					<dl class="form-group">
		        						<dt>Products: </dt>
		        						<dd id="product_name"></dd>
		        					</dl>

		        					<dl class="dl-vertical">
		        						<dt>Total Amount</dt>
		        						<dd id="total_amount"></dd>
		        					</dl>
		        				</div><hr/>

		        				<div class="form-group">
		        					<label>Product to return</label>
		        					<select class="form-control" name="return_product_id" id="return_product_id"></select>
		        				</div>

		        				<div class="form-group">
		        					<label>Return quantity</label>
		        					<input type="text" name="return_quantity" class="form-control number" id="return_quantity">
		        				</div>

		        				<div class="form-group">
		        					<label>Reason</label>
		        					<select class="form-control select2" name="return_code" id="return_code"></select>
		        				</div>

		        				<div class="form-group">
		        					<label>Brief Explanation</label>
		        					<textarea class="form-control" name="brief_explanation"></textarea>
		        				</div>

		        				<div class="form-group">
		        					<label>Action</label>
		        					<div class="stock-return-actions">
		        						<label>
		        							<input type="radio" name="action" class="icheck" data-check-value="refund" value="refund" checked> Refund 
		        						</label>
			        					<label>
			        						<input type="radio" name="action" class="icheck" data-check-value="exchange"> Exchange 
			        					</label>
		        					</div>

		        				</div>

		        				<div id="exchange_product_list" class="form-group" style="display:none">
		        					<label>Select a product for exchange</label>
		        					<select class="form-control select2" name="exchange_product_id" id="exchange_product_id"></select>
		        				</div>
			        		</div>
			        		<div class="modal-footer">
			        			<button type="submit" class="btn btn-primary btn-flat" name="submit">Submit</button>
			        		</div>
			        	{!! Form::close() !!}
	        		</div>
	        	</div>
	        </div>

		</div><!-- /.col -->
	</div><!-- /.row -->
@stop