<?php 
    use Illuminate\Support\Str;      
?>
@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">

        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_products" data-toggle="tab">Products</a></li>
                <li><a href="#tab_category" data-toggle="tab">Product Category</a></li>
                <li><a href="#tab_subcategory" data-toggle="tab">Product Subcategory</a></li>
                <li class="pull-right">
                    <code class="text-black">Sort by Category</code>
                    <select class="sort-by">
                        <option value=""></option>
                        @foreach($categories as $category)
                            <option>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_products">
                    <div class="box box-success">
                        <div class="box-header">
                            <h4 class="box-title">Products Master List</h4><br/>
                            <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-product" data-target="#form_edit_product" data-action="create" data-title="product"><i class="fa-plus fa"></i> Add New</button>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            @if( Route::is('Products::all') )
                                <small class="">
                                    <!-- <a href="{{ route('Products::index') }}">Hide Deleted Products</a> -->
                                </small>
                            @else
                                <small class="">
                                    <!-- <a href="{{ route('Products::all') }}">Show Deleted Products</a> -->
                                </small>
                            @endif
                            
                            <table class="table table-bordered table-hover datatable products-table">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Generic Name</th>
                                        <th>Description</th>
                                        <th>Unit Cost</th>
                                        <th>Selling Price/Packing</th>
                                        <th>Packing</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products->items() as $product)
                                        <tr data-id="{{ $product->id }}" class="{!! !is_null($product->deleted_at) ? 'danger' : '' !!}">
                                            <td>
                                                <a href="javascript:void(0)" class="products-gallery-toggler" data-target="#modal-products-gallery">
                                                    <img data-toggle="tooltip" data-original-title="Click to view gallery" src="{{ !empty($product->galleries[0]) ? url('images/50x50/'.$product->galleries[0]->filename) : url('images/50x50/nophoto.jpg') }}">
                                                </a>
                                            </td>
                                            <td>
                                                {!! $product->prescription_required == 1 ? '<span class="rx" data-toggle="tooltip" data-placement="right" data-original-title="This product requires a prescription">&#8478;</span>' : '' !!}
                                                <span>{{ ucfirst($product->name) }}</span>
                                            </td>
                                            <td>{{ $product->generic_name }}</td>
                                            <td>{!! Str::limit(rn2br($product->description), 150) !!}</td>
                                            <td>&#x20B1; {!! number_format($product->unit_cost, 2).' /'.$product->packing !!}</td>
                                            <td>
                                                @if( $product->is_freebie == 0 )
                                                    &#x20B1; {!! number_format($product->price, 2).' /'.$product->packing !!}
                                                @else
                                                    <span class="label label-info">FREE</span>
                                                @endif
                                            </td>
                                            <td>
                                                {!! $product->qty_per_packing." ".str_auto_plural($product->unit, $product->qty_per_packing)." per ".$product->packing !!}
                                            </td>
                                            <td>
                                                <span class="label-success label">{{ $product->subcategory->category->name }}</span>
                                                <span class="label-primary label">{{ $product->subcategory->name }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group pull-right">
                                                    @if(is_null($product->deleted_at))
                                                        <!-- <span class="btn btn-danger btn-xs action-icon remove-product" data-action="remove" data-title="product" 
                                                        data-urlmain="/products/" data-id="{{ $product->id }}" data-toggle="tooltip" data-original-title="Remove">
                                                            <i class="fa fa-trash-o"></i>
                                                        </span> -->

                                                        <span class="btn btn-warning btn-xs add-edit-btn" data-action="edit"
                                                         data-modal-target="#modal-add-edit-product" data-title="product info" data-target="#form_edit_product" 
                                                         data-id="{{ $product->id }}" title="View|Edit" data-toggle="tooltip" data-original-title="View|Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </span>
                                                    @else
                                                        <span class="btn btn-primary btn-xs action-icon restore-product" data-action="reactivate" data-title="Restore product" 
                                                        data-urlmain="/products/" data-id="{{ $product->id }}" data-toggle="tooltip" data-original-title="Restore">
                                                            <i class="fa fa-refresh"></i>
                                                        </span>
                                                    @endif
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! render_pagination($products, ['startfrom' => $startfrom]) !!}

                            <div class="row align-center">
                                <div class="col-md-12">
                                    <ul class="pagination">
                                        @foreach(range('A', 'Z') as $i)
                                            <li class="{!! $startfrom == $i ? 'active' : '' !!}">
                                                <a href="/products?startfrom={{ $i }}">{{ $i }}</a>
                                            </li>
                                        @endforeach        
                                    </ul>
                                </div>
                            </div>
                            

                            @if( isset($source) )
                            <a href="{{ url(route('Products::index')) }}">Go back</a>
                            @endif
                            
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                   
                </div>
                <div class="tab-pane fade" id="tab_category">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Product Categories</h3><br/>
                            <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-category" data-target="#form_edit_product_category" data-action="create" data-title="category"><i class="fa-plus fa"></i> Add New</button>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered table-hover datatable">
                                <thead>
                                    <th>Name</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>
                                                <!-- <span>
                                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}">   
                                                </span> -->
                                                {{ $category->name }}
                                            </td>
                                            <td>
                                                <div class="tools">
                                                    <span class="add-edit-btn edit-category" data-action="edit" data-modal-target="#modal-add-edit-category" data-title="category" data-id="{{ $category->id }}" data-action="edit" data-target="#form_edit_product_category"><i class="fa fa-edit"></i> Edit</span>
                                                    <span class="action-icon category-action-icon delete-category" data-modal-target="#modal-add-edit-category" data-title="category" data-urlmain="/products-categories/" data-action="remove" data-id="{{ $category->id }}"><i class="fa fa-trash-o"></i> Remove</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <!-- <tr>
                                        <td colspan="2">
                                            <span class="form-group">
                                                <button class="btn-danger btn" disabled><i class="fa-warning fa"></i> Remove all selected</button>
                                            </span>
                                        </td>
                                    </tr> -->
                                </tfoot>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <!-- Modal for Create/Edit Category -->
                    <div class="modal fade" id="modal-add-edit-category">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- form start -->
                                <form role="form" id="form_edit_product_category" data-mode="create" method="post" action="products-categories/create" data-urlmain="/products-categories/">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Add new product category</h4>
                                    </div>
                                    <div class="modal-body">

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="form-group">
                                            <label for="name">Category Name <i><red>*</red></i></label>
                                            <input type="text" class="form-control" id="name" placeholder="Category name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="submit">Save</button>
                                    </div>
                                   
                                </form><!-- /form -->
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.tab-pane -->
                <div class="tab-pane fade" id="tab_subcategory">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Product Subcategories</h3><br/>
                            <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-subcategory" data-target="#form_edit_product_subcategory" data-action="create" data-title="subcategory"><i class="fa-plus fa"></i> Add New</button>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered table-hover datatable">
                                <thead>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach($subcategories as $subcategory)
                                        <tr>
                                            <td>{{ $subcategory->name }}</td>
                                            <td>{{ $subcategory->category->name }}</td>
                                            <td>
                                                <div class="tools">
                                                    <span class="add-edit-btn edit-category" data-action="edit" data-title="category" data-modal-target="#modal-add-edit-subcategory" data-id="{{ $subcategory->id }}" data-action="edit" data-target="#form_edit_product_subcategory">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </span>
                                                    <span class="action-icon category-action-icon delete-category" data-title="category" data-modal-target="#modal-add-edit-subcategory" data-urlmain="/products-categories/subcategories/" data-action="remove" data-id="{{ $subcategory->id }}">
                                                        <i class="fa fa-trash-o"></i> Remove
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <!-- Modal for Create/Edit SubCategory -->
                    <div class="modal fade" id="modal-add-edit-subcategory">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- form start -->
                                <form role="form" id="form_edit_product_subcategory" data-mode="create" method="post" action="products-categories/subcategories/create" data-urlmain="/products-categories/subcategories/">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Add new product subcategory</h4>
                                    </div>
                                    <div class="modal-body">

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="form-group">
                                            <label for="category">Select Category</label>
                                            {!! Form::select('category_id', $category_names, "null", ['class' => 'select2']) !!}
                                        </div>  
                                        <div class="form-group">
                                            <label for="name">Subcategory Name <i><red>*</red></i></label>
                                            <input type="text" class="form-control" id="name" placeholder="Category name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="submit">Save</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </form><!-- /form -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->

                    

                </div><!-- /.tab-pane -->
            </div><!-- /.tab-content -->
        </div><!-- nav-tabs-custom -->

        <!-- Modal for Create/Edit product -->
        <div class="modal fade" id="modal-add-edit-product">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- form start -->
                    <form role="form" id="form_edit_product" data-mode="create" method="post" action="/products/create" data-urlmain="/products/">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add new product</h4>
                        </div>
                        <div class="modal-body">

                            {!! Form::token() !!}
                            
                            <div class="form-group">
                                <label for="subcategory_id">Category <i><red>*</red></i></label>
                                <select class="select2" name="subcategory_id" id="select_subcategory_id">
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
                                <label for="name">Product name <i><red>*</red></i></label>
                                <input type="text" class="form-control" id="name" placeholder="product name" name="name" required>
                            </div>

                            <div class="form-group">
                                <label for="generic_name">Generic name</label>
                                <textarea class="form-control" name="generic_name"></textarea>      
                            </div>

                            <div class="form-group">
                                <label for="description">Description <i><red>*</red></i></label>
                                <textarea class="form-control" name="description" required></textarea>
                            </div>

                            <div class="form-group">
                                <label data-toggle="tooltip" data-original-title="Stock Keeping Unit">SKU (<small><i>A unique product identifier that will also be used in inventory.</i></small>)</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa-tag fa"></i>
                                    </div>
                                    <input type="text" class="form-control" name="sku" data-form-id="form_edit_product" minlength="6" required/>
                                    <div class="input-group-btn">
                                        <span class="btn btn-default btn-flat btn-gensku">
                                            <i class="fa-random fa"></i> Generate SKU
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="prescription_required">Requires prescription? <i><red>*</red></i></label>
                                <select class="form-control" name="prescription_required">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="price">Unit Cost <i>(per packing)<red>*</red></i></label>
                                <div class="input-group">
                                    <span class="input-group-addon">&#x20B1;</span>
                                    <input type="text" class="form-control number" name="unit_cost" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="price">Selling Price <i>(per packing)<red>*</red></i></label>
                                <div class="input-group">
                                    <span class="input-group-addon">&#x20B1;</span>
                                    <input type="text" class="form-control number" name="price" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="packing">Packing <i>(Ex. box, bottle, strip, etc.)<red>*</red></i></label>
                                <input type="text" name="packing" class="form-control" placeholder="Ex. box, bottle, strip, etc." required>
                            </div>  

                            <div class="form-group">
                                <label for="unit">Unit <i>(Ex. tablet, capsule, etc.)<red>*</red></i></label>
                                <input type="text" class="form-control" name="unit" placeholder="Ex. tablet, capsule, etc." required>
                            </div>

                            <div class="form-group">
                                <label for="qty_per_packing">Quantity per packing <i>(How many units are there in 1 packing?) <red>*</red></i></label>
                                <input type="text" class="form-control number" name="qty_per_packing" title="How many units are there in 1 packing?" required>
                            </div>

                            <div class="form-group">
                                <label>Default Critical Inventory Number <i>(Enter quantity by packing)</i><red>*</red></label>
                                <input class="form-control number" type="text" name="critical_stock" data-default-value="10"
                                    title="This will inform us when to notify you when any of the products is on a critical stock."/>
                            </div>
                            <div class="form-group stock-return-actions">
                                <label for="per_transaction_has_free_gifts">
                                    <input type="checkbox" name="is_freebie" id="is_freebie" value="0" 
                                    data-check-value="1" data-uncheck-value="0" class="form-control icheck" />
                                    Is Freebie? <i>(If checked, this product won't show on the customer's product listing)</i>
                                </label>
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

