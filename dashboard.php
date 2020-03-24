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
                <li>
                    <a href="#">Account</a>
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
                    <input type="date" name="start_date">
                    <!-- <span class="tooltiptext">Tooltip text</span> -->
                    <input type="submit" value="APPLY">
                </div>

                 <!-- <div class="col-3 apply">
                    <button onclick="applyDate()">APPLY</button>
                </div> -->


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
                labels: ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
                datasets: [{
                    label: 'OnTime',
                    backgroundColor: "#066C81",
                    data: [5, 10, 5],
                }, {
                    label: 'OverTime',
                    backgroundColor: "#FAB418",
                    data: [5, 10, 5,],
                }, {
                    label: 'Late',
                    backgroundColor: "#DA3530",
                    data: [5, 10, 5],
                }],
            },
        options: {
             title: {
                display: true,
                fontFamily: 'Futura',
                fontColor:'#E35723',
                fontSize: '16',
                padding: -10,
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