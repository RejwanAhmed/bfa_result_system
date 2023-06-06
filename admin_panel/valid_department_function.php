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
    
    // validation for deletion any row from any table
    function row_deletion_validation($table_name,$column_name,$id)
    {
        include("lib/db_connection.php");
        // assigned_course_information table e extra sorto dewa lagbe tai ei query ektu alada hobe.
        if($table_name == "exam_committee_information")
        {
            if($column_name == "external_member_id")
            {
                $search_id_for_deletion = "SELECT count(id) FROM $table_name WHERE `external_member_id` = '$id'";
            }
            else
            {
                $search_id_for_deletion = "SELECT count(id) FROM $table_name WHERE `chairman_id` = '$id' OR `member1_id` = '$id' OR `member2_id` = '$id' OR `tabulator1_id` = '$id' OR `tabulator2_id` = '$id' OR `tabulator3_id` = '$id'";
            }
        }
        else
        {
            $search_id_for_deletion = "SELECT count(id) FROM $table_name WHERE $column_name = '$id'";
        }
        
        $run_search_id_for_deletion = mysqli_query($conn, $search_id_for_deletion);
        $res = mysqli_fetch_assoc($run_search_id_for_deletion);
        return $res['count(id)'];
    }
?>