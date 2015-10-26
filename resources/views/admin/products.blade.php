<?php use Illuminate\Support\Str; ?>
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
                    <label class="sort-by-label">Sort by Category</label>
                    <select class="sort-by">
                        <option value=""></option>
                        @foreach($categories as $category)
                            <option>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_products">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Products</h3><br/>
                            <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-product" data-target="#form_edit_product" data-action="create" data-title="product"><i class="fa-plus fa"></i> Add New</button>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Generic Name</th>
                                        <th>Description</th>
                                        <th>Price/Unit</th>
                                        <th>Packing</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr data-id="{{ $product->id }}">
                                            <td>
                                                <a href="javascript:void(0)" class="products-gallery-toggler" data-target="#modal-products-gallery">
                                                    <img src="{{ url('images/50x50/nophoto.jpg') }}">
                                                </a>
                                            </td>
                                            <td>
                                                {!! $product->prescription_required == 1 ? '<span class="rx" title="Requires a prescription">&#8478;</span>' : '' !!}
                                                <span>{{ ucfirst($product->name) }}</span>
                                            </td>
                                            <td>{{ $product->generic_name }}</td>
                                            <td>{!! Str::limit(rn2br($product->description), 150) !!}</td>
                                            <td>&#x20B1; {{ $product->price.' /'.$product->unit }}</td>
                                            <td>
                                                {!! $product->qty_per_packing." ".str_auto_plural($product->unit, $product->qty_per_packing)." per ".$product->packing !!}
                                            </td>
                                            <td>
                                                <span class="label-success label">{{ $product->subcategory->category->name }}</span>
                                                <span class="label-primary label">{{ $product->subcategory->name }}</span>
                                            </td>
                                            <td>
                                                <div class="tools">
                                                    <div class="btn-group pull-right">
                                                        <a href="javascript:void(0);" class="btn btn-default btn-sm add-edit-btn" data-action="edit" data-modal-target="#modal-add-edit-product" data-title="product info" data-target="#form_edit_product" data-id="{{ $product->id }}" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <span class="btn btn-default btn-sm action-icon remove-product" data-action="remove" data-title="product" data-urlmain="/products/" data-id="{{ $product->id }}"><i class="fa fa-trash-o"></i></span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                   
                </div>
                <div class="tab-pane" id="tab_category">
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
                                                <span>
                                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}">   
                                                </span>
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
                                    <tr>
                                        <td colspan="2">
                                            <span class="form-group">
                                                <button class="btn-danger btn" disabled><i class="fa-warning fa"></i> Remove all selected</button>
                                            </span>
                                        </td>
                                    </tr>
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
                                            <label for="name">Category Name <i>*</i></label>
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
                <div class="tab-pane" id="tab_subcategory">
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
                    <div class="modal fade" id="modal-add-edit-subcategory" tabindex="-1">
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
                                            {!! Form::select('category_id', $category_names, "null", ['class' => 'form-control']) !!}
                                        </div>  
                                        <div class="form-group">
                                            <label for="name">Subcategory Name <i>*</i></label>
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
        <div class="modal fade" id="modal-add-edit-product" tabindex="-1">
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
                                <label for="subcategory_id">Category <i>*</i></label>
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
                                <label for="description">Description <i>*</i></label>
                                <textarea class="form-control" name="description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="prescription_required">Requires prescription? <i>*</i></label>
                                <select class="form-control" name="prescription_required">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="price">Price <i>*</i></label>
                                <div class="input-group">
                                    <span class="input-group-addon">&#x20B1;</span>
                                    <input type="text" class="form-control number" name="price" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="packing">Packing <i>*</i></label>
                                <input type="text" name="packing" class="form-control" placeholder="Ex. box, bottle, strip, etc." required>
                            </div>  
                            <div class="form-group">
                                <label for="unit">Unit <i>*</i></label>
                                <input type="text" class="form-control" name="unit" placeholder="Ex. tablet, capsule, etc." required>
                            </div>
                            <div class="form-group">
                                <label for="qty_per_packing">Quantity per packing <i>*</i></label>
                                <input type="text" class="form-control number" name="qty_per_packing" title="H" required>
                            </div>
                            <div class="form-group">
                                <label title="Enter Safety Stock number per Packing">Safety Stock (<i>per Unit</i>)</label>
                                <input class="form-control number" name="safety_stock" type="text" title="The extra stock that is maintained to mitigate risk of stockouts" />
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
<div class="modal fade" id="modal-products-gallery" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Gallery</h4>
            </div>
            <div class="modal-body">
                <div class="pull-right" class="add-gallery-outer-div">
                    <a href="#" class="btn-info btn btn-flat" id="add_gallery">Add new</a>
                </div><br/>
                <div class="add-new-gallery-outer hidden">
                    <div id="droppable_div">Drag & Drop Files Here</div>
                    <br/><br/>
                    <div id="status1"></div>
                </div>
                <!-- START CAROUSEL-->
                <div id="product-gallery-carousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <!-- <li data-target="#product-gallery-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#product-gallery-carousel" data-slide-to="1" class=""></li>
                        <li data-target="#product-gallery-carousel" data-slide-to="2" class=""></li> -->
                    </ol>
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="http://placehold.it/900x500/39CCCC/ffffff&text=I+Love+Bootstrap" alt="First slide">
                            <div class="carousel-caption">
                                First Slide
                            </div>
                        </div>
                        <div class="item">
                            <img src="http://placehold.it/900x500/3c8dbc/ffffff&text=I+Love+Bootstrap" alt="Second slide">
                            <div class="carousel-caption">
                                Second Slide
                            </div>
                        </div>
                        <div class="item">
                            <img src="http://placehold.it/900x500/f39c12/ffffff&text=I+Love+Bootstrap" alt="Third slide">
                            <div class="carousel-caption">
                                Third Slide
                            </div>
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

