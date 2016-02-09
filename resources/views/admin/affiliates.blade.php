<?php 
use Carbon\Carbon;
use Illuminate\Support\Str;
?>

@extends('admin.layouts.template')

@section('content')
<div class="row referrals-row">
	<div class="col-xs-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
		        <li class="active"><a href="#referrals" data-toggle="tab">Referrals</a></li>
		        <li><a href="#points_log" data-toggle="tab">Points Log</a></li>
		    </ul>

		    <div class="tab-content">
	            <div class="tab-pane fade in active" id="referrals">
	            	<div class="box box-success">
						<div class="box-header">
							<h3>Affiliates</h3> <br/>
							<small>(Click on each name to view its downlines)</small>
						</div>
						<div class="box-body">
							<table class="table table-bordered table-hover table-referrals">
								<thead>
									<tr>
										<th>Name</th>
										<th>Referral ID</th>
										<th class="sorting_asc">Referrals</th>
										<th>Current Points</th>
										<th>Reffered By</th>
										<th>Date Joined</th>
									</tr>
								</thead>
								<tbody>
									@foreach($doctors as $doctor)
										<tr>
											<td>
												<a href="javascript:void(0);" data-id="d{{ $doctor->id }}" class="show-downlines">
													<i class="fa fa-user-md"></i>  {{ get_person_fullname($doctor) }}
												</a>
												<ul class="referral-chart" data-id="d{{ $doctor->id }}" style="display:none">
													<li class="bg-light-blue">
														<i class="fa fa-user-md"></i>  
														{!! '<span data-original-title="'.get_person_fullname($doctor).'" data-toggle="tooltip">'
															.Str::limit(get_person_fullname($doctor), 15, '').'</span>'
															.'<br/>('.$doctor->referral_id.")" !!}
														<ul>
														{!! extract_downlines( get_all_downlines($doctor->referral_id) ) !!}
														</ul>
													</li>
												</ul>
											</td>
											<td>{{ $doctor->referral_id }}</td>
											<td>{{ get_patient_referrals($doctor) }}</td>
											<td>{{ $doctor->points }}</td>
											<td>{!! get_uplines($doctor->referral_id, true, true) !!}</td>
											<td>
												<span class="label-primary label">
													<i class="fa fa-clock-o"></i>
													{{ $doctor->created_at->diffForHumans() }}
												</span>
											</td>
										</tr>
									@endforeach
									@foreach($patients as $patient)
										<tr>
											<td>
												<a href="javascript:void(0);" data-id="p{{ $patient->id }}" class="show-downlines">
													{{ get_person_fullname($patient) }}
												</a>
												<ul class="referral-chart" data-id="p{{ $patient->id }}" style="display:none">
													<li class="bg-light-blue">
														{!! '<span data-original-title="'.get_person_fullname($patient).'" data-toggle="tooltip">'
															.Str::limit(get_person_fullname($patient), 15, '').'</span>'
															.'<br/>('.$patient->referral_id.")" !!}
														<ul>
														{!! extract_downlines( get_all_downlines($patient->referral_id) ) !!}
														</ul>
													</li>
												</ul>
											</td>
											<td>{{ $patient->referral_id }}</td>
											<td>{{ get_patient_referrals($patient) }}</td>
											<td>{{ $patient->points }}</td>
											<td>{!! get_uplines($patient->referral_id, true, true) !!}</td>
											<td>
												<span class="label-primary label">
													<i class="fa fa-clock-o"></i>
													{{ $patient->created_at->diffForHumans() }}
												</span>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
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
									<div class="scroll-to-top" style="display:none;">
										<a href="javascript:void(0);" class="pull-right">
											<i class="fa-chevron-up fa"></i>
											Scroll to top
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
	            </div>

	            <div class="tab-pane fade" id="points_log">
	            	<div class="box box-success">
	            		<div class="box-header">
	            			<h3>Points Activity Log</h3> <br/>
	            		</div>
	            		<div class="box-body">
	            			<table class="table table-bordered table-hover table-points-log">
								<thead>
									<tr>
										<th>ID</th>
										<th>Name</th>
										<th>Activity</th>
									</tr>
								</thead>
								<tbody>
									@foreach($logs as $log)
									<tr>
										<td>{{ $log->id }}</td>
										<td><b>{{ $log->earner }}</b></td>
										<td>
											<span class="label label-info" data-toggle="tooltip" data-original-title="{{ $log->date }}">
												{{ $log->created_at->diffForHumans() }}
											</span><br/>
											{!! $log->notes !!}

										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
	            		</div>
	            	</div>
	            </div>
	      	</div>
	   </div>
	</div>
</div>
@stop