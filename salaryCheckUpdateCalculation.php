<?php
	 $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Failed");
     $check = false;

    if(isset($_GET['check']))
    {
        $name = $_GET['checkName'];
        $date = $_GET['checkDate'];
        $range = $_GET['checkRange'];

        $check = true;

        $count = 0;

        for($i = 0; $i < $range; $i++)
        {
            $strDate = strtotime($date);
            $year = date("Y", $strDate);
            $month = date("m", $strDate);
            $day = date("d", $strDate);


            $a = 1;

            $nextday = $day + $i;
            $actualDate = $year."-".$month."-".$nextday."<br>";

            //To call out the date between all the range
            if($nextday >= 31)
            {
                if($month == 4 || $month == 6 || $month == 9 || $month == 11)
                {
                    $actualDate = $year."-".($month+1)."-".($a+$count)."<br>";
                    $count++;
                }
            }

            if($nextday >= 32)
            {
                if($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10)
                {
                    $actualDate = $year."-".($month+1)."-".($a+$count)."<br>";
                    $count++;
                }
                else if ($month == 12)
                {
                    $actualDate = ($year+1)."-01-".($a+$count)."<br>";
                    $count++;
                } 

                echo $actualDate."<br>";
            }

            $query_in = "SELECT Name, date(DateTime), time(DateTime), DateTime from clock_in WHERE Name='$name' AND date(DateTime)='$actualDate'";
            $result_in = mysqli_query($connect, $query_in);
            $row_num_in = mysqli_num_rows($result_in);
            $row_in = mysqli_fetch_assoc($result_in);

            if($row_num_in == 1)
            {
                $name_in = $row_in['Name'];
                $date_in = $row_in['date(DateTime)'];
                $time_in = $row_in['time(DateTime)'];

                $strTime = strtotime($time_in);
            }
            // If not found
            else
            {
                $name_in = $name;
                $date_in = $actualDate;
                $time_in = "";

                $strTime = "";
                $strDate = "";

                $year = "";
                $month = "";
                $day = "";

            }

            if($strTime > strtotime('05:00:00') &&  $strTime < strtotime('10:30:00'))
            {
                $shift = "Morning";
            }
            //1300 - 1430 - 1830
            else if ($strTime > strtotime('13:00:00') &&  $strTime < strtotime('18:30:00'))
            {
                $shift = "Afternoon";
            }
            //2000 - 2130 - 0130am
            else if($strTime > strtotime('20:00:00') &&  $strTime < strtotime('23:59:00'))
            {
                $shift = "Night";
            }
            else if ($strTime == 0)
            {
                $shift = "";
            }
            else
            {
                $shift = "Part Time";
            }

            if($shift == "Night")
            {
                $strDateIn = strtotime($date_in);

                $newYear = date("Y", $strDateIn);
                $newMonth = date("m", $strDateIn);
                $newDay = date("d", $strDateIn);

                $newDay = $newDay + 1;
                $nextDate = $newYear."-".$newMonth."-".$newDay;

                $b = 1;
                $count_out = 0;

                if($newDay >= 31)
                {
                    if($month == 4 || $month == 6 || $month == 9 || $month == 11)
                    {
                        $nextDate = $newYear."-".($newMonth+1)."-".($b+$count_out);
                        $count_out++;
                        $actualDate = $nextDate;

                    }
                }

                if($newDay >= 32)
                {
                    if($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10)
                    {
                        $nextDate = $newYear."-".($newMonth+1)."-".($b+$count);
                        $count_out++;
                        $actualDate = $nextDate;

                    }
                    else if($month == 12)
                    {

                    }
                }

                if($newDay == 1)
                {
                    $nextDate = $newYear."-".($newMonth+1)."-02";
                    $actualDate = $nextDate;
                }


            }
            else if($shift== "")
            {
                $actualDate = "";
                $date_out = "";
                $time_out = "";

            }
               

            $query_out = "SELECT Name, date(DateTime), time(DateTime), DateTime from clock_out WHERE Name='$name' AND date(DateTime)='$actualDate'";
            $result_out = mysqli_query($connect, $query_out);
            $row_num_out = mysqli_num_rows($result_out);
            $row_out = mysqli_fetch_assoc($result_out);

            if($row_num_out == 1)
            {
                // $name_out = $row_out['Name'];
                $date_out = $row_out['date(DateTime)'];
                $time_out = $row_out['time(DateTime)'];
            }
            else
            {
                // $name_out = "";
                $date_out = "";
                $time_out = "";
            }

            if($shift == "" || $dateIn = "" || $dateOut = "" || $timeIn = "" || $timeOut = "")
            {
                $alert = true;
                $alert_icon = "<img src='image/alert_icon.png' class='alert_icon'>";
            }
            else 
            {
                $alert = false;
                $alert_icon = "";
            }

   
?>
            <tr>
                <form action="salaryCheckUpdate.php" method="GET">
                    <td><input type="text" value="<?php echo $name?>" name="name<?php echo $i?>" readonly></td>
                    <td><input type="text" value="<?php echo $shift?>" name="shift<?php echo $i?>" readonly></td>
                    <td><input type="date" value="<?php echo $date_in?>" name="dateIn<?php echo $i?>"></td>
                    <td><input type="time" value="<?php echo $time_in?>" name="timeIn<?php echo $i?>"></td>
                    <td><input type="date" value="<?php echo $date_out?>" name="dateOut<?php echo $i?>"></td>
                    <td><input type="time" value="<?php echo $time_out?>" name="timeOut<?php echo $i?>"></td>
                    <td><input type="submit" value="UPDATE" name="update<?php echo $i?>"></td>
                    <td><?php echo $alert_icon?></td>
                </form>
            </tr>




<?php

        }        
    }    

    for($num = 0; $num < 7; $num++)
    {
        if(isset($_GET['update'.$num]))
        {
            $name[$num] = $_GET['name'.$num];
            $dateIn[$num] = $_GET['dateIn'.$num];
            $timeIn[$num] = $_GET['timeIn'.$num];
            $dateOut[$num] = $_GET['dateOut'.$num];
            $timeOut[$num] = $_GET['timeOut'.$num];

            $timeIn24 = date("H:i:s", strtotime($timeIn[$num]));
            $timeOut24 = date("H:i:s", strtotime($timeOut[$num]));

            $dateTimeIn = $dateIn[$num]." ".$timeIn24;
            $dateTimeOut = $dateOut[$num]." ".$timeOut24;

            $strTimeIn = strtotime($timeIn[$num]);
           
            // echo $name[$num]." ".$dateIn[$num]." ".$timeIn[$num]." ".$dateOut[$num]." ".$timeOut[$num];

            // Check if clock in dateTime exists
            $query = "SELECT Name, time(DateTime), date(DateTime), dateTime from clock_in WHERE Name='$name[$num]' AND date(DateTime) = '$dateIn[$num]'";
            $result = mysqli_query($connect, $query);
            $row_num = mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);

            if($row_num == 0)
            {
                // Insert New Data
                $query = "INSERT INTO clock_in(No, Mchn, EnNo, Name, Mode, IOMd, DateTime) VALUES (NULL, 1, 0, '$name','1','0','$dateTimeIn')";
                $result = mysqli_query($connect, $query);

                $checkMessage = "Successfully Updated!";
            }
            else
            {
                //Update Data
                $query = "UPDATE clock_in SET DateTime = '$dateTimeIn' WHERE Name AND date(DateTime) = '$dateIn[$num]'";
                $result = mysqli_query($connect, $query);

                $checkMessage = "Successfully Updated!";
            }

            //Check if clock out DateTime exists
            $query_out = "SELECT Name, time(DateTime), date(DateTime), dateTime from clock_out WHERE Name='$name[$num]' AND date(DateTime) = '$dateOut[$num]'";
            $result_out = mysqli_query($connect, $query_out);
            $row_num_out = mysqli_num_rows($result_out);
            $row_out = mysqli_fetch_assoc($result_out);

            if($row_num_out == 0)
            {
                // Insert New Data
                $query_out = "INSERT INTO clock_out(No, Mchn, EnNo, Name, Mode, IOMd, DateTime) VALUES (NULL, 1, 0, '$name[$num]','1','0','$dateTimeOut')";
                $result_out = mysqli_query($connect, $query_out);

                $checkMessage = "Successfully Updated!";
            }
            else
            {
                //Update Data
                $query_out = "UPDATE clock_out SET DateTime = '$dateTimeIn' WHERE Name AND date(DateTime) = '$dateOut[$num]'";
                $result_out = mysqli_query($connect, $query_out);

                $checkMessage ="Successfully Updated!";
            }

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

            echo "<div class='checkMessageSuccess'>
                    <span>".$checkMessage."</span>
                </div>";

            echo 
            "<tr>
                <td>".$name[$num]."</td>
                <td>".$shift."</td>
                <td>".$dateIn[$num]."</td>
                <td>".$timeIn[$num]."</td>
                <td>".$dateOut[$num]."</td>
                <td>".$timeOut[$num]."</td>
                <td></td>
                <td></td>
            <tr>";
        }
    }
?>