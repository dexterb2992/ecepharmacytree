<?php use Illuminate\Support\Str; ?>
@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">  
        <div class="box box-success">
            <div class="box-header">
                <h4 class="box-title">Products Master List</h4><br/>
                <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-product-groups" data-target="#form_edit_product" data-action="create" data-title="product"><i class="fa-plus fa"></i> Add New</button>
            </div><!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-hover datatable products-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>
                                <span data-toggle="tooltip" data-original-title="Points to be earned for every &#x20B1;100.00">
                                    Points
                                </span>
                            </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product_groups as $group)
                            <tr data-id="{{ $product->id }}">
                                <td>{{ ucfirst($group->name) }}</td>
                                <td>
                                    <div class="btn-group pull-right">
                                        <span class="btn btn-default btn-sm action-icon remove-product" data-action="remove" data-title="product" 
                                            data-urlmain="/products/" data-id="{{ $product->id }}" data-toggle="tooltip" data-original-title="Remove">
                                            <i class="fa fa-trash-o"></i>
                                        </span>
                                        <span class="btn btn-default btn-sm add-edit-btn" data-action="edit"
                                            data-modal-target="#modal-add-edit-product" data-title="product info" data-target="#form_edit_product" 
                                            data-id="{{ $product->id }}" title="Edit" data-toggle="tooltip" data-original-title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
                   
    </div><!-- /.col -->
</div><!-- /.row -->

<!-- Modal for Products Photo Gallery -->
<div class="modal fade" id="modal-product-groups" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'post', 'action' => 'ProductGroupController@store']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        Add new Product Group
                    </h4>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group">
                        {!! Form::label('Name') !!}
                        {!! Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Points') !!}
                        {!! Form::text('points', '', ['class' => 'form-control number', 'placeholder' => 'Points']) !!}
                    </div>

                    <div class="form-group">    
                        {!! Form::label("Products Involved") !!}
                        <select class="form-control select2" name="products_involved" multiple>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary pull-right">Save</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- End Modal for Products Photo Gallery -->
@stop

