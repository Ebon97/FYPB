
<?php
    session_start();

    $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error()); 

    if(isset($_GET['monthly_generator']))
    {
        $year = $_GET['year'];
        $month = $_GET['month'];

        $_SESSION['year'] = $year;
        $_SESSION['month'] = $month;

        // echo $_SESSION['year']." ".$_SESSION['month'];

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

    
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Employee List</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">



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
                    <a href="#">Performance</a>
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
                    <h2>Salary Generator</h2>
                </div>
            </div>

            <div id="salary_generator_content">
                <div class="form-group" id="select_category">
                    <select class="form-control"  name="category" onchange="checkCategory(this.value)">
                        <option value="monthly">Monthly</option>
                        <option value="individual">Individual</option>
                    </select>
                </div>

                 <div id="month_form1" style="display:block">
                    <form id="monthly_form" action="salary.php" method="GET">

                        <input type="number" class="form-control" name="year" value="2019">

                        <select class="form-control" name="month">
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>

                        <button type="submit" name="monthly_generator">GENERATE</button>
                    </form>
                </div>

                <div id="indi_form2" style="display:none">
                    <form id="individual_form" action="salaryIndividualGenerator.php" method="GET">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Name" name="name">
                        </div>

                        <div class="form-group">
                            <select class="form-control"  name="indi_month">
                                <option value="all">All</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>

                        <button type="submit" name="individual_generator">GENERATE</button>
                    </form>
                </div>
            </div>


          
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {s
                $('#sidebar').toggleClass('active');
                $(this).toggleClass('active');
            });
        });

        function checkCategory(cat)
        {
            if(cat == "monthly")
            {
                document.getElementById("month_form1").style.display = "block";
                document.getElementById("indi_form2").style.display = "none";  
            }
            else if (cat == "individual")
            {
            
                document.getElementById("month_form1").style.display = "none";
                document.getElementById("indi_form2").style.display = "block";  
            }
        }


 

    </script>
</body>

</html>