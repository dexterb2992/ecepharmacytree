@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">

        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_promos" data-toggle="tab">Promo</a></li>
                <li><a href="#tab_category" data-toggle="tab">Discounts</a></li>
                <li><a href="#tab_subcategory" data-toggle="tab">Products for Free</a></li>
                <li class="pull-right">
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_promos">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Promos</h3><br/>
                            <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-promo" data-target="#form_edit_promo" data-action="create" data-title="promo"><i class="fa-plus fa"></i> Add New</button>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Duration</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($promos as $promo)
                                        <tr data-id="{{ $promo->id }}">
                                            <td>{{ ucfirst($promo->name) }}</td>
                                            <td>
                                                <span class="label-primary label"><i class="fa-clock-o fa"></i> 
                                                    {{ Carbon\Carbon::parse($promo->start_date)->format('F d, Y')." to ".
                                                        Carbon\Carbon::parse($promo->end_date)->format('F d, Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="tools">
                                                    <div class="btn-group pull-right">
                                                        <a href="javascript:void(0);" class="btn btn-default btn-sm add-edit-btn" data-action="edit" data-modal-target="#modal-add-edit-promo" data-title="promo info" data-target="#form_edit_promo" data-id="{{ $promo->id }}" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <span class="btn btn-default btn-sm action-icon remove-promo" data-action="remove" data-title="promo" data-urlmain="/promos/" data-id="{{ $promo->id }}"><i class="fa fa-trash-o"></i></span>
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
                    

                </div><!-- /.tab-pane -->

            </div><!-- /.tab-content -->
        </div><!-- nav-tabs-custom -->

        <!-- Modal for Create/Edit promo -->
        <div class="modal" id="modal-add-edit-promo">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- form start -->
                    <form role="form" id="form_edit_promo" data-mode="create" method="post" action="/promos/create" data-urlmain="/promos/">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add new promo</h4>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                            	<label>Promo name</label>
                            	<input class="form-control" type="text" name="name">
                            </div>
                            <div class="form-group">
								<label>Date range:</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control pull-right daterange" id="date_range" />
									<input type="hidden" name="start_date">
									<input type="hidden" name="end_date">
								</div><!-- /.input group -->
							</div><!-- /.form group -->
                            
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

