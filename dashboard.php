<?php
    session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Dashboard</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    <link rel="stylesheet" href="style1.css">
    <style>
        .graph
        {
          width:90%;
          display:block;
          overflow:hidden;
          margin:0 auto;
          background:#fff;
          border-radius:4px;
          margin-top: 3%;
        }

        canvas
        {
          background:#fff;
          height:250px;
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

           <!--  <nav class="navbar navbar-expand-lg navbar-light bg-light">
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
            </nav> -->

             <div class="row title">
                <div class="col-6 pageTitle">
                    <h2>Dashboard</h2>
                </div>

                <div class="col-4 datepicker" style="text-align: right;">
                    <form action="dashboard.php" method="GET">
                        <input type="date" name="start_date" value="2019-10-01">
                        <!-- <span class="tooltiptext">Tooltip text</span> -->
                        <input type="submit" name="apply" value="APPLY" class="apply">
                    </form>
                </div>

                        <?php
                            $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Failed"); 

                            $morning_shift_late = strtotime('6:40:00');
                            $afternoon_shift_late = strtotime('14:40:00');
                            $night_shift_late = strtotime('21:40:00');

                            $time_array = [];
                            $late_array= [];
                            $overtime_array = [];
                            $notOnShift_array=[];

                            $late_count = 0;
                            $overtime_count = 0;
                            $notOnShift_count = 0;

                            if(isset($_GET['apply']))
                            {
                                $startDate = $_GET['start_date'];
                                $start = strtotime($startDate);
                                
                                // Add First date
                                // array_push($time_array, $startDate.",".$day);
                                array_push($time_array, $startDate);

                                //86400 = 1 day
                                for($i = 0; $i < 6; $i++)
                                {
                                    $start = $start + 86400;
                                    // echo date("l",$start);

                                    $new_date = date("Y-m-d", $start);
                                    $day = date("l", $start);

                                    // array_push($time_array, $new_date.",".$day);
                                    array_push($time_array, $new_date);
                                }
                                

                                $query = "SELECT employee.Name, employee.shift, DateTime,date(clock_in.DateTime), time(clock_in.DateTime) 
                                        from clock_in join employee on employee.Name = clock_in.Name
                                        where date(DateTime) between '$time_array[0]' and '$time_array[6]'";
                                $result = mysqli_query($connect, $query);
                                $row = mysqli_num_rows($result);
                                $night = "";

                                while($row = mysqli_fetch_assoc($result))
                                {
                                    $name = $row['Name'];
                                    $shift = $row['shift'];
                                    $date_in = $row['date(clock_in.DateTime)'];
                                    $time_in = $row['time(clock_in.DateTime)'];
                                    $in = $row['DateTime'];

                                    $d1 = date_create($date_in);
                                    date_add($d1, date_interval_create_from_date_string('1 days'));
                                    $next_day1 = date_format($d1, 'Y-m-d');

                                    if(strtotime($time_in) > $morning_shift_late && strtotime($time_in) < strtotime('10:30:00'))
                                    {
                                        $late_count++;
                                    }

                                    if(strtotime($time_in) > $afternoon_shift_late && strtotime($time_in) < strtotime('15:30:00'))
                                    {
                                        $late_count++;
                                    }

                                    if(strtotime($time_in) > $night_shift_late && strtotime($time_in) < strtotime('23:30:00'))
                                    {
                                        $late_count++;
                                    }

                                    $array_next = [];

                                    if($shift == "Night")
                                    {
                                        $night = "1";

                                        $query_out = "SELECT date(DateTime), time(DateTime), DateTime FROM clock_out WHERE date(DateTime) = '$next_day1' AND Name='$name'";
                                        $result_out = mysqli_query($connect, $query_out);
                                        $row_out = mysqli_num_rows($result_out);

                                        while($row_out = mysqli_fetch_assoc($result_out))
                                        {
                                            $out = $row_out['DateTime'];
                                            $date_out = $row_out['date(DateTime)'];
                                            $time_out = $row_out['time(DateTime)']; 

                                            if(strtotime($in)  > strtotime($out))
                                            {
                                                $interval = strtotime($in) - strtotime($out);
                                            }
                                            else
                                            {
                                                $interval = strtotime($out) - strtotime($in);
                                            }

                                            $minutes = floor($interval/60);
                                            $hours = floor($interval/3600);
                                            $_remainder = $minutes % 60;

                                            if($hours >= 8)
                                            {
                                                $overtime_count++;
                                            }

                                            if($hours < 8)
                                            {
                                                $notOnShift_count++;
                                            }

                                          // echo "<tr>
                                          //       <td>".$name."</td>
                                          //       <td>".$shift."</td>
                                          //       <td>".$date_in."</td>
                                          //       <td>".$time_in."</td>
                                          //       <td>".$date_out."</td>
                                          //       <td>".$time_out."</td>
                                          //       <td>".$hours." H ".$_remainder." M</td>
                                          //       <td>".$notOnShift_count."</td>
                                          //   </tr>";

                                            array_push($late_array, $late_count);
                                            array_push($notOnShift_array, $notOnShift_count);
                                            array_push($overtime_array, $overtime_count);
                                        }

                                        $late_count = 0;
                                        $overtime_count = 0;
                                        $notOnShift_count = 0;
                                    }
                                    else 
                                    {

                                        $query_out = "SELECT date(DateTime), time(DateTime) FROM clock_out WHERE date(DateTime) = '$date_in' AND Name='$name'";
                                        $result_out = mysqli_query($connect, $query_out);
                                        $row_out  = mysqli_num_rows($result_out );

                                        while($row_out = mysqli_fetch_assoc($result_out))
                                        {
                                            $date_out = $row_out['date(DateTime)'];
                                            $time_out = $row_out['time(DateTime)']; 

                                            $interval = strtotime($time_out) - strtotime($time_in);

                                            $minutes = floor($interval/60);
                                            $hours = floor($interval/3600);
                                            $_remainder = $minutes % 60;

                                            if($hours > 8)
                                            {
                                                $overtime_count++;
                                            }

                                            if($hours < 8)
                                            {
                                                $notOnShift_count++;
                                                $overtime_count = 0;
                                            }


                                             // echo "<tr>
                                             //        <td>".$name."</td>
                                             //        <td>".$shift."</td>
                                             //        <td>".$date_in."</td>
                                             //        <td>".$time_in."</td>
                                             //        <td>".$date_out."</td>
                                             //        <td>".$time_out."</td>
                                             //        <td>".$hours." H ".$_remainder." M</td>
                                             //        <td>".$notOnShift_count."</td>
                                             //        </tr>";

            
                                        } 
                                    }
                                }

                                array_push($overtime_array, $overtime_count);
                                array_push($notOnShift_array, $notOnShift_count);


                                 $dates = json_encode($time_array);
                                 $late_data = json_encode($late_array);
                                 $overtime_data = json_encode($overtime_array);
                                 $notOnShift_data = json_encode($notOnShift_array);
                            
                            }

                        ?>

            </div>

            <div class="graph">
                <canvas id="myChart4"></canvas>
            </div>

            <div class="row title">
                <div class="col-9 pageTitle">
                    <h5>Attendance Sheet</h5>
                </div>

                 <div class="col-3 upload">
                    <button>UPLOAD FILE</button>
                </div>
            </div>

            <div id="attendance_sheet">
                <table id="attendance">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Punctuality</th>
                    </tr>
                        <?php
                            include("dashboardAttendance.php");

                            $username = $_SESSION['username'];
                            $hashed_password = $_SESSION['hashed_password'];

                            if(empty($username) || empty($hashed_password))
                            {
                                $message = "";
                                header("Location: login.php");
                            }


                            // echo $username." ".$hashed_password;
                        ?>
                </table>
            </div>

            
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

        var ctx = document.getElementById("myChart4").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo $dates; ?>,
                datasets: [{
                    label: 'NotOnShift',
                    backgroundColor: "#066C81",
                    data: <?php echo $notOnShift_data; ?>,
                }, {
                    label: 'OverTime',
                    backgroundColor: "#FAB418",
                    data: <?php echo $overtime_data; ?>,
                }, {
                    label: 'Late',
                    backgroundColor: "#DA3530",
                    data: <?php echo $late_data; ?>,
                }],
            },
        options: {
             title: {
                display: true,
                fontFamily: 'Futura',
                fontColor:'#E35723',
                fontSize: '16',
                padding: 10,
                text: 'Weekly Overall Status Performance'
            },
            tooltips: {
              displayColors: true,
              callbacks:{
                mode: 'x',
              },
            },

            scales: {
              xAxes: [{
                stacked: true,
                barPercentage: 0.5,
                // scaleLabel: {
                //     display: true,
                //     labelString: 'Days',
                //     fontSize: '15',
                //   },
                ticks: {
                    fontColor: "#E35723",
                    fontFamily: "Futura",
                    fontStyle: 'bold',
                    fontSize: '16',
                },
                gridLines: {
                  display: false,
                  color: '#DA3530',
                }
              }],

              yAxes: [{
                stacked: true,
                barPercentage: 0.5,
                // scaleLabel: {
                //     display: true,
                //     labelString: 'Number',
                //     padding: 20,
                //     fontSize: '15',
                //   },
                ticks: {
                  beginAtZero: true,
                    fontColor: "#E35723",
                    fontFamily: "Futura",
                    fontStyle: 'bold',
                    fontSize: '16',
                },
                gridLines: {
                  display: false,
                  color: '#E35723',
                },
                type: 'linear',
              }]
            },
                responsive: true,
                maintainAspectRatio: false,
                legend: { 
                    position: 'right',
                },
            }
        });

    </script>
</body>

</html>