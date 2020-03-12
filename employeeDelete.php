<?php
	$connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Failed");

	if(isset($_GET['EnNo']))
	{
		$no = $_GET['EnNo'];
		$query = "DELETE FROM employee where EnNo = '".$no."'";
		$result = mysqli_query($connect, $query);

		echo "Success";
		header("Location: employee.php");
	}
	else
	{
		echo "Failed to get EnNo";
	}

?>
