@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_doctors" data-toggle="tab">Doctors</a></li>
                <li><a href="#tab_specialty" data-toggle="tab">Doctor Specialty</a></li>
                <li><a href="#tab_subspecialty" data-toggle="tab">Doctor Subspecialty</a></li>
                <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_doctors">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Doctors</h3><br/>
                            <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-doctor" data-target="#form_edit_doctor" data-action="create" data-title="doctor"><i class="fa-plus fa"></i> Add New</button>
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
                                    @foreach($specialties as $specialty)
                                        <tr>
                                            <td>
                                                <span>
                                                    <input type="checkbox" name="specialties[]" value="{{ $specialty->id }}">   
                                                </span>
                                                {{ $specialty->name }}
                                            </td>
                                            <td>
                                                <div class="tools">
                                                    <span class="add-edit-btn edit-specialty" data-action="edit" data-modal-target="#modal-add-edit-specialty" data-title="specialty" data-id="{{ $specialty->id }}" data-action="edit" data-target="#form_edit_product_specialty"><i class="fa fa-edit"></i> Edit</span>
                                                    <span class="action-icon specialty-action-icon delete-specialty" data-modal-target="#modal-add-edit-specialty" data-title="specialty" data-urlmain="/doctor-specialties/" data-action="remove" data-id="{{ $specialty->id }}"><i class="fa fa-trash-o"></i> Remove</span>
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

                    <!-- Modal for Create/Edit specialty -->
                    <div class="modal" id="modal-add-edit-doctor">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- form start -->
                                <form role="form" id="form_edit_doctor" data-mode="create" method="post" action="doctors/create" data-urlmain="/doctors/">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Add Doctor</h4>
                                    </div>
                                    <div class="modal-body">

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="form-group">
                                            <label for="name">specialty Name <i>*</i></label>
                                            <input type="text" class="form-control" id="name" placeholder="specialty name" name="name" required>
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
                <div class="tab-pane active" id="tab_specialty">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Doctor Specialties</h3><br/>
                            <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-specialty" data-target="#form_edit_product_specialty" data-action="create" data-title="Specialty"><i class="fa-plus fa"></i> Add New</button>
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
                                    @foreach($specialties as $specialty)
                                        <tr>
                                            <td>
                                                <span>
                                                    <input type="checkbox" name="specialties[]" value="{{ $specialty->id }}">   
                                                </span>
                                                {{ $specialty->name }}
                                            </td>
                                            <td>
                                                <div class="tools">
                                                    <span class="add-edit-btn edit-specialty" data-action="edit" data-modal-target="#modal-add-edit-specialty" data-title="specialty" data-id="{{ $specialty->id }}" data-action="edit" data-target="#form_edit_product_specialty"><i class="fa fa-edit"></i> Edit</span>
                                                    <span class="action-icon specialty-action-icon delete-specialty" data-modal-target="#modal-add-edit-specialty" data-title="specialty" data-urlmain="/doctor-specialties/" data-action="remove" data-id="{{ $specialty->id }}"><i class="fa fa-trash-o"></i> Remove</span>
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

                    <!-- Modal for Create/Edit specialty -->
                    <div class="modal" id="modal-add-edit-specialty">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- form start -->
                                <form role="form" id="form_edit_product_specialty" data-mode="create" method="post" action="doctor-specialties/create" data-urlmain="/doctor-specialties/">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Add new product specialty</h4>
                                    </div>
                                    <div class="modal-body">

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="form-group">
                                            <label for="name">specialty Name <i>*</i></label>
                                            <input type="text" class="form-control" id="name" placeholder="specialty name" name="name" required>
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
                <div class="tab-pane" id="tab_subspecialty">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Product Subspecialties</h3><br/>
                            <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-subspecialty" data-target="#form_edit_product_subspecialty" data-action="create" data-title="Subspecialty"><i class="fa-plus fa"></i> Add New</button>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered table-hover datatable">
                                <thead>
                                    <th>Specialty</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach($subspecialties as $subspecialty)
                                        <tr>
                                            <td>{{ $subspecialty->specialty->name }}</td>
                                            <td>{{ $subspecialty->name }}</td>
                                            <td>
                                                <div class="tools">
                                                    <span class="add-edit-btn edit-specialty" data-action="edit" data-title="specialty" data-modal-target="#modal-add-edit-subspecialty" data-id="{{ $subspecialty->id }}" data-action="edit" data-target="#form_edit_product_subspecialty">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </span>
                                                    <span class="action-icon specialty-action-icon delete-specialty" data-title="specialty" data-modal-target="#modal-add-edit-subspecialty" data-urlmain="/doctor-specialties/subspecialties/" data-action="remove" data-id="{{ $subspecialty->id }}">
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

                    <!-- Modal for Create/Edit Subspecialty -->
                    <div class="modal" id="modal-add-edit-subspecialty">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- form start -->
                                <form role="form" id="form_edit_product_subspecialty" data-mode="create" method="post" action="doctor-specialties/subspecialties/create" data-urlmain="/doctor-specialties/subspecialties/">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Add new subspecialty</h4>
                                    </div>
                                    <div class="modal-body">

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="form-group">
                                            <label for="specialty">Select specialty</label>
                                            {!! Form::select('specialty_id', $specialty_names, "null", ['class' => 'form-control']) !!}
                                        </div>  
                                        <div class="form-group">
                                            <label for="name">Subspecialty Name <i>*</i></label>
                                            <input type="text" class="form-control" id="name" placeholder="specialty name" name="name" required>
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

    </div><!-- /.col -->
</div><!-- /.row -->
@stop

