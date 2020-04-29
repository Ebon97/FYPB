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
                    <h2>Dashboard</h2>
                </div>

                <div class="col-4 datepicker" style="text-align: right;">
                    <form action="dashboard.php" method="GET">
                        <input type="date" name="start_date" value="2020-04-01">
                        <!-- <span class="tooltiptext">Tooltip text</span> -->
                        <input type="submit" name="apply" value="APPLY" class="apply">
                    </form>
                </div>

                <?php 
                    include("dashboardGraphCalculation.php")
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
                        <th>Clock In Time</th>
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