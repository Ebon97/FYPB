<?php
	$connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Failed");
    $check = false;
    $array = [];

    if(isset($_GET['check']))
    {
        $name = $_GET['checkName'];
        $start = $_GET['checkStartDate'];
        $end = $_GET['checkEndDate'];

        $query_in = "SELECT clock_in.Name, date(clock_in.DateTime), time(clock_in.DateTime), clock_in.Shift, clock_in.DateTime as dateTimeIN, 
                    clock_out.Name, date(clock_out.DateTime), time(clock_out.DateTime), clock_out.Shift, clock_out.NightFix, clock_out.DateTime as dateTimeOUT 
                    from clock_in inner join clock_out on date(clock_in.DateTime) = date(clock_out.NightFix) and clock_in.Name = clock_out.Name 
                    where clock_in.Name = '$name' and date(clock_in.DateTime) between '$start' and '$end'";

        $result_in = mysqli_query($connect, $query_in);
        $row_num_in = mysqli_num_rows($result_in);
        $i = 0;

        while($row_in = mysqli_fetch_assoc($result_in))
        {
            $name = $row_in['Name'];
            $shift = $row_in['Shift'];

            $dateTimeIN = $row_in['dateTimeIN'];
            $date_in = $row_in['date(clock_in.DateTime)'];
            $time_in = $row_in['time(clock_in.DateTime)'];

            $dateTimeOUT = $row_in['dateTimeOUT'];
            $date_out = $row_in['date(clock_out.DateTime)'];
            $time_out = $row_in['time(clock_out.DateTime)'];

            // echo $date_in;

            if($date_in == "" || $time_in == "" || $date_out == "" || $time_out == "" )
            {
                $alert_icon = "<img src='image/alert_icon.png' class='alert_icon'>";
            }
            else
            {
                $alert_icon = "";
            }

            echo "<tr>
                <form action='salaryCheckUpdate.php' method='GET'>
                    <td><input type='text' value='".$name."' name='name".$i."' readonly></td>
                    <td><input type='text' value='".$shift."' name='shift".$i."' readonly></td>
                    <td><input type='date' value='".$date_in."' name='dateIn".$i."'></td>
                    <td><input type='time' value='".$time_in."' name='timeIn".$i."'></td>
                    <td><input type='date' value='".$date_out."' name='dateOut".$i."'></td>
                    <td><input type='time' value='".$time_out."' name='timeOut".$i."'></td>
                    <td><input type='submit' value='UPDATE' name='update".$i."'></td>
                    <td>".$alert_icon."</td>
                </form>
            </tr>";

            $i++;
        }

        // $diff = strtotime($end) - strtotime($start);
        // $minutes = floor($diff/60);
        // $hours = floor($diff/3600);
        // $day = $hours / 24;

        // // echo $day."<br>";
       
        // array_push($array, $day);

        

    }
    else
    {
        $name = "";
        $start = "";
        $end = "";
    }

    // echo $array;
    

    for($a = 0; $a < 30; $a++)
    {
        if(isset($_GET['update'.$a]))
        {
            $name = $_GET['name'.$a];
            $dateIn = $_GET['dateIn'.$a];
            $timeIn = $_GET['timeIn'.$a];
            $dateOut = $_GET['dateOut'.$a];
            $timeOut = $_GET['timeOut'.$a];

            $timeIn24 = date("H:i:s", strtotime($timeIn));
            $timeOut24 = date("H:i:s", strtotime($timeOut));

            $dateTimeIn = $dateIn." ".$timeIn24;
            $dateTimeOut = $dateOut." ".$timeOut24;

            $strTimeIn = strtotime($timeIn);

            // echo $name." ".$dateIn." ".$timeIn." ".$dateOut." ".$timeOut."<br>";

            if($strTimeIn > strtotime('05:00:00') &&  $strTimeIn < strtotime('10:30:00'))
            {
                $shift = "Morning";
            }
            //1300 - 1430 - 1830
            else if ($strTimeIn > strtotime('13:00:00') &&  $strTimeIn < strtotime('18:30:00'))
            {
                $shift = "Afternoon";
            }
            //2000 - 2130 - 0130am
            else if($strTimeIn > strtotime('20:00:00') &&  $strTimeIn < strtotime('23:59:00'))
            {
                $shift = "Night";
            }
            else if ($strTimeIn == 0)
            {
                $shift = "";
            }
            else
            {
                $shift = "Part Time";
            }

            $query = "SELECT Name, time(DateTime), date(DateTime), dateTime from clock_in WHERE Name='$name' AND date(DateTime) = '$dateIn'";
            $result = mysqli_query($connect, $query);
            $row_num = mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);


            // Check if clock in dateTime exists
            if($row_num == 0)
            {
                // Insert New Data
                $query = "INSERT INTO clock_in(No, Mchn, EnNo, Name, Mode, IOMd, DateTime, Shift) VALUES (NULL, 1, 0, '$name','1','0','$dateTimeIn','$shift')";
                $result = mysqli_query($connect, $query);

                $checkMessage = "Successfully Updated!";
            }
            else
            {
                //Update Data
                $query = "UPDATE clock_in SET DateTime = '$dateTimeIn' WHERE Name AND date(DateTime) = '$dateIn'";
                $result = mysqli_query($connect, $query);

                $checkMessage = "Successfully Updated!";
            }

            //Check if clock out DateTime exists
            $query_out = "SELECT Name, time(DateTime), date(DateTime), dateTime from clock_out WHERE Name='$name' AND date(DateTime) = '$dateOut'";
            $result_out = mysqli_query($connect, $query_out);
            $row_num_out = mysqli_num_rows($result_out);
            $row_out = mysqli_fetch_assoc($result_out);

            if($row_num_out == 0)
            {
                // Insert New Data
                $query_out = "INSERT INTO clock_out(No, Mchn, EnNo, Name, Mode, IOMd, DateTime) VALUES (NULL, 1, 0, '$name','1','0','$dateTimeOut','$shift')";
                $result_out = mysqli_query($connect, $query_out);

                $checkMessage = "Successfully Updated!";
            }
            else
            {
                //Update Data
                $query_out = "UPDATE clock_out SET DateTime = '$dateTimeIn' WHERE Name AND date(DateTime) = '$dateOut'";
                $result_out = mysqli_query($connect, $query_out);

                $checkMessage ="Successfully Updated!";
            }
             echo "<div class='checkMessageSuccess'>
            <span>".$checkMessage."</span>
        </div>";

            echo 
            "<tr>
                <td>".$name."</td>
                <td>".$shift."</td>
                <td>".$dateIn."</td>
                <td>".$timeIn."</td>
                <td>".$dateOut."</td>
                <td>".$timeOut."</td>
                <td></td>
                <td></td>
            <tr>";
        }

           
    }

?>