<?php
	session_start();
	$connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error());  
	$message = ""; 

	if(isset($_GET['login']))
	{
		$username = $_GET['username'];
		$password = $_GET['password'];
		$hashed_password = hash('sha256', $password);

		// echo $username . " ". $password . " " . $hashed_password; 

		if(empty($username) || empty($password))
		{
			$message = "<div class='warning'><p>Invalid Username or Password</p></div>";;
		}
		else
		{
			$query = "SELECT * from manager WHERE username='$username' AND password = '$hashed_password'";
			$result = mysqli_query($connect, $query);
			$row = mysqli_num_rows($result);

			if($row == 0)
			{
				$message = "<div class='warning'><p>Invalid username or password. Try it again</p></div>";
			}
			else
			{
				$message = "<div class='success'><p>Redirecting....</p></div>";

				$_SESSION['username'] = $username;
				$_SESSION['password'] = $password;
				$_SESSION['hashed_password'] = $hashed_password;

				header("refresh:2; url=dashboard.php");
			}
		}
	}

?>