<!DOCTYPE html>
<html style="box-sizing: border-box;">
  <head style="box-sizing: border-box;">
    <meta charset="UTF-8" style="box-sizing: border-box;">
    <title style="box-sizing: border-box;"> Pharmacy Tree | Invoice</title>
  </head>
  <body style="box-sizing: border-box;font-family: &quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-weight: 400;font-size: 14px;line-height: 1.42857;color: #333;">
    <div class="wrapper" style="box-sizing: border-box;">
      <!-- Main content -->
      <section class="invoice" style="box-sizing: border-box;display: block;position: relative;background: #FFF none repeat scroll 0% 0%;border: 1px solid #F4F4F4;padding: 20px;margin: 10px 25px;">
        <!-- title row -->
        <div class="row" style="box-sizing: border-box;">
          <div class="col-xs-12" style="box-sizing: border-box;width: 100%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
            <h2 class="page-header" style="box-sizing: border-box;margin: 40px 0px 20px;font-size: 22px;padding-bottom: 9px;border-bottom: 1px solid #EEE;">
              <i class="fa fa-globe" style="box-sizing: border-box;"></i> Pharmacy Tree
              <small class="pull-right" style="box-sizing: border-box;font-size: 65%;color: #666;display: block;margin-top: 5px;float: right !important;">Date: {{ $order_date }}</small>
            </h2>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info" style="box-sizing: border-box;">
          <div class="col-sm-4 invoice-col" style="box-sizing: border-box;width: 33.3333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
            From
            <address style="box-sizing: border-box;">
              <strong style="box-sizing: border-box;">Admin, Inc.</strong><br style="box-sizing: border-box;">
              795 Folsom Ave, Suite 600<br style="box-sizing: border-box;">
              San Francisco, CA 94107<br style="box-sizing: border-box;">
              Phone: (804) 123-5432<br style="box-sizing: border-box;">
            </address>
          </div><!-- /.col -->
          <div class="col-sm-4 invoice-col" style="box-sizing: border-box;width: 33.3333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
            To
            <address style="box-sizing: border-box;">
              <strong style="box-sizing: border-box;">{{ $recipient_name }}</strong><br style="box-sizing: border-box;">
              {{ $recipient_address }}<br style="box-sizing: border-box;">
              Phone: {{ $recipient_contactNumber }}<br style="box-sizing: border-box;">
              Email: {{ $email }}
            </address>
          </div><!-- /.col -->
          <div class="col-sm-4 invoice-col" style="box-sizing: border-box;width: 33.3333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
            <!-- <b style="box-sizing: border-box;">Order ID:</b> {{ $order_id }}<br style="box-sizing: border-box;"> -->
            <!-- <b>Payment Due:</b> 2/22/2014<br/> -->
            <!-- <b>Account:</b> 968-34567 -->
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="row" style="box-sizing: border-box;margin-top:20px;">
          <div class="col-xs-12 table-responsive" style="box-sizing: border-box;width: 100%;float: left;position: relative;min-height: .01%;padding-right: 15px;padding-left: 15px;overflow-x: auto;">
            <table class="table table-striped" style="box-sizing: border-box;border-spacing: 0;border-collapse: collapse;background-color: transparent;width: 100%;max-width: 100%;margin-bottom: 0;">
              <thead style="box-sizing: border-box;">
                <tr style="box-sizing: border-box;">
                  <th style="box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;white-space: nowrap;">Product</th>
                  <th style="box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;white-space: nowrap;">Price x Qty</th>
                  <th style="box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;white-space: nowrap;">Subtotal</th>
                </tr>
              </thead>
              <tbody style="box-sizing: border-box;">
                @foreach($order_details as $order_detail)
                <tr style="box-sizing: border-box;">
                  <td style="box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;white-space: nowrap;">{{ $order_detail->product_name }} </td>
                  <td style="box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;white-space: nowrap;">₱ {{ $order_detail->price.' X '.$order_detail->quantity }}</td>
                  <td style="box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;white-space: nowrap;">₱ {{ $order_detail->price * $order_detail->quantity }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row" style="box-sizing: border-box;margin-top:20px;">
          <!-- accepted payments column -->
          <div class="col-xs-6" style="box-sizing: border-box;min-height: 1px;padding-right: 15px;padding-left: 15px;width: 50%;">
            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;box-sizing: border-box;margin: 0px 0px 10px;color: #777;padding: 19px;border-radius: 4px;min-height: 20px;margin-bottom: 20px;background-color: #F5F5F5;border: 1px solid #E3E3E3;box-shadow: none !important;">
              <strong> Order ID: {{ $order_id }} </strong><br style="box-sizing: border-box;">
              Order receiving option: {{ $modeOfDelivery }}<br style="box-sizing: border-box;">
              Payment method: {{ $payment_method }}
            </p>
          </div><!-- /.col -->
          <div class="col-xs-6" style="box-sizing: border-box;padding-right: 15px;padding-left: 15px;width: 50%;">
            <!-- <p class="lead" style="box-sizing: border-box;margin: 0px 0px 10px;font-size: 21px;">Amount Due 2/22/2014</p> -->
            <div class="table-responsive" style="box-sizing: border-box;min-height: .01%;overflow-x: auto;">
              <table class="table" style="box-sizing: border-box;border-spacing: 0;border-collapse: collapse;background-color: transparent;width: 100%;max-width: 100%;margin-bottom: 0;">
                <tr style="box-sizing: border-box;">
                  <th style="width: 50%;box-sizing: border-box;padding: 0;text-align: left;">Subtotal:</th>
                  <td style="box-sizing: border-box;padding: 0;">{{ $gross_total }}</td>
                </tr>
                <tr style="box-sizing: border-box;">
                  <th style="box-sizing: border-box;padding: 0;text-align: left;">Delivery Charge:</th>
                  <td style="box-sizing: border-box;padding: 0;">P 25</td>
                </tr>
                <tr style="box-sizing: border-box;">
                  <th style="box-sizing: border-box;padding: 0;text-align: left;">Total:</th>
                  <td style="box-sizing: border-box;padding: 0;">{{ $totalAmount_final }}</td>
                </tr>
              </table>
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- ./wrapper -->
  </body>
</html>
