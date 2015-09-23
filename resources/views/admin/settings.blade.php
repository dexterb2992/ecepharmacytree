@extends('admin.layouts.template')
@section('content')

	<div class="row">
		<div class="col-xs-6">
			<div class="box-success box">
				<div class="box-header with-border">
					<h3 class="box-title">Referral Settings</h3>
				</div>
				<form method="post" action="{{ route('Settings::update') }}">
					<div class="box-body">
						<div class="form-group">
							<label>Points</label>
							<input class="form-control number" type="text" name="points" value="{{ $settings->points }}" />
						</div>
						<div class="form-group">
							<label>Level limit</label>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input class="form-control number" type="text" name="level_limit" value="{{ $settings->level_limit }}" />
						</div>

						<div class="box-header with-border">
							<h3 class="box-title" style="margin-left: -10px;">Inventory Settings</h3>
						</div>

						<div class="form-group">
							<label>Default Safety Stock </label>
							<input class="number form-control" type="text" name="safety_stock" title="This will automatically be used as a safety stock number to any product whose safety stock is not specified." 
								value="{{ $settings->safety_stock }}" />
						</div>

						<div class="form-group">
							<label>Default Critical Inventory Number</label>
							<input class="form-control number" type="text" name="critical_stock"
								title="This will inform us when to notify you when any of the products is on a critical stock." value="{{ $settings->critical_stock }}" />
						</div>


						<div class="form-group">
							<button class="btn btn-primary pull-right" type="submit">Save Changes</button>
						</div>
							
					</div>
				</form>
			</div>
		</div>
	</div>

@stop