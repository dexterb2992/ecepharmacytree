@extends('admin.layouts.template')
@section('content')

    <div class="row">
        <div class="col-xs-8">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">Orders/#{{ $order->id.' - '.Carbon\Carbon::parse($order->created_at)->toDayDateTimeString() }}</h3>
                    <br/>
                    <h2 class="next-heading">Order Details</h2>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table" >
                        <?php $order_total = 0; $gross_total = 0; ?>
                        @foreach($order_details as $order_detail)
                            <?php $product_total = $order_detail->price * $order_detail->quantity; ?>
                            <tr>
                                <td>{!! generate_product_link($order_detail->pname) !!}</td>
                                <td>&#8369; {{ $order_detail->price.' x '.$order_detail->quantity }}</td>
                                <td>
                                    @if($order_detail->promo_id > 0)
                                        <p style="text-decoration:line-through">&#8369; {{ $product_total }}</p>
                                        <br/>
                                        <b>
                                            @if($order_detail->promo_type == 'peso_discount')
                                                <?php $product_total -= $order_detail->peso_discount; ?>
                                                    &#8369; {{ $product_total }}
                                            @elseif($order_detail->promo_type == 'percentage_discount')
                                                <?php $product_total -= $order_detail->percentage_discount; ?>                
                                                    &#8369; {{ $product_total }}
                                            @elseif($order_detail->promo_type == 'free_gift')
                                                {{ 'free gift '.$order_detail->free_gift }}
                                            @endif
                                        </b>
                                    @else
                                        <b>&#8369; {{ $product_total }}</b>
                                    @endif
                                </td>
                            </tr>

                            <?php $order_total += $product_total; ?>
                            <?php $gross_total += $order_detail->price * $order_detail->quantity; ?>
                        @endforeach

                        <tr>
                            <td class="borderless">&nbsp;</td class="borderless"><td class="borderless">&nbsp;</td>
                            <td class="borderless">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="borderless"></td>
                            <td class="borderless type-subdued">Subtotal</td>
                            <td class="borderless">&#8369; {{ $order->billing()->first()->gross_total }}</td>
                        </tr>
                        @if($order->billing()->first()->senior_discount > 0)
                            <tr>
                                <td class="borderless"></td>
                                <td class="borderless type-subdued">Senior Discount</td>
                                <td class="borderless">&#8369; {{ $order->billing()->first()->senior_discount }}</td>
                            </tr>
                        @endif
                        @if($order->billing()->first()->coupon_discount > 0)
                            <tr>
                                <td class="borderless"></td>
                                <td class="borderless type-subdued">Coupon Discount</td>
                                <td class="borderless">&#8369; {{ $order->billing()->first()->coupon_discount }}</td>
                            </tr>
                        @endif
                        @if($order->billing()->first()->points_discount > 0)
                            <tr>
                                <td class="borderless"></td>
                                <td class="borderless type-subdued">Points Discount</td>
                                <td class="borderless">&#8369; {{ $order->billing()->first()->points_discount }}</td>
                            </tr>
                        @endif
                        @if($order->modeOfDelivery == 'delivery')
                            @if($order->promo_type == 'free_delivery')
                                <tr>
                                    <td class="borderless"></td>
                                    <td class="borderless type-subdued">Delivery Charge</td>
                                    <td class="borderless"> {{ 'Free' }}</td>
                                </tr>
                            @else
                                <?php $order_total += $order->delivery_charge; ?>
                                <?php $gross_total += $order->delivery_charge; ?>
                                <tr>
                                    <td class="borderless"></td>
                                    <td class="borderless type-subdued">Delivery Charge</td>
                                    <td class="borderless">&#8369; {{ $order->delivery_charge }}</td>
                                </tr>
                            @endif
                        @endif
                        <!-- <tr><td class="borderless"></td><td class="borderless type-subdued">Vatable Sales (12%)</td><td class="borderless">&#8369; {{ $order_total - ($order_total * .12) }}</td></tr> -->
                        <tr>
                            <td class="borderless"></td>
                            <td class="borderless next-heading">Total</td>
                            <td class="borderless next-heading">&#8369; {{ $order->billing()->first()->total }}</td>
                        </tr>
                        <tr>
                            <td class="borderless"></td>
                            <td class="great_border type-subdued">Paid by customer</td>
                            <td class="great_border">&#8369; {{ $order->billing()->first()->total}}</td>
                        </tr>

                        @if($order->billing()->first()->payment_status == "paid")
                            <tr>
                                <td>
                                    <h2 class="next-heading text-green">
                                        <i class="fa fa-check">&nbsp;</i> Payment has been accepted.
                                    </h2>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        @else
                            <tr>
                                <td>
                                    <h2 class="next-heading text-blue">
                                        <span class="fa fa-thumbs-o-up">&nbsp;</span>Accept Payment
                                    </h2>
                                </td>
                                <td></td>
                                <td>
                                    <button class="btn btn-primary margin-top-10 no-margin-left add-edit-btn" data-action="mark_as_paid" data-modal-target="#modal-mark-payment" data-action="fulfill_items">Mark as paid</button>
                                </td>
                            </tr>
                        @endif
                        @if(check_if_not_fulfilled($order))
                            <tr>
                                <td>
                                    <h2 class="next-heading text-red">
                                        <span class="fa fa-truck">&nbsp;</span>Fulfill items
                                    </h2>
                                </td>
                                <td></td>
                                <td>
                                    <button class="btn btn-primary margin-top-10  just-show-the-modal-no-ajax" data-modal-target="#modal-fulfill-items">Fulfill Items</button>
                                </td>
                            </tr>
                        @elseif(check_if_partially_fulfilled($order))
                            <tr>
                                <td>
                                    <h2 class="next-heading text-red">
                                        <span class="fa fa-truck">&nbsp;</span>Fulfill remaining items
                                    </h2>
                                </td>
                                <td></td>
                                <td>
                                    <button class="btn btn-primary margin-top-10 just-show-the-modal-no-ajax" data-modal-target="#modal-fulfill-items" data-action="fulfill_items">Fulfill Items</button>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>
                                    <h2 class="next-heading text-orange"><i class="fa fa-check">&nbsp;</i> All items have been fulfilled.</h2>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                        <!-- Deliver or Notify for pickup -->
                        <tr>
                            <td>
                                <h2 class="next-heading text-red">
                                    <span class="fa fa-check">&nbsp;</span>Deliver
                                </h2>
                            </td>
                            <td></td>
                            <td>
                                <button class="btn btn-primary margin-top-10 no-margin-left just-show-the-modal-no-ajax" data-action="notify_customer" data-modal-target="#modal-notify-customer">Notify Customer</button>
                                      <!-- <button class="btn btn-primary margin-top-10 notify_customer" data-mainurl="{{ URL::route('notify_customer') }}" data-orderid="{{ $order->id }}"><i class="fa fa-check">&nbsp;</i>Deliver</button> -->
                            </td>
                        </tr>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class="col-xs-4">
            <div class="box box-primary">
                <div class="box-header">
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <h3 class="color-black next-heading padding-customer">{{ get_person_fullname($order->patient()->first()) }}</h3>
                        </div>
                    </div>

                    @if(get_senior_age($order->patient()->first())>=60)
                        <div class="user-panel">
                            <div class="pull-left image">
                                <a href="#" data-target="#modal-view-seniorID" data-toggle="modal">
                                    <img src="{{ !empty(get_senior_Id($order->patient()->first())) ? url('db/uploads/user_'.$order->patient_id.'/'.get_senior_Id($order->patient()->first())) : url('images/50x50/nophoto.jpg') }}" class="img-circle" alt="User Image" style ="width: 53px;">
                                </a>
                            </div>
                            <div class="pull-left info">
                                <h3 class="color-black next-heading padding-customer">Senior Citizen ID</h3>
                            </div>
                        </div>
                    @elseif($order->beneficiary_id!=0)

                        @if(get_patient_beneficiariesAge($order->beneficiary_id)>59)
                            <div class="user-panel">
                                <div class="pull-left image">
                                    <a href="#" data-target="#modal-view-seniorID" data-toggle="modal">
                                        <img src="{{ !empty(get_beneficiary_senior_Id($order->beneficiary_id)) ? url('db/uploads/user_'.$order->patient_id.'/'.get_beneficiary_senior_Id($order->beneficiary_id)) : url('images/50x50/nophoto.jpg') }}" class="img-circle" alt="User Image" style ="width: 53px;">
                                    </a>

                                </div>
                                <div class="pull-left info">
                                    <h3 class="color-black next-heading padding-customer">Senior Citizen ID of beneficiary</h3>
                                </div>
                            </div>
                        @endif
                    @endif
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
                                        <td>
                                            {{ Carbon\Carbon::parse($order_details_with_prescription->patient_prescriptions()->first()->created_at)->toDayDateTimeString() }}
                                        </td>
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
        <input type="hidden" name="referral_id" value ="{{ $order->patient()->first()->referral_id }}">
        <div class="modal" id="modal-mark-payment">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
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

    <form method="post" name="notify_customer" action="{{ URL::route('notify_customer') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <input type="hidden" name="email_address" value="{{ $order->patient()->first()->email_address }}">
        <div class="modal" id="modal-notify-customer">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title next-heading">Notify Customer</h4>
                    </div>
                    <div class="modal-body">
                        This will send a notification to customer of <b>Order #{{ $order->id }}</b>
                        <button class="btn btn-primary margin-top-10 add-edit-btn" type="submit">Send Notification</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form method="post" name="fulfill_orders" action="/fulfill_orders">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="branch_id" value="{{ $order->branch_id }}">
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <div class="modal" id="modal-fulfill-items">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
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
                                        <td>{{ ucfirst($order_detail->pname) }}</td>
                                        <td>&nbsp;</td>
                                        <td class="col-xs-3">
                                            @if($order_detail->available_quantity == 0)
                                                <i>Out of Stock</i><br/>
                                                <b>Fulfilled: {{ $order_detail->qty_fulfilled }}</b>
                                                <b>Unfulfilled: {{ $order_detail->quantity - $order_detail->qty_fulfilled }}</b>
                                            @elseif($order_detail->quantity > $order_detail->available_quantity)
                                                <div class="input-group">
                                                    <input type="hidden" name="order_detail_pid[{{ $order_detail->id }}]" value="{{ $order_detail->product_id }}">
                                                    <input type="number" name="order_fulfillment_qty[{{ $order_detail->id }}]" class="form-control" value="{{ $order_detail->available_quantity }}" max="{{ $order_detail->available_quantity }}" min="0">
                                                    <div class="input-group-addon">
                                                        of  <strong>{{ $order_detail->quantity - $order_detail->qty_fulfilled }}</strong>
                                                    </div>
                                                </div>
                                                <b>Limited Stocks</b>
                                            @else
                                                <div class="input-group">
                                                    <input type="hidden" name="order_detail_pid[{{ $order_detail->id }}]" value="{{ $order_detail->product_id }}">
                                                    <input type="number" name="order_fulfillment_qty[{{ $order_detail->id }}]" class="form-control" value="{{ $order_detail->quantity - $order_detail->qty_fulfilled }}" max="{{ $order_detail->quantity - $order_detail->qty_fulfilled }}" min="0">
                                                    <div class="input-group-addon">
                                                        of  <strong>{{ $order_detail->quantity - $order_detail->qty_fulfilled }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="borderless">&nbsp;</td>
                                    <td class="borderless">&nbsp;</td>
                                    <td class="borderless">
                                        <button class="btn btn-primary margin-top-10 add-edit-btn" type="submit">Fulfill Items</button>
                                    </td>
                                </tr>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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


    <div class="modal" id="modal-view-seniorID">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- <form role="form" id="form_view_member" data-urlmain="/members/"> -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">View Senior Citizen ID</h4>
                </div>
                <div class="modal-body">
                    <div class="ytp-thumbnail-overlay ytp-cued-thumbnail-overlay">

                        @if(get_senior_age($order->patient()->first())>=60)
                            <p>
                                <span><b>Senior Citizen ID Number: </b></span>
                                <span>{{ get_senior_Id_number($order->patient()->first()) }}</span>
                            </p>
                            <p>
                                <span><b>Contact Number: </b></span>
                                <span>{{ get_person_number($order->patient()->first()) }}</span>
                            </p>
                            <img id="image_holder" class="img-responsive primary-photo" src="{{ !empty(get_senior_Id($order->patient()->first())) ? url('db/uploads/user_'.$order->patient_id.'/'.get_senior_Id($order->patient()->first())) : url('images/50x50/nophoto.jpg') }}">
                        @elseif($order->beneficiary_id!=0)

                            @if(get_patient_beneficiariesAge($order->beneficiary_id)>59)
                                <p>
                                    <span><b>Senior Citizen ID Number: </b></span>
                                    <span>{{ get_beneficiary_senior_Id_number($order->beneficiary_id) }}</span>
                                </p>
                                <p>
                                    <span><b>Contact Number: </b></span>
                                    <span>{{ get_beneficiary_senior_Id_Contactnumber($order->beneficiary_id) }}</span>
                                </p>

                                <img id="image_holder" class="img-responsive primary-photo" src="{{ !empty(get_beneficiary_senior_Id($order->beneficiary_id)) ? url('db/uploads/user_'.$order->patient_id.'/'.get_beneficiary_senior_Id($order->beneficiary_id)) : url('images/50x50/nophoto.jpg') }}">
                            @endif
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    @if(get_senior_age($order->patient()->first())>=60)
                        @if(get_IsSenior_Status($order->patient()->first())==0)

                            <button type="button" class="btn btn-success pull-right" id="approve_seniorId" data-status="1" data-redirect="/members/update" data-id="{{$order->patient_id}}">Approve </button>
                            <button type="button" class="btn btn-danger" style="margin-right: 10px;" id="disapprove_seniorId" data-redirect="/members/update" data-status="2" data-id="{{$order->patient_id}}">Disapprove</button>
                        @else
                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                        @endif
                        @elseif($order->beneficiary_id!=0)

                            @if(get_patient_beneficiariesAge($order->beneficiary_id)>59)
                                @if(get_beneficiary_Status($order->beneficiary_id)==0)

                                <button type="button" class="btn btn-success pull-right" id="approve_Beneficiary_seniorId" data-status="1" data-redirect="/members/edit_beneficiary" data-id="{{$order->beneficiary_id}}">Approve </button>
                                <button type="button" class="btn btn-danger" style="margin-right: 10px;" id="disapprove_Beneficiary_seniorId" data-redirect="/members/edit_beneficiary" data-status="2" data-id="{{$order->beneficiary_id}}">Disapprove</button>
                            @else
                                <button type="button" class="btn btn-default pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                            @endif
                        @endif
                    @endif
                </div>
                <!-- </form> -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div><!-- /.col -->
@stop

