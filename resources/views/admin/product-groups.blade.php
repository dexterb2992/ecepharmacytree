<?php use Illuminate\Support\Str; ?>
@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">  
        <div class="box box-success">
            <div class="box-header">
                <h4 class="box-title">Product Groups</h4><br/>
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
                            <th>Products</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product_groups as $group)
                            <tr data-id="{{ $group->id }}">
                                <td>{{ ucfirst($group->name) }}</td>
                                <td>{{ $group->points }}</td>
                                <td>
                                    <?php $x = 0; ?>
                                    @if(count($group->products) > 0)
                                        @foreach($group->products as $product)
                                            @if($x < 4)
                                                <a href="{{ route('product_search').'?q='.$product->name }}" data-toggle="tooltip" data-original-title="{{ $product->name }}" target="_blank" class="btn btn-xs btn-success">{{ $product->name }}</a>
                                            @else
                                                <div class="more_{{ $group->id }}" style="display:none;">
                                                    <a href="{{ route('product_search').'?q='.$product->name }}" data-toggle="tooltip" data-original-title="{{ $product->name }}" target="_blank" class="btn btn-xs btn-success">{{ $product->name }}</a>
                                                </div>
                                                @if($x == count($group->products)-1)
                                                    <span data-toggle="tooltip" data-target=".more_{{ $group->id }}" data-original-title="Expand to show more products"
                                                        class="btn btn-xs bg-purple show-hide-more-products" >
                                                        <i class="fa-eye fa"></i>
                                                    </span>
                                                @endif
                                            @endif
                                            <?php $x++; ?>
                                        @endforeach
                                    @else
                                        <span class="label label-default">No product is associated with this group</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <span class="btn btn-warning btn-xs add-edit-btn" data-action="edit"
                                            data-modal-target="#modal-product-groups" data-title="product group info" data-target="#form_edit_product_groups" 
                                            data-id="{{ $group->id }}" title="Edit" data-toggle="tooltip" data-original-title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </span>

                                        <span class="btn btn-danger btn-xs action-icon remove-product" data-action="remove" data-title="product" 
                                            data-urlmain="/product-groups/" data-id="{{ $group->id }}" data-toggle="tooltip" data-original-title="Remove">
                                            <i class="fa fa-trash-o"></i>
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
            {!! Form::open(['method' => 'post', 'action' => 'ProductGroupController@store', 'id' => 'form_edit_product_groups', 'data-urlmain' => '/product-groups/']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        Add new Product Group
                    </h4>
                </div>
                <div class="modal-body">
                    <small>Note: Fields with <b>(<red>*</red>)</b> on its label is <span class="red">required</span></small>
                    <div class="form-group">
                        <label>Name<red>*</red></label>
                        {!! Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                    </div>
                    <div class="form-group">
                        <label>
                            Points
                            <small><i>(How much points a member will earn for every {{ peso() }}100.00 for this group of products?)</i></small>
                            <red>*</red>
                        </label>
                        {!! Form::text('points', '', ['class' => 'form-control number', 'placeholder' => 'Points']) !!}
                    </div>

                    <div class="form-group">    
                        {!! Form::label("Products Involved") !!}
                        <!-- <select class="form-control select2" name="products_involved[]" multiple> -->
                            
                        <!-- </select> -->
                        <input type="hidden" name="products_involved" class="products-multiple-select2">
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

