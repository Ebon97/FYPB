<?php
   $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error());   

   $query = "SELECT * FROM employee group by Name order by EnNo";
   $result = mysqli_query($connect, $query);
   $row = mysqli_num_rows($result);

   while($row = mysqli_fetch_assoc($result))
   {
   		$no = $row['EnNo'];
   		$emp_name = $row['Name'];
         $position = $row['position'];
         $salary = $row['salary'];
         $startDate = $row['startDate'];

   		echo 
         "<tr>
            <td>".$no."</td>
            <td>".$emp_name."</td>
            <td>".$position."</td>
            <td>RM ".$salary."</td>
            <td>".$startDate."</td>
            <td>
               <a href='employeeEdit.php?EnNo=".$row['EnNo']."'><img src=image/edit_icon.png alt=Edit ></a>
               <a href='#'><img src=image/delete_icon.png alt=Delete></a>
            </td>
         </tr>";
   }

   // <a href='editEmployeeList.php?EnNo=".$row['EnNo']."'><img src=image/edit_icon.png alt=Edit onclick='showModal()'></a>

?>