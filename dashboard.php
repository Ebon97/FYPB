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
    <link rel="icon" href="image/favicon.png">
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
                    <h2>Dashboard</h2>
                </div>
                <?php
                    // echo  CURRENT_TIMESTAMP();
                    date_default_timezone_set('UTC');

                    $day = date('w');
                    $prefix_date = date('Y-m-d', strtotime('-'.($day-1).' days'));

                    // echo $prefix_date;

                ?>

                <div class="col-4 datepicker" style="text-align: right;">
                    <form action="dashboard.php" method="GET">
                        <input type="week" name="start_date" value="<?php echo $prefix_date?>">
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

                 <div id="flip" class="col-3 upload">
                    <button>SELECT FILE</button>
                </div>
            </div>

            <div id="panel">

                <form class="form-inline" action="uploads.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="clockinfile">Clock In Log File:</label>
                        <input type="file" id="clockinfile" name="clockinfile">
                    </div>
                    <div class="form-group">
                        <label for="clockoutfile">Clock Out Log File:</label>
                        <input type="file" id="clockoutfile" name="clockoutfile">
                    </div>
                    <button type="submit" class="btn btn-default">UPLOAD</button>
                </form>
            </div>

            <div id="attendance_sheet">
                <table id="attendance">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Shift</th>
                        <th>Punctuality</th>
                    </tr>
                        <?php
                            include("dashboardAttendance.php");

                            if(empty($username) || empty($hashed_password))                                             
                            {
                                $message = "";
                               
                            }

                        ?>
                </table>
            </div>

            
        </div>
    </div>

    <!-- jQuery CDN - Slim version -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script> 
        $(document).ready(function(){
            $("#flip").click(function(){
                $("#panel").slideToggle("slow");
            });
        });
    </script>

    <style>

    #panel {
        display:none;
    }

    .form-inline {  
      display: flex;
      flex-flow: row wrap;
      align-items: center;
      padding-left: 112px;
      padding-right: 112px;
      padding-top: 20px;
      padding-bottom: 20px;
    }

    .form-inline label {
        font-family: Futura;
        font-weight: bold;
        color: #E35723;
        margin: 5px 2px 5px 0;
    }

    .form-inline input {
      vertical-align: middle;
    }

    .form-inline button {
        color: white;
        background-color: #E35723;
        font-family: Futura;
        font-weight: bold;
        outline: none;
        border:none;
        border-radius: 7px;
        padding:0.5vh 1vw;
    }

    .form-inline button:hover {
        cursor: pointer;
        opacity: 0.7;
    }
      
    @media (max-width: 800px) {
      .form-inline input {
        margin: 10px 0;
      }
      
      .form-inline {
        flex-direction: column;
        align-items: stretch;
      }
    }
</style>

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
                datasets: [
                {
                    label: 'Late',
                    backgroundColor: "#DA3530",
                    borderColor: "#DA3530",
                    data: <?php echo $late_data; ?>,
                },
                {
                    label: 'Punctual',
                    backgroundColor: "#228C22",
                    borderColor: "#228C22",
                    data: <?php echo $punctual_data; ?>,
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
                    stepSize: 5,
                    max: 25,
                    fontFamily: "Futura",
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