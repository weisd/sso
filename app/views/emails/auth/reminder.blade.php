<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
<<<<<<< HEAD
		<h2>重设密码</h2>

		<div>
			点击链接重设密码: {{ URL::to('/reset-password', array($token)) }}.
=======
		<h2>Password Reset</h2>

		<div>
			To reset your password, complete this form: {{ URL::to('password/reset', array($token)) }}.
>>>>>>> cb959f70d1a8d6ccf47f8f24432f2edddb44a29d
		</div>
	</body>
</html>
