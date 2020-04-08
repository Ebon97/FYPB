<?php
    session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Rates</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    <link rel="stylesheet" href="style1.css">
</head>
    <style>
        /* Float cancel and delete buttons and add an equal width */
        .cancelbtn, .deletebtn 
        {
/*          float: left;
          width: 50%;*/
        }

        /* Add a color to the cancel button */
        .cancelbtn {
          background-color: #ccc;
          color: black;
        }

        /* Add a color to the delete button */
        .deletebtn {
          background-color: #f44336;
        }

        /* Add padding and center-align text to the container */
        .container {
          padding: 10px;
          text-align: center;
        }

        /* The Modal (background) */
        .modal {
          display: none; /* Hidden by default */
          position: fixed; /* Stay in place */
          z-index: 1; /* Sit on top */
          left: 0;
          top: 0;
          width: 100%; /* Full width */
          height: 100%; /* Full height */
          overflow: auto; /* Enable scroll if needed */
          background-color: #E35723;
          padding-top: 50px;
          opacity: 0.95;
        }

        /* Modal Content/Box */
        .modal-content {
          background-color: #fefefe;
          margin: 7% auto 15% auto; /* 7% from the top, 15% from the bottom and centered */
          width: 70%; /* Could be more or less, depending on screen size */
          border-radius: 10px;
        }

        /* Style the horizontal ruler */
        hr {
          border: 1px solid #f1f1f1;
          margin-bottom: 25px;
        }

        /* The Modal Close Button (x) */
        .close {
          position: absolute;
          right: 35px;
          top: 15px;
          font-size: 40px;
          font-weight: bold;
          color: white;
        }

        .close:hover,
        .close:focus {
          cursor: pointer;
        }

        /* Clear floats */
        .clearfix::after {
          content: "";
          clear: both;
          display: table;
        }

        .clearfix button
        {
            color: white;
            background-color: #E35723;
            outline: none;
            border: 1px solid #E35723;
            padding: 0.5vh 2vw;
            font-weight: bold;
            border-radius: 7px;
            margin: 2vh 0;
        }

         .clearfix button:hover
         {
            opacity: 0.8;
            cursor: pointer;
         }

        /* Change styles for cancel button and delete button on extra small screens */
        @media screen and (max-width: 300px) {
          .cancelbtn, .deletebtn {
            width: 100%;
          }
        }
    </style>

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
                    <a href="salary.php">Salary Payroll</a>
                </li>
                <li>
                    <a href="rates.php">Rates</a>
                </li>
                <li>
                    <a href="history.php">History</a>
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

             <div class="row title">
                <div class="col-6 pageTitle">
                    <h2>Salary Confirmation</h2>
                </div>
            </div>

            <?php
                $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Failed");
                $message = "";

                $year = $_SESSION['year'];
                $month = $_SESSION['month'];

                $_SESSION['year'] = $year;
                $_SESSION['month'] = $month;

                if(isset($_GET['monthly_generator']))
                {
                    $year = $_GET['year'];
                    $month = $_GET['month'];

                    $query = "SELECT * FROM checkgenerator WHERE year='$year' AND month='$month'";
                    $result = mysqli_query($connect, $query);
                    $row = mysqli_num_rows($result);

                    if($row == 1)
                    {
                        header("Location: salaryMonthlyGenerator.php");
                    }
                    else
                    {
                        header("Location: salaryConfirmation.php");
                    }
                }

                $query = "SELECT * FROM rates";
                $result = mysqli_query($connect, $query);
                $row = mysqli_num_rows($result);

                if($row == 1)
                {

                    $row = mysqli_fetch_assoc($result);
                    
                    $overtime_bonus = $row['overtime_bonus'];                                               
                    $late_penalties = $row['late_penalties'];
                    $shift_penalties = $row['shift_penalties'];
                }
            ?>

            <div id="ratesConfirm">
                <h5>Rates</h5>
                <table>
                    <tr>
                        <th>Bonus Rate</th>
                        <th>Late Penalties Rate</th>
                        <th>Shift Penalties Rate</th>
                    </tr>
                    <tr>
                        <td><?php echo $overtime_bonus; ?></td>
                        <td><?php echo $late_penalties; ?></td>
                        <td><?php echo $shift_penalties; ?></td>
                    </tr>
                </table>

                 <h5>Salary Raise</h5>
                 <table id="salaryRaise">
                     <tr>
                         <th>No</th>
                         <th>Employee</th>
                         <th>Last Month Salary</th>
                         <th>Raise</th>
                         <th>Cut</th>
                         <th>Final Salary</th>
                     </tr>

                     <?php
                        $query = "SELECT * FROM employee";
                        $result = mysqli_query($connect, $query);
                        $row = mysqli_num_rows($result);
                        $i = 0;

                        while($row = mysqli_fetch_assoc($result))
                        {
                            $no = $row['No'];
                            $employee = $row['Name'];
                            $salary = $row['salary'];
                        
                            echo "<tr>
                                <td>".$no."</td>
                                <td>".$employee."</td>
                                <td>".$salary."</td>
                                <td><input type='number' class='raise'></td>
                                <td><input type='number' class='cut'></td>
                                <td><span class='final'></span></td>
                            </tr>"; 
                        }

                    ?>

                 </table>

                <div id="salaryConfirmbutton">
                    <button onclick="add()">Add</button>
                    <form action="salaryMonthlyGenerator.php" method="GET">
                        <input type="hidden" value="<?php echo $year; ?>" name="year">
                        <input type="hidden" value="<?php echo $month; ?>" name="month">
                        <button type="submit" name="confirmsalary">Confirm</button>
                    </form>
                </div>

                <div id="id01" class="modal">
                <span onclick="closeModal()" class="close" title="Close Modal">&times;</span>

                <form class="modal-content" action="/action_page.php" method="GET">
                    <div class="container">
                        <h5>You haven't add the latest salary</h5>

                        <div class="clearfix">
                            <button type="button" class="okbtn" data-dismiss="modal" onclick="closeModal()">OK</button>
                        </div>
                    </div>
                </form>
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
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $(this).toggleClass('active');
            });
        });

        function add()
        {
            var i;
            var table = document.getElementById("salaryRaise");

            var r = document.getElementsByClassName("raise");
            var c = document.getElementsByClassName("cut");
            var f = document.getElementsByClassName("final");


            for (i = 1; i < table.rows.length; i++) 
            {

                initial = parseInt(table.rows[i].cells[2].innerHTML);
                raise = Number(r[i-1].value);
                cut  = Number(c[i-1].value);


                f[i-1].innerHTML = initial + raise - cut;

            }
        }

        function confirm()
        {
            var table = document.getElementById("salaryRaise");
            var f = document.getElementsByClassName("final");

            for (i = 1; i < table.rows.length; i++) 
            {
                if(f[i-1].innerHTML.length == 0)
                {
                   document.getElementById('id01').style.display = 'block';
                }
                else
                {
                    window.location = "salaryMonthlyGenerator.php";
                }
            }

        }

        function closeModal()
        {
          document.getElementById('id01').style.display = 'none';
        }

    </script>
</body>

</html>