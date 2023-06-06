<?php
    session_start();
    if(!isset($_SESSION['teacher_id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
    include('lib/db_connection.php');
    if(isset($_GET['id']) && isset($_GET['session']) && isset($_GET['course_year'])  && isset($_GET['course_semester']))
    {
        $id_validation_qry = "SELECT * FROM `assigned_course_information` WHERE `id` = '$_GET[id]' AND `teacher_id` = '$_SESSION[teacher_id]' && `verification` = '0'";
        $run_id_validation_qry = mysqli_query($conn, $id_validation_qry);
        $res_id_validation_qry = mysqli_fetch_assoc($run_id_validation_qry);

        if($res_id_validation_qry == false)
        {
            ?>
            <script>
                window.alert("Error");
                window.location = "assigned_course_list.php";
            </script>
            <?php
            exit();
        }
        else
        {
            $update_assigned_course = "UPDATE `assigned_course_information` SET `verification` = '1' WHERE `id` = '$_GET[id]' AND `teacher_id` = '$_SESSION[teacher_id]'";
            $run_update_assigned_course = mysqli_query($conn, $update_assigned_course);

            if($run_update_assigned_course)
            {
                ?>
                <script>
                    window.alert("Course is Verified");
                    window.location = "view_assigned_course_session_semester_wise.php?session=<?php echo $_GET['session'] ?>&course_year=<?php echo $_GET['course_year'] ?>&course_semester=<?php echo $_GET['course_semester'] ?>";
                </script>
                <?php
                exit();
            }
        }
    }
    else
    {
        ?>
        <script>
            window.location = "assigned_course_list.php";
        </script>
        <?php
        exit();
    }

?>
