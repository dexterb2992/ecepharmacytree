<!DOCTYPE html>
<html style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
  <head>
    <meta charset="UTF-8">
    <title>AdminLTE 2 | Invoice</title>
  </head>
  <body onload="window.print();" style="margin: 0;">
    <div class="wrapper">
      <!-- Main content -->
      <section class="invoice" style="display: block;">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <i class="fa fa-globe" style="display: inline-block;font: normal normal normal 14px/1 FontAwesome;font-size: inherit;text-rendering: auto;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;transform: translate(0, 0);"></i> AdminLTE, Inc.
              <small class="pull-right" style="float: right;font-size: 80%;">Date: 2/10/2014</small>
            </h2>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            From
            <address>
              <strong style="font-weight: bold;">Admin, Inc.</strong><br>
              795 Folsom Ave, Suite 600<br>
              San Francisco, CA 94107<br>
              Phone: (804) 123-5432<br>
              Email: info@almasaeedstudio.com
            </address>
          </div><!-- /.col -->
          <div class="col-sm-4 invoice-col">
            To
            <address>
              <strong style="font-weight: bold;">John Doe</strong><br>
              795 Folsom Ave, Suite 600<br>
              San Francisco, CA 94107<br>
              Phone: (555) 539-1037<br>
              Email: john.doe@example.com
            </address>
          </div><!-- /.col -->
          <div class="col-sm-4 invoice-col">
            <b style="font-weight: bold;">Invoice #007612</b><br>
            <br>
            <b style="font-weight: bold;">Order ID:</b> 4F3S8J<br>
            <b style="font-weight: bold;">Payment Due:</b> 2/22/2014<br>
            <b style="font-weight: bold;">Account:</b> 968-34567
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped" style="border-spacing: 0;border-collapse: collapse;">
              <thead>
                <tr>
                  <th style="padding: 0;">Qty</th>
                  <th style="padding: 0;">Product</th>
                  <th style="padding: 0;">Serial #</th>
                  <th style="padding: 0;">Description</th>
                  <th style="padding: 0;">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="padding: 0;">1</td>
                  <td style="padding: 0;">Call of Duty</td>
                  <td style="padding: 0;">455-981-221</td>
                  <td style="padding: 0;">El snort testosterone trophy driving gloves handsome</td>
                  <td style="padding: 0;">$64.50</td>
                </tr>
                <tr>
                  <td style="padding: 0;">1</td>
                  <td style="padding: 0;">Need for Speed IV</td>
                  <td style="padding: 0;">247-925-726</td>
                  <td style="padding: 0;">Wes Anderson umami biodiesel</td>
                  <td style="padding: 0;">$50.00</td>
                </tr>
                <tr>
                  <td style="padding: 0;">1</td>
                  <td style="padding: 0;">Monsters DVD</td>
                  <td style="padding: 0;">735-845-642</td>
                  <td style="padding: 0;">Terry Richardson helvetica tousled street art master</td>
                  <td style="padding: 0;">$10.70</td>
                </tr>
                <tr>
                  <td style="padding: 0;">1</td>
                  <td style="padding: 0;">Grown Ups Blue Ray</td>
                  <td style="padding: 0;">422-568-642</td>
                  <td style="padding: 0;">Tousled lomo letterpress</td>
                  <td style="padding: 0;">$25.99</td>
                </tr>
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
          <!-- accepted payments column -->
          <div class="col-xs-6">
            <p class="lead">Payment Methods:</p>
            <img src="../../dist/img/credit/visa.png" alt="Visa" style="border: 0;">
            <img src="../../dist/img/credit/mastercard.png" alt="Mastercard" style="border: 0;">
            <img src="../../dist/img/credit/american-express.png" alt="American Express" style="border: 0;">
            <img src="../../dist/img/credit/paypal2.png" alt="Paypal" style="border: 0;">
            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
              Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
            </p>
          </div><!-- /.col -->
          <div class="col-xs-6">
            <p class="lead">Amount Due 2/22/2014</p>
            <div class="table-responsive">
              <table class="table" style="border-spacing: 0;border-collapse: collapse;">
                <tr>
                  <th style="width: 50%;padding: 0;">Subtotal:</th>
                  <td style="padding: 0;">$250.30</td>
                </tr>
                <tr>
                  <th style="padding: 0;">Tax (9.3%)</th>
                  <td style="padding: 0;">$10.34</td>
                </tr>
                <tr>
                  <th style="padding: 0;">Shipping:</th>
                  <td style="padding: 0;">$5.80</td>
                </tr>
                <tr>
                  <th style="padding: 0;">Total:</th>
                  <td style="padding: 0;">$265.24</td>
                </tr>
              </table>
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- ./wrapper -->

    <!-- AdminLTE App -->
    <script src="../../dist/js/app.min.js" type="text/javascript"></script>
  </body>
</html>
