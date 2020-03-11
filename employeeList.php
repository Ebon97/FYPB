<?php
   $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error());   

   $query = "SELECT * FROM employee group by Name order by EnNo";
   $result = mysqli_query($connect, $query);
   $row = mysqli_num_rows($result);

   while($row = mysqli_fetch_assoc($result))
   {
   		$no = $row['EnNo'];
   		$emp_name = $row['Name'];
   		echo "<tr><td>".$no."</td><td>".$emp_name."</td></tr>";
   }

?>