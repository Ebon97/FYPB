
<?php
    for ($i = 1; $i < 32; $i++)
    {
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

            $missing_data_count = 1;
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
                $bonus = 0;
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
                    $latep = 0;
                }

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

                    $shiftp = round($penalties_min * $shift_penalties,2);
                    $total_shift_penalties = $total_shift_penalties + $shiftp;
                }
                else
                {
                    $shiftp = 0;
                }

                

        }
        
        // echo "<tr>
        //     <td>".$name_in."</td>
        //     <td>".$day_in."</td>
        //     <td>".$shift."</td>
        //     <td>".$date_in."</td>
        //     <td>".$time_in."</td>
        //     <td>".$time_out."</td>
        //     <td>".$hours." H ".$_remainder." M</td>
        //     <td>".$bonus."</td>
        //     <td>".$latep."</td>
        //     <td>".$shiftp."</td>
        // </tr>";
    }

            $final_salary = round($salary + $total_bonus - $total_shift_penalties - $total_late_penalties,2);

            $total_missing_data = $total_missing_data + $missing_data_count;
            

            if($missing_data_count >= 1)
            {
                 $alert = "<img src='image/alert_icon.png' class='alert_icon' alt='Missing Data'>";
            }
            else
            {
                $alert = "";
                    $query_past = "INSERT INTO salary_past(no, year, month, name, init_salary, shift_penalties, late_penalties, bonus, final_salary) VALUES (NULL,'$year','$month','$name','$salary','$total_shift_penalties','$total_late_penalties','$total_bonus','$final_salary')";
                    $result_past = mysqli_query($connect, $query_past);
            }

    ?>

        <tr>
            <td><?php echo $name; ?></td>
            <td>RM <?php echo $salary; ?></td>
            <td><?php echo $total_shift_penalties; ?></td>
            <td><?php echo $total_late_penalties; ?></td>
            <td><?php echo $total_bonus; ?></td>
            <td>RM <?php echo $final_salary; ?></td>
            <td>
                <form action="salaryShowDetails.php" method="GET">
                    <input type="hidden" name="name" value="<?php echo $name; ?>">
                    <input type="hidden" name="month" value="<?php echo $month; ?>">
                    <input type="hidden" name="year" value="<?php echo $year; ?>">
                    <button name="show">Show Details</button>
                </form>
            </td>
            <td style="padding:0;"><?php echo $alert; ?></td>
        </tr>
<?php

    //Reset Total Penalties
    $total_shift_penalties = 0;
    $total_bonus = 0;
    $total_late_penalties = 0;
    $final_row_count = 0;
?>