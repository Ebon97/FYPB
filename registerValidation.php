<?php
	$connect =  mysqli_connect("localhost", "root", "", "shellsbt") 
				or die ("Connection Failed: ". mysqli_connect_error());  
	$message = ""; 

	if(isset($_GET['register']))
	{
		$username = $_GET['username'];
		$password = $_GET['password'];
		$confirm = $_GET['confirm_password'];
		$email = $_GET['email'];
		$hash = hash('sha256',$password);

		// Check empty Input
		if(empty($username) || empty($password) || empty($confirm) || empty($email))
		{
			$message = "<div class='warning'><p>Missing Input</p></div>";
		}
		//Check Password
		else if($password != $confirm)
		{
			$message = "<div class='warning'><p>Password are not same. Try Again</p></div>";
		}
		//Check Email
		else if (!empty($email))
		{
			$query = "SELECT * FROM manager where email='$email'";
			$result = mysqli_query($connect, $query);
			$row = mysqli_num_rows($result);

			if($row == 0)
			{
				$query = "INSERT INTO `manager`(`ID`, `username`, `email`, `password`) 
						VALUES (null,'$username','$email','$hash')";
				$result= mysqli_query($connect, $query);

				$message = "<div class='success'><p>Register Sucessfully. Redirecting...</p></div>";

				header("refresh:3; url=login.php");
			}
			else
			{
				$message = "<div class='warning'><p>Email has been registerd before. Try use another email</p></div>";
			}
			
		}
		
	}

?>