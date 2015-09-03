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
                            <tr data-id="{{ $product->id }}">
                                <td>
                                    {!! $product->prescription_required == 1 ? '<span class="rx" title="Requires a prescription">&#8478;</span>' : '' !!}
                                    <span>{{ ucfirst($product->name) }}</span>
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
                                        <span class="action-icon remove-product" data-action="remove" data-title="product" data-urlmain="/products/" data-id="{{ $product->id }}"><i class="fa fa-trash-o"></i></span>
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
                                <label for="subcategory_id">Category</label>
                                <select class="form-control" name="subcategory_id">
                                    @foreach($categories as $category)
                                        <optgroup label="{{ $category->name }}">
                                            @foreach($category->subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">Product name <i>*</i></label>
                                <input type="text" class="form-control" id="name" placeholder="product name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="generic_name">Generic name</label>
                                <textarea class="form-control" name="generic_name"></textarea>      
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="prescription_required">Requires prescription?</label>
                                <select class="form-control" name="prescription_required">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <div class="input-group">
                                    <span class="input-group-addon">&#x20B1;</span>
                                    <input type="text" class="form-control" name="price">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="packing">Packing</label>
                                <input type="text" name="packing" class="form-control" placeholder="Ex. box, bottle, strip, etc.">
                            </div>  
                            <div class="form-group">
                                <label for="unit">Unit</label>
                                <input type="text" class="form-control" name="unit" placeholder="Ex. tablet, capsule, etc.">
                            </div>
                            <div class="form-group">
                                <label for="qty_per_packing">Quantity per packing</label>
                                <input type="text" class="form-control" name="qty_per_packing" title="H">
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

