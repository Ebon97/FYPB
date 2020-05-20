<?php
	if(isset($_GET['settings_update']))
    {
        $sUsername = $_GET['sUsername'];
        $sPassword = $_GET['sPassword'];
        $sEmail = $_GET['sEmail'];
        $sHashPassword = hash('sha256', $sPassword);

        // echo $sUsername." ".$sPassword." ".$sEmail." ".$sHashPassword."<br>";

        if(empty($sUsername) || empty($sPassword) || empty($sEmail))
        {
            $message = "<div class='sUpdate_warning'><p>Missing Input</p></div>";
        }
        else
        {
            $query = "UPDATE `manager` SET `username`='$sUsername',`email`='$sEmail',`password`='$sHashPassword' WHERE ID='$db_id'";
            // echo $query;
            $result = mysqli_query($connect, $query);

            $message = "<div class='checkMessageSuccess' style='margin-left: 30%;'><span>Update successfully</span></div>";

            echo $message;
        }

        // echo "HI";
    }
?>