<?php
    session_start();

	$connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error()); 

    $month_name_array = ["NULL","January","February","March","April","May","June","July","August","September","October","November","December"];
    $found = false;
    $notification = "";
    $i = 1;

    //When Generating New Payroll
    if(isset($_GET['confirmsalary']))
    {
        $year = $_GET['year'];
        $month = $_GET['month'];
        $month_add_one = $month + 1;

        $table_row = $_SESSION['row'];

        for($i = 1; $i < $table_row; $i++)
        {
            $salary[$i] = $_GET['salary'.$i];
            $name[$i] = $_GET['name'.$i];

            // UPDATE employee SET salary=[value-5] WHERE Name = '';
            $query_upd = "UPDATE employee SET salary='$salary[$i]' WHERE Name = '$name[$i]'";
            $result_upd = mysqli_query($connect, $query_upd);

        }

        $found = false;
    }
    //When Generating Past Record
    else
    {
        $year = $_SESSION['year'];
        $month = $_SESSION['month'];
        $month_add_one = $month+1;

        $query = "SELECT * FROM checkgenerator WHERE year='$year' AND month='$month'";
        $result = mysqli_query($connect, $query);
        $row = mysqli_num_rows($result);

        if($row == 1)
        {
            $found = true;
        }
    }

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Salary Payroll</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
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
                    <a href="salary.php">Salary Payroll</a>
                </li>
                 <li>
                    <a href="rates.php">Rates</a>
                </li>
                <li>
                    <a href="history.php">History</a>
                </li>
            </ul>

            <ul class="list-unstyled icon">
                <li>
                    <a href="settings.php" class="setting_icon"><img src="image/setting_icon.png"></a>
                </li>
                <li>
                    <a href="login.php" class="logout_icon"><img src="image/logout_icon.png"></a>
                </li>
            </ul>
        </nav>

        <!-- Page Content Holder -->
        <div id="content">

       		<div class="row title">
               <div class="col-6 pageTitle">
                   <h2><?php echo $month_name_array[$month];?> Payroll</h2>
               </div>

                <div class="col-4 search" style="padding-right: 4.5%; ">
                    <input type="text" id="searchInput" onkeyup="searchFunction()" placeholder="Search Names" title="Type in a name">
                </div>
            </div>

            <div id="salary_sheet">
	        	<table>
	        		<tr>
	        			<th>Name</th>
                        <th>Salary</th>
	        			<th>Total Shift Penalties</th>
	        			<th>Total Late Penalties</th>
	        			<th>Total Bonus</th>
	        			<th>Final Salary</th>
	        			<th colspan="2">Action</th>
	        		</tr>

		 			<?php 

                        if($found == false)
                        {
                            $query = "SELECT * from employee";
                            $result = mysqli_query($connect, $query);
                            $row = mysqli_num_rows($result);

                            while($row = mysqli_fetch_assoc($result))
                            {
                                $name = $row['Name'];
                                $shift = $row['shift'];
                                $salary = $row['salary'];

                                if($shift == "Morning" || $shift == "Afternoon")
                                {
                                    include("salaryCalculationMF.php");
                                }
                                else if ($shift == "Night")
                                {
                                    include("salaryCalculationN.php");
                                }

                            }

                            if($alert_count == 0)
                            {
                                $query_check = "INSERT INTO checkgenerator(no, year, month, status) VALUES (NULL,'$year','$month',1)";
                                $result_check = mysqli_query($connect, $query_check);

                                $query_his = "INSERT INTO history(no, date, category, description) VALUES (NULL,CURRENT_TIMESTAMP,'Salary Payroll','$month_name_array[$month] Salary Payroll is successfully generated')";
                                $result_his = mysqli_query($connect, $query_his);

                                $notification = "<div class='success'><p>Payroll generated successfully</p></div>";
                            }
                            else
                            {
                                $notification = "<div class='warning'><p>This Payroll is generated unsuccessfully</p></div>";

                            }

                        }
                        else if ($found == true)
                        {
                            $query = "SELECT * from salary_past WHERE year='$year' and month='$month'";
                            $result = mysqli_query($connect, $query);
                            $row = mysqli_num_rows($result);
                            $i = 0;
                            
                            while($row = mysqli_fetch_assoc($result))
                            {
                                $name = $row['name'];
                                $shift_penalties = $row['shift_penalties'];
                                $late_penalties = $row['late_penalties'];
                                $bonus = $row['bonus'];
                                $final_salary = $row['final_salary'];
                                $i++;

                                echo "<tr>
                                    <td>".$name."</td>
                                    <td>".$shift_penalties."</td>
                                    <td>".$late_penalties."</td>
                                    <td>".$bonus."</td>
                                    <td>".$final_salary."</td>
                                </tr>";
                            }
                        }


						

					?>
	            </table>

        	</div>
            <strong style='color:red;'>successfully</strong>

            <?php
                if($found == true)
                {
                    $notification = "<div class='warning'><p>This Payroll had been generated before</p></div>";
                }

                echo $notification;
            ?>
        </div>
    </div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $(this).toggleClass('active');
            });
        });

        function searchFunction() 
        {
            var input, filter, table, tr, td, i, txtValue;

            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("salary_sheet");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) 
            {
                td = tr[i].getElementsByTagName("td")[0];

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