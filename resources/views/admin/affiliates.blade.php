<?php use Carbon\Carbon; ?>

@extends('admin.layouts.template')

@section('content')
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<div class="box-header">
					<h3>Affiliates</h3> <br/>
				</div>
				<div class="box-body">
					<table class="table table-bordered table-hover datatable table-referrals">
						<thead>
							<tr>
								<th>Name</th>
								<th>Referral ID</th>
								<th>Referrals</th>
								<th>Date Joined</th>
							</tr>
						</thead>
						<tbody>
							@foreach($patients as $patient)
								<tr>
									<td>
										<a href="javascript:void(0);" data-id="{{ $patient->id }}" class="show-downlines">{{ get_patient_fullname($patient) }}</a>
										<ul class="referral-chart" data-id="{{ $patient->id }}" style="display:none">
											<li>
												{!! $patient->fname." ".$patient->lname."<br/>(".$patient->referral_id.")" !!}
												<ul>
												{!! $patient->referred_byUser != '' ? extract_downlines( get_all_downlines($patient->referral_id) ) : 'referred by doctor' !!}
												</ul>
											</li>
										</ul>
									</td>
									<td>{{ $patient->referral_id }}</td>
									<td>{{ get_patient_referrals($patient) }}</td>
									<td>{{ Carbon::parse($patient->created_at)->diffForHumans() }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="box-success box">
				<div class="box-header">
					<h3>Referral Chart</h3> <br/>
				</div>
				<div class="box-body">
					<div id="chart" class="orgChart"></div>
				</div>
			</div>
		</div>
	</div>
	<?php 
		
	?>
@stop