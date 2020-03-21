<?php
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
    	$onshift_status = "O";
    }
    else
    {
    	$onshift_status = "X";
    }

    //Checking Penalities if not work full 8 Hours
    if($minutes < 480)
    {
    	$penalties_min = 480 - $minutes;

    	$penalties = round($penalties_min * $penalties_per_minutes,2);
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
	
?>