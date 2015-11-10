@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Orders</h3><br/>
            </div><!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-hover datatable">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Payment Status</th>
                            <th>Fulfillment Status</th>
                            <th>Delivery Option</th>
                            <th>Payment Option</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order )
                        <?php $carbon_date = Carbon\Carbon::parse($order->created_at); ?>
                        <tr data-id="{{ $order->id }}">
                            <td><a href="{{ 'orders/'.$order->id }}">{{ '#'.$order->id }}</a></td>
                            <td>{{ ($carbon_date->isToday() ) ? $carbon_date->diffForHumans() : ($carbon_date->isYesterday() ? $carbon_date->format('\Y\e\s\t\e\r\d\a\y \a\t h:i a') : $carbon_date->toDayDateTimeString()) }}</td>
                            <td>
                                <span>{{ get_person_fullname $order->patient()->first() ) }}</span>
                            </td>
                            <td>{{ ucFirst($order->billing()->first()->payment_status) }}</td>
                            <td>{{ ((check_if_not_fulfilled($order)) ? 'Not Fulfilled' : ((check_if_partially_fulfilled($order)) ? 'Partially Fulfilled' : 'Fulfilled')) }}</td>
                            <td>{{ $order->modeOfDelivery }}</td>
                            <td>{{ $order->billing()->first()->payment_method }}</td>
                            <td>&#8369; {{ $order->billing()->first()->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

</div><!-- /.col -->
</div><!-- /.row -->
@stop

