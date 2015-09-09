@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Clinics</h3><br/>
                <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-clinic" data-target="#form_add_edit_clinics" data-action="create" data-title="clinic"><i class="fa-plus fa"></i> Add New</button>
            </div><!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-hover datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact Number</th>
                            <th>Full Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clinics as $clinic )
                            <tr data-id="{{ $clinic->id }}">
                               <td>{{ $clinic->name }}</td>
                               <td>{{ $clinic->contact_no }}</td>
                                <td>{{ ucfirst($clinic->address_street).', '.ucfirst($clinic->address_barangay).', '.ucfirst($clinic->address_city_municipality) }}</td>
                                <td>
                                    <div class="tools">
                                        <a href="javascript:void(0);" class="add-edit-btn" data-action="edit" data-modal-target="#modal-add-edit-clinic" data-title="product info" data-target="#form_add_edit_clinic" data-id="{{ $clinic->id }}" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <span class="action-icon remove-clinic" data-action="remove" data-title="clinic" data-urlmain="/clinics/" data-id="{{ $clinic->id }}"><i class="fa fa-trash-o"></i></span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

        <!-- Modal for Create/Edit clinic -->
        <div class="modal" id="modal-add-edit-clinic">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- form start -->
                    <form role="form" id="form_add_edit_clinic" data-mode="create" method="post" action="/clinics/create" data-urlmain="/clinics/">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add new Clinic</h4>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                            <div class="form-group">
                                <label for="name">Clinic Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Clinic Name" name="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="name">Contact Number</label>
                                <input type="text" class="form-control" id="contact_no" placeholder="Contact Number" name="contact_no" required>
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
                                <input type="text" class="form-control" name="unit_floor_room_no" id="unit_floor_room_no" placeholder="Unit/Room No." >
                            </div>
                            <div class="col-xs-4">
                                <input type="text" class="form-control" name="building" id="building" placeholder="Building" >
                            </div>
                            <div class="col-xs-4">
                                <input type="text" class="form-control" name="block_no" id="block_no" placeholder="Block No." >
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
                                <input type="text" class="form-control" name="lot_no" id="lot_no" placeholder="Lot No." >
                            </div>
                            <div class="col-xs-4">
                                <input type="text" class="form-control" name="phase_no" id="phase_no" placeholder="Phase No." >
                            </div>
                            <div class="col-xs-4">
                                <input type="text" class="form-control" name="address_zip" id="address_zip" placeholder="ZIP Code" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address_street">Street and/or Subdivision (<i>Include Subdivision if applicable</i>)</label>
                            <input type="text" class="form-control" id="address_street" placeholder="Street" name="address_street"  >
                        </div>
                        <div class="form-group">
                            <label for="address_barangay">Barangay<i>*</i></label>
                            <input type="text" class="form-control" id="address_barangay" placeholder="Barangay" name="address_barangay"  >
                        </div>
                        <div class="form-group">
                            <label for="address_city_municipality">Municipality<i>*</i></label>
                            <input type="text" class="form-control" id="address_city_municipality" placeholder="Municipality" name="address_city_municipality"  >
                        </div>
                        <div class="form-group">
                            <label for="address_province">Province<i>*</i></label>
                            <input type="text" class="form-control" id="address_province" placeholder="Province" name="address_province"  >
                        </div>
                        <div class="form-group">
                            <label for="address_province">Region<i>*</i></label>
                            <select class="form-control" name="address_region"  >
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

