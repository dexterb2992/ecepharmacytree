<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Password Reset</h2>

		<div>
			To reset your password, follow this <a href="{{ URL::to('password/reset', array($token)) }}">link</a>.
			<br/>
			<br/><hr/>
			<i>If the link above doesn't work. Please copy and paste this url to your browser.</i><br/>
			{{ URL::to('password/reset', array($token)) }}
		</div>
	</body>
</html>