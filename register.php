<!DOCTYPE html>
<html>
	<head>
		<title>Register</title>
		<meta charset="utf-8">

		<link rel="stylesheet" type="text/css" href="style1.css">
		<link rel="icon" href="image/favicon.png">
	</head>

	<body>
		<div id="register_logo">
			<img src="image/shell_logo2.png">
		</div>
		
		<div id="register_page_content">
			<div class="back_icon">
				<a href="login.php"><img src="image/back_icon.png"><a>
			</div>

			<span>REGISTER</span>
			<form id="register_form" action="register.php" method="GET">
				<div id="register_username">
					<input type="text" name="username" placeholder="Username" autocomplete="off">
				</div>

				<div id="register_password">
					<input type="password" name="password" placeholder="Password" autocomplete="off">
				</div>

				<div id="register_confirmpassword">
					<input type="password" name="confirm_password" placeholder="Confirm Password" autocomplete="off">
				</div>

				<div id="register_email">
					<input type="email" name="email" placeholder="Email" autocomplete="off">
				</div>

				<div id="register_button">
					<input type="submit" name="register" value="REGISTER">
				</div>
			</form>
		</div>

		<?php
			include("registerValidation.php");
			echo "
				<div class='message'>
					<p>".$message."</p>
				</div>";
		?>
	</body>
</html>