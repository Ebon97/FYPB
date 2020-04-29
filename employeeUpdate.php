
<?php
	$connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Failed"); 

	if(isset($_POST['update']))
	{
		$empID = $_POST['empID'];
		$empName = $_POST['empName'];
		$empPosition = $_POST['empPosition'];
		$empShift = $_POST['empShift'];
		$empSalary = $_POST['empSalary'];
		$empSDate = $_POST['empSDate'];

		// echo $empNo."<br>".$empName."<br>".$empPosition."<br>".$empSalary."<br>".$empSDate;

		$query = "UPDATE `employee` SET `Name`='".$empName."',`Position`='".$empPosition."',`Shift`='".$empShift."', `Salary`='".$empSalary."', `startDate`='".$empSDate."' WHERE ID ='".$empID."'";

		$result = mysqli_query($connect, $query);
		
		header("Refresh:2; url=employee.php");

		$message = "<h5>Succesfully Updated</h5><h6>Redirecting to Employee Page....</h6>";
		echo"
		<div class='form-group row'>
		<label>Name</label>
		<label>:</label>
		<div>
			<input type='text' class='form-control' value=".$empName." name='empName'>
		</div>
	</div>
	
	<div class='form-group row'>
		<label>Position</label>
		<label>:</label>
		<div>
			<input type='text' class='form-control' value=".$empPosition." name='empPosition'>
		</div>
	</div>

	<div class='form-group row'>
		<label>Shift</label>
		<label>:</label>
		<div>
			<input type='text' class='form-control' value=".$empShift." name='empShift'>
		</div>
	</div>
	
	<div class='form-group row'>
		<label>Salary</label>
		<label>:</label>
		<div>
			<input type='text' class='form-control' value=".$empSalary." name='empSalary'>
		</div>
	</div>
	
	<div class='form-group row'>
		<label>Joined Since</label>
		<label>:</label>
		<div>
			<input type='text' class='form-control' value=".$empSDate." name='empSDate'>
		</div>
	</div>";

	
		ob_end_flush();
		
	}

?>

        