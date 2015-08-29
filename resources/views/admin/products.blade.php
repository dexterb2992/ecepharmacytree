@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">products</h3><br/>
                <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-product" data-target="#form_edit_product" data-action="create" data-title="product"><i class="fa-plus fa"></i> Add New</button>
            </div><!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-hover datatable">
                    name
                    generic_name
                    description
                    prescription_required
                    price
                    unit
                    packing
                    qty_per_packing
                    <thead>
                        <tr>
                        <th>Name</th>
                        <th>Generic Name</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Packing</th>
                        <th>Qty per packing</th>
                        <th>Sub Category</th>
                        <th>Category</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr data-id="{{ $product->id }}" class="{{ $product->status == 1? '' : 'warning' }}">
                                <td>
                                    {!! $product->prescription_required == 1 ? <span class="label label-danger">&#8478;</span> : '' !!}
                                    <span>{{ $product->name }}</span>
                                </td>
                                <td>{{ $product->generic_name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->unit }}</td>
                                <td>{{ $product->packing }}</td>
                                <td>{{ $product->qty_per_packing }}</td>
                                <td>{{ $product->subcategory->name }}</td>
                                <td>{{ $product->subcategory->category->name }}</td>
                                <td>
                                    <div class="tools">
                                        <a href="javascript:void(0);" class="add-edit-btn" data-action="edit" data-modal-target="#modal-add-edit-product" data-title="product info" data-target="#form_edit_product" data-id="{{ $product->id }}" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <span class="action-icon remove-product" data-action="remove" data-title="product" data-urlmain="/products/" data-id="{{ $product->id }}"><i class="fa fa-trash-o"></i> Remove</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

        <!-- Modal for Create/Edit product -->
        <div class="modal" id="modal-add-edit-product">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- form start -->
                    <form role="form" id="form_edit_product" data-mode="create" method="post" action="/products/create" data-urlmain="/products/">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add new product</h4>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label for="name">product Name <i>*</i></label>
                                <input type="text" class="form-control" id="name" placeholder="product name" name="name" required>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <label for="unit_floor_room_no">Unit/Room No.</label>
                                </div>
                                <div class="col-xs-4">
                                    <label for="building">Building</label>
                                </div>
                                <div class="col-xs-4">
                                    <label for="block_no">Block No.</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    {!! Form::select('subcategory_id', $categories_subcategories, "null", ["class" => "form-control"]) !!}
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" name="building" id="building" placeholder="Building">
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" name="block_no" id="block_no" placeholder="Block No.">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <label for="lot_no">Lot No.</label>
                                </div>
                                <div class="col-xs-4">
                                    <label for="phase_no">Phase No.</label>
                                </div>
                                <div class="col-xs-4">
                                    <label for="address_zip">ZIP Code <i>*</i></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" name="lot_no" id="lot_no" placeholder="Lot No.">
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" name="phase_no" id="phase_no" placeholder="Phase No.">
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" name="address_zip" id="address_zip" placeholder="ZIP Code" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address_street">Street and/or Subdivision (<i>Include Subdivision if applicable</i>)</label>
                                <input type="text" class="form-control" id="address_street" placeholder="Street" name="address_street" required>
                            </div>
                            <div class="form-group">
                                <label for="address_barangay">Barangay<i>*</i></label>
                                <input type="text" class="form-control" id="address_barangay" placeholder="Barangay" name="address_barangay" required>
                            </div>
                            <div class="form-group">
                                <label for="address_city_municipality">Municipality<i>*</i></label>
                                <input type="text" class="form-control" id="address_city_municipality" placeholder="Municipality" name="address_city_municipality" required>
                            </div>
                            <div class="form-group">
                                <label for="address_province">Province<i>*</i></label>
                                <input type="text" class="form-control" id="address_province" placeholder="Province" name="address_province" required>
                            </div>
                            <div class="form-group">
                                <label for="address_province">Region<i>*</i></label>
                                <select class="form-control" name="address_region">
                                @foreach(get_ph_regions() as $region)
                                <option value="{{ $region }}">{{ $region }}</option>
                                @endforeach
                                </select>
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

