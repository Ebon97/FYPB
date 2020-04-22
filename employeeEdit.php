<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Employee List</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">



    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="style1.css">


</head>

<body>

    <div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <img src="image/shell_logo2.png">
            </div>

            
            <ul class="list-unstyled components">
                <li>
                    <a href="dashboard.php">Dashboard</a>
                </li>
                 <li>
                    <a href="employee.php">Employee List</a>
                </li>
                <li>
                    <a href="#">Performance</a>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Salary</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="salary.php">Generate Payroll</a>
                        </li>
                        <li>
                            <a href="salaryCheckUpdate.php">Check & Update</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="rates.php">Rates</a>
                </li>
                <li>
                    <a href="history.php">History</a>
                </li>

                <li>
                    <a href="settings.php" class="setting_icon"><img src="image/setting_icon.png"></a>
                    <a href="login.php" class="logout_icon"><img src="image/logout_icon.png"></a>
                </li>
            </ul>

        </nav>

        <!-- Page Content Holder -->
        <div id="content">

            <div class="row title">
                <div class="col-6 pageTitle">
                    <h2>Employee List</h2>
                </div>
            </div>

        	<form id="editEmployeeForm" action="employeeEdit.php" method="POST">
        		<?php
					$connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Failed");

					$message = "";
					$link = ""; 

					if(isset($_GET['No']))
					{
						$no = $_GET['No'];

						$query = "SELECT * FROM `employee` WHERE No = ".$no." group by No";

						$result = mysqli_query($connect, $query);
						$row  = mysqli_num_rows($result);

						 while($row = mysqli_fetch_assoc($result))
						 {
						 	$no = $row['No'];
					   		$emp_name = $row['Name'];
					        $position = $row['position'];
					        $salary = $row['salary'];
					        $startDate = $row['startDate'];

				?>
						<input type="hidden" value="<?php echo $no; ?>" name="empNo">
						<div class='form-group row'>
							<label class="">Name</label>
							<label>:</label>
							<div>
								<input type='text' class='form-control' value="<?php echo $emp_name; ?>" name="empName">
							</div>
						</div>
						
						<div class='form-group row'>
							<label>Position</label>
							<label>:</label>
							<div>
								<input type='text' class='form-control' value="<?php echo $position; ?>" name="empPosition">
							</div>
						</div>
						
						<div class='form-group row'>
							<label>Salary</label>
							<label>:</label>
							<div>
								<input type='text' class='form-control' value="<?php echo $salary; ?>" name="empSalary">
							</div>
						</div>
						
						<div class='form-group row'>
							<label>Joined Since</label>
							<label>:</label>
							<div>
								<input type='text' class='form-control' value="<?php echo $startDate; ?>" name="empSDate">
							</div>
						</div>

				<?php
						 }
					}
					else
					{
						// echo "FAILED";
					}
				?>
					
				<div id="editEmployeeButton">
					<?php include('employeeUpdate.php')?>
					<input type="submit" name="update" value="Update" id="updateButton">
				</div>

				<div id="editSuccessMessage">
					<h5><?php echo $message; ?></h5>
				</div>
        	</form>

       

			<div id="myModal" class="modal">
				<div class="modal-content">
					<span class="close" onclick="hideModal()">&times;</span>
					<span class="auth_title">Authentication</span>
				</div>
			</div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {s
                $('#sidebar').toggleClass('active');
                $(this).toggleClass('active');
            });
        });

        function searchFunction() 
        {
            var input, filter, table, tr, td, i, txtValue;

            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("employeeList");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) 
            {
                td = tr[i].getElementsByTagName("td")[1];

                if (td) 
                {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) 
                    {
                        tr[i].style.display = "";
                    } 
                    else 
                    {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }
		

    </script>
</body>

</html>