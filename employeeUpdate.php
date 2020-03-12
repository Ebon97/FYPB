<?php
	$connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Failed"); 

	if(isset($_POST['update']))
	{
		$empNo = $_POST['empNo'];
		$empName = $_POST['empName'];
		$empPosition = $_POST['empPosition'];
		$empSalary = $_POST['empSalary'];
		$empSDate = $_POST['empSDate'];

		// echo $empNo."<br>".$empName."<br>".$empPosition."<br>".$empSalary."<br>".$empSDate;

		$query = "UPDATE `employee` SET `Name`='".$empName."',`position`='".$empPosition."', `salary`='".$empSalary."',`startDate`='".$empSDate."' WHERE EnNo='".$empNo."'";

		$result = mysqli_query($connect, $query);
	}
	else
	{
		
	}

?>