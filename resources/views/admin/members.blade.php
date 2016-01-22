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
                            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $member->birthdate)->age }}</td>
                            <td>{{ $member->full_address() }}</td>
                            <td>{{ $member->email_address }}</td>
                            <td>{{ $member->mobile_no }}</td>
                            <td>{!! ($member->deleted_at != null ) ? '<label class="label-danger label">blocked</label>' : '<label class="label-success label">active</label>' !!}</td>
                            <td>
                                <div class="tools">
                                   <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="View" class="add-edit-btn btn btn-primary btn-xs" data-action="edit" data-modal-target="#modal-view-member" data-title="Member Info" data-target="#form_view_member" data-id="{{ $member->id }}" title="">
                                    <i class="fa fa-eye"></i>
                                    </a>
                                    @if($member->deleted_at != null )
                                    <span class="action-icon marginleft-zero  btn btn-purple btn-xs" data-toggle="tooltip" data-original-title="Unblock" data-action="unblock" data-title="member" data-urlmain="/members/" data-id="{{ $member->id }}">
                                        <i class="fa fa-thumbs-o-up"></i> 
                                    </span>
                                    @else
                                    <span class="action-icon marginleft-zero btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Block" data-action="deactivate" data-title="member" data-urlmain="/members/" data-id="{{ $member->id }}">
                                        <i class="fa fa-thumbs-o-down"></i>
                                    </span>
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

                                <div class="form-group">
                                    <label for="address_street">Full Address</label>
                                    <input type="text" class="form-control whitenbg" id="address_street" placeholder="Street" name="address_street"  readonly="">
                                </div>
                                <!-- <div class="form-group">
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
                                        @foreach($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                        @endforeach
                                    </select>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </form><!-- /form --> 
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



</div><!-- /.col -->
</div><!-- /.row -->
@stop

