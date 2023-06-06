<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    if(!isset($_SESSION['id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
?>

<?php
    include("lib/db_connection.php");
    // ei file include kora hocche kono ekta row delete kora jabe kina ta check korar jonno row_deletion_validation function er maddhome
    include('valid_department_function.php');
    if(isset($_GET['id']))
    {
        // Start of Whether an id is valid or not
        $id_validation_qry = "SELECT * FROM `student_information` WHERE `id` = '$_GET[id]'";
        $id_validation_qry_run = mysqli_query($conn, $id_validation_qry);
        $id_validation_qry_run_res = mysqli_fetch_assoc($id_validation_qry_run);
        if($id_validation_qry_run_res==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "index.php";
            </script>
            <?php
            exit();
        }
        $id = $_GET['id'];
        $page = $_GET['page'];
        //End of Whether an id is valid or not
    }
    else if(!isset($_GET['id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
    // row_deletion_validation($table_name, $column_name, $id)
    $num_rows = 0;
    // deletion validation from result table
    if(row_deletion_validation('result','student_id',$_GET['id'])==0)
    {
        // deletion validation from improvement_result table
       if(row_deletion_validation('improvement_result','student_id',$_GET['id'])==0)
       {
        // deletion validation from semester_cgpa table
            if(row_deletion_validation('semester_cgpa','student_id',$_GET['id'])>0)
            {
                $num_rows = 1;
            }
       }
       else
       {
            $num_rows = 1;
       }
    }
    else
    {
       $num_rows = 1; 
    }
    // end of validation
    
     // start: ekta student delete korar age check korbo je session er student delete korte chacchi oi session er kono result ki processing obosthay ache naki. jdi processing obosthay thake mane exam_committee_information table er 1st_sem_status or 2nd_sem_status er value jdi 1 thake tahole kono student delete kora jabe na.
        
     $select_exam_committee = "SELECT `1st_sem_status`, `2nd_sem_status` FROM `exam_committee_information` WHERE `session` = '$id_validation_qry_run_res[current_session]' AND (`1st_sem_status` = '1' OR `2nd_sem_status` = '1')";
     $run_select_exam_committee = mysqli_query($conn, $select_exam_committee);
     $row_select_exam_committee = mysqli_fetch_assoc($run_select_exam_committee);
     if($row_select_exam_committee)
     {
         ?>
             <script>
                 window.alert("This Student Can Not Be Deleted From This Session");
                 window.location = "view_student_information.php?id=<?php echo $_GET['id'] ?>";
             </script>
         <?php
         exit(); 
     }
     // End: ekta student delete korar age check korbo je session er student delete korte chacchi oi session er kono result ki processing obosthay ache naki. jdi processing obosthay thake mane exam_committee_information table er 1st_sem_status or 2nd_sem_status er value jdi 1 thake tahole kono student delete kora jabe na. 
    
    if($num_rows==0)
    {
        $delete_qry = "DELETE FROM `student_information` WHERE `id` = '$id'";
        $delete_qry_run = mysqli_query($conn, $delete_qry);
        if($delete_qry_run)
        {
            ?>
            <script type="text/javascript">
                window.alert("Student Deleted Successfully");
                window.location = "view_student_information.php?page=<?php echo $page; ?>";
            </script>
            <?php
            exit();         
        }
    }
    else
    {
        ?>
        <script type="text/javascript">
            window.alert("Sorry!! <?php echo $id_validation_qry_run_res['name'] ?> can not be deleted");
            window.location = "view_student_information.php?page=<?php echo $page; ?>";
        </script>
        <?php
        exit();
    }
    
?>
