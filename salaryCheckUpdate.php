<?php
    session_start();
    
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Check & Update</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    <link rel="stylesheet" href="style1.css">

</head>

<body>

    <div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <a href="dashboard.php"><img src="image/shell_logo2.png"></a>
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
                    <h2>Check & Update</h2>
                </div>
            </div>

            <form id="checkSalaryForm" action="salaryCheckUpdate.php" method="GET">
                <div class="form-group row">
                    <label class="title">Name</label>
                    <label class="colon">:</label>
                    <div>
                        <input type="text" class='form-control' name="checkName">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="title">Start Date</label>
                    <label class="colon">:</label>
                    <div>
                        <input type="date" class='form-control' name="checkStartDate">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="title">Range</label>
                    <label class="colon">:</label>
                    <div>
                         <select class="form-control" name="range">
                            <option value="1">1 day</option>
                            <option value="3">3 days</option>
                            <option value="7">7 days</option>
                            <option value="10">10 days</option>
                        </select>
                    </div>
                </div>

                <input type="submit" name="check" value="CHECK" >
            </form>



            <table id="checkUpdateTable" >
                <tr>
                    <th>Name</th>
                    <th>Shift</th>
                    <th>Date</th>
                    <th>Clock In</th>
                    <th>Date</th>
                    <th>Clock Out</th>
                    <th>Alert</th>
                </tr>

                <?php
                    echo "<form action='salaryCheckUpdate.php' method='GET'>";

                    $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error()); 
                   if(isset($_GET['check']))
                   {
                        $name = $_GET['checkName'];
                        $startdate = $_GET['checkStartDate'];
                        $range = $_GET['range'];

                        if(empty($name) || $startdate == null)
                        {
                             echo "<div class='checkMessageWarning'>
                                <span>Missing Input</span>
                            </div>";
                        }
                        else
                        {

                            $day = date("d", strtotime($startdate));
                            $month = date("m", strtotime($startdate));
                            $year = date("Y", strtotime($startdate));



                            $query = "SELECT * FROM employee WHERE Name='$name'";
                            $result = mysqli_query($connect, $query);
                            $row_num = mysqli_num_rows($result);
                            $row = mysqli_fetch_assoc($result);

                            $name = $row['Name'];
                            $shift = $row['Shift']; 

                           for($a = 0; $a < $range; $a++)
                            {
                               $new_date = date('Y-m-d', strtotime("+$a days", strtotime($startdate)));

                               $query_check_in = "SELECT date(DateTime), time(DateTime) FROM clock_in WHERE date(DateTime) = '$new_date' AND Name='$name'";
                               // echo $query_check_in."<br>";
                               $check_result_in = mysqli_query($connect, $query_check_in);
                               $row_num_check_in = mysqli_num_rows($check_result_in);

                               $row_check_in = mysqli_fetch_assoc($check_result_in);
                               
                               // $time_in = $row_check['time(DateTime)'];

                               if($row_num_check_in == 1)
                               {
                                    $time_in = $row_check_in['time(DateTime)'];
                               }
                               else
                               {
                                    $time_in = "NOT FOUND";
                               }

                               $query_check_out = "SELECT date(DateTime), time(DateTime), NightFix FROM clock_out WHERE NightFix = '$new_date' AND Name='$name'";
                               // echo $query_check_out."<br>";
                               $check_result_out = mysqli_query($connect, $query_check_out);
                               $row_num_check_out = mysqli_num_rows($check_result_out);

                               $row_check_out = mysqli_fetch_assoc($check_result_out);


                               if($row_num_check_out == 1)
                               {
                                    $date_out = $row_check_out['date(DateTime)'];
                                    $time_out = $row_check_out['time(DateTime)'];
                               }
                               else
                               {
                                    $time_out = "";
                                    $date_out = "";

                               }

                               if($shift == "Afternoon" || $shift == "Morning")
                               {
                                    $date_out = $new_date;
                               }
                               else
                               {
                                    $date_out = date('Y-m-d', strtotime("+1 days", strtotime($new_date)));
                               }

                               if($time_in == null || $time_out == null)
                               {
                                    $alert = "<img src='image/alert_icon.png' class='alert_icon'>";
                               }
                               else
                               {
                                    $alert = "";
                               }

                                echo "<tr>
                                    <td><input type='text' value='".$name."' name='name".$a."' readonly ></td>
                                    <td><input style='width:100%;' type='text' value='".$shift."' name='shift".$a."' readonly ></td>
                                    <td><input style='width:120%;' type='date' value='".$new_date."' name='new_date".$a."' readonly></td>
                                    <td><input style='width:120%;' type='time' value='".$time_in."' name='time_in".$a."'></td>
                                    <td><input style='width:120%;' type='date' value='".$date_out."' name='date_out".$a."' readonly></td>
                                    <td><input style='width:120%;' type='time' value='".$time_out."' name='time_out".$a."'></td>
                                    <td>".$alert."</td>
                                </tr>";
                            }

                            echo "<tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><input type='hidden' value='".$a."' name='range'></td>
                                    <td><input type='submit' value='UPDATE' name='update'></td>
                                    <td></td>
                                </tr>
                                </form>";
                        }
                   }

                   if(isset($_GET['update']))
                   {
                        $count = $_GET['range'];

                        for($z = 0; $z < $count; $z++)
                        {
                            $_name = $_GET['name'.$z];
                            $_shift = $_GET['shift'.$z];

                            $_date_in = $_GET['new_date'.$z];
                            $_time_in = $_GET['time_in'.$z];

                            $_date_out = $_GET['date_out'.$z];
                            $_time_out = $_GET['time_out'.$z];

                            $dateTimeOUT = $_date_out." ".$_time_out;
                            $dateTimeIN = $_date_in." ".$_time_in;

                           

                            // echo $_name." ".$_date_in." ".$_time_in." ".$_date_out." ".$_time_out."<br>";

                            if(!empty($_date_in) && !empty($_time_in))
                            {
                                $query_in = "SELECT *, date(DateTime), time(DateTime) FROM clock_in WHERE Name='$_name' AND date(DateTime) = '$_date_in'";
                                $result_in = mysqli_query($connect, $query_in);
                                $row_num_in = mysqli_num_rows($result_in);
                                $row_in = mysqli_fetch_assoc($result_in);

                                if($row_num_in == 1)
                                {
                                    $query_update_in = "UPDATE clock_in SET DateTime = '$dateTimeIN' WHERE Name='$_name' AND date(DateTime) = '$_date_in'";
                                }
                                else
                                {
                                    $query_update_in = "INSERT INTO clock_in(No, Mchn, EnNo, Name, Mode, IOMd, DateTime, Shift) 
                                                VALUES (NULL,'1','0','$_name','1','0','$dateTimeIN','$_shift')";
                                }

                                $result_update_in = mysqli_query($connect, $query_update_in);
                            }

                            if(!empty($_date_out) && !empty($_time_out))
                            {
                                $query_out = "SELECT *, date(DateTime), time(DateTime) FROM clock_out WHERE Name='$_name' AND date(DateTime) = '$_date_out'";
                                $result_out = mysqli_query($connect, $query_out);
                                $row_num_out = mysqli_num_rows($result_out);
                                $row_out = mysqli_fetch_assoc($result_out);

                                if($row_num_out == 1)
                                {
                                    $query_update_out = "UPDATE clock_out SET DateTime = '$dateTimeOUT' WHERE Name='$_name' AND date(DateTime) = '$_date_out'";
                                }
                                else
                                {
                                    $query_update_out = "INSERT INTO clock_in(No, Mchn, EnNo, Name, Mode, IOMd, DateTime, Shift) 
                                                VALUES (NULL,'2','0','$_name','1','0','$dateTimeOUT','$_shift')";
                                }

                                 $result_update_out = mysqli_query($connect, $query_update_out);
                            }

                            if(empty($_time_in) || empty($_time_out))
                            {
                                $alert = "<img src='image/alert_icon.png' class='alert_icon'>";
                            }
                            else
                            {
                                 $alert = "";
                            }

                            $notification = "<div class='checkMessageSuccess'><span>Update Successfully</span></div>";

                            echo "<tr>
                                    <td>".$_name."</td>
                                    <td>".$_shift."</td>
                                    <td>".$_date_in."</td>
                                    <td>".$_time_in."</td>
                                    <td>".$_date_out."</td>
                                    <td>".$_time_out."</td>
                                    <td>".$alert."</td>
                                </tr>
                                <tr></tr>";

                        }
                         echo $notification;
                   }
                ?>
            </table>

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

        function closeModal()
        {
          document.getElementById('id01').style.display = 'none';
        }


    </script>
</body>

</html>