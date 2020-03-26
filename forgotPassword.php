<?php session_start()?>
<!DOCTYPE html>
<html>
	<head>
		<title>Forgot Password</title>
		<meta charset="utf-8">

		<link rel="stylesheet" type="text/css" href="style1.css">
	</head>

	<body>
		<div id="login_logo">
			<img src="image/shell_logo2.png">
		</div>

		<div id="forgot_page_content">
			<div class="back_icon">
				<a href="login.php"><img src="image/back_icon.png"><a>
			</div>

			<span>FORGOT PASSWORD</span>
			<form id="forgot_form" action="forgotPassword.php" method="GET">
				<div id="forgot_email">
					<input type="email" name="recover_email" placeholder="Email" autocomplete="off">
				</div>

				<div id="forgot_password_button">
					<input type="submit" name="reset" value="RESET PASSWORD">
				</div>
			</form>
		</div>

		<?php
			$connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error());
			$message = "";

			if(isset($_GET['reset']))
			{
				$recover_email = $_GET['recover_email'];

				$_SESSION['recover_email'] = $recover_email;

				if(empty($recover_email))
				{
					$message = "<div class='warning'><p>Missing Input</p></div>";
				}
				else
				{
					$query = "SELECT * from manager WHERE email='$recover_email'";
					$result = mysqli_query($connect, $query);
					$row = mysqli_num_rows($result);

					if($row == 0)
					{
						$message = "<div class='warning'><p>Email is not found. Try it again</p></div>";
					}
					else if($row == 1)
					{
						header("Location: forgotPasswordNew.php");
					}


				}
			}

			echo $message;
		?>
	</body>
</html>