<?php 
	session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>New Password</title>
		<meta charset="utf-8">

		<link rel="stylesheet" type="text/css" href="style1.css">
	</head>

	<body>
		<div id="login_logo">
			<img src="image/shell_logo2.png">
		</div>

		<div id="new_password_page_content">
			<div class="back_icon">
				<a href="forgotPassword.php"><img src="image/back_icon.png"><a>
			</div>

			<span>NEW PASSWORD</span>
			<form id="new_password_form" action="forgotPasswordNew.php" method="GET">
				<div id="new_password">
					<input type="password" name="new_password" placeholder="New Password" autocomplete="off">
				</div>

				<div id="new_confirm_password">
					<input type="password" name="new_confirm_password" placeholder="Confirm New Password" autocomplete="off">
				</div>

				<div id="confirm_new_password_button">
					<input type="submit" name="confirm" value="CONFIRM">
				</div>
			</form>
		</div>
		<?php
			$connect =  mysqli_connect("localhost", "root", "", "shellsbt") 
				or die ("Connection Failed: ". mysqli_connect_error());  

			$recover_email = $_SESSION['recover_email'];
			$message = ""; 

			if(isset($_GET['confirm']))
			{
				$first_password = $_GET['new_password'];
				$second_password = $_GET['new_confirm_password'];

				// echo $first_password." ".$second_password." ".$recover_email;

				if(empty($first_password) || empty($second_password))
				{
					$message = "<div class='warning'><p>Missing Input</p></div>";
				}
				else if ($first_password != $second_password)
				{
					$message = "<div class='warning'><p>Password are not the same. Try it again</p></div>";
				}
				else
				{
					$hash_password = hash('sha256',$first_password);

					$query = "UPDATE `manager` SET `password`='$hash_password' WHERE email='$recover_email'";
					$result = mysqli_query($connect, $query);
					$message = "<div class='success'><p>Update Successfully. Redirecting...</p></div>";

					header("refresh:2; url=login.php");
				}
			}

			echo $message;

		?>
	</body>
</html>