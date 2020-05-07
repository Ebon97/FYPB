<?php
	$connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Failed");

	if(isset($_GET['ID']))
	{
		$emp_id = $_GET['ID'];
		$query = "DELETE FROM employee where ID = '".$emp_id."'";
		$result = mysqli_query($connect, $query);

		echo "Success";
		header("Location: employee.php");
	}
	else
	{
		echo "Failed to get ID";
	}

?>
