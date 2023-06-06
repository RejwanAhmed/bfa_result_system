<?php 
    
    function valid_department()
    {
        include("lib/db_connection.php");
        $select_from_department_information = "SELECT * FROM `department_information`";
        $run_select_from_department_information = mysqli_query($conn, $select_from_department_information);
        $department_id_array = array();
        $department_name_array = array();
        $department_id_array[0] = "0";
        $department_name_array[0] = "Foundation Course";
        while($row=mysqli_fetch_assoc($run_select_from_department_information))
        {
            array_push($department_id_array, $row['id']);
            array_push($department_name_array, $row['department_name']);
        }
        return array($department_id_array, $department_name_array);
    }
?>