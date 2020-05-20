<?php
    session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Salary Confirmation</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    <link rel="stylesheet" href="style1.css">
</head>
    <style>

    </style>

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

                 <h5>Salary Changes</h5>
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

                        $table_row = 0;
                        
                        while($row = mysqli_fetch_assoc($result))
                        {
                            $id = $row['ID'];
                            $employee = $row['Name'];
                            $salary = $row['Salary'];

                        
                            echo "<tr>
                                <td>".$id."</td>
                                <td class='name'>".$employee."</td>
                                <td>".$salary."</td>
                                <td><input type='number' class='raise'></td>
                                <td><input type='number' class='cut'></td>
                                <td><span class='final'></span></td>
                            </tr>";
                            $table_row++;
                       }


                       $_SESSION['row'] = $table_row;

                    ?>

                 </table>

                <div id="salaryConfirmbutton">
                    <button onclick="add()">Add</button>
                    <form action="salaryMonthlyGenerator.php" method="GET" id="confirmSalaryForm">
                        <input type="hidden" value="<?php echo $year; ?>" name="year" id="year">
                        <input type="hidden" value="<?php echo $month; ?>" name="month" id="month">
                        <button type="submit" name="confirmsalary" style="visibility: hidden" id="confirmsalary">Confirm</button>
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

        var count = 0;
        var namearray = [];

        function add()
        {
            count++;
            var i;
            var table = document.getElementById("salaryRaise");

            var n = document.getElementsByClassName("name");
            var r = document.getElementsByClassName("raise");
            var c = document.getElementsByClassName("cut");
            var f = document.getElementsByClassName("final");

            var confirmButton = document.getElementById("confirmsalary");
            var month = document.getElementById("month");

            // alert(count);
            for (i = 1; i < table.rows.length; i++) 
            {
                name = table.rows[i].cells[1].innerHTML;
                initial = parseInt(table.rows[i].cells[2].innerHTML);
                raise = Number(r[i-1].value);
                cut  = Number(c[i-1].value);
                namearray.push(name);

                f[i-1].innerHTML = initial + raise - cut;

                var inputN = document.createElement("input");
                inputN.setAttribute("type","text");
                inputN.setAttribute("value",name);
                inputN.setAttribute("id","name"+[i]);
                inputN.setAttribute("name","name"+[i]);

                var input = document.createElement("input");
                input.setAttribute("type","number");
                input.setAttribute("value",f[i-1].innerHTML);
                input.setAttribute("id","salary"+[i]);
                input.setAttribute("name","salary"+[i]);

                if(document.getElementById("input"+[i]) == null)
                {
                    month.appendChild(inputN);
                    month.appendChild(input);
                                      
                }
                else if(document.getElementById("input"+[i]) != null)
                {
                    console.log(f[i-1].innerHTML);
                    var x = document.getElementById("input"+[i]);
                    x.value = f[i-1].innerHTML;
                }

                console.log(namearray);

                
                
            }
            
            confirmButton.style.visibility = "visible";

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

    <?php
        echo"<script>console.log(obj);</script>";
    ?>
</body>


</html>