<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
		<meta charset="utf-8">

		<link rel="stylesheet" type="text/css" href="style1.css">
	</head>

	<body>
		<div id="login_logo">
			<img src="image/shell_logo2.png">
		</div>

		<div id="login_page_content">
			<p>WELCOME</p>
			<form id="login_form">
				<div id="login_username">
					<input type="text" name="username" placeholder="Username" autocomplete="off">
				</div>

				<div id="login_password">
					<input type="password" name="password" placeholder="Password" autocomplete="off">
				</div>

				<a href="#">Forgot Password</a>
				<a href="register.php">Register</a>

				<div id="login_button">
					<input type="submit" name="login" value="LOGIN">
				</div>
			</form>
		</div>
	</body>
</html>