<?php
	session_start();
	$connect =  mysqli_connect("localhost", "root", "", "shellsbt"); 
	
	$initial_salary = 1200;

	$total_bonus = 0;
	$total_late_pen = 0;
	$total_shift_pen = 0;
	$total_working_minutes = 0;

	$penalties_per_minutes = 4.86 / 60;
    $per_shift_minutes = 480;

    $punctual_count = 0;
    $not_punctual_count = 0;

    $display_bonus = "";
    $display_late_pen = "";
    $display_pen_shift = "";
    $display_total_bonus = "";

    $total_final_penalties = 0;
    $display_final_penalties="";

    $morning_shift_late = strtotime('6:40:00');
    $afternoon_shift_late = strtotime('13:40:00');
    $night_shift_late = strtotime('21:40:00');

    if(isset($_POST['submit_employee']))
    {
        $name = $_POST['employee_name'];
        $month = $_POST['month'];
        $next_month = $month + 1;

        // echo $name." ".$month;
    }

    $query = "Select clock_in.No, clock_in.EnNo as 'ID', clock_in.Name, date(clock_in.DateTime), time(clock_in.DateTime),time(clock_out.DateTime) 
                    From clock_in Inner Join clock_out On clock_in.No = clock_out.No
                    where clock_in.Name='$name'and month(clock_in.DateTime) = '$month'";

    $result_name_in = mysqli_query($connect, $query);

    $row_result = mysqli_num_rows($result_name_in);

    //echo $row_result;

    while($row = mysqli_fetch_assoc($result_name_in))
    {
        $name = $row['Name'];
        $date = $row['date(clock_in.DateTime)'];
        $in = $row['time(clock_in.DateTime)'];
        $out = $row['time(clock_out.DateTime)'];

        $time_in = strtotime($in);
        $time_out = strtotime($out);

        if($time_out > $time_in)
        {
            $interval = $time_out - $time_in;
        }
        else
        {
            $interval = $time_in - $time_out;
        }

        $minutes = floor($interval/60);
        $total_working_minutes = $total_working_minutes + $minutes;

        $hours = floor($interval/3600);
        $_remainder = $minutes % 60;

        //STATUS
        if($hours >= 8 && $hours < 12)
        {
            $onshift_status = "<strong style='color:green'>O</strong>";
        }
        else if ($hours >= 12)
        {
            $onshift_status = "<strong style='color:orange'>O</strong>";
        }
        else
        {
            $onshift_status = "<strong style='color:red'>X</strong>";
        }

        //BONUS
        if($hours > 8)
        {
            if($hours >= 12)
            {
                $bonus = 4 * 4.86;
                $display_bonus = "<strong style='color:orange'>".$bonus."</strong>";
            }
            else
            {
                $overtime_hours = $hours - 8;
                $bonus = $overtime_hours * 4.86;
                $display_bonus = "<strong style='color:green'>".$bonus."</strong>";
            }

            $total_bonus = $total_bonus + $bonus;
            $display_total_bonus = "<span style='color:green'>".$total_bonus."</span>";

        }
        else
        {
            $display_bonus =" ";
        }

         //SHIFT PENALTIES
        if($minutes < 480)
        {
            //$notonshift = "X";
            $shift_min = 480 - $minutes;

            $pen_shift = round($shift_min * $penalties_per_minutes,2);
            $total_shift_pen = $total_shift_pen + $pen_shift;

            $display_pen_shift = "<strong style='color:red'>".$pen_shift."</strong>";
        }
        else
        {
            //$notonshift = "";
            $display_pen_shift=" ";
        }
        

        if($time_in > $morning_shift_late || $time_in > $afternoon_shift_late || $time_in > $night_shift_late)
        {
            if($time_in < strtotime('09:30:00'))
            {
                $pen_sec = $time_in - $morning_shift_late;
                $punctual_count ++;
                $num = 1;

            }
            else if ($time_in < strtotime('15:30:00'))
            {
                $pen_sec = $time_in - $afternoon_shift_late;
                $punctual_count ++;
            }
            else if ($time_in < strtotime('23:30:00'))
            {
                $pen_sec = $time_in - $night_shift_late;
                $punctual_count ++;
            }

            $pen_min = round($pen_sec / 60,2);

            if($pen_min < 1)
            {
                $late_pen = " ";
                $display_late_pen = " ";
            }
            else
            {
                $late_pen = round($pen_min * $penalties_per_minutes,2);
                $total_late_pen = $total_late_pen + $late_pen;
                $display_late_pen = "<strong style='color:red'>".$late_pen."</strong>";
            }
        }
        else
        {
            $not_punctual_count ++;
            $display_late_pen="";
        }

             // echo "<tr>
             //         <td>".$name."</td>
             //         <td>".$date."</td>
             //         <td>".$in."</td>
             //         <td>".$out."</td>
             //         <td>".$hours." H ".$_remainder." M</td>
             //         <td>".$display_bonus."</td>
             //         <td>".$display_late_pen."</td>
             //         <td>".$display_pen_shift."</td>
            //  </tr>";

        $total_final_penalties = $total_shift_pen + $total_late_pen;
        $display_final_penalties = "<span style='color:red'>".$total_final_penalties."</span>";

        $final_salary = $initial_salary - $total_final_penalties + $total_bonus;
        $display_final_salary = "<span style='color:blue'>".$final_salary."</span>";

    }

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Salary</title>

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
               <!--  <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Home</a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li>
                            <a href="#">Home 1</a>
                        </li>
                        <li>
                            <a href="#">Home 2</a>
                        </li>
                        <li>
                            <a href="#">Home 3</a>
                        </li>
                    </ul>
                </li> -->
               <!--  <li>
                    <a href="#">About</a>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Pages</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="#">Page 1</a>
                        </li>
                        <li>
                            <a href="#">Page 2</a>
                        </li>
                        <li>
                            <a href="#">Page 3</a>
                        </li>
                    </ul>
                </li> -->
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
                    <a href="salary.php">Salary</a>
                </li>
                <li>
                    <a href="#">Others</a>
                </li>
            </ul>

           <!--  <ul class="list-unstyled CTAs">
                <li>
                    <a href="https://bootstrapious.com/tutorial/files/sidebar.zip" class="download">Download source</a>
                </li>
                <li>
                    <a href="https://bootstrapious.com/p/bootstrap-sidebar" class="article">Back to article</a>
                </li>
            </ul> -->
        </nav>

        <!-- Page Content Holder -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="navbar-btn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="#">Hi, John Wick</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <table id="salaryDisplayTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Month</th>
                        <th>Total Late Penalties</th>
                        <th>Total Shift Penalties</th>
                        <th>Total Bonus</th>
                        <th>Salary</th>
                    </tr>
                </thead>

                <tbody>
                    <!-- <tr>
                        <td>A</td>
                        <td>A</td>
                        <td>A</td>
                        <td>A</td>
                        <td>A</td>
                        <td>A</td>
                    </tr> -->
                    <?php
                        echo "<tr>
                                <td>".$name."</td>
                                <td>".$month."</td>
                                <td style='color:red'>".$total_late_pen."</td>
                                <td style='color:red'>".$total_shift_pen."</td>
                                <td style='color:green'>".$total_bonus."</td>
                                <td>".$display_final_salary."</td>
                            </tr>";
                    ?>
                </tbody>
            </table>

    </div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {s
                $('#sidebar').toggleClass('active');
                $(this).toggleClass('active');
            });
        });

    </script>
</body>

</html>
