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
                        <h2>Performance</h2>
                    </div>
                </div>

                <?php
                	session_start();

                	$connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error()); 

                    $query = "SELECT * FROM employee";
                    $result = mysqli_query($connect, $query);
                    $row_num = mysqli_num_rows($result);
                    // $row = mysqli_fetch_assoc($result);

                    $arrayInfo = [];

                    // echo $row_num."<br>";

                    // echo $row."<br>";
                    
                    for($num = 0; $num < $row_num; $num++)
                    {
                    	if(isset($_GET['check'.$num]))
                    	{
                    		$name = $_GET['name'.$num];
                    		// echo $name[$num];

                            $query_info = "SELECT * FROM employee WHERE Name='$name'";
                            $result_info = mysqli_query($connect, $query_info);
                            $row_count_info = mysqli_num_rows($result_info);
                            $row_info = mysqli_fetch_assoc($result_info);

                            if($row_count_info == 1)
                            {
                                // echo "Found";

                                $name_info = $row_info['Name'];
                                $position_info = $row_info['Position'];
                                $shift_info = $row_info['Shift'];
                                $salary_info = $row_info['Salary'];

                                // echo $name_info." ".$position_info." ".$shift_info." ".$salary_info."<br>";

                                array_push($arrayInfo, $name_info);
                                array_push($arrayInfo, $position_info);
                                array_push($arrayInfo, $shift_info);
                                array_push($arrayInfo, $salary_info);
                            }
                    	}
                    }
                ?>
                <!--  -->

                <div class="info_container">
                    <!-- First Row -->
                    <div class="row">
                        <div class="col-6">
                            <div class="row" >
                                <div class="col-12">
                                    <table id="performance_info">
                                        <?php

                                            $arrayLabel = array("Name","Position","Shift","Salary");

                                            for($b = 0; $b < sizeof($arrayInfo); $b++)
                                            {

                                                echo "<tr>
                                                    <th>".$arrayLabel[$b]."</th>
                                                    <th>:</th>
                                                    <td>".$arrayInfo[$b]."</td>
                                                </tr>";
                                          
                                            }
                                        ?>
                                    </table>
                                </div>
                            </div>

                            <div class="row" style="margin-top:3%;">
                                <div class="col-2">
                                    
                                </div>
                                <div class="col-4 penaltiesH">
                                    <?php

                                        $query_hp = "SELECT * FROM salary_past  WHERE Name='$arrayInfo[0]'";
                                        $result_hp = mysqli_query($connect, $query_hp);
                                        $row_count_hp = mysqli_num_rows($result_hp);

                                        // echo $arrayInfo[0];
                                        // echo $row_count_hp;

                                        $penaltiesH = 0;
                                        $bonusH = 0;

                                        $arraySalary = ["October"];
                                        $arrayMonthSalary  = ["2000"];
                                        $month_name_array = ["NULL","January","February","March","April","May","June","July","August","September","October","November","December"];

                                        while($row_hp = mysqli_fetch_assoc($result_hp))
                                        {
                                            $month_num = $row_hp['month'];
                                            $shiftp = $row_hp['shift_penalties'];
                                            $latep = $row_hp['late_penalties'];
                                            $bonus = $row_hp['bonus'];
                                            $salaryf = $row_hp['final_salary'];

                                            if($shiftp > $latep)
                                            {
                                                $penaltiesH = $shiftp;
                                            }
                                            else
                                            {
                                                $penaltiesH = $latep;
                                            }

                                            if($bonus > $bonusH)
                                            {
                                                $bonusH = $bonus;
                                            }

                                            $month = $month_name_array[$month_num];

                                            array_push($arraySalary, $salaryf);
                                            array_push($arrayMonthSalary, $month);

                                        }


                                        $salary_data = json_encode($arraySalary);
                                        $salary_month_data = json_encode($arrayMonthSalary);

                                    ?>
                                    <p>Highest Penalties</p>
                                    <span><?php echo $penaltiesH;?></span>
                                </div>

                                <div class="col-1">
           
                                </div>

                                <div class="col-4 bonusH">
                                    <p>Highest Bonus</p>
                                    <span><?php echo $bonusH; ?></span>
                                </div> 
                            </div>
                        </div>

                        <div class="col-5" style="margin-left:2%;">
                           <canvas id="dougnutChart" width="500" height="230"></canvas>
                        </div>

                    </div>

                    <!-- Second Row -->
                    <div class='row' style="margin-top:4%;">
                        <div class='col-6'>
                           <canvas id="lineChart" width="600" height="290"></canvas>
                        </div>

                        <div class='col-5' style="margin-left:2%;">
                            <canvas id="barChart" width="450" height="290"></canvas>
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
                            labels: ['Overall Performance'],
                            datasets: 
                            [{
                                data: [87, 100-87],
                                backgroundColor: [
                                    'green',
                                ],
                                borderColor: [
                                    'green',
                                ],
                                borderWidth: 1
                            }]
                        },

                options: {
                    cutoutPercentage: 60,
                    responsive: false,
                    }
                }
                );


            //Bar Chart
            var bar = document.getElementById("barChart");
            var barChart = new Chart(bar, {
              type: 'bar',
              data: {
                labels: <?php echo $salary_month_data; ?>,
                datasets: [{
                  label: 'Monthly Salary Report',
                  data: <?php echo $salary_data; ?>,
                  backgroundColor: [
                    '#DA3530',
                  ],
                  borderWidth: 1
                }]
              },
              options: {
                responsive: false,
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
              }
            });

            //Line Chart
            var line = document.getElementById("lineChart");

            var dataFirst = {
                label: "Late",
                data: [0, 59, 45, 20, 20, 55],
                lineTension: 0,
                fill: false,
                borderColor: '#D93630'
              };

            var dataSecond = {
                label: "Bonus",
                data: [20, 15, 60, 60, 65, 30],
                lineTension: 0,
                fill: false,
              borderColor: '#006C81'
              };

           var dataThird = {
            label: "Shift",
            data: [5, 10, 50, 30, 45, 50],
            lineTension: 0,
            fill: false,
            borderColor: '#FFB500',
          };

            var lineData = {
              labels: ["0s", "10s", "20s", "30s", "40s", "50s"],
              datasets: [dataFirst, dataSecond, dataThird]
            };

            var chartOptions = {
              legend: {
                display: true,
                position: 'top',
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
              type: 'line',
              data: lineData,
              options: chartOptions
            });

        </script>
    </body>
</html>