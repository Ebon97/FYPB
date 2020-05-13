<?php
	if(isset($_GET['rates']))
    {
        $bonus = $_GET['bonus'];
        $late = $_GET['late'];
        $shift = $_GET['shift'];

        if(empty($bonus) || empty($late) || empty($shift))
        {
            $message = "<div class='rUpdate_warning'><p>Missing Input</p></div>";
        }
        else
        {
            $username = $_SESSION['username'];

            //Update Rates
            $query = "UPDATE rates SET overtime_bonus=$bonus, late_penalties=$late, shift_penalties=$shift WHERE no=1";
            $result = mysqli_query($connect, $query);

            //Insert History of Changing
            $query2 = "INSERT INTO history (no,dateTime, category, description) VALUES 
                        (NULL,CURRENT_TIMESTAMP,'Rates','Overtime = $bonus, Late Penalties = $late, Shift Penalties = $shift')";
            $result2 = mysqli_query($connect, $query2);

            $message = "<div class='rUpdate_success'><p>Update successfully. Click here to <a href='rates.php'>Refresh</a></p></div>";
        }
    }
?>