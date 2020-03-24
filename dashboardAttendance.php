<?php
    $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error()); 
    $i = 0;

    $query = "SELECT clock_in.Name, employee.shift,date(clock_in.DateTime), time(clock_in.DateTime) from clock_in join employee where clock_in.Name = employee.Name order by date(DateTime) DESC LIMIT 0,6";
    $result = mysqli_query($connect, $query);
    $row = mysqli_num_rows($result);

    while($row = mysqli_fetch_assoc($result))
    {
        $name = $row['Name'];
        $shift = $row['shift'];
        $date = $row['date(clock_in.DateTime)'];
        $time = $row['time(clock_in.DateTime)'];
        $i++;

        $morning_shift_late = strtotime('6:40:00');
        $afternoon_shift_late = strtotime('14:40:00');
        $night_shift_late = strtotime('21:40:00');


        if(strtotime($time) > $morning_shift_late && strtotime($time) < strtotime('10:30:00'))
        {
            $late = "<span style='color:red'>LATE</span>";
        }
        else
        {
            $late = "<span style='color:green'>PUNCTUAL</span>";
        }

        if(strtotime($time) > $afternoon_shift_late && strtotime($time) > strtotime('10:30:00'))
        {
            $late = "<span style='color:red'>LATE</span>";
        }
        else
        {
            $late = "<span style='color:green'>PUNCTUAL</span>";
        }

        if(strtotime($time) > $night_shift_late && strtotime($time) < strtotime('23:30:00'))
        {
            $late = "<span style='color:red'>LATE</span>";
        }
        else
        {
            $late = "<span style='color:green'>PUNCTUAL</span>";
        }

         
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $name; ?></td>
                <td><?php echo $date; ?></td>
                <td><?php echo $time; ?></td>
                <td><?php echo $late;?></td>
            </tr>
        <?php
    }

?>