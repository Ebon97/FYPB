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
                    <a href="performance.php">Performance</a>
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
                    <!-- <tr>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <th>Shift</th>
                        <th>Date In</th>
                        <th>Time In</th>
                        <th>Date Out</th>
                        <th>Time Out</th>
                        <th>Duration</th>
                        <th>Late Status</th>
                        <th>Late Penalties</th>
                        <th>Shift Status</th>
                        <th>Shift Penalties</th>
                        <th>Bonus</th>
                        <th></th>
                        
                    </tr> -->

		 			<?php 

                        if($found == false)
                        {
                            $query_rates = "SELECT * FROM rates WHERE no=1";
                            $result_rates = mysqli_query($connect, $query_rates);
                            $row_rates = mysqli_num_rows($result_rates);

                            if($row_rates == 1)
                            {
                                $row_rates = mysqli_fetch_assoc($result_rates);
                                
                                $no = $row_rates['no'];
                                $bonus = $row_rates['overtime_bonus'];
                                $late_penalties = $row_rates['late_penalties'];
                                $shift_penalties = $row_rates['shift_penalties'];
                            }

                            $total_shift_penalties = 0;
                            $total_late_penalties = 0;
                            $total_bonus = 0;

                            $morning_shift_late = strtotime('6:40:00');
                            $afternoon_shift_late = strtotime('14:40:00');
                            $night_shift_late = strtotime('22:40:00');

                            $row_count = 0;
                            $final_row_count = 0;
                            $alert_count = 0;
                            $missing_data_count = 0;
                            $total_missing_data = 0;
                            $total_row_count = 0;

                            // $late = "";
                            // $diff = 0;

                            $query_em = "SELECT * from employee";
                            $result_em = mysqli_query($connect, $query_em);
                            $row_em = mysqli_num_rows($result_em);


                            while($row_em = mysqli_fetch_assoc($result_em))
                            {
                                $id = $row_em['ID'];
                                $name = $row_em['Name'];
                                $shift = $row_em['Shift'];
                                $salary = $row_em['Salary'];

                                 // include("salaryCalculation.php");

                                $query = "SELECT clock_in.Name, date(clock_in.DateTime), time(clock_in.DateTime), clock_in.Shift, clock_in.DateTime as dateTimeIN, 
                                            clock_out.Name, date(clock_out.DateTime), time(clock_out.DateTime), clock_out.Shift, clock_out.NightFix, clock_out.DateTime as dateTimeOUT 
                                            from clock_in inner join clock_out 
                                            on date(clock_in.DateTime) = date(clock_out.NightFix) and clock_in.Name = clock_out.Name 
                                            where month(clock_in.DateTime) = '$month' and clock_in.Name = '$name'";
                                $result = mysqli_query($connect, $query);
                                $row_num = mysqli_num_rows($result);

                                // echo $row_num;

                                while($row = mysqli_fetch_assoc($result))
                                {
                                    $name = $row['Name'];
                                    $shift = $row['Shift'];

                                    $dateTimeIN = $row['dateTimeIN'];
                                    $date_in = $row['date(clock_in.DateTime)'];
                                    $time_in = $row['time(clock_in.DateTime)'];

                                    $dateTimeOUT = $row['dateTimeOUT'];
                                    $date_out = $row['date(clock_out.DateTime)'];
                                    $time_out = $row['time(clock_out.DateTime)'];

                                    // echo $name." ".$shift." ".$dateTimeIN." ".$dateTimeOUT."<br>";

                                    $interval = strtotime($dateTimeOUT) - strtotime($dateTimeIN);
                                    $minutes = floor($interval/60);
                                    $hours = floor($interval/3600);
                                    $_remainder = $minutes % 60;


                                    //Late Status
                                    if($shift == "Morning")
                                    {
                                        // echo "HI<br>";
                                        if(strtotime($time_in) > $morning_shift_late)
                                        {
                                            $late = "Late";
                                            $diff = strtotime($time_in) - $morning_shift_late;
                                        }
                                         else
                                        {
                                            $late = "O";
                                            $diff = 0;
                                        }

                                    }
                                    
                                    if ($shift == "Afternoon")
                                    {
                                        if(strtotime($time_in) > $afternoon_shift_late)
                                        {
                                            $late = "Late";
                                            $diff = strtotime($time_in) - $afternoon_shift_late;
                                        }
                                         else
                                        {
                                            $late = "O";
                                            $diff = 0;
                                        }

                                    }

                                    if ($shift == "Night")
                                    {
                                        if(strtotime($time_in) > $night_shift_late)
                                        {
                                            $late = "Late";
                                            $diff = strtotime($time_in) - $night_shift_late;
                                        }
                                        else
                                        {
                                            $late = "O";
                                            $diff = 0;
                                        }
                                    }

                                    $l_minutes = floor($diff/60);
                                    $l_hours = floor($diff/3600);
                                    $l_remainder = $l_minutes % 60;
                                    $latep = round($l_minutes * $late_penalties, 2);

                                    //Shift Status
                                    if($hours < 8)
                                    {
                                        $shift = "X";
                                        $shift_diff = 480 - $minutes;
                                        $shiftp = $shift_diff * $shift_penalties;
                                    }
                                    else
                                    {
                                        $shift = "O";
                                        $shift_diff = 0;
                                        $shiftp = 0;
                                    }

                                    if($hours > 8)
                                    {
                                        $_bonus = ($hours - 8) * $bonus;
                                    }
                                    else
                                    {
                                        $_bonus = 0;
                                    }
                                    

                                    // echo "<tr>
                                    //     <td>".$name."</td>
                                    //     <td>".$shift."</td>
                                    //     <td>".$date_in."</td>
                                    //     <td>".$time_in."</td>
                                    //     <td>".$date_out."</td>
                                    //     <td>".$time_out."</td>
                                    //     <td>".$hours." Hr ".$_remainder." Min</td>
                                    //     <td>".$late."</td>
                                    //     <td>".$latep."</td>
                                    //     <td>".$shift."</td>
                                    //     <td>".$shiftp."</td>
                                    //     <td>".$_bonus."</td>
                                    // </tr>";

                                    $row_count  =  1;

                                    $total_late_penalties = $total_late_penalties + $latep;
                                    $total_shift_penalties = $total_shift_penalties + $shiftp;
                                    $total_bonus = $total_bonus + $_bonus;
                                    $total_row_count = $total_row_count + $row_count;

                                    
                                    // echo $row_count."<br>";
                                }

                                $final_salary = round($salary - $total_shift_penalties - $total_late_penalties + $total_bonus, 2);

                                if($total_row_count < 26 )
                                {
                                    $alert = "<img src='image/alert_icon.png' class='alert_icon' alt='Missing Data'>";
                                }
                                else
                                {
                                    $alert = "";
                                    $query_past = "INSERT INTO salary_past(no, year, month, name, init_salary, shift_penalties, late_penalties, bonus, final_salary) VALUES (NULL,'$year','$month','$name','$salary','$total_shift_penalties','$total_late_penalties','$total_bonus','$final_salary')";
                                    $result_past = mysqli_query($connect, $query_past);
                                }

                                 echo "<tr>
                                        <td>".$name."</td>
                                        <td>RM ".$salary."</td>
                                        <td>".$total_late_penalties."</td>
                                        <td>".$total_shift_penalties."</td>
                                        <td>".$total_bonus."</td>
                                        <td>RM ".$final_salary."</td>
                                        <td>
                                            <form action='salaryShowDetails.php' method='GET'>
                                                <input type='hidden' name='name' value='".$name."'>
                                                <input type='hidden' name='month' value='".$month."'>
                                                <input type='hidden' name='year' val ue='".$year."'>
                                                <button name='show'>Show Details</button>
                                            </form>
                                        </td>
                                        <td style='padding:0;'>".$alert."</td>                                      
                                    </tr>";

                                    $total_late_penalties = 0;
                                    $total_shift_penalties = 0;
                                    $total_bonus = 0;
                                    $total_row_count = 0;

                            }

                            if($total_missing_data  > 26)
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
                                    <td>".$final_salary."</td>
                                    <td></td>
                                    <td></td>
                                </tr>";
                            }                     
                        }
					?>
	            </table>

        	</div>

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