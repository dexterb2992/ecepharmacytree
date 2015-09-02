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
								<th>Expiration</th>
								<th>Date Added</th>
							</tr>
						</thead>
						<tbody>
							<tbody>
								@foreach($inventories as $inventory)
									<tr>
										<td>{{ $inventory->product->sku }}</td>
										<td>{{ $inventory->product->name }}</td>
										<td>{{ $inventory->quantity }}</td>
										<td>{{ $inventory->expiration }}</td>
										<td>{{ $inventory->created_at }}</td>
									</tr>
								@endforeach
							</tbody>
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
	                            	<label for="product_sku">Product</label>
	                            	<select class="form-control" name="product_sku">
	                            		@foreach($products as $product)
	                            			<option value="{{ $product->sku }}">{{ $product->name }}</option>
	                            		@endforeach
	                            	</select>
	                            </div>
	                            <div class="form-group">
	                            	<label for="quantity">Quantity</label>
	                            	<input type="text" name="quantity" class="number form-control">
	                            </div>
	                            <div class="form-group">
	                            	<label for="expiration">Expiration Date</label>
	                            	<div class="input-group">
	                            		<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" name="expiration" class="form-control datemask" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask />
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