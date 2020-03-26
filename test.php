<?php

    $query_in = "SELECT Name,date(DateTime), time(DateTime) FROM clock_in where date(DateTime) between '$time_array[0]' AND '$time_array[6]'";
    $query_out = "SELECT Name,date(DateTime), time(DateTime) FROM clock_out where date(DateTime) between '$time_array[0]' AND '$time_array[6]'";

    $result_in = mysqli_query($connect, $query_in);
    $result_out = mysqli_query($connect, $query_out);

    $row_in = mysqli_num_rows($result_in);
    $row_out = mysqli_num_rows($result_out);

    while(($row_in = mysqli_fetch_assoc($result_in)) && ($row_out = mysqli_fetch_assoc($result_out)))
    {
        $name_in = $row_in['Name'];
        $date_in = $row_in['date(DateTime)'];
        $time_in = $row_in['time(DateTime)'];

        $name_out = $row_out['Name'];
        $date_out = $row_out['date(DateTime)'];
        $time_out = $row_out['time(DateTime)'];

        if(strtotime($time_in) > $morning_shift_late && strtotime($time_in) < strtotime('10:30:00'))
        {
            $late_count++;

        }

        if(strtotime($time_in) > $afternoon_shift_late && strtotime($time_in) > strtotime('10:30:00'))
        {
            $late_count++;
        }

        echo 
        "<tr>
            <td>".$name_in."</td>
            <td>".$shift."</td>
            <td>".$date_in."</td>
            <td>".$time_in."</td>
            <td>".$name_out."</td>
            <td>".$date_out."</td>
            <td>".$time_out."</td>
            <td>".$late_count."</td>
        </tr>";
		
    }

    
    $name_in = "0";
    $shift = "0";
    $date_in = "0";
    $time_in = "0";
    $name_out = "0";
    $date_out = "0";
    $time_out = "0";
		
    echo "<tr><td>HI</td></tr>";
    
?>