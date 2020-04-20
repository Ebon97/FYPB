<?php
   $connect =  mysqli_connect("localhost", "root", "", "shellsbt") or die ("Connection Failed: ". mysqli_connect_error());   

   $query = "SELECT * FROM employee group by Name order by No";
   $result = mysqli_query($connect, $query);
   $row = mysqli_num_rows($result);

   while($row = mysqli_fetch_assoc($result))
   {
   		$no = $row['No'];
   		$emp_name = $row['Name'];
         $position = $row['position'];
         $shift = $row['shift'];
         $salary = $row['salary'];
         $startDate = $row['startDate'];

   		echo 
         "<tr>
            <td>".$no."</td>
            <td>".$emp_name."</td>
            <td>".$position."</td>
            <td>".$shift."</td>
            <td>RM ".$salary."</td>
            <td>".$startDate."</td>
            <td>
               <a href='employeeEdit.php?No=".$row['No']."'><img src=image/edit_icon.png alt=Edit ></a>
               <a href='employeeDelete.php?No=".$row['No']."'><img src=image/delete_icon.png alt=Delete></a>
            </td>
         </tr>";
   }

   // <a href='editEmployeeList.php?EnNo=".$row['EnNo']."'><img src=image/edit_icon.png alt=Edit onclick='showModal()'></a>

?>