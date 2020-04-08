<?php

	$query_rates = "SELECT * FROM rates WHERE no=1";
    $result_rates = mysqli_query($connect, $query_rates);
    $row_rates = mysqli_num_rows($result_rates);

    if($row_rates == 1)
    {
    	$row_rates = mysqli_fetch_assoc($result_rates);
    	
    	$no = $row_rates['no'];
    	$bonus = $row_rates['overtime_bonus'];
    	$late_penalties = $row_rates['late_penalties'];
    	$shift_penalties = $row_rates['shift_penalties'];
    }

	$total_shift_penalties = 0;
	$total_late_penalties = 0;
	$total_bonus = 0;

	$morning_shift_late = strtotime('6:40:00');
    $afternoon_shift_late = strtotime('14:40:00');
    $night_shift_late = strtotime('21:40:00');



	$query_in = "SELECT Name, date(DateTime),time(DateTime) 
				 FROM clock_in WHERE Name='".$name."' 
				 AND date(DateTime) BETWEEN '".$year."-".$month."-01' and '".$year."-".$month."-31'
				 ORDER BY date(dateTime)";

	$query_out = "SELECT Name, date(DateTime),time(DateTime) 
				 FROM clock_out WHERE Name='".$name."' 
				 AND date(DateTime) BETWEEN '".$year."-".$month."-01' and '".$year."-".$month."-31'
				 ORDER BY date(dateTime)";

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
			$diff = 0;

			//Calculation
			if(strtotime($time_out) > strtotime($time_in))
		    {
		    	$interval = strtotime($time_out) - strtotime($time_in);
		    }
		    else
		    {
		    	$interval = strtotime($time_in) - strtotime($time_out);
		    }

		    $minutes = floor($interval/60);
			$hours = floor($interval/3600);
		    $_remainder = $minutes % 60;

		    //Checking if on shift 8 hours
		    if($hours >= 8 && $hours < 12)
		    {
		    	$onshift_status = "<span style='color:green;'>O</span>";
		    }
		    else
		    {
		    	$onshift_status = "<span style='color:red;'>X</span>";
		    }

		    //Checking Penalities if not work full 8 Hours
		    if($minutes < 480)
		    {
		    	$penalties_min = 480 - $minutes;

		    	$penalties = round($penalties_min * $shift_penalties,2);
		    	$total_shift_penalties = $total_shift_penalties + $penalties;
		    }
		    else
		    {
		    	$penalties = " ";
		    }

		    //Checking if excedd 8 hours, bonus added
		    //Calculate by hours
		    if($hours > 8)
		    {
		    	if($hours >= 12)
		    	{
		    		$bonus = 4 * 4.86;
		    	}
		    	else
		    	{
		    		$overtime_hours = $hours - 8;
		        	$bonus = $overtime_hours * 4.86;
		    	}

		    	$total_bonus = $total_bonus + $bonus;
		    }
		    else
		    {
		    	$bonus = " ";
		    }

		    
			if(strtotime($time_in) > $morning_shift_late && strtotime($time_in) < strtotime('10:30:00'))
			{
				$late = "1";

				$late_penalties = round((strtotime($time_in) - $morning_shift_late)/60 * $late_penalties,2);
				$total_late_penalties = $total_late_penalties + $late_penalties;
			}
			else 
			{
				$late = "0";
				$late_penalties = " ";
			}


			if(strtotime($time_in) > $afternoon_shift_late && strtotime($time_in) > strtotime('10:30:00'))
			{
				$late = "1";

				$late_penalties = round((strtotime($time_in) - $afternoon_shift_late)/60 * $late_penalties,2);
				$total_late_penalties = $total_late_penalties + $late_penalties;
			}
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
?>
		<tr>
			<td><?php echo $name_in; ?></td>
			<td><?php echo $date_in; ?></td>
			<td><?php echo $time_in; ?></td>
			<!-- <td><?php echo $date_out; ?></td> -->
			<td><?php echo $time_out; ?></td>
			<!-- <td><?php echo $match; ?></td> -->
			<!-- <td><?php echo $diff ?></td> -->
			<td><?php echo $hours; ?> Hours <?php echo $_remainder ?> Minutes</td>
			<td><?php echo $onshift_status; ?></td>
			<td><?php echo $penalties; ?></td>
			<td><?php echo $late_penalties; ?></td>
			<td><?php echo $bonus; ?></td>
		</tr>

<?php
	}

	// $final_salary = round($salary + $total_bonus - $total_shift_penalties - $total_late_penalties,2);

	// //Reset Total Penalties
	// $total_shift_penalties = 0;
	// $total_bonus = 0;
	// $total_late_penalties = 0;
	
?>