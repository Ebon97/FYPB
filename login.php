<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
		<meta charset="utf-8">

		<link rel="stylesheet" type="text/css" href="style1.css">
		<link rel="icon" href="image/favicon.png">
	</head>

	<body>
		<div id="login_logo">
			<img src="image/shell_logo2.png">
		</div>

		<div id="login_page_content">
			<div class="back_icon">
				<a href="login.php"><img src="image/back_icon.png" style="visibility: hidden"><a>
			</div>

			<span>WELCOME</span>

			<form id="login_form" action="login.php" method="GET">
				<div id="login_username">
					<input type="text" name="username" placeholder="Username" autocomplete="off">
				</div>

				<div id="login_password">
					<input type="password" name="password" placeholder="Password" autocomplete="off">
				</div>

				<a href="forgotPassword.php">Forgot Password</a>
				<a href="register.php">Register</a>

				<div id="login_button">
					<input type="submit" name="login" value="LOGIN">
				</div>
			</form>
		</div>

		<?php
			include("loginValidation.php");
			echo $message;
		?>

		
	</body>
</html>