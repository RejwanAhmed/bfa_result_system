<?php include('lib/tabulator_header.php') ?>
<?php include('valid_department_function.php') ?>
<?php include('semester_wise_cgpa_calculation.php') ?>
<?php 
    // Validation
    if(!isset($_GET['course_semester']) || !isset($_GET['course_id']) || !isset($_GET['department_id']) || !isset($_GET['teacher_id']))
    {
        ?>
        <script>
            window.location = "home.php";
        </script>
        <?php
        exit();
    }
    else if(isset($_GET['course_semester']) && isset($_GET['course_id']) && isset($_GET['teacher_id']) && isset($_GET['department_id']) && ($_GET['course_semester']=="1st semester" || $_GET['course_semester']=="2nd semester"))
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
                window.location = "1st_2nd_semester.php?semester=<?php echo $_GET['course_semester']?>&department_id=<?php echo $_GET['department_id'] ?> ";
            </script>
            <?php
            exit();
        }
        // end of department_id validation
        
        // first check whether data already has been entered or not
        // if entered then redirect to 1st_2nd_semester.php page
        $check_internal_from_result = "SELECT `id` FROM `result` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$_GET[course_semester]' AND `teacher_id` = '$_GET[teacher_id]' AND `course_id` = '$_GET[course_id]'";
        $run_check_internal_from_result = mysqli_query($conn, $check_internal_from_result);
        if(mysqli_num_rows($run_check_internal_from_result)>0)
        {
            ?>
                <script>
                    window.alert("Internal Marks Have Been Already Given!!");
                    window.location = "1st_2nd_semester.php?semester=<?php echo $_GET['course_semester'] ?>&department_id=<?php echo $_GET['department_id'] ?>";
                </script>
            <?php
            exit();
        }
        
        $id_validation_qry = "SELECT ac.id, ac.session, ac.course_year, ac.course_semester, ac.teacher_id, ac.indexing, ac.verification, ac.course_id, c.course_title as course_title ,c.course_code as course_code, c.course_credit FROM assigned_course_information as ac INNER JOIN course_information as c ON c.id = ac.course_id WHERE ac.session = '$_SESSION[session]' && ac.course_year = '$_SESSION[course_year]' && ac.course_semester = '$_GET[course_semester]' && ac.teacher_id = '$_GET[teacher_id]' AND ac.course_id = '$_GET[course_id]' AND ac.teacher_id != '-1' AND ac.course_id!='-1'";
        $run_id_validation_qry = mysqli_query($conn, $id_validation_qry);
        $res_id_validation_qry = mysqli_fetch_assoc($run_id_validation_qry);
        if($res_id_validation_qry==false)
        {
            ?>
            <script>
                window.alert("Invalid ID");
                window.location = "1st_2nd_semester.php?semester=<?php echo $_GET['course_semester'] ?>&department_id=<?php echo $_GET['department_id'] ?>";
            </script>
            <?php
            exit();
        }
        else
        {
            // jdi department = Foundation course hoy tahole sob student der ke show korbe. otherwise department onujayi student show korbe.
            // status = 0 mane student active ache.
            if($department_id==0)
            {
                $select_student = "SELECT * FROM `student_information` WHERE `current_session` = '$_SESSION[session]' AND `status` = '0' ORDER BY `actual_session` DESC ,`roll_no` ASC ";
            }
            else
            {
                $select_student = "SELECT * FROM `student_information` WHERE `current_session` = '$_SESSION[session]' AND `status` = '0' AND `department_id` = '$department_id' ORDER BY `actual_session` DESC ,`roll_no` ASC ";
            }
            $run_select_student = mysqli_query($conn, $select_student);
            $num_rows = mysqli_num_rows($run_select_student);
        }
    }
    else 
    {
        ?>
        <script>
            window.alert("Invalid Course Semester");
            window.location = "home.php";
        </script>
        <?php 
        exit();
    }
    
    
    $course_qry = "SELECT * FROM `course_information` WHERE `id` = '$_GET[course_id]'";
    $run_course_qry = mysqli_query($conn, $course_qry);
    $res_course_qry = mysqli_fetch_assoc($run_course_qry);
    
    //start: exam_committee theke 1st semester hole 1st_sem_status ar 2nd semester hole 2nd_sem_status er value niye asbo. jate kore amra marks enter korar option show korbo kina ta decide korte pari.
    if($_GET['course_semester']=="1st semester")
    {
        $col_name = "1st_sem_status";
    }
    else 
    {
        $col_name = "2nd_sem_status";
    }

    $select_exam_committee = "SELECT `$col_name` FROM `exam_committee_information` WHERE `session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]'";
    $run_select_exam_committee = mysqli_query($conn, $select_exam_committee);
    $res_select_exam_committee = mysqli_fetch_assoc($run_select_exam_committee);
    $exam_committee_status = $res_select_exam_committee[$col_name];
    //end: exam_committee theke 1st semester hole 1st_sem_status ar 2nd semester hole 2nd_sem_status er value niye asbo. jate kore amra marks enter korar option show korbo kina ta decide korte pari.
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header form_header text-center">
                    <h2>Add Internal Marks Of <?php echo "($_SESSION[session], $_SESSION[course_year], $_GET[course_semester])" ?></h2>
                    <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
                    <h4 class = "text-center text-secondary bg-white"><?php echo "Course Code: $res_id_validation_qry[course_code], Course Title: $res_id_validation_qry[course_title], Course Credit: $res_id_validation_qry[course_credit]" ?></h4>
                </div>
                <div class="card-body table-responsive">
                    <?php
                     // $exam_committee_status e 0 or 1 or 2 or 3 value thakbe. 0 mane hocche ekhno processing start hoy nai. tai student show korano jabe na. 1 mane start hoyse student show korabo. 2 mane result processing stop hoise tai student show korabo na. 3 mane result processing finish hoye gese.
                     if($exam_committee_status==0 || $exam_committee_status==2)
                     {
                         echo "<h3 class = 'text-danger text-center'>Result processing is in pending.</h3>";
                     }
                     else if($num_rows>0)
                    {
                        ?>
                        <form action="" method = "POST">
                            <table class = "table  table-bordered table-hover text-center table-lg-responsive ">                 
                                <tr>
                                    <thead class ="thead-light">
                                        <th>Roll No</th>
                                        <th>Total Internal (40%)</th>
                                    </thead>
                                </tr>
                                <?php
                                $i=0;
                                while($row = mysqli_fetch_assoc($run_select_student))
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['roll_no'] ?></td>
                                        <input type="hidden" name = "id[]" value = "<?php echo $row['id'] ?>">
                                        <input type="hidden" name = "current_session[]" value = "<?php echo $row['current_session'] ?>">
                                        <input type="hidden" name = "actual_session[]" value = "<?php echo $row['actual_session'] ?>">
                                        <td><input type="number" step = "0.01" name = "total_internal[]"
                                             placeholder="Enter Total Internal Marks"
                                            value = "<?php if(isset($_POST['total_internal'][$i]))
                                            {
                                                echo $_POST['total_internal'][$i];
                                            } ?>" required>
                                            <p  id = "total_internal<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </table>
                            <div class="row justify-content-center">
                                <div class="col-lg-4 col-md-4 col-12">
                                    <input type="submit" name = "submit" value = "Enter" class = "form-control btn">
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                    else
                    {
                        echo "<h3 class = 'text-center text-danger'>No Students Are Added</h3>";
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include('lib/tabulator_footer.php') ?>
<?php
    if(isset($_POST['submit']))
    {
        // internal marks add er khetre course_credit = 1.5 or 3 jai hok total_internal field er value 40 er besi entry kora jabe na jehetu eita tabulator entry kortese.
       
        $count_error = 0;
        for($i=0;$i<sizeof($_POST['total_internal']);$i++)
        {
            if($_POST['total_internal'][$i]>40 || $_POST['total_internal'][$i]<0)
            {
                ?>
                <script>
                    document.getElementById('total_internal<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 40 or < 0`;
                </script>
                <?php
                $count_error++;
            }
        }
        // sob error eksathe dekhanor jonno ei error count use korsi
        if($count_error>0)
        {
            exit();
        }
        
    
        for($i=0;$i<sizeof($_POST['total_internal']);$i++)
        {
            // Insert Query
            $student_id = $_POST['id'][$i];
            $actual_session = $_POST['actual_session'][$i];
            $current_session = $_POST['current_session'][$i];
            $attendance = -1;
            $mid1 = -1;
            $mid2 = -1;
            $ass_pre = -1;
            $total_internal =$_POST['total_internal'][$i];
            
            // insert korar age result table e oi student er jdi oi course er marks ager theke thake tahole seta invalid kore dite hobe.
            
            if($actual_session!=$current_session)
            {
                $update_result_validation_col = "UPDATE `result` SET `result_validation` = 'i' WHERE `student_id` = '$student_id' AND `course_id`= '$_GET[course_id]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$_GET[course_semester]' AND `result_validation` = 'v'";
                $run_update_result_validation_col = mysqli_query($conn, $update_result_validation_col);
            }
            
            // 3rd_examinee_eligibility default vabe 0 thakbe. 0 hole 3rd_examinee er marks dorkar nai. 1 hole dorkar ache. 3rd_examinee er marks dewa hoye geleo 3rd_examinee_eligibility = 1 thakbe. karon eita dara eligibility bujhacche.
            
            $insert_result = "INSERT INTO `result`(`actual_session`, `current_session`, `course_year`, `course_semester`, `student_id`, `teacher_id`, `course_id`, `department_id`, `attendance`, `mid1`, `mid2`, `ass_pre`,`totaL_internal`,`1st_examinee`,`2nd_examinee`,`3rd_examinee`,`3rd_examinee_eligibility`,`total_final_marks`,`improvement_eligibility`,`improvement_result_status`,`total_improvement_exam`,`result_status`,`result_validation`) VALUES ('$actual_session', '$current_session', '$_SESSION[course_year]', '$_GET[course_semester]','$student_id','$_GET[teacher_id]','$_GET[course_id]','$department_id','$attendance','$mid1','$mid2','$ass_pre','$total_internal','-1','-1','-1','0','-1','N','N','0','0','v')";
            $run_insert_result = mysqli_query($conn, $insert_result);
            if($run_insert_result)
            {   
                // set verification = 1 in assigned_course_information table
                $update_assigned_course = "UPDATE `assigned_course_information` SET `verification` = '1' WHERE `session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$_GET[course_semester]' AND `course_id` = '$_GET[course_id]' AND `teacher_id` = '$_GET[teacher_id]'";
                $run_update_assigned_course = mysqli_query($conn, $update_assigned_course);
                if($run_update_assigned_course)
                {
                    ?>
                    <script>
                        window.alert("Marks Inserted Successfully");
                        window.location = "1st_2nd_semester.php?semester=<?php echo $_GET['course_semester']?>&department_id=<?php echo $_GET['department_id'] ?> ";
                    </script>
                    <?php
                }
                
            }
        }
    }

?>