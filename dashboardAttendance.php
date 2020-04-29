<?php
    $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error()); 

    $query1 = "SELECT No, Name, Shift, date(DateTime), time(DateTime) FROM clock_in ORDER BY DateTime DESC LIMIT 0,6";
    $result1 = mysqli_query($connect, $query1);
    $row1 = mysqli_num_rows($result1);

    $late_count = 0;

    while($row1 = mysqli_fetch_assoc($result1))
    {

        $no = $row1['No'];
        $name = $row1['Name'];
        $shift = $row1['Shift'];
        $date = $row1['date(DateTime)'];
        $time = $row1['time(DateTime)'];

        $morning_shift_late = strtotime('06:40:00');
        $afternoon_shift_late = strtotime('14:40:00');
        $night_shift_late = strtotime('22:40:00');

        if($shift == "Morning")
        {
            if(strtotime($time) > $morning_shift_late && strtotime($time) < strtotime('10:30:00'))
            {
                $late = "<span style='color:red'>LATE</span>";
            }
            
        }
        else if($shift == "Afternoon")
        {
            if(strtotime($time) > $afternoon_shift_late && strtotime($time) < strtotime('15:30:00'))
            {
                $late = "<span style='color:red'>LATE</span>";
            }
        }
        else if($shift == "Night")
        {
            if(strtotime($time) > $night_shift_late && strtotime($time) < strtotime('23:30:00'))
            {
                $late = "<span style='color:red'>LATE</span>";
            }
        }
        else
        {
            $late = "<span style='color:green'>PUNCTUAL</span>";
        }

         
        ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $name; ?></td>
                <td><?php echo $date; ?></td>
                <td><?php echo $time; ?></td>
                <td><?php echo $late;?></td>
            </tr>
        <?php
    }

?>