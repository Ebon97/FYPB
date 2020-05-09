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
                <div class="col-5 pageTitle">
                    <h2>Performance</h2>
                </div>
                
                <div class="col-4 search">
                    <input type="text" id="searchInput" onkeyup="searchFunction()" placeholder="Search Names" title="Type in a name">
                </div>
            </div>

            <table id="performanceList">
                <tr>
                    <th>No</th>
                    <th>Employee Name</th>
                    <th>Position</th>
                    <!-- <th>Overall Performance</th> -->
                </tr>

                <?php
                    $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error()); 

                    $query = "SELECT * FROM employee";
                    $result = mysqli_query($connect, $query);
                    $row = mysqli_num_rows($result);
                    $i = 0;

                    while($row = mysqli_fetch_assoc($result))
                    {
                        $id = $row['ID'];
                        $emp_name = $row['Name'];
                        $position = $row['Position'];

                        $shift = $row['Shift'];
                        $salary = $row['Salary'];
                        $startDate = $row['startDate'];

                        echo 
                         "<tr>
                            <td>".$id."</td>
                            <form action='performanceDashboard.php' method='GET'>
                                <td><input type='text' value='".$emp_name."' name='name".$i."'></td>
                                <td>".$position."</td>
                                <td><input type='submit' name='check".$i."' value='CHECK'></td>
                            </form>
                         </tr>";

                         $i++;
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