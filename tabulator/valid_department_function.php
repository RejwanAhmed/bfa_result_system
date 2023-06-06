<?php 
    
    function valid_department($course_year,$id)
    {
        include("lib/db_connection.php");
        // department_id = -1 dewar karon hocche jate department id jdi match na kore tahole false return kore.
        $department_id = -1;
        $department_name = "false";
        // jdi course year = 1st year ebong department_id = 0 hoy tahole valid department check korar dorkar nai. karon first year der jonno default vabe Foundation Course thake.
        if($course_year=="1st year" && $id==0)
        {
            $department_id = 0;
            $department_name = "Foundation Course";
        }
        else if($course_year=="2nd year" || $course_year=="3rd year" || $course_year=="4th year")
        {
            // department_id validation
            // valid department url e pass hocche kina ta check korar jonno.
            // department_id == 0 mane hocche foundation course. jehetu array_search fail korle 0 dekhay tai oita alada vabe $_GET['department_id] == 0 diye check korsi
            $select_from_department_information = "SELECT * FROM `department_information` WHERE `id` = '$id'";
            $run_select_from_department_information = mysqli_query($conn, $select_from_department_information);
            if(mysqli_num_rows($run_select_from_department_information)==1)
            {
                $row = mysqli_fetch_assoc($run_select_from_department_information);
                $department_id = $row['id'];
                $department_name = $row['department_name'];
            }
           
            // end of department_id validation
        }
        
        return array($department_id, $department_name);
    }
    
    
?>