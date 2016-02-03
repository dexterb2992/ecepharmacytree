<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title> Pharmacy Tree | Invoice</title>
   <style type="text/css">
      .invoice {
      position: relative;
      background: #FFF none repeat scroll 0% 0%;
      border: 1px solid #F4F4F4;
      padding: 20px;
      margin: 10px 25px;
    }
    article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
      display: block;
    }

    * {
      box-sizing: border-box;
    }

    .h1 .small, .h1 small, .h2 .small, .h2 small, .h3 .small, .h3 small, h1 .small, h1 small, h2 .small, h2 small, h3 .small, h3 small {
      font-size: 65%;}

      body {
        font-family: "Source Sans Pro","Helvetica Neue",Helvetica,Arial,sans-serif;
        font-weight: 400;
      }
      body {
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 14px;
        line-height: 1.42857;
        color: #333;
      }
      .col-xs-12 {
        width: 100%;
      }
      .page-header {
        margin: 10px 0px 20px;
        font-size: 22px;
      }
      .page-header {
        padding-bottom: 9px;
        margin: 40px 0px 20px;
        border-bottom: 1px solid #EEE;
      }
      .page-header > small {
        color: #666;
        display: block;
        margin-top: 5px;
      }
      .pull-right {
        float: right !important;
      }

      .col-sm-4 {
        width: 33.3333%;
      }
      .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9 {
        float: left;
      }
      .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
        float: left;
      }
      .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
      }

      table {
        border-spacing:0;
        border-collapse:collapse
      }
      td,th {
        padding:0
      }

      table {
        background-color:transparent
      }
      caption {
        padding-top:8px;
        padding-bottom:8px;
        color:#777;
        text-align:left
      }
      th {
        text-align:left
      }
      .table {
        width:100%;
        max-width:100%;
        margin-bottom:20px
      }
      .table>tbody>tr>td,.table>tbody>tr>th,.table>tfoot>tr>td,.table>tfoot>tr>th,.table>thead>tr>td,.table>thead>tr>th {
        padding:8px;
        line-height:1.42857143;
        vertical-align:top;
        border-top:1px solid #ddd
      }
      .table>thead>tr>th {
        vertical-align:bottom;
        border-bottom:2px solid #ddd
      }
      .table>caption+thead>tr:first-child>td,.table>caption+thead>tr:first-child>th,.table>colgroup+thead>tr:first-child>td,.table>colgroup+thead>tr:first-child>th,.table>thead:first-child>tr:first-child>td,.table>thead:first-child>tr:first-child>th {
        border-top:0
      }
      .table>tbody+tbody {
        border-top:2px solid #ddd
      }
      .table .table {
        background-color:#fff
      }
      .table-condensed>tbody>tr>td,.table-condensed>tbody>tr>th,.table-condensed>tfoot>tr>td,.table-condensed>tfoot>tr>th,.table-condensed>thead>tr>td,.table-condensed>thead>tr>th {
        padding:5px
      }
      .table-bordered {
        border:1px solid #ddd
      }
      .table-bordered>tbody>tr>td,.table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>td,.table-bordered>tfoot>tr>th,.table-bordered>thead>tr>td,.table-bordered>thead>tr>th {
        border:1px solid #ddd
      }
      .table-bordered>thead>tr>td,.table-bordered>thead>tr>th {
        border-bottom-width:2px
      }
      .table-striped>tbody>tr:nth-of-type(odd) {
        background-color:#f9f9f9
      }
      .table-hover>tbody>tr:hover {
        background-color:#f5f5f5
      }
      table col[class*=col-] {
        position:static;
        display:table-column;
        float:none
      }
      table td[class*=col-],table th[class*=col-] {
        position:static;
        display:table-cell;
        float:none
      }
      .table>tbody>tr.active>td,.table>tbody>tr.active>th,.table>tbody>tr>td.active,.table>tbody>tr>th.active,.table>tfoot>tr.active>td,.table>tfoot>tr.active>th,.table>tfoot>tr>td.active,.table>tfoot>tr>th.active,.table>thead>tr.active>td,.table>thead>tr.active>th,.table>thead>tr>td.active,.table>thead>tr>th.active {
        background-color:#f5f5f5
      }
      .table-hover>tbody>tr.active:hover>td,.table-hover>tbody>tr.active:hover>th,.table-hover>tbody>tr:hover>.active,.table-hover>tbody>tr>td.active:hover,.table-hover>tbody>tr>th.active:hover {
        background-color:#e8e8e8
      }
      .table>tbody>tr.success>td,.table>tbody>tr.success>th,.table>tbody>tr>td.success,.table>tbody>tr>th.success,.table>tfoot>tr.success>td,.table>tfoot>tr.success>th,.table>tfoot>tr>td.success,.table>tfoot>tr>th.success,.table>thead>tr.success>td,.table>thead>tr.success>th,.table>thead>tr>td.success,.table>thead>tr>th.success {
        background-color:#dff0d8
      }
      .table-hover>tbody>tr.success:hover>td,.table-hover>tbody>tr.success:hover>th,.table-hover>tbody>tr:hover>.success,.table-hover>tbody>tr>td.success:hover,.table-hover>tbody>tr>th.success:hover {
        background-color:#d0e9c6
      }
      .table>tbody>tr.info>td,.table>tbody>tr.info>th,.table>tbody>tr>td.info,.table>tbody>tr>th.info,.table>tfoot>tr.info>td,.table>tfoot>tr.info>th,.table>tfoot>tr>td.info,.table>tfoot>tr>th.info,.table>thead>tr.info>td,.table>thead>tr.info>th,.table>thead>tr>td.info,.table>thead>tr>th.info {
        background-color:#d9edf7
      }
      .table-hover>tbody>tr.info:hover>td,.table-hover>tbody>tr.info:hover>th,.table-hover>tbody>tr:hover>.info,.table-hover>tbody>tr>td.info:hover,.table-hover>tbody>tr>th.info:hover {
        background-color:#c4e3f3
      }
      .table>tbody>tr.warning>td,.table>tbody>tr.warning>th,.table>tbody>tr>td.warning,.table>tbody>tr>th.warning,.table>tfoot>tr.warning>td,.table>tfoot>tr.warning>th,.table>tfoot>tr>td.warning,.table>tfoot>tr>th.warning,.table>thead>tr.warning>td,.table>thead>tr.warning>th,.table>thead>tr>td.warning,.table>thead>tr>th.warning {
        background-color:#fcf8e3
      }
      .table-hover>tbody>tr.warning:hover>td,.table-hover>tbody>tr.warning:hover>th,.table-hover>tbody>tr:hover>.warning,.table-hover>tbody>tr>td.warning:hover,.table-hover>tbody>tr>th.warning:hover {
        background-color:#faf2cc
      }
      .table>tbody>tr.danger>td,.table>tbody>tr.danger>th,.table>tbody>tr>td.danger,.table>tbody>tr>th.danger,.table>tfoot>tr.danger>td,.table>tfoot>tr.danger>th,.table>tfoot>tr>td.danger,.table>tfoot>tr>th.danger,.table>thead>tr.danger>td,.table>thead>tr.danger>th,.table>thead>tr>td.danger,.table>thead>tr>th.danger {
        background-color:#f2dede
      }
      .table-hover>tbody>tr.danger:hover>td,.table-hover>tbody>tr.danger:hover>th,.table-hover>tbody>tr:hover>.danger,.table-hover>tbody>tr>td.danger:hover,.table-hover>tbody>tr>th.danger:hover {
        background-color:#ebcccc
      }
      .table-responsive {
        min-height:.01%;
        overflow-x:auto
      }
      @media screen and (max-width:767px) {
        .table-responsive {
          width:100%;
          margin-bottom:15px;
          overflow-y:hidden;
          -ms-overflow-style:-ms-autohiding-scrollbar;
          border:1px solid #ddd
        }
        .table-responsive>.table {
          margin-bottom:0
        }
        .table-responsive>.table>tbody>tr>td,.table-responsive>.table>tbody>tr>th,.table-responsive>.table>tfoot>tr>td,.table-responsive>.table>tfoot>tr>th,.table-responsive>.table>thead>tr>td,.table-responsive>.table>thead>tr>th {
          white-space:nowrap
        }
        .table-responsive>.table-bordered {
          border:0
        }
        .table-responsive>.table-bordered>tbody>tr>td:first-child,.table-responsive>.table-bordered>tbody>tr>th:first-child,.table-responsive>.table-bordered>tfoot>tr>td:first-child,.table-responsive>.table-bordered>tfoot>tr>th:first-child,.table-responsive>.table-bordered>thead>tr>td:first-child,.table-responsive>.table-bordered>thead>tr>th:first-child {
          border-left:0
        }
        .table-responsive>.table-bordered>tbody>tr>td:last-child,.table-responsive>.table-bordered>tbody>tr>th:last-child,.table-responsive>.table-bordered>tfoot>tr>td:last-child,.table-responsive>.table-bordered>tfoot>tr>th:last-child,.table-responsive>.table-bordered>thead>tr>td:last-child,.table-responsive>.table-bordered>thead>tr>th:last-child {
          border-right:0
        }
        .table-responsive>.table-bordered>tbody>tr:last-child>td,.table-responsive>.table-bordered>tbody>tr:last-child>th,.table-responsive>.table-bordered>tfoot>tr:last-child>td,.table-responsive>.table-bordered>tfoot>tr:last-child>th {
          border-bottom:0
        }
      }
      .col-xs-6 {
        width: 50%;
      }
      .lead {
        font-size: 21px;
      }
      .text-muted {
        color: #777;
      }
      p {
        margin: 0px 0px 10px;
      }
      .no-shadow {
        box-shadow: none !important;
      }
      .well-sm {
        padding: 9px;
        border-radius: 3px;
      }
      .well {
        min-height: 20px;
        padding: 19px;
        margin-bottom: 20px;
        background-color: #F5F5F5;
        border: 1px solid #E3E3E3;
        border-radius: 4px;
        box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.05) inset;
      }
   </style>
  </head>
  <body>
    <div class="wrapper">
      <!-- Main content -->
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <i class="fa fa-globe"></i> Pharmacy Tree
              <small class="pull-right">Date: {{ $order_date }}</small>
            </h2>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            From
            <address>
              <strong>Admin, Inc.</strong><br>
              795 Folsom Ave, Suite 600<br>
              San Francisco, CA 94107<br>
              Phone: (804) 123-5432<br/>
            </address>
          </div><!-- /.col -->
          <div class="col-sm-4 invoice-col">
            To
            <address>
              <strong>{{ $recipient_name }}</strong><br>
              {{ $recipient_address }}<br>
              Phone: {{ $recipient_contactNumber }}<br/>
              Email: {{ $email }}
            </address>
          </div><!-- /.col -->
          <div class="col-sm-4 invoice-col">
            <b>Order ID:</b> {{ $order_id }}<br/>
            <!-- <b>Payment Due:</b> 2/22/2014<br/> -->
            <!-- <b>Account:</b> 968-34567 -->
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Price x Qty</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order_details as $order_detail)
                <tr class="normal-tags hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
                  <td class="content-styles-full" style="width: 33%;">{{ $order_detail->product_name }} </td>
                  <td class="content-styles-full" style="width: 33%;">₱ {{ $order_detail->price.' X '.$order_detail->quantity }}</td>
                  <td class="content-styles-full" style="width: 33%;">₱ {{ $order_detail->price * $order_detail->quantity }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
          <!-- accepted payments column -->
          <div class="col-xs-6">
            <p class="lead">Order Details:</p>
            <img src="../../dist/img/credit/visa.png" alt="Visa" />
            <img src="../../dist/img/credit/mastercard.png" alt="Mastercard" />
            <img src="../../dist/img/credit/american-express.png" alt="American Express" />
            <img src="../../dist/img/credit/paypal2.png" alt="Paypal" />
            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
              <address>
              Order receiving option: {{ $modeOfDelivery }}<br/>
              Payment method: {{ $payment_method }}
            </address>
            </p>
          </div><!-- /.col -->
          <div class="col-xs-6">
            <p class="lead">Amount Due 2/22/2014</p>
            <div class="table-responsive">
              <table class="table">
                <tr>
                  <th style="width:50%">Subtotal:</th>
                  <td>{{ $gross_total }}</td>
                </tr>
                <tr>
                  <th>Delivery Charge:</th>
                  <td>P 25</td>
                </tr>
                <tr>
                  <th>Total:</th>
                  <td>{{ $totalAmount_final }}</td>
                </tr>
              </table>
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- ./wrapper -->
  </body>
</html>
