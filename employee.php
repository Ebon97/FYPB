<?php session_start()?>
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
                    <a href="performance.php">Performance</a>
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

            <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
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
                    <h2>Employee List</h2>
                </div>
                
                <div class="col-4 search">
                    <input type="text" id="searchInput" onkeyup="searchFunction()" placeholder="Search Names" title="Type in a name">
                </div>
            </div>

            <table id="employeeList">
                <tr>
                    <th>No.</th>
                    <th>Employee Name</th>
                    <th>Position</th>
                    <th>Salary</th>
                    <th>Joined Since</th>
                    <th>Action</th>
                </tr>

                <?php 
                    include('employeeList.php'); 
                ?>

            </table>

           <!--  <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="hideModal()">&times;</span>

                    <form>
                        <input value="" id="empNo">
                        <?php 

                        ?>

                    </form>
                </div>
            </div> -->

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

        function searchFunction() 
        {
            var input, filter, table, tr, td, i, txtValue;

            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("employeeList");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) 
            {
                td = tr[i].getElementsByTagName("td")[1];

                if (td) 
                {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) 
                    {
                        tr[i].style.display = "";
                    } 
                    else 
                    {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }

        // var modal = document.getElementById("myModal");

        // function showModal(no)
        // {
        //     document.getElementById("empNo").value = no;

        //     modal.style.display = "block";
        // }

        // function hideModal()
        // {
        //     modal.style.display = "none";
        // }



        

    </script>
</body>

</html>