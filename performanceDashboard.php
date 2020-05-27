<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Performance Dashboard</title>

        <!-- Bootstrap CSS CDN -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
        <link rel="icon" href="image/favicon.png">
        <!-- Our Custom CSS -->
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
                <?php
                	session_start();

                	$connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error());

                    $month_name_array = ["NULL","January","February","March","April","May","June","July","August","September","October","November","December"];

                    $timeline = "Overall";

                    $nameArray = [];

                    // if(isset($_GET['check']) || isset($_GET['perf']))
                    // {
                    // 	$name = $_GET['name'];
                    //     // $quarter = $_GET['quarter'];
                    // 	// echo $name[$num];

                    //     $query_info = "SELECT * FROM employee WHERE Name='$name'";
                    //     $result_info = mysqli_query($connect, $query_info);
                    //     $row_count_info = mysqli_num_rows($result_info);
                    //     $row_info = mysqli_fetch_assoc($result_info);

                    //     $name = $row_info['Name'];
                    //     $position = $row_info['Position'];
                    //     $shift = $row_info['Shift'];
                    //     $salary = $row_info['Salary'];
                    //     $startDate = $row_info['startDate'];

                    //     $query_salp = "SELECT * FROM salary_past WHERE Name='$name'";
                    //     $result_salp = mysqli_query($connect, $query_info);
                    //     $row_count_salp = mysqli_num_rows($result_info);
                    //     $row_salp = mysqli_fetch_assoc($result_info);

                    // }
                    $quarter = 0;

                    if(isset($_GET['perf']) || isset($_GET['check']))
                    {
                        $name = $_GET['name'];
                        $quarter = $_GET['quarter'];
                        // echo $name." ".$quarter."<br>";

                         // Employee Personal Data
                        $query_info = "SELECT * FROM employee WHERE Name='$name'";
                        $result_info = mysqli_query($connect, $query_info);
                        $row_count_info = mysqli_num_rows($result_info);
                        $row_info = mysqli_fetch_assoc($result_info);

                        $name = $row_info['Name'];
                        $position = $row_info['Position'];
                        $shift = $row_info['Shift'];
                        $salary = $row_info['Salary'];
                        $startDate = $row_info['startDate'];

                        // Bar Chart
                        $month = [];
                        $initArray = [];
                        $penaltiesArray = [];
                        $bonusArray = [];

                        $penaltiesH = [];
                        $bonusH = [];

                        $highest_bonus = 0;
                        $highest_penalties = 0;

                        //Line Chart
                        $late_array = [];
                        $overtime_array = [];
                        $notonShift_array = [];

                        $total_init_salary = 0;
                        $total_shiftp = 0;
                        $total_latep = 0;
                        $total_bonus = 0;

                        $late_count = 0;
                        $overtime_count = 0;
                        $notonShift_count = 0;

                        // Dounghut-
                        $total_quarter_penalties = 0;
                        $overall_array = [];
                        $overall_colour_array = [];
                        $doughnutChart_name = [];

                        $morning_shift_late = strtotime('6:40:00');
                        $afternoon_shift_late = strtotime('14:40:00');
                        $night_shift_late = strtotime('22:40:00');


                    //Checking Quarters
                        if($quarter == "All" || isset($_GET['check']))
                        {
                            $total_salary = 0;
                            // $query_salp = "SELECT * FROM salary_past WHERE Name='$name'";
                            array_push($month, "Overall Salary");
                            $timeline = "Overall";
                            array_push($doughnutChart_name, "Overall Performance");

                            $query_salp = "SELECT * FROM salary_past WHERE Name='$name'";
                            $result_salp = mysqli_query($connect, $query_salp);
                            $row_count_salp = mysqli_num_rows($result_salp);

                            while($row_salp = mysqli_fetch_assoc($result_salp))
                            {
                                $isalary = $row_salp['init_salary'];
                                $shiftp = $row_salp['shift_penalties'];
                                $latep = $row_salp['late_penalties'];
                                $bonus = $row_salp['bonus'];
                                $fsalary = $row_salp['final_salary'];

                                if($bonus > $highest_bonus)
                                {
                                    $highest_bonus = $bonus;
                                }

                                if($shiftp > $latep)
                                {
                                    $highest_penalties = $shiftp;
                                }
                                else if ($latep > $shiftp)
                                {
                                    $highest_penalties = $latep;
                                }

                                $total_init_salary = $total_salary + $isalary;
                                $total_shiftp = $total_shiftp + $shiftp;
                                $total_latep = $total_latep + $latep;
                                $total_bonus = $total_bonus + $bonus;

                            }

                            array_push($bonusH, $highest_bonus);
                            array_push($penaltiesH, $highest_penalties);

                            array_push($initArray, round($total_init_salary/3,2));
                            array_push($penaltiesArray, $total_latep + $total_shiftp/3);
                            array_push($bonusArray, $total_bonus/3);

                            $init_salary_data = json_encode($initArray);
                            $init_salary_data = json_encode($initArray);
                            $penalties_data = json_encode($penaltiesArray);
                            $bonus_data = json_encode($bonusArray);

                            // echo $bonusH[0];

                            $query_status = "SELECT clock_in.Name, time(clock_in.DateTime), clock_in.Shift, clock_in.DateTime as dateTimeIN, clock_out.Name, date(clock_out.DateTime), time(clock_out.DateTime), clock_out.Shift, clock_out.NightFix, clock_out.DateTime as dateTimeOUT from clock_in inner join clock_out on date(clock_in.DateTime) = date(clock_out.NightFix) and clock_in.Name = clock_out.Name where clock_in.Name = '$name'";
                            $result_status = mysqli_query($connect, $query_status);
                            $row_count_status = mysqli_num_rows($result_status);

                            while($row_status = mysqli_fetch_assoc($result_status))
                            {  
                                // Late
                                $time_in = $row_status['time(clock_in.DateTime)'];

                                // Not on Shift & Bonus
                                $shift = $row_status['Shift'];
                                $dateTimeIN = $row_status['dateTimeIN'];
                                $dateTimeOUT = $row_status['dateTimeOUT'];

                                $interval = strtotime($dateTimeOUT) - strtotime($dateTimeIN);
                                $minutes = floor($interval/60);
                                $hours = floor($interval/3600);
                                $_remainder = $minutes % 60;


                                //Late Count
                                if($shift == "Morning")
                                {
                                    // echo "HI<br>";
                                    if(strtotime($time_in) > $morning_shift_late)
                                    {
                                        $late_count++;
                                        $diff = strtotime($time_in) - $morning_shift_late;
                                    }

                                }
                                
                                if ($shift == "Afternoon")
                                {
                                    if(strtotime($time_in) > $afternoon_shift_late)
                                    {
                                        $late_count++;
                                        $diff = strtotime($time_in) - $afternoon_shift_late;
                                    }

                                }

                                if ($shift == "Night")
                                {
                                    if(strtotime($time_in) > $night_shift_late)
                                    {
                                        $late_count++;
                                        $diff = strtotime($time_in) - $night_shift_late;
                                    }
                                }

                                //Overtime
                                if($hours > 8)
                                {
                                    $overtime_count ++;
                                }
                                else if ($hours < 8)
                                {
                                    $notonShift_count ++;
                                }
                                
                            }

                            $quarter_penalties = $late_count + $notonShift_count; 

                            $total_quarter_penalties = $total_quarter_penalties + $quarter_penalties;

                            // echo $total_quarter_penalties;

                            $final_percentage = round($total_quarter_penalties/78 * 100, 2);

                            if($final_percentage >= 80)
                            {
                                array_push($overall_colour_array, "green");
                            }
                            else if ($final_percentage >= 60 && $final_percentage < 80)
                            {
                                array_push($overall_colour_array, "orange");
                            }
                            else
                            {
                                array_push($overall_colour_array, "red");
                            }

                            array_push($late_array, $late_count);
                            array_push($overtime_array, $overtime_count);
                            array_push($notonShift_array, $notonShift_count);

                            array_push($overall_array, $final_percentage);
                            array_push($overall_array, 100-$final_percentage);

                            //Dougnut Chart
                            $overall_data = json_encode($overall_array);
                            $overall_colour_data = json_encode($overall_colour_array);

                            //Line Chart Become Bar Chart
                            $late_data = json_encode($late_array);
                            $overtime_data = json_encode($overtime_array);
                            $notonShift_data = json_encode($notonShift_array);
                                

                            // echo $month[0];

                        }
                        else
                        {
                            $iquarter = (int)$quarter;

                            if($iquarter == 1)
                            {
                                $timeline = "First Quarter";
                                array_push($doughnutChart_name, "Q1's Performance");
                            }
                            else if ($iquarter == 4)
                            {
                                $timeline = "Second Quarter";
                                array_push($doughnutChart_name, "Q2's Performance");
                            }
                            else if ($iquarter == 7)
                            {
                                $timeline = "Third Quarter";
                                array_push($doughnutChart_name, "Q3's Performance");
                            }
                            else if ($iquarter == 9)
                            {
                                $timeline = "Fourth Quarter";
                                 array_push($doughnutChart_name, "Q4's Performance");
                            }
                            else
                            {
                                $timeline = "Overall";
                            }
                            
                            for($count = 0; $count < 3; $count++)
                            {
                                $query_salp = "SELECT * FROM salary_past WHERE Name='$name' AND month='$iquarter'";
                                array_push($month, $month_name_array[$iquarter]);

                                // echo $query_salp."<br>";

                                $result_salp = mysqli_query($connect, $query_salp);
                                $row_count_salp = mysqli_num_rows($result_salp);
                                $row_salp = mysqli_fetch_assoc($result_salp);

                                // Bar Chart Data
                                if($row_count_salp == 1)
                                {
                                    $isalary = $row_salp['init_salary'];
                                    $shiftp = $row_salp['shift_penalties'];
                                    $latep = $row_salp['late_penalties'];
                                    $bonus = $row_salp['bonus'];

                                    // echo $shiftp." ".$latep."<br>";

                                    array_push($initArray, $isalary);
                                    array_push($penaltiesArray, ($shiftp+$latep));
                                    array_push($bonusArray, $bonus);

                                    if($bonus > $highest_bonus)
                                    {
                                        $highest_bonus = $bonus;
                                    }

                                    if($shiftp > $latep)
                                    {
                                        $highest_penalties = $shiftp;
                                    }
                                    else if ($latep > $shiftp)
                                    {
                                        $highest_penalties = $latep;
                                    }


                                }
                                else
                                {

                                }

                                // Line Chart Data
                                $query_status = "SELECT clock_in.Name, time(clock_in.DateTime), clock_in.Shift, clock_in.DateTime as dateTimeIN, clock_out.Name, date(clock_out.DateTime), time(clock_out.DateTime), clock_out.Shift, clock_out.NightFix, clock_out.DateTime as dateTimeOUT from clock_in inner join clock_out on date(clock_in.DateTime) = date(clock_out.NightFix) and clock_in.Name = clock_out.Name where month(clock_in.DateTime) = '$iquarter' and clock_in.Name = '$name'";

                                $result_status = mysqli_query($connect, $query_status);
                                $row_count_status = mysqli_num_rows($result_status);

                                while($row_status = mysqli_fetch_assoc($result_status))
                                {  
                                    // Late
                                    $time_in = $row_status['time(clock_in.DateTime)'];

                                    // Not on Shift & Bonus
                                    $shift = $row_status['Shift'];
                                    $dateTimeIN = $row_status['dateTimeIN'];
                                    $dateTimeOUT = $row_status['dateTimeOUT'];

                                    $interval = strtotime($dateTimeOUT) - strtotime($dateTimeIN);
                                    $minutes = floor($interval/60);
                                    $hours = floor($interval/3600);
                                    $_remainder = $minutes % 60;


                                    //Late Count
                                    if($shift == "Morning")
                                    {
                                        // echo "HI<br>";
                                        if(strtotime($time_in) > $morning_shift_late)
                                        {
                                            $late_count++;
                                            $diff = strtotime($time_in) - $morning_shift_late;
                                        }

                                    }
                                    
                                    if ($shift == "Afternoon")
                                    {
                                        if(strtotime($time_in) > $afternoon_shift_late)
                                        {
                                            $late_count++;
                                            $diff = strtotime($time_in) - $afternoon_shift_late;
                                        }

                                    }

                                    if ($shift == "Night")
                                    {
                                        if(strtotime($time_in) > $night_shift_late)
                                        {
                                            $late_count++;
                                            $diff = strtotime($time_in) - $night_shift_late;
                                        }
                                    }

                                    //Overtime
                                    if($hours > 8)
                                    {
                                        $overtime_count ++;
                                    }
                                    else if ($hours < 8)
                                    {
                                        $notonShift_count ++;
                                    }
                                    
                                }

                                // echo $month_name_array[$iquarter]." Late Count: ".$late_count."<br>";
                                // echo $month_name_array[$iquarter]." Overtime Count: ".$overtime_count."<br>";
                                // echo $month_name_array[$iquarter]." NotOnShift Count: ".$notonShift_count."<br>";

                                $quarter_penalties = $late_count + $notonShift_count; 

                                $total_quarter_penalties = $total_quarter_penalties + $quarter_penalties;


                                array_push($late_array, $late_count);
                                array_push($overtime_array, $overtime_count);
                                array_push($notonShift_array, $notonShift_count);

                                $late_count = 0;
                                $overtime_count = 0;
                                $notonShift_count = 0;
                                $iquarter++;


                            }

                            // echo "Total: ".$total_quarter_penalties."<br>";
                            $final_percentage = round($total_quarter_penalties/78 * 100, 2);

                            if($final_percentage >= 80)
                            {
                                array_push($overall_colour_array, "green");
                            }
                            else if ($final_percentage >= 60 && $final_percentage < 80)
                            {
                                array_push($overall_colour_array, "orange");
                            }
                            else
                            {
                                array_push($overall_colour_array, "red");
                            }


                            array_push($bonusH, $highest_bonus);
                            array_push($penaltiesH, $highest_penalties);
                            
                            array_push($overall_array, $final_percentage);
                            array_push($overall_array, 100-$final_percentage);
                            // echo $month[0]." ".$month[1]." ".$month[2];


                        }

                        // Bar Chart
                        $month_data = json_encode($month);
                        $init_salary_data = json_encode($initArray);
                        $penalties_data = json_encode($penaltiesArray);
                        $bonus_data = json_encode($bonusArray);

                        //Line Chart
                        $late_data = json_encode($late_array);
                        $overtime_data = json_encode($overtime_array);
                        $notonShift_data = json_encode($notonShift_array);

                        //Dougnut Chart
                        $overall_data = json_encode($overall_array);
                        $overall_colour_data = json_encode($overall_colour_array);
                        $dougnutChart_label = json_encode($doughnutChart_name);

                        // echo $overall_data." ".$overall_colour_data;

                    }
               
                    
                ?>
                <!--  -->
                <div class="row title">
                    <div class="col-7 pageTitle">
                        <h2><?php echo $timeline; ?> Performance</h2>
                    </div>
                </div>

                <div class="info_container">
                    <!-- First Row -->
                    <div class="row" >
                        <div class="col-6">
                            <div class="row" >
                                <div class="col-12">
                                    <table id="performance_info">
                                        <?php

                                            echo "<tr>
                                                <th>Name</th>
                                                <th>:</th>
                                                <td>".$name."</td>
                                            </tr>

                                            <tr>
                                                <th>Position</th>
                                                <th>:</th>
                                                <td>".$position."</td>
                                            </tr>

                                            <tr>
                                                <th>Shift</th>
                                                <th>:</th>
                                                <td>".$shift."</td>
                                            </tr>

                                            <tr>
                                                <th>Salary</th>
                                                <th>:</th>
                                                <td>RM ".$salary."</td>
                                            </tr>

                                            <tr>
                                                <th>Joined</th>
                                                <th>:</th>
                                                <td>".$startDate."</td>
                                            </tr>";
                                            
                                        ?>
                                    </table>
                                </div>
                            </div>

                            <div class="row" style="margin-top:3%;">
                                <div class="col-2">
                                    
                                </div>
                                <div class="col-4 penaltiesH">
                                    <p>Highest Penalties</p>
                                    <span>RM <?php echo $penaltiesH[0]; ?></span>
                                </div>

                                <div class="col-1">
           
                                </div>

                                <div class="col-4 bonusH">
                                    <p>Highest Bonus</p>
                                    <span>RM <?php echo $bonusH[0]; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-5" style="margin-left:2%;">
                            <?php
                                $arrayQuarter = array("1","4","7","9","All");
                                $arrayQuarterName = array("Q1","Q2","Q3","Q4","Overall");

                                for($b = 0; $b < 5; $b++)
                                {
                                    echo "
                                        <form style='float:left' action='performanceDashboard.php' method='GET' id='quarterbutton'>
                                            <input type='hidden' name='name' value='".$name."'>
                                            <input type='hidden' name='quarter' value='".$arrayQuarter[$b]."'>
                                            <input type='submit' value='".$arrayQuarterName[$b]."' name='perf'>
                                        </form>";
                                }

                            ?>
                           <canvas id="dougnutChart" width="500" height="240" style="margin-top:10%;"></canvas>
                        </div>

                    </div>

                    <!-- Second Row -->
                    <div class='row' style="margin-top:3%;">
                        <div class='col-6'>
                           <canvas id="lineChart" width="500" height="250"></canvas>
                        </div>

                        <div class='col-5' style="padding-left:4%; margin-left: 3%;">
                            <canvas id="barChart" width="450" height="300"></canvas>
                        </div>
                    </div>

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
                $('#sidebarCollapse').on('click', function () {s
                    $('#sidebar').toggleClass('active');
                    $(this).toggleClass('active');
                });
            });

            // Dougnut Chart
            var ctx = document.getElementById("dougnutChart");
            var dougnutChart = new Chart(ctx, 
                {
                    type: 'doughnut',
                    data: {
                            labels: <?php echo $dougnutChart_label;?>,
                            datasets: 
                            [{
                                data: <?php echo $overall_data;?>,
                                backgroundColor: [
                                    <?php echo $overall_colour_data;?>,
                                ],
                                borderColor: [
                                    <?php echo $overall_colour_data;?>,
                                ],
                                borderWidth: 1
                            }]
                        },

                options: {
                    legend: { 
                        position: 'top',
                    },
                    cutoutPercentage: 60,
                    responsive: false,
                    }
                }
                );


            //Bar Chart
            var bar = document.getElementById("barChart");

            var bar_data1 = {
                    label: 'Initial Salary',
                    backgroundColor: "#066C81",
                    borderColor: "#066C81",
                    data: <?php echo $init_salary_data; ?>,
                };

            var bar_data2 = {
                    label: 'Bonus',
                    backgroundColor: "#FAB418",
                    borderColor: "#FAB418",
                    data: <?php echo $bonus_data; ?>,
                };

            var bar_data3 = {
                    label: 'Penalties',
                    backgroundColor: "#DA3530",
                    borderColor: "#DA3530",
                    data: <?php echo $penalties_data; ?>,
                };

            var barChart = new Chart(bar, {
              type: 'bar',
              data: {
                labels: <?php echo $month_data?>,

               datasets: [bar_data1, bar_data2, bar_data3],
            },
              options: {
                legend: { 
                    position: 'top',
                },
                responsive: false,
                scales: {
                    xAxes: [{
                        stacked: true,
                        gridLines: {
                            drawOnChartArea: false, 
                            color: '#DA3530',
                        },
                        ticks: {
                            fontColor: "#E35723",
                            fontFamily: "Futura",
                            fontStyle: 'bold',
                            fontSize: '16',
                        },
                    }],
                    yAxes: [{
                        stacked: true,
                        ticks: {
                            fontColor: "#E35723",
                            fontFamily: "Futura",
                            fontStyle: 'bold',
                            fontSize: '16',
                        },
                        gridLines: {
                            drawOnChartArea: false,
                            color: '#DA3530',
                        }   
                    }]
                }
              }
            });

            //Line Chart
            var line = document.getElementById("lineChart");


            var dataFirst = {
                label: "Late",
                data: <?php echo $late_data?>,
                lineTension: 0,
                fill: false,
                backgroundColor: '#D93630',
                borderColor: '#D93630'
              };

            var dataThird = {
                label: "Overtime",
                data: <?php echo $overtime_data?>,
                lineTension: 0,
                fill: false,
                backgroundColor: '#006C81',
                borderColor: '#006C81'
              };

           var dataSecond = {
            label: "Not Full Shift",
            data: <?php echo $notonShift_data?>,
            lineTension: 0,
            fill: false,
            backgroundColor: '#FFB500',
            borderColor: '#FFB500'
          };

            var lineData = {
              labels: <?php if($timeline == "Overall"){echo "['Overall Performance']";}else if ($timeline != "Overall"){echo $month_data;}?>,
              datasets: [dataFirst, dataSecond, dataThird]
            };

            var chartOptions = {
              legend: {
                display: true,
                position: 'top',
                margin:15,
                labels: {
                  // boxWidth: 20,
                  fontColor: 'black'
                }
              },
              scales: {
                    xAxes: [{
                        gridLines: {
                            drawOnChartArea: false,
                            color: '#DA3530',
                        },
                        ticks: {
                            fontColor: "#E35723",
                            fontFamily: "Futura",
                            fontStyle: 'bold',
                            fontSize: '16',
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            fontColor: "#E35723",
                            fontFamily: "Futura",
                            fontStyle: 'bold',
                            fontSize: '16',
                        },
                        gridLines: {
                            drawOnChartArea: false,
                            color: '#DA3530',
                        }   
                    }]
                }

            };

            var lineChart = new Chart(line, {
              type: '<?php if($timeline == "Overall"){echo "bar";}else{echo "line";}?>',
              data: lineData,
              options: chartOptions
            });

        </script>
    </body>
</html>