<!-- Modal for Products Photo Gallery -->
<div class="modal fade" id="modal-products-gallery">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    Gallery
                    <small>
                        <i>
                            (Right click image for more options)
                        </i>
                    </small>
                </h4>
            </div>
            <div class="modal-body">
                <div class="pull-right add-gallery-outer-div">
                    <a href="#" class="btn-info btn btn-flat" id="add_gallery">Add new</a>
                </div><br/>
                <div class="add-new-gallery-outer hidden">
                    <div class="gallery-empty"></div>
                    <div id="droppable_div">Drag & Drop Files Here</div>
                    <br/><br/>
                    <div id="status1"></div>
                </div>
                <!-- START CAROUSEL-->
                <div id="product-gallery-carousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#product-gallery-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#product-gallery-carousel" data-slide-to="1" class=""></li>
                        <li data-target="#product-gallery-carousel" data-slide-to="2" class=""></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="http://placehold.it/900x500/39CCCC/ffffff&text=1. Add photos for this product" alt="First slide">
                        </div>
                        <div class="item">
                            <img src="http://placehold.it/900x500/3c8dbc/ffffff&text=2. Click the button 'Add new'" alt="Second slide">
                        </div>
                        <div class="item">
                            <img src="http://placehold.it/900x500/f39c12/ffffff&text=3. Drop photos on the dotted space" alt="Third slide">
                        </div>
                    </div>
                    <a class="left carousel-control" href="#product-gallery-carousel" data-slide="prev">
                        <span class="fa fa-angle-left"></span>
                    </a>
                    <a class="right carousel-control" href="#product-gallery-carousel" data-slide="next">
                        <span class="fa fa-angle-right"></span>
                    </a>
                </div>
                <!-- END CAROUSEL-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal for Products Photo Gallery -->
@stop

