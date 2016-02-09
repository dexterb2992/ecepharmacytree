<?php 
	use Carbon\Carbon; 
	use Illuminate\Support\Str; 
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
						<a href="#tab_stock_returns" data-toggle="tab">Stock Returns </a>
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

								<div class="pull-right">
					                <button class="btn-info btn add-edit-btn glow" data-toggle="modal" data-modal-target="#modal-add-edit-inventory" 
					                	data-action="create" data-title="inventory">
					                	<i class="fa-plus fa"></i> Add New
					                </button>
					                <button class="btn-success btn  btn-stock-return glow" data-target="#modal-stock-return" data-toggle="modal">
					                	<i class="fa-refresh fa"></i> Stock Return
					                </button>
								</div>

							</div>
							<div class="box-body">
								@if( Route::is('Inventory::all') )
	                                <small class="">
	                                    <a href="{{ route('Inventory::index') }}">Hide Out-of-Stock Inventories</a>
	                                </small>
	                            @else
	                                <small class="">
	                                    <a href="{{ route('Inventory::all') }}">Show Out-of-Stock Inventories</a>
	                                </small>
	                            @endif
								<table class="table table-bordered table-hover datatable">
									<thead>
										<tr>
											<th>ID</th>
											<th>Lot #</th>
											<th>SKU</th>
											<th>Product name</th>
											<th>Quantity Received</th>
											<th>Available Quantity</th>
											<th>Stock Expiration</th>
											<th>Date Added</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@foreach($inventories->items() as $inventory)
											@if(!is_null($inventory->product))
											<tr data-pid="{{ $inventory->product_id }}" data-id="{{ $inventory->id }}">
												<td>{{ $inventory->id }}</td>
												<td>
													{{ $inventory->lot_number }}
												</td>
												<td>
													<?php 
														$check_stock_availability = check_stock_availability($inventory->product);
													?>
													@if($check_stock_availability == 'out_of_stock')
														<i class="fa-warning fa" style="color:#dd4b39;" data-toggle="tooltip" data-original-title="Out of Stock"></i>
													@elseif($check_stock_availability == 'critical')
														<i class="fa-warning fa" style="color:#f0ad4e;" data-toggle="tooltip" data-original-title="Critical Stock"></i>
													@endif
													<span> {{ $inventory->product->sku }}</span>
												</td>
												<td>
													<a href="{!! url('search/products?q='.$inventory->product->name) !!}" target="_blank" class="show-product-info" data-id="{{ $inventory->product->id }}">
														{{ $inventory->product->name }}
													</a>
												</td>
												<td>
													{{ $inventory->quantity }}
												</td>
												<td>
													<?php 
														$total = $inventory->available_quantity * $inventory->product->qty_per_packing; 
													?>
													{!! '<b>'.$inventory->available_quantity." ".str_auto_plural($inventory->product->packing, $inventory->available_quantity)."</b> "
														."(".$total." ".str_auto_plural($inventory->product->unit, $total).")" !!}
												
												</td>
												<td>
													<?php $expiration = Carbon::parse($inventory->expiration_date); ?>
													<span class="label label-success" data-toggle="tooltip" data-original-title="{{ $expiration->formatLocalized('%A %d %B %Y') }}">
														<i class="fa-clock-o fa"></i> 
														{{ $expiration->diffForHumans() }}
													</span>
												</td>
												<td>
													<?php 
														$date_added = $inventory->created_at;
													?>
													<span class="label label-primary" data-toggle="tooltip" data-original-title="{{ $date_added->toDayDateTimeString() }}">
														<i class="fa-clock-o fa"></i> 
														{{ $date_added->diffForHumans() }}
													</span>
												</td>
												<td>
													<div class="btn-group pull-right">
														@if( $check_stock_availability == 'out_of_stock' || $check_stock_availability == 'critical')
															<!-- <span class="btn-xs btn-primary btn action-icon pull-right" title="Restock" data-action="restock" data-pid="{{ $inventory->product->id }}">
															<i class="fa-refresh fa"></i></span> -->
														@endif
														<span class="btn btn-danger btn-xs action-icon remove-product pull-right" data-action="remove" data-title="inventory" data-urlmain="/inventory/"
															 data-id="{{ $inventory->id }}" title="Remove">
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
								{!! render_pagination($inventories) !!}
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="tab_stock_returns">
						<div class="box-warning box">
							<div class="box-header">
								Stock Returns
							</div>
							<div class="box-body">
								<table class="table table-bordered table-hover" id="tbl_stock_returns">
									<thead>
										<tr>
											<th>Customer</th>
											<th>Items</th>
											<th>Date</th>
										</tr>
									</thead>
									<tbody>
										@foreach($stock_returns as $stock_return)
											<tr>
												<td>{{ $stock_return->order->patient->fname.' '.$stock_return->order->patient->lname }}</td>
												<td>
													<?php $x = 0; ?>
													@foreach($stock_return->product_stock_returns as $psr)
													
														@if($x < 2)
                                                        <span class="btn-success btn btn-xs" data-toggle="tooltip" 
                                                            data-original-title="{!! $psr->quantity.' '.str_auto_plural($psr->product->packing, $psr->quantity).' of '.$psr->product->name !!}">
                                                            {!! $psr->quantity.' x '.Str::limit($psr->product->name, 29) !!}
                                                        </span>
                                                        @else
                                                            <div class="more_{{ $stock_return->id }}" style="display:none;">
                                                                <span class="btn-success btn btn-xs" data-toggle="tooltip" 
                                                                	data-original-title="{!! $psr->quantity.' '.str_auto_plural($psr->product->packing, $psr->quantity).' of '.$psr->product->name !!}">
                                                                    {!! $psr->quantity.' x '.Str::limit($psr->product->name, 29) !!}
                                                                </span>
                                                            </div>
                                                            @if($x == count($stock_return->product_stock_returns)-1)
                                                                <span data-toggle="tooltip" data-target=".more_{{ $stock_return->id }}" data-original-title="Expand to show more products"
                                                                    class="btn btn-xs bg-purple show-hide-more-products" data-id="{{ $stock_return->id }}">
                                                                    <i class="fa-eye fa"></i>
                                                                </span>
                                                            @endif
                                                           
                                                        @endif
                                                        <?php $x++; ?>
													@endforeach
													<a class="btn-xs btn btn-warning btn-flat classify-returned-products" data-toggle="tooltip"
														data-original-title="Classify returned items" data-id="{{ $stock_return->id }}">
														<i class="fa-filter fa"></i> 
													</a>
												</td>
												<td>
													<?php $date_added = Carbon::parse($stock_return->created_at); ?>
													<span class="label label-primary" data-toggle="tooltip" data-original-title="{{ $date_added->diffForHumans() }}">
														<i class="fa-clock-o fa"></i> 
														{{ $date_added }}
													</span>
												</td>
											</tr>
										@endforeach	
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="tab_logs">
						<div class="box box-primary">
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
										@foreach($logs->items() as $log)
											<tr>
												<td>{{ get_person_fullname($log->user) }}</td>
												<td>{!! $log->action !!}</td>
												<td>
													<?php $date_added = $log->created_at; ?>
													<span class="label label-primary" data-toggle="tooltip" data-original-title="{{ $date_added->toDayDateTimeString() }}">
														<i class="fa-clock-o fa"></i> 
														{{ $date_added }}
													</span>
												</td>
											</tr>
										@endforeach	
									</tbody>
								</table>
								{!! render_pagination($logs) !!}
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal for New Inventory -->
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
	                            	<!-- <select class="form-control" name="product_id" id="inventories_product_id"> -->
	                            	<input type="hidden" id="inventories_product_id" style="width:100%;" name="product_id" placeholder="Start typing, scroll for more results"/>
	                            	<div class="temp_name" style="display:none;"></div>
	                            	<!-- </select> -->
	                            </div>
	                            <div class="form-group">
	                            	<label for="lot_number">Lot Number</label>
	                            	<input class="form-control autocomplete" type="text" id="inventory_lot_number" name="lot_number" autocomplete="off" required>
	                            	<input class="form-control autocomplete" type="text" id="inventory_lot_number_setter" autocomplete="off" style="display:none;">
	                            </div>
	                            <div class="form-group">
	                            	<label for="quantity" title="Add quantity by product's packing">Quantity Received 
	                            		<small>(<i>per <span id="outer_packing"> - </span></i>)</small>
	                            	</label>
	                            	<div class="input-group">
		                            	<input type="text" id="inventory_quantity" name="quantity" data-min="1" class="number form-control" placeholder="Add quantity by product's packing" title="Add quantity by product's packing" required>
		                            	<div class="input-group-addon">
		                            		<span class="add-on-product-packing" name="packing"> - </span>
		                            	</div>
	                            	</div>
	                            	<span id="total_quantity_in_unit"></span>
	                            </div>
	                            <div class="form-group">
	                            	<label for="expiration">Expiration Date <small><i>(Leave empty if this product doesn't have an expiration)</i></small></label>
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
	                            	<input class="form-control number" type="text" name="new_quantity" id="new_quantity" required>
	                            </div>
	                            <div class="form-group">
	                            	<label for="new_quantity">Reason</label>
	                            	<textarea class="form-control" name="reason" required></textarea>
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
	        			{!! Form::open(['action' => 'StockReturnController@store', 'method' => 'post', 'id' => 'form_return_n_refund']) !!}
		        			<div class="modal-header">
		        				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        				<h4>STOCK RETURNS / RETURN FORM</h4>
		        			</div>
		        			<div class="modal-body">
		        				<div class="form-group">
		        					<label>Order No.</label>
									<select class="select2" name="order_id" id="order_id"></select>	        					
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
		        						<dt>Gross total:</dt>
		        						<dd id="gross_total"></dd>
		        					</dl>
		        					<br/>
		        					<dl class="dl-vertical">
		        						<dt>Less:</dt>
									  	<dd id="all_less"></dd>
									</dl>
									<br/>
		        					<dl class="dl-vertical">
		        						<dt>Total amount paid:</dt>
		        						<dd id="total_amount"></dd>
		        					</dl>
		        				</div><hr/>

		        				<div class="form-group stock-return-actions">
		        					<label>All products on this order are returned?</label>
		        					<label>
	        							<input type="radio" name="all_product_is_returned" class="icheck" 
	        								data-check-value="1" value="1" checked> Yes 
	        						</label>
		        					<label>
		        						<input type="radio" name="all_product_is_returned" class="icheck data-show" 
			        						data-show-target="#if_only_specific_products_to_return" 
			        						data-show-target-when="0" data-check-value="0"> No 
		        					</label>
		        				</div>

		        				<div id="if_only_specific_products_to_return" style="display:none;">
			        				<div class="form-group">
			        					<label>Product to return</label>
			        					<select name="return_product_id[]" id="return_product_id" multiple></select>
			        					<div class="selected-products-qty-div form-horizontal"></div>
			        				</div>
			        			</div>

		        				<div class="form-group">
		        					<label>Reason</label>
		        					<select class="select2" name="return_code" id="return_code"></select>
		        				</div>

		        				<div class="form-group">
		        					<label>Brief Explanation</label>
		        					<textarea class="form-control" name="brief_explanation"></textarea>
		        				</div>

		        				<div class="form-group">
		        					<label>Action</label>
		        					<div class="stock-return-actions">
		        						<label>
		        							<input type="radio" name="action" class="icheck" data-check-value="replace" value="replace" checked> Replacement 
		        						</label>
			        					<label data-toggle="tooltip" data-original-title="Replacement Amount">{{ peso() }} <span id="refund_amount"> - </span></label>
		        					</div>
		        					<input type="hidden" name="amount_refunded" id="amount_refunded">
		        				</div>
			        		</div>
			        		
			        		<div class="modal-footer">
			        			<button type="submit" class="btn btn-primary btn-flat glow" name="submit">Return to Stocks</button>
			        		</div>
			        	{!! Form::close() !!}
	        		</div>
	        	</div>
	        </div>

	        <!-- Modal For Each Returned Product -->
	        <div class="modal" id="modal-stock-return-details">
	        	<div class="modal-dialog">
	        		<div class="modal-content">
	        			<div class="modal-header">
	        				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        			<h4 class="alert alert-warning align-center glow" style="margin-top: -2px;margin-right: -4px;">
		        				Remove the newly returned items from inventory
		        			</h4>
	        			</div>
	        			<div class="modal-body">
	        				<span id="returned_stocks_lists_request_status"></span>
	        				<ul class="todo-list" id="returned_stocks_lists">
			                    
			                </ul>
	        			</div>
	        			<div class="modal-footer"></div>
	        		</div>
	        	</div>
	        </div>

		</div><!-- /.col -->
	</div><!-- /.row -->
@stop