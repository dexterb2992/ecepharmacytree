@extends('admin.layouts.template')
@section('content')

<div class="row">
  <div class="col-xs-8">
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Orders/#{{ $order->id.' - '.Carbon\Carbon::parse($order->created_at)->toDayDateTimeString() }}</h3><br/>
        <h2 class="next-heading">Order Details</h2>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table class="table" >
          <?php $order_total = 0; ?>
          @foreach($order_details as $order_detail)
          <tr>
            <!-- <strong><i class="fa fa-close"></i> Unfulfilled</strong> -->
            <td>{{ $order_detail->product()->first()->name }}</td>
            <td>&#8369; {{ $order_detail->product()->first()->price.' x '.$order_detail->quantity }}</td>
            <td>&#8369; {{ ($order_detail->product()->first()->price * $order_detail->quantity) }}</td>
            <?php $order_total = $order_total + ($order_detail->product()->first()->price * $order_detail->quantity); ?>
          </tr>
          @endforeach
          <tr><td class="borderless">&nbsp;</td class="borderless"><td class="borderless">&nbsp;</td><td class="borderless">&nbsp;</td></tr>
          <tr><td class="borderless"></td><td class="borderless type-subdued">Subtotal</td><td class="borderless">&#8369; {{ $order_total }}</td></tr>
          <tr><td class="borderless"></td><td class="borderless type-subdued">Vatable Sales (12%)</td><td class="borderless">&#8369; {{ $order_total - ($order_total * .12) }}</td></tr>
          <tr><td class="borderless"></td><td class="borderless next-heading">Total</td><td class="borderless next-heading">&#8369; {{ $order_total }}</td></tr>
          <tr><td class="borderless"></td><td class="great_border type-subdued">Paid by customer</td><td class="great_border">&#8369; {{ $order_total }}</td></tr>
          
          @if($order->billing()->first()->payment_status == "paid")
          <tr><td><h2 class="next-heading"><i class="fa fa-check">&nbsp;</i> Payment has been accepted.</h2></td><td></td><td></td></tr>
          @else
          <tr><td><h2 class="next-heading"><span class="fa fa-thumbs-o-up">&nbsp;</span>Accept Payment</h2></td><td></td><td>
            <button class="btn btn-primary margin-top-10 no-margin-left add-edit-btn" data-action="mark_as_paid" data-modal-target="#modal-mark-payment" data-action="fulfill_items">Mark as paid</button></td></tr>
            @endif
            @if(check_if_not_fulfilled($order))
            <tr><td><h2 class="next-heading"><span class="fa fa-truck">&nbsp;</span>Fulfill items</h2></td><td></td><td><button class="btn btn-primary margin-top-10 add-edit-btn" data-modal-target="#modal-fulfill-items" data-action="fulfill_items">Fulfill Items</button></td></tr>
            @elseif(check_if_partially_fulfilled($order))
            <tr><td><h2 class="next-heading"><span class="fa fa-truck">&nbsp;</span>Fulfill remaining items</h2></td><td></td><td><button class="btn btn-primary margin-top-10 add-edit-btn" data-modal-target="#modal-fulfill-items" data-action="fulfill_items">Fulfill Items</button></td></tr>
            @else
            <tr><td><h2 class="next-heading"><i class="fa fa-check">&nbsp;</i> All items have been fulfilled.</h2></td><td></td><td></td></tr>
            @endif
            
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
    <div class="col-xs-4">
      <div class="box box-primary">
        <div class="box-header">
          <!-- <h3 class="box-title">{{ get_person_fullname($order->patient()->first()) }}</h3> -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <h3 class="color-black next-heading padding-customer">{{ get_person_fullname($order->patient()->first()) }}</h3>
            </div>
          </div>
        </div>
        <div class="box-body">
          <hr class="margin-top-5"/> 
          <h3 class="next-heading align-center">Shipping Address</h3>
          <p class="align-center">{{ ucfirst($order->recipient_address) }}</p>
          <hr class="margin-top-5"/> 
          <p class="align-center"><i class="fa fa-phone-square">&nbsp;</i>{{ $order->recipient_contactNumber }}</p>
          <hr class="margin-top-5"/> 
          <p class="align-center"><i class="fa fa-truck"></i> <b>Shipping Method:</b> {{ ucFirst($order->modeOfDelivery) }}</p>
          <hr class="margin-top-5"/> 
          <p class="align-center"><span class="pf pf-cash-on-delivery"></span><span class="pf pf-paypal"></span><span class="pf pf-credit-card"></span> <b>Payment Option:</b> {{ ucFirst($order->billing()->first()->payment_method) }}</p>
          <hr class="margin-top-5"/> 
          <p class="align-center"><i class="fa fa-building"></i> <b>Chosen Branch:</b> {{ ucFirst($order->branch()->first()->name) }}</p>
        </div>
      </div>
      @if(check_if_order_had_approved_prescriptions($order))
      <div class="box box-success">
        <div class="box-header">
          <h2 class="next-heading align-center">Prescriptions</h2>
        </div>
        <div class="box-body">
          <table class="table">
            <thead>
              <tr>
                <th>Image</th>
                <th>Date Uploaded</th>
              </tr>
            </thead>
            <tbody>
             @foreach($order_details_with_prescriptions as $order_details_with_prescription)
             <tr>
              <!-- <strong><i class="fa fa-close"></i> Unfulfilled</strong> -->
              <td>
                <a href="javascript:void(0);" class="add-edit-btn" data-action="preview_image" data-modal-target="#modal-view-prescription" data-target="#view-prescription-form">
                  <img class="img-responsive primary-photo table-size-image" name="photo" src="{{ URL::to('/db/uploads/user_'.$order->patient_id.'/'.$order_details_with_prescription->patient_prescriptions()->first()->filename) }}" alt="Photo">
                </a>
              </td>
              <td>{{ Carbon\Carbon::parse($order_details_with_prescription->patient_prescriptions()->first()->created_at)->toDayDateTimeString() }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
  </div>
</div><!-- /.row -->

<form method="post" name="order_form_nothing">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>

<form method="post" name="mark_payment" action="{{ URL::route('mark_order_as_paid') }}">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input type="hidden" name="order_id" value="{{ $order->id }}">
  <div class="modal" id="modal-mark-payment">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title next-heading">Mark Payment</h4>
        </div>
        <div class="modal-body">
          Processed by <b>{{ $order->billing()->first()->payment_method }}</b>
        <input type="text" name="or_txn_number" class="form-control" required>
        <button class="btn btn-primary margin-top-10 add-edit-btn" type="submit">Accept Payment</button>
        </div>
      </div>
    </div>
  </div>
</form>

<form method="post" name="fulfill_orders" action="/fulfill_orders">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <div class="modal" id="modal-fulfill-items">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title next-heading">Fulfill Items</h4>
        </div>
        <div class="modal-body">
          <table class="table">
            <thead>
              <th>Items</th>
              <th></th>
              <th>Quantity</th>
            </thead>
            <tbody>
              @foreach($order_details as $order_detail)
              <tr>
                <td>{{ ucfirst($order_detail->product()->first()->name) }}</td>
                <td>&nbsp;</td>
                <td class="col-xs-3">
                  <div class="input-group">
                    <input type="number" name="order_fulfillment_qty[{{ $order_detail->id }}]" class="form-control" value="{{ $order_detail->quantity }}" max="{{ $order_detail->quantity }}" min="0">
                    <div class="input-group-addon">
                      of  <strong>{{ $order_detail->quantity }}</strong>
                    </div>
                  </div>
                </td>
              </tr>
              @endforeach
              <tr><td class="borderless">&nbsp;</td><td class="borderless">&nbsp;</td><td class="borderless"><button class="btn btn-primary margin-top-10 add-edit-btn" type="submit">Fulfill Items</button></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</form>

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
@stop

