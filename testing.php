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

    <style>
        /* Set a style for all buttons */
        button {
          background-color: #E35723;
          color: white;
          padding: 14px 20px;
          margin: 8px 0;
          border: none;
          cursor: pointer;
          width: 30%;
          opacity: 0.9;
        }

        button:hover {
          opacity:1;
        }

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
          margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
          border: 1px solid #888;
          width: 80%; /* Could be more or less, depending on screen size */
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
          color: #f1f1f1;
        }

        .close:hover,
        .close:focus {
          color: #f44336;
          cursor: pointer;
        }

        /* Clear floats */
        .clearfix::after {
          content: "";
          clear: both;
          display: table;
        }

        /* Change styles for cancel button and delete button on extra small screens */
        @media screen and (max-width: 300px) {
          .cancelbtn, .deletebtn {
            width: 100%;
          }
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
                    <h2>Testing</h2>
                </div>
            </div>

            

            <form>
              <input type="text" name="month" id="month">
              <input type="text" name="month_name" id="month_name">
              
              <button onclick="openModal()" name="open" id="open">Open Modal</button>
            </form>
          
            <div id="id01" class="modal">
                <span onclick="closeModal()" class="close" title="Close Modal">&times;</span>

                <form class="modal-content" action="/action_page.php" method="GET">
                    <div class="container">
                        <h1>Delete Account</h1>
                        <p>Are you sure you want to delete your account?</p>

                        <div class="clearfix">
                            <button type="button" class="cancelbtn">Cancel</button>
                            <button type="button" class="deletebtn">Delete</button>
                        </div>
                    </div>
                </form>
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

         // Get the modal
        var modal = document.getElementById('id01');

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) 
        {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }

        function openModal()
        {
          document.getElementById('id01').style.display = 'block';
          // x = document.getElementById('open').name;

        }

        function closeModal()
        {
          document.getElementById('id01').style.display = 'none';
        }

 

    </script>
</body>

</html>