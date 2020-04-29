
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

		$query = "UPDATE employee SET Name='$empName',Position='$empPosition', Salary='$empSalary',startDate='$empSDate' WHERE ID='$empNo'";

		$result = mysqli_query($connect, $query);

		echo "<meta http-equiv='Refresh' content='2; URL=employee.php'>";

		// header('Location: employee.php');

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
					<input type='text' class='form-control' value=".$empPosition." name=.empPosition'>
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
	else
	{

	}

?>

        