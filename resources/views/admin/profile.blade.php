@extends('admin.layouts.template')

@section('title', 'Edit Profile')

@section('content')
	<?php 
		$user = isset($user) ? $user : Auth::user(); 
	?>
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<div class="box-header">
					<h3>Profile</h3>
				</div>
				<div class="box-body">
					<div class="col-md-7">
						<div class="row">
							<div class="col-md-6">
								<img id="user_photo" src="{{ url('images/160x160/'.Auth::user()->photo) }}" class="img-responsive" alt="">
								{!! Form::open([
									'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'form_update_user_photo',
									'action' => 'UserController@update_photo'
								]) !!}
								<div class="form-group div-change-user-photo">
									<div class="text-muted no-shadow" id="photo_filename"></div>
									<input class="form-control hidden" type="file" name="photo" id="browse_photo" accept="image/*"/>
									<button class="btn-primary btn btn-xs btn-flat" type="button" onclick="$('#browse_photo').click()">Browse</button>
									{!! Form::submit('Submit', ['class' => 'btn-warning btn-xs btn-flat btn']) !!}
								</div>	
								{!! Form::close() !!}
							</div>
							<div class="col-md-6">
								<div class="basic-info">
									{!! Form::open(['method' => 'post', 'id' => 'form_update_info']) !!}
										<div class="form-group has-feedback">
											{!! Form::text('fname', $user->fname, ['class' => 'form-control', 'placeholder' => 'First name']) !!}
											<span class="glyphicon glyphicon-user form-control-feedback"></span>
											{!! _error($errors->first('fname')) !!}
										</div>

										<div class="form-group has-feedback">	
											{!! Form::text('mname', $user->mname, ['class' => 'form-control', 'placeholder' => 'Middle name (Optional)']) !!}
											<span class="glyphicon glyphicon-user form-control-feedback"></span>
											{!! _error($errors->first('mname')) !!}
										</div>

										<div class="form-group has-feedback">
											{!! Form::text('lname', $user->lname, ['class' => 'form-control', 'placeholder' => 'Last name']) !!}
											<span class="glyphicon glyphicon-user form-control-feedback"></span>
								            {!! _error($errors->first('lname')) !!}
										</div>

										<div class="form-group has-feedback">
								            {!! Form::email('email', $user->email, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
								            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
								            {!! _error($errors->first('email')) !!}
										</div>
										<div class="row">
								            <div class="col-xs-12">
								            	{!! Form::button('Update Info', ['class' => 'btn btn-primary btn-block btn-flat', 'id' => 'btn_update_info']) !!}
								            	{!! Form::button('Change Password', ['class' => 'btn btn-warning btn-block btn-flat', 'id' => 'btn_change_password', 
								            		'data-toggle' => 'modal', 'data-target' => '#modal_update_password']) !!}
								            	
								            </div><!-- /.col -->
								        </div>
									{!! Form::close() !!}
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-5">			
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal" id="modal_update_password">
		<div class="modal-dialog">
			<div class="modal-content">
				{!! Form::open(['method' => 'post', 'id' => 'form_update_password']) !!}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title">Change Password</h4>
				</div>
				<div class="modal-body">
					
					<div class="form-group has-feedback">
						{!! Form::password('old_password', ['class' => 'form-control', 'placeholder' => 'Enter old password']) !!}
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						{!! Form::password('new_password', ['class' => 'form-control', 'placeholder' => 'Enter new password']) !!}
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						{!! Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm new password']) !!}
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="btn_submit_form_update_password">Update password</button>
				</div>
				{!! Form::close() !!}
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
@stop