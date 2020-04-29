<?php
    $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error()); 
    $i = 0;

    $query = "SELECT clock_in.EnNo, clock_in.Name, date(clock_in.DateTime), time(clock_in.DateTime), time(clock_out.DateTime), clock_in.Shift from clock_in INNER JOIN clock_out ON clock_in.EnNo = clock_out.EnNo AND date(clock_in.DateTime) = clock_out.NightFix AND clock_in.Shift = clock_out.Shift ORDER BY clock_in.DateTime DESC";
    $result = mysqli_query($connect, $query);
    $row = mysqli_num_rows($result);

    while($row = mysqli_fetch_assoc($result))
    {

        $id = $row['EnNo'];
        $name = $row['Name'];
        $date = $row['date(clock_in.DateTime)'];
        $time1 = $row['time(clock_in.DateTime)'];
        $time2 = $row['time(clock_out.DateTime)'];
        $shift = $row['Shift'];
        $i++;

        $shift1 = strtotime('06:30:00');
        $shift2 = strtotime('14:30:00');
        $shift3 = strtotime('22:30:00');

        $morning_shift_late = strtotime('06:40:00');
        $afternoon_shift_late = strtotime('14:40:00');
        $night_shift_late = strtotime('22:40:00');

        if($shift == "Morning")
        {
            if(strtotime($time1) > $morning_shift_late && strtotime($time1) < $shift2)
            {
                $late = "<span style='color:red'>LATE</span>";
            }
            else
            {
                $late = "<span style='color:green'>PUNCTUAL</span>";
            }
        }
        else if ($shift == "Afternoon"){
            if(strtotime($time1) > $afternoon_shift_late && strtotime($time1) < $shift3)
            {
                $late = "<span style='color:red'>LATE</span>";
            }
            else
            {
                $late = "<span style='color:green'>PUNCTUAL</span>";
            }
        }
        else if($shift == "Night"){
            if(strtotime($time1) > $night_shift_late)
            {
                $late = "<span style='color:red'>LATE</span>";
            }
            else
            {
                $late = "<span style='color:green'>PUNCTUAL</span>";
            }
        }
         
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $id; ?></td>
                <td><?php echo $name; ?></td>
                <td><?php echo $date; ?></td>
                <td><?php echo $time1; ?></td>
                <td><?php echo $time2; ?></td>
                <td><?php echo $shift; ?></td>
                <td><?php echo $late;?></td>
            </tr>
        <?php
    }

?>