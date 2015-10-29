<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<!-- <h2>Welcome to Pharmacy Tree</h2> -->

		<!-- <div>
			{{ Auth::user()->fname." ".Auth::user()->lname }} has added you as {{ $role }} at {{ $branch_name }}. Please login <a href="{{ url('auth/login') }}">here</a> using this credentials:<br/>
			Email: {{ $email }}<br/>
			Password: {{ $password }}
			<br/>
			<br/>
			<b>Note: It is strictly recommended that you change your password after your first login.</b>
			<br/>
			<br/><hr/>
			<i>If the link above doesn't work. Please copy and paste this url to your browser.</i><br/>
			{{ url('auth/login') }}
		</div> -->
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
		    background-image: url({{ url('images/original/email-bg.jpg') }});">
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
						<div>
							<b>{{ Auth::user()->fname." ".Auth::user()->lname }}</b> has added you as {{ $role }} at {{ $branch_name }}. <br/> Please login using this credentials:
							<br/><br/>
							Email: <code>{{ $email }}</code><br/>
							Password: <code>{{ $password }}</code>
							<br/><br/>
							<div style="text-align: center;">
								<a style="    -webkit-box-shadow: none;
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
								    border-radius: 4px;
								    color: #fff;
								    text-decoration: none;" href="{{ url('auth/login') }}">Login now</a>
							</div>
							<br/>
							<b>Note: It is strictly recommended that you change your password after your first login.</b>
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
									{{ url('auth/login') }}
								</small>
							</code>
						</i>
					</td>
				</tr>
			</tfoot>
		</table>
	</body>
</html>