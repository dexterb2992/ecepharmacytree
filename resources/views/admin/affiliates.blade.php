<?php 
use Carbon\Carbon;
use Illuminate\Support\Str;
?>

@extends('admin.layouts.template')

@section('content')
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<div class="box-header">
					<h3>Affiliates</h3> <br/>
					<small>(Click on each name to view its downlines)</small>
				</div>
				<div class="box-body">
					<table class="table table-bordered table-hover datatable table-referrals">
						<thead>
							<tr>
								<th>Name</th>
								<th>Referral ID</th>
								<th class="sorting_asc">Referrals</th>
								<th>Date Joined</th>
							</tr>
						</thead>
						<tbody>
							@foreach($doctors as $doctor)
								<tr>
									<td>
										<a href="javascript:void(0);" data-id="{{ $doctor->id }}" class="show-downlines">
											<i class="fa fa-user-md"></i>  {{ get_patient_fullname($doctor) }}
										</a>
										<ul class="referral-chart" data-id="{{ $doctor->id }}" style="display:none">
											<li class="bg-light-blue">
												<i class="fa fa-user-md"></i>  
												{!! '<span data-original-title="'.get_patient_fullname($doctor).'" data-toggle="tooltip">'
													.Str::limit(get_patient_fullname($doctor), 15, '').'</span>'
													.'<br/>('.$doctor->referral_id.")" !!}
												<ul>
												{!! extract_downlines( get_all_downlines($doctor->referral_id) ) !!}
												</ul>
											</li>
										</ul>
									</td>
									<td>{{ $doctor->referral_id }}</td>
									<td>{{ get_patient_referrals($doctor) }}</td>
									<td>{{ Carbon::parse($doctor->created_at)->diffForHumans() }}</td>
								</tr>
							@endforeach
							@foreach($patients as $patient)
								<tr>
									<td>
										<a href="javascript:void(0);" data-id="{{ $patient->id }}" class="show-downlines">
											{{ get_patient_fullname($patient) }}
										</a>
										<ul class="referral-chart" data-id="{{ $patient->id }}" style="display:none">
											<li class="bg-light-blue">
												{!! '<span data-original-title="'.get_patient_fullname($patient).'" data-toggle="tooltip">'
													.Str::limit(get_patient_fullname($patient), 15, '').'</span>'
													.'<br/>('.$patient->referral_id.")" !!}
												<ul>
												{!! extract_downlines( get_all_downlines($patient->referral_id) ) !!}
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
	<div class="row referral-chart-row">
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