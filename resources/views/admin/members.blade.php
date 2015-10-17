@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Members</h3><br/>
            </div><!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-hover datatable">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Age</th>
                            <th>Full Address</th>
                            <th>Email Address</th>
                            <th>Mobile Number</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $member)
                        <tr data-id="{{ $member->id }}" class="{!! $member->deleted_at != null ? 'warning' : '' !!}">
                            <td>
                                <span>{{ ucfirst($member->fname) }}</span>
                            </td>
                            <td>
                                <span>{{ ucfirst($member->lname) }}</span>
                            </td>
                            <td>{{ $member->birthdate != "" ? Carbon\Carbon::createFromFormat('Y-m-d', $member->birthdate)->age : 'n/a' }}</td>
                            <td>{{ ucfirst($member->address_street).', '.ucfirst($member->address_barangay).', '.ucfirst($member->address_city_municipality) }}</td>
                            <td>{{ $member->email_address }}</td>
                            <td>{{ $member->mobile_no }}</td>
                            <td>{!! ($member->deleted_at != null ) ? '<label class="label-danger label">blocked</label>' : '<label class="label-success label">active</label>' !!}</td>
                            <td>
                                <div class="tools">
                                 <a href="javascript:void(0);" class="add-edit-btn" data-action="edit" data-modal-target="#modal-view-member" data-title="Member Info" data-target="#form_view_member" data-id="{{ $member->id }}" title="">
                                    <i class="fa fa-eye"></i> View
                                </a>
                                <br/>
                                @if($member->deleted_at != null )
                                <span class="action-icon marginleft-zero" data-action="unblock" data-title="member" data-urlmain="/members/" data-id="{{ $member->id }}"><i class="fa fa-thumbs-o-up"></i> Unblock </span>
                                @else
                                <span class="action-icon marginleft-zero" data-action="deactivate" data-title="member" data-urlmain="/members/" data-id="{{ $member->id }}"><i class="fa fa-thumbs-o-down"></i> Block </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

    <!-- Modal for Create/Edit product -->
    <div class="modal" id="modal-view-member">
        <div class="modal-dialog">
            <div class="modal-content">
              <form role="form" id="form_view_member" data-urlmain="/members/">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">View Member Information</h4>
                </div>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_category" data-toggle="tab">General Information</a></li>
                        <li><a href="#tab_subcategory" data-toggle="tab">Contact Information</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_category">
                          <!-- form start -->
                          <div class="modal-body">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="row">
                                <div class="col-xs-4"></div>
                                <div class="col-xs-4">
                                    <img class="img-responsive primary-photo" name="photo" src="" alt="Photo">
                                </div>
                                <div class="col-xs-4"></div>
                            </div>

                            <div class="form-group">
                                <label for="name">First Name</label>
                                <input type="text" class="form-control whitenbg" id="fname" name="fname" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="name">Last Name</label>
                                <input type="text" class="form-control whitenbg" id="lname" name="lname" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="name">Middle Name</label>
                                <input type="text" class="form-control whitenbg" id="mname" name="mname" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="name">Usename</label>
                                <input type="text" class="form-control whitenbg" id="username" name="username" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="name">Occupation</label>
                                <input type="text" class="form-control whitenbg" id="occupation" name="occupation" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="name">Birthdate</label>
                                <input type="text" class="form-control whitenbg" id="birthdate" name="birthdate" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="name">Gender</label>
                                <input type="text" class="form-control whitenbg" id="sex" name="sex" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="name">Civil Status</label>
                                <input type="text" class="form-control whitenbg" id="civil_status" name="civil_status" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="name">Height</label>
                                <input type="text" class="form-control whitenbg" id="height" name="height" readonly=""> 
                            </div>

                            <div class="form-group">
                                <label for="name">Weight</label>
                                <input type="text" class="form-control whitenbg" id="weight" name="weight" readonly="">
                            </div>

                        </div><!-- /.modal-content -->
                    </div>
                    <div class="tab-pane" id="tab_subcategory">

                        <div class="form-group">
                            <label for="name">Email Address</label>
                            <input type="text" class="form-control whitenbg" id="email_address" name="email_address" readonly="">
                        </div>

                        <div class="form-group">
                            <label for="name">Telephone Number</label>
                            <input type="text" class="form-control whitenbg" id="tel_no" name="tel_no" readonly="">
                        </div>

                        <div class="form-group">
                            <label for="name">Mobile Number</label>
                            <input type="text" class="form-control whitenbg" id="mobile_no" name="mobile_no" readonly="">
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
                                <input type="text" class="form-control whitenbg" name="unit_floor_room_no" id="unit_floor_room_no" placeholder="Unit/Room No." readonly="">
                            </div>
                            <div class="col-xs-4">
                                <input type="text" class="form-control whitenbg" name="building" id="building" placeholder="Building" readonly="">
                            </div>
                            <div class="col-xs-4">
                                <input type="text" class="form-control whitenbg" name="block_no" id="block_no" placeholder="Block No." readonly="">
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
                                <input type="text" class="form-control whitenbg" name="lot_no" id="lot_no" placeholder="Lot No." readonly="">
                            </div>
                            <div class="col-xs-4">
                                <input type="text" class="form-control whitenbg" name="phase_no" id="phase_no" placeholder="Phase No." readonly="">
                            </div>
                            <div class="col-xs-4">
                                <input type="text" class="form-control whitenbg" name="address_zip" id="address_zip" placeholder="ZIP Code" readonly="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address_street">Street and/or Subdivision (<i>Include Subdivision if applicable</i>)</label>
                            <input type="text" class="form-control whitenbg" id="address_street" placeholder="Street" name="address_street"  readonly="">
                        </div>
                        <div class="form-group">
                            <label for="address_barangay">Barangay<i>*</i></label>
                            <input type="text" class="form-control whitenbg" id="address_barangay" placeholder="Barangay" name="address_barangay"  readonly="">
                        </div>
                        <div class="form-group">
                            <label for="address_city_municipality">Municipality<i>*</i></label>
                            <input type="text" class="form-control whitenbg" id="address_city_municipality" placeholder="Municipality" name="address_city_municipality"  readonly="">
                        </div>
                        <div class="form-group">
                            <label for="address_province">Province<i>*</i></label>
                            <input type="text" class="form-control whitenbg" id="address_province" placeholder="Province" name="address_province"  readonly="">
                        </div>
                        <div class="form-group">
                            <label for="address_province">Region<i>*</i></label>
                            <select class="form-control whitenbg" name="address_region"  readonly="">
                                @foreach(get_ph_regions() as $region)
                                <option value="{{ $region }}">{{ $region }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form><!-- /form --> 
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



</div><!-- /.col -->
</div><!-- /.row -->
@stop

