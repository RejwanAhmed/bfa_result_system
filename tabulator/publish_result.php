<?php include('lib/tabulator_header.php') ?>
<?php include('valid_department_function.php') ?>
<?php 
    if(isset($_GET['course_semester']) && ($_GET['course_semester'] == "1st semester" || $_GET['course_semester']=="2nd semester") && isset($_GET['department_id']))
    {
         // department validation
        // valid department theke ekta department valid ache kina check kore niye ase.
        $department_info = valid_department($_SESSION['course_year'],$_GET['department_id']);
        if($department_info[0]!=-1)
        {
            $department_id = $department_info[0];
            $department_name = $department_info[1];
        }
        else
        {
            ?>
            <script>
                window.alert("Invalid Department");
                window.location = "home.php";
            </script>
            <?php
            exit();
        }
        // Start: result publish korar age check korte hobe je result calcualte hoyse naki. jdi calculate na hoye thake that means jdi semester_cgpa table e jdi data na thake tahole result publish kora jabe na.
        
        $select_semester_cgpa = "SELECT `id` FROM `semester_cgpa` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$_GET[course_semester]' AND `department_id` = '$department_id'";
        
        $run_select_semester_cgpa = mysqli_query($conn, $select_semester_cgpa);
        if(mysqli_num_rows($run_select_semester_cgpa)==0)
        {
            ?>
            <script>
                window.alert("Result Has Not been Calculated Yet!! Please First Click On Calculate Result Button");
                window.location = "1st_2nd_semester.php?semester=<?php echo $_GET['course_semester']?>&department_id=<?php echo $department_id ?>";
            </script>
            <?php
            exit();
        }
        // End: result publish korar age check korte hobe je result calcualte hoyse naki. jdi calculate na hoye thake that means jdi semester_cgpa table e jdi data na thake tahole result publish kora jabe na.
        
        
        $change_result_status = "UPDATE `result` SET `result_status` = '1' WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$_GET[course_semester]' AND `department_id` = '$department_id'";
        $res_change_result_status = mysqli_query($conn, $change_result_status);
        
        if($res_change_result_status)
        {
            ?>
            <script>
                window.alert("Result Has been Published");
                window.location = "1st_2nd_semester.php?semester=<?php echo $_GET['course_semester']?>&department_id=<?php echo $department_id ?>";
            </script>
            <?php 
        }
    }
    else
    {
        ?>
        <script>
            window.alert("Invalid Semester");
            window.location = "home.php";
        </script>
        <?php 
    }
?>
<?php include('lib/tabulator_footer.php') ?>