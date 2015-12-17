<!DOCTYPE html>
<html>
<head>
	<title>Invoice</title>
	<style type="text/css">
		.content-width {
			width: 640px;
		}
		.full-width {
			width: 100%;
		}
		.half-width {
			width: 50%
		}
		.auto-margin{
			margin: auto;
		}
		.gradient-border {
			margin: auto;
			border: 3px solid transparent;
			-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);
			-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);
			border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);
			border-image-slice: 1;padding:10px;
		}
		.headline-style{
			text-align:center;
			font-family: Helvetica, Arial, Sans-Serif;
			word-break: break-word;
			color:#5B9A68;font-size:17px;
			font-weight:bold;
		}
		.hr-headline{
			border: 1px solid transparent;
			-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);
			-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);
			border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);
			border-image-slice: 1
		}
		.normal-tags {
			font-family: Helvetica, Arial, Sans-Serif;
			word-break: break-word;color:#5B9A68;
			font-size:15px;
		}
		.content-styles {
			width:50%;
		}
		.content-styles-full{
			width:33%;
		}
	</style>
</head>
<body>
	<table class="content-width auto-margin" cellspacing="0">
		<tr>
			<td style="font-family:Helvetica,Arial,Sans-Serif;line-height:1em;word-break:break-word;background:linear-gradient(to right,#458B74,lightgreen);padding:15px 50px;color:white;">
				<h2>Thank you for your order !</h2>
			</td>
		</tr>
		<tr>
			<td class="gradient-border">
				<table class="full-width gradient-border" cellpadding="0" cellspacing="0" border="0" padding="0">
					<tr><td><p class="headline-style">Details</p></td></tr>
					<tr>
						<td><hr class="hr-headline"/></td>
					</tr>
					<tr>
						<td>
							<table class="full-width">
								<tr class="normal-tags hr-headline">
									<td class="content-styles">Order Id: </td>
									<td class="content-styles">{{ $order_id }}</td>
								</tr>
								<tr><td class="full-width" colspan="2"><hr class="hr-headline"/></td></tr>
								<tr class="normal-tags">
									<td class="content-styles">Date and Time: </td>
									<td class="content-styles">{{ $order_date }}</td>
								</tr>
								<tr><td class="full-width" colspan="2"><hr class="hr-headline"/></td></tr>
								<tr class="normal-tags">
									<td class="content-styles">Order Receiving Option: </td>
									<td class="content-styles">{{ $modeOfDelivery }}</td>
								</tr>
								<tr><td class="full-width" colspan="2"><hr class="hr-headline"/></td></tr>
								<tr class="normal-tags">
									<td class="content-styles">Address For Delivery: </td>
									<td class="content-styles">{{ $recipient_address }}</td>
								</tr>
								<tr><td class="full-width" colspan="2"><hr class="hr-headline"/></td></tr>
								<tr class="normal-tags">
									<td class="content-styles">Recipient Name: </td>
									<td class="content-styles">{{ $recipient_name }}</td>
								</tr>
								<tr><td class="full-width" colspan="2"><hr class="hr-headline"/></td></tr>
								<tr class="normal-tags">
									<td class="content-styles">Recipient Contact Number: </td>
									<td class="content-styles">{{ $recipient_contactNumber }}</td>
								</tr>
								<tr><td class="full-width" colspan="2"><hr class="hr-headline"/></td></tr>
								<tr class="normal-tags">
									<td class="content-styles">Payment Method: </td>
									<td class="content-styles">{{ $payment_method }}</td>
								</tr>
								<tr><td class="full-width" colspan="2"><hr class="hr-headline"/></td></tr>
								<tr class="normal-tags">
									<td class="content-styles">Status: </td>
									<td class="content-styles">{{ $status }}</td>
								</tr>
								<tr><td class="full-width" colspan="2"><hr class="hr-headline"/></td></tr>
								<tr class="normal-tags">
									<td class="content-styles">Subtotal: </td>
									<td class="content-styles">&#8369 {{ $gross_total }}</td>
								</tr>
								<tr><td class="full-width" colspan="2"><hr class="hr-headline"/></td></tr>
								<tr class="normal-tags">
									<td class="content-styles">Coupon Discount: </td>
									<td class="content-styles">&#8369 {{ $coupon_discount }}</td>
								</tr>
								<tr><td class="full-width" colspan="2"><hr class="hr-headline"/></td></tr>
								<tr class="normal-tags">
									<td class="content-styles">Points Discount: </td>
									<td class="content-styles">&#8369 {{ $points_discount }}</td>
								</tr>
								<tr><td class="full-width" colspan="2"><hr class="hr-headline"/></td></tr>
								<tr class="normal-tags">
									<td class="content-styles">Total: </td>
									<td class="content-styles">&#8369 {{ $totalAmount_final }}</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<table class="full-width gradient-border" cellpadding="0" cellspacing="0" border="0" padding="0" style="margin-top:10px">
					<tr><td><p class="headline-style">Order/s</p></td></tr>
					<tr>
						<td><hr class="hr-headline"/></td>
					</tr>
					<tr>
						<td>
							<table class="full-width">
								@foreach($order_details as $order_detail)
								<tr class="normal-tags hr-headline">
									<td class="content-styles-full">{{ $order_detail->product_name }} </td>
									<td class="content-styles-full">&#8369 {{ $order_detail->price.' X '.$order_detail->quantity }}</td>
									<td class="content-styles-full">&#8369 {{ $order_detail->price * $order_detail->quantity }}</td>
								</tr>
								<tr><td class="full-width" colspan="3"><hr class="hr-headline"/></td></tr>
								@endforeach
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>