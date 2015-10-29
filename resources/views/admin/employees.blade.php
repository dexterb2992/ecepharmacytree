@extends('admin.layouts.template')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Members</h3><br/>
                <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-view-member" data-target="#form_edit_inventory" data-action="create" data-title="inventory"><i class="fa-plus fa"></i> Add New</button>
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
                            <td>{{ Carbon\Carbon::createFromFormat('m/d/Y', $member->birthdate)->age }}</td>
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

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">View Member Information</h4>
                </div>
                    
                <div class="moda-body">
                	<div class="register-box-body">
				        <p class="login-box-msg">Register an account</p>
				        {!! Form::open(['action' => 'UserController@create', 'method' => 'post', 'enctype' => "multipart/form-data"]) !!}
				          	<div class="form-group">
				          		{!! Form::label('Email') !!}
				          		{!! Form::email('email', '', ['class' => 'form-control']) !!}
				          		{!! _error($errors->first('email')) !!}
				          	</div>

							<div class="form-group">
								{!! Form::label('Role') !!}
								<select class="form-control" name="access_level">
									<option value="1">Admin</option>
									<option value="2">Branch Manager</option>
									<option value="3">Pharmacist</option>
								</select>
								{!! _error($errors->first('access_level')) !!}
							</div>

				          <div class="form-group has-feedback">
				            {!! Form::label('ECE Branch') !!}
				            <?php 
				              $arr_branches = [];
				              foreach ($branches as $branch) {
				                $arr_branches[$branch->id] = $branch->name;
				              }
				            ?>

				            {!! Form::select('branch_id', $arr_branches, '', ["class" => "form-control"]) !!}
				          </div>

				          <div class="row">
				            <div class="col-xs-8"></div>
				            <div class="col-xs-4">
				              {!! Form::submit('Register', ['class' => 'btn btn-primary btn-block btn-flat']) !!}
				            </div><!-- /.col -->
				          </div>
				        {!! Form::close() !!}
				      </div><!-- /.form-box -->
                </div>
            </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->



	</div><!-- /.col -->
</div><!-- /.row -->
@stop