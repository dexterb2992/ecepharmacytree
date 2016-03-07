<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<style type="text/css">
			table{
				background-size: cover;
				background-position-x: -260px;
			}
		</style>
		<table style="font-family: 'Source Sans Pro',sans-serif;
		    font-size: medium;
		    background-color: rgba(0, 128, 0, 0.15);
		    border-radius: 5px;
		    border: 1px solid;
		    background: #f5f5f5;
		    padding: 10px 10px 0;
		    font: 14px/1.4285714 Arial,sans-serif;
		    display: table;
		    border-collapse: separate;
		    border-spacing: 2px;
		    border-color: rgba(128, 128, 128, 0.52);
		    background-size: cover;
			background-position-x: -260px;
		    background: url({{ url('images/original/email-bg.jpg') }}) no-repeat right top;">
			<thead>
				<tr>
					<th>
						<img src="{{ url('/images/50x50/favicon.png') }}">
						<div style="font-size: larger;">Welcome to Pharmacy Tree</div>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div style="font-size: 14px;color:black !important;">
							<span>Hi {{ $fname.' '.$lname }},</span><br/>
							<b style="color:black;">{{ Auth::user()->fname." ".Auth::user()->lname }}</b> has added you as {{ $role }} at {{ $branch_name }}. <br/>
							<span style="color:black;">Please login using this credentials:</span>
							<br/><br/>
							<span style="color:black;">Email: </span><code>{{ $email }}</code><br/>
							<span style="color:black;">Password: </span><code>{{ $password }}</code>
							<br/><br/>
							<div style="text-align: center;">
								<a style="-webkit-box-shadow: none;
								    box-shadow: none;
								    background-color: #00a65a;
								    border-color: #008d4c;
								    display: inline-block;
								    padding: 6px 12px;
								    margin-bottom: 0;
								    font-size: 14px;
								    font-weight: 400;
								    line-height: 1.42857143;
								    text-align: center;
								    white-space: nowrap;
								    vertical-align: middle;
								    -ms-touch-action: manipulation;
								    touch-action: manipulation;
								    cursor: pointer;
								    -webkit-user-select: none;
								    -moz-user-select: none;
								    -ms-user-select: none;
								    user-select: none;
								    background-image: none;
								    border: 1px solid transparent;
								    border-radius: 0;
								    -webkit-box-shadow: none;
								    -moz-box-shadow: none;
								    box-shadow: none;
								    border-width: 1px;
								    color: #fff;
								    text-decoration: none;" href="{{ url('profile') }}"
								    onmouseover="this.style.backgroundColor='#008d4c'" onmouseout="this.style.backgroundColor='#449d44'">Login now</a>
							</div>
							<br/>
							<b style="font-size: 13px;color: black;">Note: It is strictly recommended that you change your password after your first login.</b>
							<br/>
							<br/>
							
						</div>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td>
						<i>
							<code>
								<small>
									If the link above doesn't work. Please copy and paste this url to your browser.
									<br/>
									{{ url('profile') }}
								</small>
							</code>
						</i>
					</td>
				</tr>
			</tfoot>
		</table>
	</body>
</html>