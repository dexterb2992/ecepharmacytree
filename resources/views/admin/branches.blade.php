@extends('admin.layouts.template')
@section('content')

	<div class="row">
        <div class="col-xs-12">
          <div class="box box-success">
            <div class="box-header">
              <h3 class="box-title">Branches</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered table-hover datatable">
                <thead>
                  <tr>
                  	<th>Name</th>
                    <th>Address</th>
                    <th>Active</th>
                    <th>Date Added</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                	@foreach($branches as $branch)
                		<?php 
                			$address = $branch->unit_floor_room_no." ".
                				$branch->building." ".$branch->lot_no." ".$branch->block_no." ".
                				$branch->phase_no." ".
                				$branch->address_street." <br>".
                				$branch->address_barangay.", ".
                				$branch->address_city_municipality.", ".
                				$branch->address_province." <br>".
                				$branch->address_region.", ".
                				$branch->address_zip." ";
                		?>
                		<tr data-id="{{ $branch->id }}">
                			<td>
                				<a href="javascript:void(0);" class="edit-branch" data-id="{{ $branch->id }}" title="Edit">
                					<i class="fa fa-edit"></i>
                				</a>
                				<span>{{ $branch->name }}</span>
                			</td>
                			<td>
                				<span><?php echo $address; ?></span>
                			</td>
                			<td>{{ $branch->status == 1? 'Yes' : 'No' }}</td>
                			<td>{{ $branch->created_at }}</td>
                			<td>
                				<div class="tools">
                					<span class="action-icon deactivate-branch" data-id="{{ $branch->id }}"><i class="fa fa-warning"></i> Deactivate</span>
                					<span class="action-icon remove-branch" data-id="{{ $branch->id }}"><i class="fa fa-trash-o"></i> Remove</span>
                				</div>
                			</td>
                		</tr>
                	@endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th>Address</th>
                    <th>Active</th>
                    <th>Date Added</th>
                    <th>Action</th>
                  </tr>
                </tfoot>
              </table>
            </div><!-- /.box-body -->
          </div><!-- /.box -->

           <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Create Branch</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" id="form_edit_branch" data-mode="create" action="branches/create">
                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="box-body">
	                    <div class="form-group">
	                      <label for="name">Branch Name <i>*</i></label>
	                      <input type="text" class="form-control" id="name" placeholder="Branch name" name="name" required>
	                    </div>
	                    <div class="row">
		                    <div class="col-xs-4">
		                      <label for="unit_floor_room_no">Unit/Room No.</label>
		                    </div>
		                    <div class="col-xs-4">
		                      <label for="building">Building</label>
		                    </div>
		                    <div class="col-xs-4">
		                    	<label for="lot_no">Lot No.</label>
		                    </div>
		                </div>
	                    <div class="row">
		                    <div class="col-xs-4">
		                      <input type="text" class="form-control" name="unit_floor_room_no" id="unit_floor_room_no" placeholder="Unit/Room No.">
		                    </div>
		                    <div class="col-xs-4">
		                      <input type="text" class="form-control" name="building" id="building" placeholder="Building">
		                    </div>
		                    <div class="col-xs-4">
		                      <input type="text" class="form-control" name="lot_no" id="lot_no" placeholder="Lot No.">
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="col-xs-4">
		                      <label for="block_no">Block No.</label>
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
		                      <input type="text" class="form-control" name="block_no" id="block_no" placeholder="Block No.">
		                    </div>
		                    <div class="col-xs-4">
		                      <input type="text" class="form-control" name="phase_no" id="phase_no" placeholder="Phase No.">
		                    </div>
		                    <div class="col-xs-4">
		                      <input type="text" class="form-control" name="address_zip" id="address_zip" placeholder="ZIP Code" required>
		                    </div>
		                </div>
		                <div class="form-group">
	                        <label for="address_street">Street and/or Subdivision (<i>Include Subdivision if applicable</i>)</label>
	                        <input type="text" class="form-control" id="address_street" placeholder="Street" name="address_street" required>
	                    </div>
	                    <div class="form-group">
	                        <label for="address_barangay">Barangay<i>*</i></label>
	                        <input type="text" class="form-control" id="address_barangay" placeholder="Barangay" name="address_barangay" required>
	                    </div>
	                    <div class="form-group">
							<label for="address_city_municipality">Municipality<i>*</i></label>
							<input type="text" class="form-control" id="address_city_municipality" placeholder="Municipality" name="address_city_municipality" required>
	                    </div>
	                    <div class="form-group">
	                      	<label for="address_province">Province<i>*</i></label>
	                      	<input type="text" class="form-control" id="address_province" placeholder="Province" name="address_province" required>
	                    </div>
	                    <div class="form-group">
							<label for="address_province">Region<i>*</i></label>
							<select class="form-control" name="address_region">
								@foreach(get_ph_regions() as $region)
									<option value="{{ $region }}">{{ $region }}</option>
								@endforeach
							</select>
	                    </div>
                  	</div><!-- /.box-body -->

                  	<div class="box-footer">
                    	<button type="submit" class="btn btn-primary" name="submit">Submit</button>
                  	</div>
                </form>
              </div><!-- /.box -->

          
        </div><!-- /.col -->
      </div><!-- /.row -->
@stop