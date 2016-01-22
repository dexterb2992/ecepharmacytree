@extends('admin.layouts.template')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Employees</h3><br/>
                <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-view-employee" data-action="create" data-title="inventory"><i class="fa-plus fa"></i> Add New</button>
            </div><!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-hover datatable">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email Address</th>
                            <th>Role</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr data-id="{{ $employee->id }}" class="{!! $employee->deleted_at != null ? 'warning' : '' !!}">
                            <td>
                                <span>{{ ucfirst($employee->fname) }}</span>
                            </td>
                            <td>
                                <span>{{ ucfirst($employee->lname) }}</span>
                            </td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ get_role($employee->access_level) }}</td>
                            <td>
                            	{{ $employee->branch->name }}
                            	<a href="javascript:void(0);" class="add-edit-btn" data-action="edit" data-toggle="tooltip" data-original-title="Change branch for this employee?" data-modal-target="#modal-view-employee" data-title="Employee Info" data-target="#form_update_employee" data-id="{{ $employee->id }}" title="">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <td>{!! ($employee->deleted_at != null ) ? '<label class="label-danger label">deactivated</label>' : '<label class="label-success label">active</label>' !!}</td>
                            <td>
                                <div class="tools">
	                                @if($employee->deleted_at != null )
	                                <span class="action-icon marginleft-zero btn btn-purple btn-xs" data-toggle="tooltip" data-original-title="Reactivate" data-action="reactivate" data-title="employee" data-urlmain="/employees/" data-id="{{ $employee->id }}">
                                        <i class="fa fa-thumbs-o-up"></i> 
                                    </span>
	                                @else
	                                <span class="action-icon marginleft-zero btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Deactivate" data-action="deactivate" data-title="employee" data-urlmain="/employees/" data-id="{{ $employee->id }}">
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
    <div class="modal" id="modal-view-employee">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">View Employee Information</h4>
                </div>
                    
                <div class="modal-body">
                	<div class="register-box-body">
				        <p class="login-box-msg">Register an account</p>
				        {!! Form::open(['action' => 'UserController@create', 'method' => 'post', 'id' => 'form_add_employee', 'enctype' => "multipart/form-data"]) !!}
				            <div class="form-group">
                                {!! Form::label('First name') !!}
                                {!! Form::text('fname', '', ['class' => 'form-control']) !!}
                                {!! _error($errors->first('fname')) !!}
                            </div>  

                            <div class="form-group">
                                {!! Form::label('Last name') !!}
                                {!! Form::text('lname', '', ['class' => 'form-control']) !!}
                                {!! _error($errors->first('fname')) !!}
                            </div>  

				          	<div class="form-group">
				          		{!! Form::label('Email') !!}
				          		{!! Form::email('email', '', ['class' => 'form-control']) !!}
				          		{!! _error($errors->first('email')) !!}
				          	</div>

							<div class="form-group">
								{!! Form::label('Role') !!}
								<select class="form-control" name="access_level">
									{!! Auth::user()->access_level == 1 ? '<option value="1">Super Admin</option>' : '' !!}
									<option value="2">Branch Admin</option>
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