<?php
	if(!empty($_FILES['clockinfile']))
    {
      $filename = $_FILES["clockinfile"]["name"];
      $filetype = $_FILES["clockinfile"]["type"];
      $filesize = $_FILES["clockinfile"]["size"];
      $tempfile = $_FILES["clockinfile"]["tmp_name"];
      $filenameWithDirectory = "ClockInLog/".$filename;
      if(move_uploaded_file($tempfile, $filenameWithDirectory))
	{
		
	}
	else 
	{
		
	}
    }
  
    if(!empty($_FILES['clockoutfile']))
    {
      $filename = $_FILES["clockoutfile"]["name"];
      $filetype = $_FILES["clockoutfile"]["type"];
      $filesize = $_FILES["clockoutfile"]["size"];
      $tempfile = $_FILES["clockoutfile"]["tmp_name"];
      $filenameWithDirectory = "ClockOutLog/".$filename;
      if(move_uploaded_file($tempfile, $filenameWithDirectory))
	{
		
	}
	else 
	{
		
	}
    }
?>