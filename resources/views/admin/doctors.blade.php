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
                                    <th>PRC No.</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Specialty</th>
                                    <th>Affiliation</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach($doctors as $doctor)
                                    <tr>
                                        <td>{{ $doctor->prc_no }}</td>
                                        <td>{{ ucfirst($doctor->fname) }}</td>
                                        <td>{{ ucfirst($doctor->mname) }}</td>
                                        <td>{{ ucfirst($doctor->lname) }}</td>
                                        <td>{{ $doctor->subspecialty()->first()->name }} ({{ $doctor->subspecialty()->first()->specialty()->first()->name }})</td>
                                        <td>{{ ucfirst($doctor->affiliation) }}</td>
                                        <td>{{ $doctor->email }}</td>

                                        <td>
                                            <div class="tools">
                                                <span class="add-edit-btn edit-doctor" data-action="edit" data-modal-target="#modal-add-edit-doctor" data-title="doctor" data-id="{{ $doctor->id }}" data-action="edit" data-target="#form_edit_doctor"><i class="fa fa-edit"></i> Edit</span>

                                                <span class="action-icon specialty-action-icon delete-doctor" data-modal-target="#modal-add-edit-doctor" data-title="doctor" data-urlmain="/doctor/" data-action="remove" data-id="{{ $doctor->id }}"><i class="fa fa-trash-o"></i> Remove</span>
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

                    <!-- Modal for Create/Edit specialty -->
                    <div class="modal" id="modal-add-edit-doctor">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- form start -->
                                <form role="form" id="form_edit_doctor" data-mode="create" method="post" action="/doctors/create" data-urlmain="/doctors/">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Add Doctor</h4>
                                    </div>
                                    <div class="modal-body">

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group">
                                            <label for="name">PRC Number</label>
                                            <input type="text" class="form-control" id="prc_no" placeholder="PRC Number" name="prc_no" required>
                                        </div>

                                        <div class="form-group">
                                        <label for="sub_specialty_id">Specialty</label>
                                            <select class="form-control" name="sub_specialty_id">
                                                @foreach($specialties as $specialty)
                                                <optgroup label="{{ $specialty->name }}">
                                                    @foreach($specialty->subspecialties as $subspecialty)
                                                    <option value="{{ $subspecialty->id }}">{{ $subspecialty->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="name">First Name</label>
                                            <input type="text" class="form-control" id="fname" placeholder="First Name" name="fname" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Middle Name</label>
                                            <input type="text" class="form-control" id="mname" placeholder="Middle Name (Optional)" name="mname">
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Last Name</label>
                                            <input type="text" class="form-control" id="lname" placeholder="Last Name" name="lname" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Affiliation</label>
                                            <input type="text" class="form-control" id="affiliation" placeholder="Affiliation" name="affiliation" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Email</label>
                                            <input type="text" class="form-control" id="email" placeholder="Email" name="email" required>
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
                <div class="tab-pane" id="tab_specialty">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Doctor Specialties</h3><br/>
                            <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-specialty" data-target="#form_edit_product_specialty" data-action="create" data-title="specialty"><i class="fa-plus fa"></i> Add New</button>
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
                                            <!-- <span>
                                                <input type="checkbox" name="specialties[]" value="{{ $specialty->id }}">   
                                            </span> -->
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
                            <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-subspecialty" data-target="#form_edit_product_subspecialty" data-action="create" data-title="subspecialty"><i class="fa-plus fa"></i> Add New</button>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered table-hover datatable">
                                <thead>
                                    <th>specialty</th>
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
                                        <h4 class="modal-title">Add new product subspecialty</h4>
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
                                </form><!-- /form -->
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.tab-pane -->
            </div><!-- /.tab-content -->
        </div><!-- nav-tabs-custom -->

    </div><!-- /.col -->
</div><!-- /.row -->
@stop

