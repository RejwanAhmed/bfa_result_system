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
        // delete_validation = 0 thakle delete kora jabe ebong 1 thakle delete kora jabe na
        $id_validation_qry = "SELECT * FROM `department_information` WHERE `id` = '$_GET[id]'";
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
    // deletion validation from student_information table
    if(row_deletion_validation('student_information','department_id',$_GET['id'])==0)
    {
        // deletion validation from course_information table
       if(row_deletion_validation('course_information','department_id',$_GET['id'])==0)
       {
        // deletion validation from assigned_course_information table
            if(row_deletion_validation('assigned_course_information','department_id',$_GET['id'])==0)
            {
                if(row_deletion_validation('improvement_result','department_id',$_GET['id'])==0)
                {
                    if(row_deletion_validation('result','department_id',$_GET['id'])==0)
                    {
                        if(row_deletion_validation('semester_cgpa','department_id',$_GET['id'])>0)
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
    }
    else
    {
       $num_rows = 1; 
    }
    // end of validation
    
    if($num_rows==0)
    {
        $delete_qry = "DELETE FROM `department_information` WHERE `id` = '$id'";
        $delete_qry_run = mysqli_query($conn, $delete_qry);
        if($delete_qry_run)
        {
            ?>
            <script type="text/javascript">
                window.alert("department Deleted Successfully");
                window.location = "view_department_information.php?page=<?php echo $page; ?>";
            </script>
            <?php
        }
    }
    else
    {
        ?>
        <script type="text/javascript">
            window.alert("Sorry!! <?php echo $id_validation_qry_run_res['department_name'] ?> can not be deleted");
            window.location = "view_department_information.php?page=<?php echo $page; ?>";
        </script>
        <?php
    }
    
?>
