<?php
    $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Failed"); 

    $morning_shift_late = strtotime('6:40:00');
    $afternoon_shift_late = strtotime('14:40:00');
    $night_shift_late = strtotime('21:40:00');

    $time_array = [];
    $late_array= [];
    $overtime_array = [];
    $notOnShift_array=[];

    $late_count = 0;
    $overtime_count = 0;
    $notOnShift_count = 0;

    if(isset($_GET['apply']))
    {
        $startDate = $_GET['start_date'];
        $start = strtotime($startDate);
        
        // Add First date
        // array_push($time_array, $startDate.",".$day);
        array_push($time_array, $startDate);

        //86400 = 1 day
        for($i = 0; $i < 6; $i++)
        {
            $start = $start + 86400;
            // echo date("l",$start);

            $new_date = date("Y-m-d", $start);
            $day = date("l", $start);

            // array_push($time_array, $new_date.",".$day);
            array_push($time_array, $new_date);
        }
        

        $query = "SELECT employee.Name, employee.shift, DateTime,date(clock_in.DateTime), time(clock_in.DateTime) 
                from clock_in join employee on employee.Name = clock_in.Name
                where date(DateTime) between '$time_array[0]' and '$time_array[6]'";
        $result = mysqli_query($connect, $query);
        $row = mysqli_num_rows($result);
        $night = "";

        while($row = mysqli_fetch_assoc($result))
        {
            $name = $row['Name'];
            $shift = $row['shift'];
            $date_in = $row['date(clock_in.DateTime)'];
            $time_in = $row['time(clock_in.DateTime)'];
            $in = $row['DateTime'];

            $d1 = date_create($date_in);
            date_add($d1, date_interval_create_from_date_string('1 days'));
            $next_day1 = date_format($d1, 'Y-m-d');

            if(strtotime($time_in) > $morning_shift_late && strtotime($time_in) < strtotime('10:30:00'))
            {
                $late_count++;
            }

            if(strtotime($time_in) > $afternoon_shift_late && strtotime($time_in) < strtotime('15:30:00'))
            {
                $late_count++;
            }

            if(strtotime($time_in) > $night_shift_late && strtotime($time_in) < strtotime('23:30:00'))
            {
                $late_count++;
            }

            $array_next = [];

            if($shift == "Night")
            {
                $night = "1";

                $query_out = "SELECT date(DateTime), time(DateTime), DateTime FROM clock_out WHERE date(DateTime) = '$next_day1' AND Name='$name'";
                $result_out = mysqli_query($connect, $query_out);
                $row_out = mysqli_num_rows($result_out);

                while($row_out = mysqli_fetch_assoc($result_out))
                {
                    $out = $row_out['DateTime'];
                    $date_out = $row_out['date(DateTime)'];
                    $time_out = $row_out['time(DateTime)']; 

                    if(strtotime($in)  > strtotime($out))
                    {
                        $interval = strtotime($in) - strtotime($out);
                    }
                    else
                    {
                        $interval = strtotime($out) - strtotime($in);
                    }

                    $minutes = floor($interval/60);
                    $hours = floor($interval/3600);
                    $_remainder = $minutes % 60;

                    if($hours >= 8)
                    {
                        $overtime_count++;
                    }

                    if($hours < 8)
                    {
                        $notOnShift_count++;
                    }

                  // echo "<tr>
                  //       <td>".$name."</td>
                  //       <td>".$shift."</td>
                  //       <td>".$date_in."</td>
                  //       <td>".$time_in."</td>
                  //       <td>".$date_out."</td>
                  //       <td>".$time_out."</td>
                  //       <td>".$hours." H ".$_remainder." M</td>
                  //       <td>".$notOnShift_count."</td>
                  //   </tr>";

                    array_push($late_array, $late_count);
                    array_push($notOnShift_array, $notOnShift_count);
                    array_push($overtime_array, $overtime_count);
                }

                $late_count = 0;
                $overtime_count = 0;
                $notOnShift_count = 0;
            }
            else 
            {

                $query_out = "SELECT date(DateTime), time(DateTime) FROM clock_out WHERE date(DateTime) = '$date_in' AND Name='$name'";
                $result_out = mysqli_query($connect, $query_out);
                $row_out  = mysqli_num_rows($result_out );

                while($row_out = mysqli_fetch_assoc($result_out))
                {
                    $date_out = $row_out['date(DateTime)'];
                    $time_out = $row_out['time(DateTime)']; 

                    $interval = strtotime($time_out) - strtotime($time_in);

                    $minutes = floor($interval/60);
                    $hours = floor($interval/3600);
                    $_remainder = $minutes % 60;

                    if($hours > 8)
                    {
                        $overtime_count++;
                    }

                    if($hours < 8)
                    {
                        $notOnShift_count++;
                        $overtime_count = 0;
                    }


                     // echo "<tr>
                     //        <td>".$name."</td>
                     //        <td>".$shift."</td>
                     //        <td>".$date_in."</td>
                     //        <td>".$time_in."</td>
                     //        <td>".$date_out."</td>
                     //        <td>".$time_out."</td>
                     //        <td>".$hours." H ".$_remainder." M</td>
                     //        <td>".$notOnShift_count."</td>
                     //        </tr>";


                } 
            }
        }

        array_push($overtime_array, $overtime_count);
        array_push($notOnShift_array, $notOnShift_count);


         $dates = json_encode($time_array);
         $late_data = json_encode($late_array);
         $overtime_data = json_encode($overtime_array);
         $notOnShift_data = json_encode($notOnShift_array);
    
    }

    else
    {
        $dates = json_encode($time_array);
        $late_data = json_encode($late_array);
        $overtime_data = json_encode($overtime_array);
        $notOnShift_data = json_encode($notOnShift_array);
    }

?>