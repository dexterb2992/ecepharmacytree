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
	<table class="content-width auto-margin" cellspacing="0" style="width: 640px;margin: auto;">
		<tr>
			<td style="font-family:Helvetica,Arial,Sans-Serif;line-height:1em;word-break:break-word;background:linear-gradient(to right,#458B74,lightgreen);padding:15px 50px;color:white;">
				<h2>Thank you for your order !</h2>
			</td>
		</tr>
		<tr>
			<td class="gradient-border" style="margin: auto;border: 3px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;padding: 10px;">
				<table class="full-width gradient-border" cellpadding="0" cellspacing="0" border="0" padding="0" style="width: 100%;margin: auto;border: 3px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;padding: 10px;">
					<tr><td><p class="headline-style" style="text-align: center;font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 17px;font-weight: bold;">Details</p></td></tr>
					<tr>
						<td><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td>
					</tr>
					<tr>
						<td>
							<table class="full-width" style="width: 100%;">
								<tr class="normal-tags hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
									<td class="content-styles" style="width: 50%;">Order Id: </td>
									<td class="content-styles" style="width: 50%;">{{ $order_id }}</td>
								</tr>
								<tr><td class="full-width" colspan="2" style="width: 100%;"><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td></tr>
								<tr class="normal-tags" style="font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
									<td class="content-styles" style="width: 50%;">Date and Time: </td>
									<td class="content-styles" style="width: 50%;">{{ $order_date }}</td>
								</tr>
								<tr><td class="full-width" colspan="2" style="width: 100%;"><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td></tr>
								<tr class="normal-tags" style="font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
									<td class="content-styles" style="width: 50%;">Order Receiving Option: </td>
									<td class="content-styles" style="width: 50%;">{{ $modeOfDelivery }}</td>
								</tr>
								<tr><td class="full-width" colspan="2" style="width: 100%;"><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td></tr>
								<tr class="normal-tags" style="font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
									<td class="content-styles" style="width: 50%;">Address For Delivery: </td>
									<td class="content-styles" style="width: 50%;">{{ $recipient_address }}</td>
								</tr>
								<tr><td class="full-width" colspan="2" style="width: 100%;"><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td></tr>
								<tr class="normal-tags" style="font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
									<td class="content-styles" style="width: 50%;">Recipient Name: </td>
									<td class="content-styles" style="width: 50%;">{{ $recipient_name }}</td>
								</tr>
								<tr><td class="full-width" colspan="2" style="width: 100%;"><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td></tr>
								<tr class="normal-tags" style="font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
									<td class="content-styles" style="width: 50%;">Recipient Contact Number: </td>
									<td class="content-styles" style="width: 50%;">{{ $recipient_contactNumber }}</td>
								</tr>
								<tr><td class="full-width" colspan="2" style="width: 100%;"><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td></tr>
								<tr class="normal-tags" style="font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
									<td class="content-styles" style="width: 50%;">Payment Method: </td>
									<td class="content-styles" style="width: 50%;">{{ $payment_method }}</td>
								</tr>
								<tr><td class="full-width" colspan="2" style="width: 100%;"><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td></tr>
								<tr class="normal-tags" style="font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
									<td class="content-styles" style="width: 50%;">Status: </td>
									<td class="content-styles" style="width: 50%;">{{ $status }}</td>
								</tr>
								<tr><td class="full-width" colspan="2" style="width: 100%;"><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td></tr>
								<tr class="normal-tags" style="font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
									<td class="content-styles" style="width: 50%;">Subtotal: </td>
									<td class="content-styles" style="width: 50%;">&#8369 {{ $gross_total }}</td>
								</tr>
								<tr><td class="full-width" colspan="2" style="width: 100%;"><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td></tr>
								<tr class="normal-tags" style="font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
									<td class="content-styles" style="width: 50%;">Coupon Discount: </td>
									<td class="content-styles" style="width: 50%;">&#8369 {{ $coupon_discount }}</td>
								</tr>
								<tr><td class="full-width" colspan="2" style="width: 100%;"><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td></tr>
								<tr class="normal-tags" style="font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
									<td class="content-styles" style="width: 50%;">Points Discount: </td>
									<td class="content-styles" style="width: 50%;">&#8369 {{ $points_discount }}</td>
								</tr>
								<tr><td class="full-width" colspan="2" style="width: 100%;"><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td></tr>
								<tr class="normal-tags" style="font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
									<td class="content-styles" style="width: 50%;">Total: </td>
									<td class="content-styles" style="width: 50%;">&#8369 {{ $totalAmount_final }}</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<table class="full-width gradient-border" cellpadding="0" cellspacing="0" border="0" padding="0" style="margin-top: 10px;width: 100%;margin: auto;border: 3px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;padding: 10px;">
					<tr><td><p class="headline-style" style="text-align: center;font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 17px;font-weight: bold;">Order/s</p></td></tr>
					<tr>
						<td><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td>
					</tr>
					<tr>
						<td>
							<table class="full-width" style="width: 100%;">
								@foreach($order_details as $order_detail)
								<tr class="normal-tags hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;font-family: Helvetica, Arial, Sans-Serif;word-break: break-word;color: #5B9A68;font-size: 15px;">
									<td class="content-styles-full" style="width: 33%;">{{ $order_detail->product_name }} </td>
									<td class="content-styles-full" style="width: 33%;">&#8369 {{ $order_detail->price.' X '.$order_detail->quantity }}</td>
									<td class="content-styles-full" style="width: 33%;">&#8369 {{ $order_detail->price * $order_detail->quantity }}</td>
								</tr>
								<tr><td class="full-width" colspan="3" style="width: 100%;"><hr class="hr-headline" style="border: 1px solid transparent;-moz-border-image: -moz-linear-gradient(right, lightgreen 0%, #458B74 100%);-webkit-border-image: -webkit-linear-gradient(right, lightgreen 0%, #458B74 100%);border-image: linear-gradient(to left, lightgreen 0%, #458B74 100%);border-image-slice: 1;"></td></tr>
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