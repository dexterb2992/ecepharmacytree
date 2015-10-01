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
							<div class="input-group">
								<input class="form-control number" type="text" name="points" value="{{ $settings->points }}" />
								<span class="input-group-addon">for every &#x20B1;100.00</span>
							</div>
						</div>
						<div class="form-group">
							<label>Points to Peso<small><i> ( How much should be equivalent to 1 Point? )</small></i></label>
							<div class="input-group">
								<span class="input-group-addon">&#x20B1; </span>
								<input class="form-control number" type="text" name="points_to_peso" value="{{ $settings->points_to_peso }}" />
								<span class="input-group-addon"> = 1 Point</span>
							</div>
						</div>
						<div class="form-group">
							<label>Level limit</label>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input class="form-control number" type="text" name="level_limit" value="{{ $settings->level_limit }}" />
						</div>
						<div class="form-group">
							<label>Referral Commission <small><i>(% per points earned by Primary Level downlines)</small></i></label>
							<div class="input-group">
								<input class="form-control number" type="text" name="referral_commission" value="{{ $settings->referral_commission }}" />
								<span class="input-group-addon">%</span>
							</div>
							
						</div>

						<div class="form-group">
							<label>
								Commission Variation <small><i>(Variation of Commission per level, for example: You entered 50,  the Referrer will earn {{ $settings->referral_commission }}% of the 
								points earned by his Primary level downlines, the commission will be reduced by 50% in Secondary level and so on and so fourth) </i></small>
							</label>
							<div class="input-group">
								<input class="form-control number" type="text" name="commission_variation" value="{{ $settings->commission_variation }}" />
								<span class="input-group-addon">%</span>
							</div>
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

						<div class="box-header with-border">
							<h3 class="box-title" style="margin-left: -10px;">Delivery</h3>
						</div>

						<div class="form-group">
							<label>Delivery Charge</label>
							<div class="input-group">
								<span class="input-group-addon">&#x20B1;</span>
								<input type="text" name="delivery_charge" class="number form-control" value="{{ $settings->delivery_charge }}">
							</div>
						</div>

						<div class="form-group">
							<label>Minimum Amount <small><i>(Minimum amount in &#x20B1; to accept Delivery)</i></small></label>
							<div class="input-group">
								<span class="input-group-addon">&#x20B1;</span>
								<input type="text" name="delivery_minimum" class="number form-control" value="{{ $settings->delivery_minimum }}">
							</div>
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