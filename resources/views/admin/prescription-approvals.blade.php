@extends('admin.layouts.template')
@section('content')

<div class="row">
  <div class="col-xs-12">
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Prescription Approvals</h3><br/>
        <button class="btn-info btn pull-right view_modal" data-modal-target="#modal-add-edit-clinic" data-target="#form_add_edit_clinics" data-action="create" data-title="clinic"><i class="fa-plus fa"></i> Add New</button>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table class="table table-bordered table-hover datatable">
          <thead>
            <tr>
              <th>Patient Name</th>
              <th>Product</th>
              <th>Quantity</th>
              <th>Prescription Image</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($prescription_approvals as $prescription_approval )
            <tr data-id="{{ $prescription_approval->id }}">
              <td>{{ get_patient_fullname($prescription_approval->patient()->first()) }}</td>
              <td>{{ $prescription_approval->products()->first()->name }}</td>
              <td>{{ $prescription_approval->quantity }}</td>
              <td>
                <a href="javascript:void(0);" class="add-edit-btn" data-action="preview_image" data-modal-target="#modal-view-prescription" data-target="#view-prescription-form">
                  <img class="img-responsive primary-photo table-size-image" name="photo" src="{{ URL::to('/db/uploads/user_'.$prescription_approval->patient_id.'/'.$prescription_approval->patient_prescriptions()->first()->filename) }}" alt="Photo">
                </a>
              </td>
              <td>{{ ($prescription_approval->is_approved == 0 ? 'pending' : ($prescription_approval->is_approved == 1  ? 'approved' : 'disapproved')) }}</td>
              <td>
                <div class="tools">
                 <form id="view-prescription-form" >
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">

                  @if($prescription_approval->is_approved == 0 || $prescription_approval->is_approved == 2)
                  <span class="action-icon marginleft-zero" data-action="approve" data-title="Prescription" data-urlmain="/prescription-approval/" data-id="{{ $prescription_approval->id }}"><i class="fa fa-thumbs-o-up"></i> Approve </span>
                  @endif

                  &nbsp;

                  @if($prescription_approval->is_approved == 0 || $prescription_approval->is_approved == 1 )
                  <span class="action-icon marginleft-zero" data-action="disapprove" data-title="Prescription" data-urlmain="/prescription-approval/" data-id="{{ $prescription_approval->id }}"><i class="fa fa-thumbs-o-down"></i> Disapprove </span>
                  @endif
                  
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div><!-- /.box-body -->
  </div><!-- /.box -->

  <!-- Modal for Create/Edit product -->
  <div class="modal" id="modal-view-prescription">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- <form role="form" id="form_view_member" data-urlmain="/members/"> -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">View Prescription</h4>
        </div>
        <div class="modal-body">
          <div class="ytp-thumbnail-overlay ytp-cued-thumbnail-overlay">
            <img id="image_holder" class="img-responsive primary-photo" src="">
          </div>
        </div>
        <!-- </form> -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div><!-- /.col -->


</div><!-- /.col -->
</div><!-- /.row -->
@stop

