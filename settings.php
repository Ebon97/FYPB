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
                    <h2>Settings</h2>
                </div>
            </div>

      
            <?php
                $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error());  

                $username = $_SESSION['username'];
                $password = $_SESSION['password'];
                $hashed_password = $_SESSION['hashed_password'];

                // echo $username." ".$password." ".$hashed_password."<br>";

                $query = "SELECT * FROM manager where username='$username' and password='$hashed_password'";
                $result = mysqli_query($connect, $query);
                $row = mysqli_num_rows($result);

                // echo $row;

                if($row == 1)
                {
                        $row = mysqli_fetch_assoc($result);
                            
                        $db_id = $row['ID'];
                        $db_name = $row['username'];
                        $db_email = $row['email'];
                        $db_password = $password;
                        $db_hashpassword = $row['password'];

                        // echo $db_name." ".$db_email." ".$db_password." ".$db_hashpassword;
            ?>
                    <div id="update_setting">
                        <form action="settings.php" method="GET">
                           <div class='form-group row'>
                                <label>Username</label>
                                <label>:</label>
                                <div>
                                    <input type='text' class='form-control' value="<?php echo $db_name?>" name="sUsername">
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label>Password</label>
                                <label>:</label>
                                <div>
                                    <input type='text' class='form-control' value="<?php echo $db_password?>" name="sPassword">
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label>Email</label>
                                <label>:</label>
                                <div>
                                    <input type='text' class='form-control' value="<?php echo $db_email?>" name="sEmail">
                                </div>
                            </div>

                            <div id="update_button">
                                <input type="submit" name="settings_update" value="UPDATE">
                            </div>
                        </form>
                    <div>
            <?php   

                    include("settingsUpdate.php");
                }
                else 
                {
                   $message = "Something's not wrong. Re-login is recommended";
                }

            ?>

            
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

    </script>
</body>

</html>