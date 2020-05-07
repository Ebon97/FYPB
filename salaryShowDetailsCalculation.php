
<?php
    for ($i = 1; $i < 32; $i++)
    {
        $missing_data_count = 0;

        $day = $year."-".$month."-".$i;
        $query_in = "SELECT Name, date(DateTime),time(DateTime), DateTime 
                 FROM clock_in WHERE Name='$name' AND date(DateTime) ='$day'";
        $result_in = mysqli_query($connect, $query_in);
        $row_num_in = mysqli_num_rows($result_in);
        $row_in = mysqli_fetch_assoc($result_in);


        if($row_num_in == 0)
        {
            $name_in = "";
            $dateTime_in = "";
            $date_in = $day;
            $time_in = "N/A";
            $day_in = "";
            $shift = "";

        }
        else if($row_num_in == 1)
        {
            $name_in = $row_in['Name'];
            $dateTime_in = $row_in['DateTime'];
            $date_in = $row_in['date(DateTime)'];
            $time_in = $row_in['time(DateTime)'];
            $day_in = date("l",strtotime($row_in['date(DateTime)']));
        }

        // Define the shift 
        // 0500AM - 0630AM - 1030AM
        if(strtotime($time_in) > strtotime('05:00:00') &&  strtotime($time_in) < strtotime('10:30:00'))
        {
            $shift = "Morning";
        }
        //1300 - 1430 - 1830
        else if (strtotime($time_in) > strtotime('13:00:00') &&  strtotime($time_in) < strtotime('18:30:00'))
        {
            $shift = "Afternoon";
        }
        //2000 - 2130 - 0130am
        else if(strtotime($time_in) > strtotime('20:00:00') &&  strtotime($time_in) < strtotime('23:59:00'))
        {
            $shift = "Night";
        }
        else if (strtotime(null))
        {
            $shift = "Part Time";
        }
        else
        {
            $shift="";
        }

        if($shift == "Night")
        {
            $nextday = $year."-".$month."-".($i+1);
            $query_out = "SELECT Name, date(DateTime),time(DateTime), DateTime 
                 FROM clock_out WHERE Name='$name' AND date(DateTime) ='$nextday'";

            $result_out = mysqli_query($connect, $query_out);
            $row_num_out = mysqli_num_rows($result_out);
            $row_out = mysqli_fetch_assoc($result_out);

            if(($i+1) == 32)
            {
                $next_month_day = $year."-".($month+1)."-01";
                $query_out = "SELECT Name, date(DateTime),time(DateTime), DateTime  
                     FROM clock_out WHERE Name='$name' AND date(DateTime) ='$next_month_day'";
            
                $result_out = mysqli_query($connect, $query_out);
                $row_num_out = mysqli_num_rows($result_out);
                $row_out = mysqli_fetch_assoc($result_out);
            }
        }   
        else
        {
            $nextday = $year."-".$month."-".$i;
            $query_out = "SELECT Name, date(DateTime),time(DateTime) , DateTime 
                 FROM clock_out WHERE Name='$name' AND date(DateTime) ='$nextday'";


            $result_out = mysqli_query($connect, $query_out);
            $row_num_out = mysqli_num_rows($result_out);
            $row_out = mysqli_fetch_assoc($result_out);
        }


        if($row_num_out == 0)
        {
            $name_out = "";
            $date_out = $day;
            $time_out = "N/A";
            $dateTime_out = "";
        }
        else if($row_num_out == 1)
        {
            $name_out = $row_out['Name'];
            $dateTime_out = $row_out['DateTime'];
            $date_out = $row_out['date(DateTime)'];
            $time_out = $row_out['time(DateTime)'];

        }

        //Calculation
        if($time_in == "N/A" || $time_out == "N/A")
        {
            $interval = "";
            $minutes = "";
            $hours = "";
            $_remainder = "";
            $bonus = "";   
            $shiftp = "";
            $latep = "";
            $onshift_statusTime = "";

            $missing_data_count++;
        }
        else
        {
            $interval = strtotime($dateTime_out) - strtotime($dateTime_in);
            $minutes = floor($interval/60);
            $hours = floor($interval/3600);
            $_remainder = $minutes % 60;

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
                    $total_bonus = $total_bonus + $bonus;
                }
            }
            else
            {
                $bonus = "";
            }

             // Checking Late Penalties
            // Morning Shift
            if(strtotime($time_in) > $morning_shift_late && strtotime($time_in) < strtotime('10:30:00'))
            {
                $latep = round((strtotime($time_in) - $morning_shift_late)/60 * $late_penalties,2);
                $total_late_penalties = $total_late_penalties + $latep;
            }
            //Afternoon Shift
            else if (strtotime($time_in) > $afternoon_shift_late && strtotime($time_in) < strtotime('18:30:00'))
            {
                $latep = round((strtotime($time_in) - $afternoon_shift_late)/60 * $late_penalties,2);
                $total_late_penalties = $total_late_penalties + $latep;
            }
            //Night Shift
            else if(strtotime($time_in) > $night_shift_late && strtotime($time_in) < strtotime('23:30:00'))
            {
                $latep = round((strtotime($time_in) - $night_shift_late)/60 * $late_penalties,2);
                $total_late_penalties = $total_late_penalties + $latep;
            }
            else
            {
                $latep = "";
            }

            // Checking if on shift 8 hours
            if($hours >= 8 && $hours < 12)
            {
                // 
                $onshift_statusTime = "<strong style='color:green'>".$hours." Hr ".$_remainder." Min</strong>";
            }
            else if($hours < 8)
            {
                $onshift_statusTime = "<strong style='color:red'>".$hours." Hr ".$_remainder." Min</strong>";
            }



             //Checking Penalities if not work full 8 Hours
            if($minutes < 480)
            {
                $penalties_min = 480 - $minutes;

                $shiftp = round($penalties_min * $shift_penalties,2);
                $total_shift_penalties = $total_shift_penalties + $shiftp;
            }
            else 
            {
                $shiftp = "";
            }
        }
        
        echo "<tr>
            <td>".$name."</td>
            <td>".$shift."</td>
            <td>".$date_in."</td>
            <td>".$time_in."</td>
            <td>".$time_out."</td>
            <td>".$onshift_statusTime."</td>
            <td>".$latep."</td>
            <td>".$shiftp."</td>
            <td>".$bonus."</td>
        </tr>";
        
    }

    //Reset Total Penalties
    $total_shift_penalties = 0;
    $total_bonus = 0;
    $total_late_penalties = 0;
    $final_row_count = 0; 
?>