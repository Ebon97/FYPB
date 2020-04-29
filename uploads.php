<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shellsbt";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

mysqli_query($conn,"TRUNCATE TABLE clock_in");
mysqli_query($conn,"TRUNCATE TABLE clock_out");

include("fileUpload.php");

$handlein = fopen("ClockInLog/GLogData.txt", "r");
      if ($handlein) {
          fgets($handlein, 10000);
          while (($line = fgets($handlein, 10000)) !== false) {
      
               $lineArr = explode("\t", "$line");
      
               // instead assigning one by onb use php list       
               list($c1, $c2, $c3, $c4, $c5, $c6, $c7) = $lineArr;

               $shift1 = strtotime('06:30:00');
               $shift2 = strtotime('14:30:00');
               $shift3 = strtotime('22:30:00');

               $time = substr($c7, 11);

               if(strtotime($time) >= $shift1 && strtotime($time) < $shift2)
               {
               $shift = "Morning";
               }

               if(strtotime($time) >= $shift2 && strtotime($time) < $shift3)
               {
               $shift = "Afternoon";
               }

               if(strtotime($time) >= $shift3)
               {
               $shift = "Night";
               }
      
               // and then insert data
               mysqli_query($conn,"INSERT INTO clock_in (No, Mchn, EnNo, Name, Mode, IOMd, DateTime, Shift) 
      VALUES ('$c1', '$c2', '$c3', '$c4', '$c5', '$c6', '$c7', '$shift')");
          }
      
          fclose($handlein);
      }

$handleout = fopen("ClockOutLog/GLogData.txt", "r");
      if ($handleout) {
          fgets($handleout, 10000);
          while (($line = fgets($handleout, 10000)) !== false) {
      
               $lineArr = explode("\t", "$line");
      
               // instead assigning one by onb use php list       
               list($c1, $c2, $c3, $c4, $c5, $c6, $c7) = $lineArr;

               $shift1 = strtotime('14:30:00');
               $shift2 = strtotime('22:30:00');
               $shift3 = strtotime('06:30:00');
               
               $time = substr($c7, 11);

               $date = substr($c7, 0, -10);

               if(strtotime($time) >= $shift1 && strtotime($time) < $shift2)
               {
               $shift = "Morning";
               $nightfix = $date;
               }

               if(strtotime($time) >= $shift2)
               {
               $shift = "Afternoon";
               $nightfix = $date;
               }

               if(strtotime($time) >= $shift3 && strtotime($time) < $shift1)
               {
               $shift = "Night";
               $nightfix = date('Y-m-d', strtotime ($date . '-1 day'));
               }
      
               // and then insert data
               mysqli_query($conn,"INSERT INTO clock_out (No, Mchn, EnNo, Name, Mode, IOMd, DateTime, Shift, NightFix) 
      VALUES ('$c1', '$c2', '$c3', '$c4', '$c5', '$c6', '$c7', '$shift', '$nightfix')");
          }
      
          fclose($handleout);
      }

      mysqli_query($conn,"INSERT IGNORE INTO employee (ID, Name) SELECT EnNo, Name FROM clock_in");

$conn->close();
header('location: dashboard.php');
?>