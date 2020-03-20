<?php
    $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error()); 
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Salary</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="style1.css">

    <style>
        table,td,th,td
        {
            border:1px solid black;
            padding:0.2vh 2vw;
        }

        table
        {
            border-collapse: collapse;
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
                    <a href="#">Others</a>
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
            <?php


            ?>
            <div class="row title">
                <div class="col-6 pageTitle">
                    <?php
                    $monthy_array = array("NULL","January","February","March","April","May","June","July","August","September","October","November","December");

                        if(isset($_GET['monthly_generator']))
                        {
                            $month = $_GET['month'];


                            if($month == "all")
                            {
                                
                            }
                            else 
                            {
                                echo "<h2>".$monthy_array[$month]." Salary Payroll</h2>";
                    ?>
                </div>
            </div>
                <table>
                    <?php
                            

                            $query_in = "SELECT employee.No, clock_in.Name, employee.shift, date(clock_in.DateTime), time(clock_in.DateTime)
                                        from clock_in join employee on employee.Name = clock_in.Name
                                        where date(clock_in.DateTime) between '2019-10-01' and '2019-10-31'
                                        ORDER BY employee.No,date(clock_in.DateTime)";

                            $query_out = "SELECT employee.No, clock_out.Name, employee.shift, date(clock_out.DateTime), time(clock_out.DateTime)
                                            from clock_out join employee on employee.Name = clock_out.Name
                                            where date(clock_out.DateTime) between '2019-10-01' and '2019-11-01'
                                            ORDER BY employee.No,date(clock_out.DateTime)";

                            
                            
                            $result_in = mysqli_query($connect, $query_in);
                            $result_out = mysqli_query($connect, $query_out);

                            $row_in = mysqli_num_rows($result_in);
                            $row_out = mysqli_num_rows($result_out);

                            //mysqli_fetch_assoc($result)
                            //while(($row = $result->fetch_assoc()) && ($row2 = $result2->fetch_assoc()))

                            while(($row_in = mysqli_fetch_assoc($result_in)) && ($row_out = mysqli_fetch_assoc($result_out)))
                            {
                                $name_in = $row_in['Name'];
                                $shift_in = $row_in['shift'];
                                $date_in = $row_in['date(clock_in.DateTime)'];
                                $time_in = $row_in['time(clock_in.DateTime)'];

                                $name_out = $row_out['Name'];
                                $shift_out = $row_out['shift'];
                                $date_out = $row_out['date(clock_out.DateTime)'];
                                $time_out = $row_out['time(clock_out.DateTime)'];

                                echo "<tr>
                                    <td>".$name_in."</td>
                                    <td>".$shift_in."</td>
                                    <th>".$date_in."</th>
                                    <td>".$time_in."</td>
                                    <td>".$shift_out."</td>
                                    <th>".$date_out."</th>
                                    <td>".$time_out."</td>
                                </tr>";
                            }

                        }
                        

                        
                    }
                    else if (isset($_GET['individual_generator']))
                    {
                        echo "individual<br>";
                        $name = $_GET['name'];
                        $month = $_GET['indi_month'];

                        echo $name." ".$month."<br>";


                    }
                    else
                    {
                        echo "nothing";
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
