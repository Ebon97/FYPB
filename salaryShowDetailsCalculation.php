
<?php



    // while($row_em = mysqli_fetch_assoc($result_em))
    // {
    //     $id = $row_em['ID'];
    //     $name = $row_em['Name'];
    //     $shift = $row_em['Shift'];
    //     $salary = $row_em['Salary'];

    //      // include("salaryCalculation.php");

    //     $query = "SELECT clock_in.Name, date(clock_in.DateTime), time(clock_in.DateTime), clock_in.Shift, clock_in.DateTime as dateTimeIN, 
    //                 clock_out.Name, date(clock_out.DateTime), time(clock_out.DateTime), clock_out.Shift, clock_out.NightFix, clock_out.DateTime as dateTimeOUT 
    //                 from clock_in inner join clock_out 
    //                 on date(clock_in.DateTime) = date(clock_out.NightFix) and clock_in.Name = clock_out.Name 
    //                 where month(clock_in.DateTime) = '$month' and clock_in.Name = '$name'";
    //     $result = mysqli_query($connect, $query);
    //     $row_num = mysqli_num_rows($result);

    //     // echo $row_num;

    //     while($row = mysqli_fetch_assoc($result))
    //     {
    //         $name = $row['Name'];
    //         $shift = $row['Shift'];

    //         $dateTimeIN = $row['dateTimeIN'];
    //         $date_in = $row['date(clock_in.DateTime)'];
    //         $time_in = $row['time(clock_in.DateTime)'];

    //         $dateTimeOUT = $row['dateTimeOUT'];
    //         $date_out = $row['date(clock_out.DateTime)'];
    //         $time_out = $row['time(clock_out.DateTime)'];

    //         // echo $name." ".$shift." ".$dateTimeIN." ".$dateTimeOUT."<br>";

    //         $interval = strtotime($dateTimeOUT) - strtotime($dateTimeIN);
    //         $minutes = floor($interval/60);
    //         $hours = floor($interval/3600);
    //         $_remainder = $minutes % 60;


    //         //Late Status
    //         if($shift == "Morning")
    //         {
    //             // echo "HI<br>";
    //             if(strtotime($time_in) > $morning_shift_late)
    //             {
    //                 $late = "Late";
    //                 $diff = strtotime($time_in) - $morning_shift_late;
    //             }
    //              else
    //             {
    //                 $late = "O";
    //                 $diff = 0;
    //             }

    //         }
            
    //         if ($shift == "Afternoon")
    //         {
    //             if(strtotime($time_in) > $afternoon_shift_late)
    //             {
    //                 $late = "Late";
    //                 $diff = strtotime($time_in) - $afternoon_shift_late;
    //             }
    //              else
    //             {
    //                 $late = "O";
    //                 $diff = 0;
    //             }

    //         }

    //         if ($shift == "Night")
    //         {
    //             if(strtotime($time_in) > $night_shift_late)
    //             {
    //                 $late = "Late";
    //                 $diff = strtotime($time_in) - $night_shift_late;
    //             }
    //             else
    //             {
    //                 $late = "O";
    //                 $diff = 0;
    //             }
    //         }

    //         $l_minutes = floor($diff/60);
    //         $l_hours = floor($diff/3600);
    //         $l_remainder = $l_minutes % 60;
    //         $latep = round($l_minutes * $late_penalties, 2);

    //         //Shift Status
    //         if($hours < 8)
    //         {
    //             $shift = "X";
    //             $shift_diff = 480 - $minutes;
    //             $shiftp = $shift_diff * $shift_penalties;
    //         }
    //         else
    //         {
    //             $shift = "O";
    //             $shift_diff = 0;
    //             $shiftp = 0;
    //         }

    //         if($hours > 8)
    //         {
    //             $_bonus = ($hours - 8) * $bonus;
    //         }
    //         else
    //         {
    //             $_bonus = 0;
    //         }
            

    //         echo "<tr>
    //             <td>".$name."</td>
    //             <td>".$shift."</td>
    //             <td>".$date_in."</td>
    //             <td>".$time_in."</td>
    //             <td>".$date_out."</td>
    //             <td>".$time_out."</td>
    //             <td>".$hours." Hr ".$_remainder." Min</td>
    //             <td>".$late."</td>
    //             <td>".$latep."</td>
    //             <td>".$shift."</td>
    //             <td>".$shiftp."</td>
    //             <td>".$_bonus."</td>
    //         </tr>";

    //         $row_count  =  1;

    //         $total_late_penalties = $total_late_penalties + $latep;
    //         $total_shift_penalties = $total_shift_penalties + $shiftp;
    //         $total_bonus = $total_bonus + $_bonus;
    //         $total_row_count = $total_row_count + $row_count;

            
    //         // echo $row_count."<br>";
    //     }

    //     $final_salary = round($salary - $total_shift_penalties - $total_late_penalties + $total_bonus, 2);

    //     if($total_row_count < 26 )
    //     {
    //         $alert = "<img src='image/alert_icon.png' class='alert_icon' alt='Missing Data'>";
    //     }
    //     else
    //     {
    //         $alert = "";
    //         $query_past = "INSERT INTO salary_past(no, year, month, name, init_salary, shift_penalties, late_penalties, bonus, final_salary) VALUES (NULL,'$year','$month','$name','$salary','$total_shift_penalties','$total_late_penalties','$total_bonus','$final_salary')";
    //         $result_past = mysqli_query($connect, $query_past);
    //     }

    //      // echo "<tr>
    //      //        <td>".$name."</td>
    //      //        <td>RM ".$salary."</td>
    //      //        <td>".$total_late_penalties."</td>
    //      //        <td>".$total_shift_penalties."</td>
    //      //        <td>".$total_bonus."</td>
    //      //        <td>RM ".$final_salary."</td>
    //      //        <td>
    //      //            <form action='salaryShowDetails.php' method='GET'>
    //      //                <input type='hidden' name='name' value='".$name."'>
    //      //                <input type='hidden' name='month' value='".$month."'>
    //      //                <input type='hidden' name='year' val ue='".$year."'>
    //      //                <button name='show'>Show Details</button>
    //      //            </form>
    //      //        </td>
    //      //        <td style='padding:0;'>".$alert."</td>
                
    //      //    </tr>";

    //         $total_late_penalties = 0;
    //         $total_shift_penalties = 0;
    //         $total_bonus = 0;
    //         $total_row_count = 0;


    // }

?>