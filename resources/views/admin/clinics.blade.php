@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Clinics</h3><br/>
                <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-clinic" data-target="#form_add_edit_clinic" data-action="create" data-title="clinic">
                    <i class="fa-plus fa"></i> Add New
                </button>
            </div><!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-hover datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact Number</th>
                            <th>Full Address</th>
                            <th>Doctors</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clinics->items() as $clinic )
                            <tr data-id="{{ $clinic->id }}">
                               <td>{{ $clinic->name }}</td>
                               <td>{{ $clinic->contact_no }}</td>
                                <td>{{ $clinic->full_address() }}</td>
                                <td>
                                    <span class="clinic-doctors-div" data-id="{{ $clinic->id }}">
                                        <span class="badge bg-yellow" data-toggle="tooltip" data-original-title="{{ count($clinic->doctors) }} total doctors">{{ count($clinic->doctors) }}</span> 
                                        @foreach ( limit($clinic->doctors, 3) as $doctor)
                                            <span class="badge bg-aqua">{{ get_person_fullname($doctor) }}</span>
                                        @endforeach
                                    </span>
                                    <a href="javascript:void(0);" class="edit-clinic-doctors" data-id="{{ $clinic->id }}" 
                                        data-toggle="tooltip" data-original-title="View more">
                                        <i class="fa-edit fa" alt="View more"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="tools">
                                        <a href="javascript:void(0);" class="add-edit-btn btn btn-primary btn-xs"
                                            data-action="edit" data-modal-target="#modal-add-edit-clinic" data-title="clinic info" 
                                            data-target="#form_add_edit_clinic" data-id="{{ $clinic->id }}" data-toggle="tooltip" 
                                            data-orignial-title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <span class="action-icon remove-clinic btn btn-danger btn-xs" data-toggle="tooltip" 
                                            data-original-title="Delete" data-action="remove" data-title="clinic" data-urlmain="/clinics/" 
                                            data-id="{{ $clinic->id }}">
                                            <i class="fa fa-trash-o"></i>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! render_pagination($clinics) !!}
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
                                <label for="name">Clinic Name<red>*</red></label>
                                <input type="text" class="form-control" id="name" placeholder="Clinic Name" name="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="name">Contact Number<red>*</red></label>
                                <input type="text" class="form-control" id="contact_no" placeholder="Contact Number" name="contact_no" required>
                            </div>

                            
                            <div class="form-group">
                                <label for="address_province">Region<red>*</red></label>
                                <select class="select2" name="region_id" id="address_region">
                                    <option value="0">- Select Region - </option>
                                    @foreach($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name.' ('.$region->code.')' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="address_province">Province<red>*</red></label>
                                <select class="select2" id="address_province" name="province_id" required>
                                    <option value="0">- Select Province -</option>
                                </select>
                                <!-- <input type="text" class="form-control" id="address_province" placeholder="Province" name="address_province" required> -->
                            </div>

                            <div class="form-group">
                                <label for="address_city_municipality">Municipality<red>*</red></label>
                                <select class="select2" name="municipality_id" id="address_city_municipality">
                                    <option value="0">- Select Municipality -</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="address_barangay">Barangay<red>*</red></label>
                                <select class="select2" name="barangay_id" id="address_barangay">
                                    <option value="0">- Select Barangay -</option>
                                </select>
                            </div>

                            <div class="form-group"> 
                                <label for="additional_address">Additional address (<i>Be more specific with the address as you can as possible</i>)</label>
                                <input type="text" class="form-control" id="additional_address" placeholder="Street" name="additional_address" required>
                            </div>

                            <div class="form-group">
                                <label for="additional_address">Set the place (Please drag the marker to the appropriate location of the branch)</label>
                                <div id="map" style="height:300px;width:100%;">Google Map</div>
                                <input type="hidden" name="google_lat" id="google_lat">
                                <input type="hidden" name="google_lng" id="google_lng">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="submit">Save changes</button>
                        </div>
                    </div><!-- /.modal-content -->
                </form><!-- /form -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        
        <!-- Modal for Clinic-Doctor Tagging -->
        <div class="modal" id="modal_tag_clinic_doctor">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">View/Edit Doctors for this Clinic</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="doctor_ids">Select Doctors</label>
                            <input type="text" name="doctor_ids" id="doctor_ids">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-flat bg-yellow" name="submit" id="btn_doctor_clinic_save_changes">Apply changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('scripts')
    @include('includes._googlemap')
@stop