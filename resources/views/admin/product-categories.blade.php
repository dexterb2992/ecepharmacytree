@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">

        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_category" data-toggle="tab">Product Category</a></li>
                <li><a href="#tab_subcategory" data-toggle="tab">Product Subcategory</a></li>
                <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_category">
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
                    <div class="modal" id="modal-add-edit-category">
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
                                    <th>Category</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach($subcategories as $subcategory)
                                        <tr>
                                            <td>{{ $subcategory->category->name }}</td>
                                            <td>{{ $subcategory->name }}</td>
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
                    <div class="modal" id="modal-add-edit-subcategory">
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

    </div><!-- /.col -->
</div><!-- /.row -->
@stop

