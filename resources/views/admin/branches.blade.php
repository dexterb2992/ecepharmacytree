<?php use Carbon\Carbon; ?>
@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Branches</h3><br/>
                <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-branch" data-target="#form_edit_branch" data-action="create" data-title="branch"><i class="fa-plus fa"></i> Add New</button>
            </div><!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-hover datatable">
                    <thead>
                        <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Status</th>
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
                            <tr data-id="{{ $branch->id }}" class="{{ $branch->status == 1? '' : 'warning' }}">
                                <td>
                                    <a href="javascript:void(0);" class="add-edit-btn" data-action="edit" data-modal-target="#modal-add-edit-branch" data-title="branch info" data-target="#form_edit_branch" data-id="{{ $branch->id }}" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <span>{{ $branch->name }}</span>
                                </td>
                                <td>
                                    <span>{!! $address !!}</span>
                                </td>
                                <td>{!! $branch->status == 1? '<span class="label label-info">Active</span>' : '<span class="label label-warning">Inactive</span>' !!}</td>
                                <td>
                                    <span class="label-primary label"><i class="fa-clock-o fa"></i>
                                        {{ Carbon::parse($branch->created_at)->diffForHumans() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="tools">
                                        
                                        @if($branch->status == 1)
                                            <span class="action-icon deactivate-branch" data-title="category" data-urlmain="/branches/" data-action="deactivate" data-id="{{ $branch->id }}"><i class="fa fa-warning"></i> Deactivate</span> 
                                        @else
                                            <span class="action-icon reactivate-branch" data-title="category" data-urlmain="/branches/" data-action="reactivate" data-id="{{ $branch->id }}"><i class="fa fa-check-square-o"></i> Reactivate</span>
                                        @endif
                                        <span class="action-icon remove-branch" data-action="remove" data-title="category" data-urlmain="/branches/" data-id="{{ $branch->id }}"><i class="fa fa-trash-o"></i> Remove</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

        <!-- Modal for Create/Edit Branch -->
        <div class="modal" id="modal-add-edit-branch">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- form start -->
                    <form role="form" id="form_edit_branch" data-mode="create" method="post" action="/branches/create" data-urlmain="/branches/">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add new branch</h4>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                                    <label for="block_no">Block No.</label>
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
                                    <input type="text" class="form-control" name="block_no" id="block_no" placeholder="Block No.">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <label for="lot_no">Lot No.</label>
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
                                    <input type="text" class="form-control" name="lot_no" id="lot_no" placeholder="Lot No.">
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="submit">Save changes</button>
                        </div>
                    </div><!-- /.modal-content -->
                </form><!-- /form -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        

    </div><!-- /.col -->
</div><!-- /.row -->
@stop

