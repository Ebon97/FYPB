<?php
    $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Failed"); 

    $morning_shift_late = strtotime('6:40:00');
    $afternoon_shift_late = strtotime('14:40:00');
    $night_shift_late = strtotime('22:40:00');

    $time_array = [];
    $late_array= [];
    $overtime_array = [];
    $notOnShift_array=[];

    $punctual_array = [];

    $late_count = 0;
    $overtime_count = 0;
    $notOnShift_count = 0;

    $punctual_count = 0;

    $total_late_count = 0;
    $total_ot_count = 0;
    $total_nos_count = 0;

    $total_punctual_count = 0;

    if(isset($_GET['apply']))
    {
        $startDate = $_GET['start_date'];
        $start = strtotime($startDate);
        
        // Add First date
        // array_push($time_array, $startDate.",".$day);
        array_push($time_array, $startDate);

        //86400 = 1 day
        for($i = 0; $i < 7; $i++)
        {
            $start = $start + 86400;
            // echo date("l",$start);

            $new_date = date("Y-m-d", $start);
            $day = date("l", $start);

            // array_push($time_array, $new_date.",".$day);
            array_push($time_array, $new_date);

            $query = "SELECT clock_in.Name, date(clock_in.DateTime), time(clock_in.DateTime), clock_in.Shift, clock_in.DateTime as dateTimeIN, 
                        clock_out.Name, date(clock_out.DateTime), time(clock_out.DateTime), clock_out.Shift, clock_out.NightFix, clock_out.DateTime as dateTimeOUT 
                        from clock_in inner join clock_out on date(clock_in.DateTime) = date(clock_out.NightFix) and clock_in.Name = clock_out.Name 
                        where date(clock_in.DateTime) = '$time_array[$i]'";

            // echo "<br>".$query;
            $result = mysqli_query($connect, $query);
            $row = mysqli_num_rows($result);

            while($row = mysqli_fetch_assoc($result))
            {
                $name = $row['Name'];
                $shift = $row['Shift'];

                $dateTimeIN = $row['dateTimeIN'];
                $date_in = $row['date(clock_in.DateTime)'];
                $time_in = $row['time(clock_in.DateTime)'];

                $dateTimeOUT = $row['dateTimeOUT'];
                $date_out = $row['date(clock_out.DateTime)'];
                $time_out = $row['time(clock_out.DateTime)'];


                //Late
                if($shift == "Morning")
                {
                    if(strtotime($time_in) > $morning_shift_late && strtotime($time_in) < strtotime('10:30:00'))
                    {
                        $late_count ++;
                    }
                    else
                    {
                        $late_count = 0;
                        $punctual_count ++ ;
                    }

                }
                else if($shift == "Afternoon")
                {
                    if(strtotime($time_in) > $afternoon_shift_late && strtotime($time_in) < strtotime('15:30:00'))
                    {
                        $late_count ++;
                    }
                    else
                    {
                        $late_count = 0;
                        $punctual_count ++ ;
                    }

                }
                else if($shift == "Night")
                {
                    if(strtotime($time_in) > $night_shift_late && strtotime($time_in) < strtotime('23:30:00'))
                    {
                        $late_count ++;
                    }
                    else
                    {
                        $late_count = 0;
                        $punctual_count ++ ;
                    }
                }
                else
                {
                    $late_count = 0;
                    $punctual_count ++ ;
                }

                // OverTime
                $interval = strtotime($dateTimeOUT) - strtotime($dateTimeIN);
                $minutes = floor($interval/60);
                $hours = floor($interval/3600);
                $_remainder = $minutes % 60;

                if($hours > 8)
                {
                    $overtime_count = 1;
                }

                //Not On Shift
                if($hours < 8)
                {
                    $notOnShift_count = 1;
                }

                // echo $punctual_count."<br>";

                $total_late_count = $total_late_count + $late_count;
                $total_punctual_count = $total_punctual_count + $punctual_count;
                $total_ot_count = $total_ot_count + $overtime_count;
                $total_nos_count = $total_ot_count + $notOnShift_count;

                // echo $name." ".$date_in." ".$time_in." ".$date_out." ".$time_out." ".$hours."H".$_remainder."M"."<br>";

            
            }

            array_push($late_array, $late_count);
            array_push($punctual_array, $punctual_count);

            $late_count = 0;
            $punctual_count = 0;

            // array_push($late_array, $total_late_count);
            // array_push($overtime_array, $total_ot_count);
            // array_push($notOnShift_array, $total_nos_count);

            // $total_late_count = 0;
            // $total_ot_count = 0;
            // $total_nos_count = 0;
            

        }

        $dates = json_encode($time_array);
        $late_data = json_encode($late_array);
        $punctual_data = json_encode($punctual_array);
        // $overtime_data = json_encode($overtime_array);
        // $notOnShift_data = json_encode($notOnShift_array);


        // echo "<br>Dates Array: ", $dates;
        // echo "<br>Late Array: ", $late_data;
        // echo "<br>Punctual Array: ", $punctual_data;
        // echo "<br>OverTime Array: ", $overtime_data;
        // echo "<br>NotOnShift Array: ", $notOnShift_data;
        
    }
    else
    {

        // echo $prefix_date;

        $currentday = 0;

        for($a = 0; $a < 7; $a++)
        {
            $currentday = $currentday + 1;

            $prefix_date = $currentyear."-".$currentmonth."-0".$currentday;
            // echo $prefix_date."<br>";

            array_push($time_array, $prefix_date);
        }
        
        $late_array= [0,0,0,0,0,0,0];
        $punctual_array= [0,0,0,0,0,0,0];

        $overtime_array = [0,0,0,0,0,0,0];
        $notOnShift_array=[0,0,0,0,0,0,0];
        
        $dates = json_encode($time_array);
        $late_data = json_encode($late_array);
        $punctual_data = json_encode($punctual_array);
        
        $overtime_data = json_encode($overtime_array);
        $notOnShift_data = json_encode($notOnShift_array);
    }

?>