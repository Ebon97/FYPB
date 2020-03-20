<?php

	$month = 10;
	$employee_name = "david";

	$connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error()); 

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">

	    <title>TESTING</title>

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
	<body><div class="wrapper">
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
                    <a href="#">Salary</a>
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

        	<table>
        		<tr>
        			<th>Name</th>
        			<th>Date In</th>
        			<th>Time In</th>
        			<th>Date Out</th>
        			<th>Time Out</th>
        			<th>MATCH?</th>
        		</tr>
 			<?php 

				$query = "SELECT * from employee";
				$result = mysqli_query($connect, $query);
				$row = mysqli_num_rows($result);

				while($row = mysqli_fetch_assoc($result))
				{
					$name = $row['Name'];
					$shift = $row['shift'];


					if($shift == "Morning" || $shift == "Afternoon")
					{
						// echo $name." ".$shift." query1<br>";

						$query_in = "SELECT Name, date(DateTime),time(DateTime) FROM clock_in WHERE Name='".$name."' AND date(DateTime) BETWEEN '2019-10-01' and '2019-10-31'";
						$query_out = "SELECT Name, date(DateTime),time(DateTime) FROM clock_out WHERE Name='".$name."' AND date(DateTime) BETWEEN '2019-10-01' and '2019-10-31'";

						// echo "<br>".$query_in."<br>".$query_out;

						$result_in = mysqli_query($connect, $query_in);
						$result_out = mysqli_query($connect, $query_out);

						while(($row_in = mysqli_fetch_assoc($result_in)) && ($row_out = mysqli_fetch_assoc($result_out)))
						{
							$name_in = $row_in['Name'];
							$date_in = $row_in['date(DateTime)'];
							$time_in = $row_in['time(DateTime)'];

							$name_out = $row_out['Name'];
							$date_out = $row_out['date(DateTime)'];
							$time_out = $row_out['time(DateTime)'];

							if($date_in == $date_out)
							{
								$match = "MATCH";
							}
							else
							{
								$match = "NOT MATCH";

								// Checkin $in_date in clockout table
								$checkClockOut_query = "SELECT * from clock_out WHERE date(dateTime) = ".$date_in."";
								$checkClockOut_result = mysqli_query($connect, $checkClockOut_query);
								$checkClockOut_row = mysqli_num_rows($checkClockOut_result);

								// Check $out_date in clockin table
								$checkClockIn_query = "SELECT * from clock_in WHERE date(dateTime) = ".$date_out."";
								$checkClockIn_result = mysqli_query($connect, $checkClockIn_query);
								$checkClockIn_row = mysqli_num_rows($checkClockIn_result);

								if($checkClockOut_row == 0)
								{
									echo "Missing data of ".$in_date;
									echo "<br>";

									// $update_query_in = "INSERT INTO `clock_out`(`No`, `Mchn`, `EnNo`, `Name`, `Mode`, `IOMd`, `DateTime`) 
									// 					VALUES ('null',1,1,'david',1,1,'".$in_date." 00:00:00')";

									// $update_result_in = mysqli_query($connect, $update_query_in);

								}
								else if ($checkClockIn_row == 0)
								{
									echo "<br>";
									echo "Missing data of ".$out_date2;

									// $update_query_out = "INSERT INTO `clock_in`(`No`, `Mchn`, `EnNo`, `Name`, `Mode`, `IOMd`, `DateTime`) 
									// 								VALUES ('',1,1,'david',1,1,'".$out_date2." 00:00:00')";
									// $update_result_out = mysqli_query($connect, $update_query_in);

								}

							}

							echo 
							"<tr>
								<td>".$name_in."</td>
								<td>".$date_in."</td>
								<td>".$time_in."</td>
								<td>".$date_out."</td>
								<td>".$time_out."</td>
								<td>".$match."</td>
							</tr>";
						}

					}
					else if ($shift == "Night")
					{
						$query_in = "SELECT Name, date(DateTime),time(DateTime) FROM clock_in WHERE Name='".$name."' AND date(DateTime) BETWEEN '2019-10-01' and '2019-10-31'";
						$query_out = "SELECT Name, date(DateTime),time(DateTime) FROM clock_out WHERE Name='".$name."' AND date(DateTime) BETWEEN '2019-10-02' and '2019-11-01'";

						// echo "<br>".$query_in."<br>".$query_out;

						$result_in = mysqli_query($connect, $query_in);
						$result_out = mysqli_query($connect, $query_out);


						while(($row_in = mysqli_fetch_assoc($result_in)) && ($row_out = mysqli_fetch_assoc($result_out)))
						{
							$name_in = $row_in['Name'];
							$date_in = $row_in['date(DateTime)'];
							$time_in = $row_in['time(DateTime)'];

							$name_out = $row_out['Name'];
							$date_out = $row_out['date(DateTime)'];
							$time_out = $row_out['time(DateTime)'];
							
							$diff = round((strtotime($date_out) - strtotime($date_in))/3600/24,1);
							if($diff == 1)
							{
								$match = "MATCH";
							}
							else if ($diff == 0 || $diff > 1)
							{
								$match = "NOT MATCH";

								// Checkin $in_date in clockout table
								$checkClockOut_query = "SELECT * from clock_out WHERE date(dateTime) = ".$date_in."";
								$checkClockOut_result = mysqli_query($connect, $checkClockOut_query);
								$checkClockOut_row = mysqli_num_rows($checkClockOut_result);

								// Check $out_date in clockin table
								$checkClockIn_query = "SELECT * from clock_in WHERE date(dateTime) = ".$date_out."";
								$checkClockIn_result = mysqli_query($connect, $checkClockIn_query);
								$checkClockIn_row = mysqli_num_rows($checkClockIn_result);

								if($checkClockOut_row == 0)
								{
									echo "Missing data of ".$in_date;
									echo "<br>";

									// $update_query_in = "INSERT INTO `clock_out`(`No`, `Mchn`, `EnNo`, `Name`, `Mode`, `IOMd`, `DateTime`) 
									// 					VALUES ('null',1,1,'david',1,1,'".$in_date." 00:00:00')";

									// $update_result_in = mysqli_query($connect, $update_query_in);

								}
								else if ($checkClockIn_row == 0)
								{
									echo "<br>";
									echo "Missing data of ".$out_date2;

									// $update_query_out = "INSERT INTO `clock_in`(`No`, `Mchn`, `EnNo`, `Name`, `Mode`, `IOMd`, `DateTime`) 
									// 								VALUES ('',1,1,'david',1,1,'".$out_date2." 00:00:00')";
									// $update_result_out = mysqli_query($connect, $update_query_in);

								}
							}
									
							echo 
							"<tr>
								<td>".$name_in."</td>
								<td>".$date_in."</td>
								<td>".$time_in."</td>
								<td>".$date_out."</td>
								<td>".$time_out."</td>
								<td>".$match."</td>
								<td>".$diff."</td>
							</tr>";
						}
					}

				}

			?>
            
        </div>
    </div>
		



		<!-- $query = "SELECT Name, date(DateTime),time(DateTime) from clock_in WHERE Name='david' ORDER BY date(DateTime)";
			$query2 = "SELECT Name, date(DateTime),time(DateTime) from clock_out WHERE Name='david' ORDER BY date(DateTime)";


			$result = mysqli_query($connect, $query);
			$result2 = mysqli_query($connect, $query2);

			while(($row = $result->fetch_assoc()) && ($row2 = $result2->fetch_assoc()))
			{
				$name = $row['Name'];
				$in_date = $row['date(DateTime)'];
				$in_time = $row['time(DateTime)'];

				$name2 = $row2['Name'];
				$out_date2 = $row2['date(DateTime)'];
				$out_time2 = $row2['time(DateTime)'];
			} -->
		<br>
	</body>
</html>
