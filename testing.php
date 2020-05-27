<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Performance</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="style1.css">
    <link rel="icon" href="image/favicon.png">
    <style>
      @import url('http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css');
div[class^="col-"] {
    border: 1px solid black;
}
    </style>

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
            <table>
                <tr>    
                    <th>Date</th>
                    <th>Clock In Date</th>
                    <th>Clock In Time</th>
                    <th>Clock Out Date</th>
                    <th>Clock Out Time</th>
                    <th>Late</th>
                </tr>

                <?php
                    $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Failed"); 

                    $morning_shift_late = strtotime('6:40:00');
                    $afternoon_shift_late = strtotime('14:40:00');
                    $night_shift_late = strtotime('22:40:00');

                    $late_count = 0;

                    $query = "SELECT clock_in.Name, date(clock_in.DateTime), time(clock_in.DateTime), clock_in.Shift, 
                            clock_out.Name, date(clock_out.DateTime), time(clock_out.DateTime), clock_out.Shift, clock_out.NightFix
                            from clock_in inner join clock_out
                            on date(clock_in.DateTime) = date(clock_out.NightFix) and clock_in.Name = clock_out.Name
                            ";

                    // echo "<br>".$query;
                    $result = mysqli_query($connect, $query);
                    $row = mysqli_num_rows($result);

                    while($row = mysqli_fetch_assoc($result))
                    {
                        $name = $row['Name'];
                        $shift = $row['Shift'];

                        $date_in = $row['date(clock_in.DateTime)'];
                        $time_in = $row['time(clock_in.DateTime)'];

                        $date_out = $row['date(clock_out.DateTime)'];
                        $time_out = $row['time(clock_out.DateTime)'];

                        if($shift == "Shift 1")
                        {
                            if(strtotime($time_in) > $morning_shift_late && strtotime($time_in) < strtotime('10:30:00'))
                            {
                                $late_count = 1;
                            }
                        }
                        else if($shift == "Shift 2")
                        {
                            if(strtotime($time_in) > $afternoon_shift_late && strtotime($time_in) < strtotime('15:30:00'))
                            {
                                $late_count = 1;
                            }

                        }
                        else if($shift == "Shift 3")
                        {
                            if(strtotime($time_in) > $night_shift_late && strtotime($time_in) < strtotime('23:30:00'))
                            {
                                $late_count = 1;
                            }
                        }
                        else
                        {
                            $late_count = 0;
                        }

                       

                        echo "<tr>
                            <td>".$name."</td>
                            <td>".$date_in."</td>
                            <td>".$time_in."</td>
                            <td>".$date_out."</td>
                            <td>".$time_out."</td>
                            <td>".$shift."</td>
                            <td>".$late_count."</td>
                        </tr>";
                    }


                ?>

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