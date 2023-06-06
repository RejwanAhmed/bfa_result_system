<?php include('lib/teacher_header.php') ?>
<?php include('valid_department_function.php') ?>
<?php
    if(!isset($_GET['id']) || !isset($_GET['session']) || !isset($_GET['course_year']) || !isset($_GET['course_semester']) || !isset($_GET['course_id']) || !isset($_GET['department_id']))
    {
        ?>
        <script>
            window.location = "assigned_course_list.php";
        </script>
        <?php
        exit();
    }
    else if(isset($_GET['id']) && isset($_GET['session']) && isset($_GET['course_year']) && isset($_GET['course_semester']) && isset($_GET['course_id']) && isset($_GET['department_id']))
    {
        // department_id validation
        // valid department url e pass hocche kina ta check korar jonno.
        // department_id == 0 mane hocche foundation course. jehetu array_search fail korle 0 dekhay tai oita alada vabe $_GET['department_id] == 0 diye check korsi
        $valid_department_info = valid_department();
        $department_id_array = $valid_department_info[0];
        $department_name_array = $valid_department_info[1];
        if(array_search($_GET['department_id'],$department_id_array) || ($_GET['department_id']==0))
        {
            $department_id = $_GET['department_id'];
            $department_name = $department_name_array[array_search($_GET['department_id'],$department_id_array)];
        }
        else
        {
            ?>
            <script>
                window.alert("Invalid Department");
                window.location = "assigned_course_list.php";
            </script>
            <?php
            exit();
        }
        // end of department_id validation
        
        // Start: ekta course er internal marks jdi entry hoye jay tahole oi course er marks sudhu update kora jay. tai age validation check korte hobe je ekta particular courser marks entry hoise naki. jdi hoy tahole take ei page e dhukte dewa jabe na.
        
        $select_from_result = "SELECT `id` FROM `result` WHERE `current_session` = '$_GET[session]' AND `course_year` = '$_GET[course_year]' AND `course_semester` = '$_GET[course_semester]' AND `course_id` = '$_GET[course_id]' AND `teacher_id` = '$_SESSION[teacher_id]' AND `department_id` = '$department_id'";
        $run_select_from_result = mysqli_query($conn, $select_from_result);
        if(mysqli_num_rows($run_select_from_result)>0)
        {
            ?>
                <script>
                    window.alert("Marks Already Entered!! You can only update it.");
                    window.location = "update_internal_marks.php?session=<?php echo $_GET['session'] ?>&course_year=<?php echo $_GET['course_year'] ?>&course_semester=<?php echo $_GET['course_semester'] ?>&course_id=<?php echo $_GET['course_id'] ?>&department_id=<?php echo $department_id?>";
                </script>
            <?php
            exit(); 
        }
        // End: ekta course er internal marks jdi entry hoye jay tahole oi course er marks sudhu update kora jay. tai age validation check korte hobe je ekta particular courser marks entry hoise naki. jdi hoy tahole take ei page e dhukte dewa jabe na.
        
        
        $id_validation_qry = "SELECT ac.id, ac.session, ac.course_year, ac.course_semester, ac.teacher_id, ac.indexing, ac.verification, ac.course_id, c.course_title as course_title ,c.course_code as course_code, c.course_credit FROM assigned_course_information as ac INNER JOIN course_information as c ON c.id = ac.course_id WHERE ac.session = '$_GET[session]' && ac.course_year = '$_GET[course_year]' && ac.course_semester = '$_GET[course_semester]' && ac.teacher_id = '$_SESSION[teacher_id]' AND ac.course_id = '$_GET[course_id]' AND ac.verification = '1'";
        $run_id_validation_qry = mysqli_query($conn, $id_validation_qry);
        $res_id_validation_qry = mysqli_fetch_assoc($run_id_validation_qry);
        if($res_id_validation_qry==false)
        {
            ?>
            <script>
                window.alert("Invalid ID");
                window.location = "assigned_course_list.php";
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
                $select_student = "SELECT * FROM `student_information` WHERE `current_session` = '$_GET[session]' AND `status` = '0' ORDER BY `actual_session` DESC ,`roll_no` ASC ";
            }
            else
            {
                $select_student = "SELECT * FROM `student_information` WHERE `current_session` = '$_GET[session]' AND `status` = '0' AND `department_id` = '$department_id' ORDER BY `actual_session` DESC ,`roll_no` ASC";
            }
            $run_select_student = mysqli_query($conn, $select_student);
            $num_rows = mysqli_num_rows($run_select_student);
        }
    }
    
    //start: exam_committee theke 1st semester hole 1st_sem_status ar 2nd semester hole 2nd_sem_status er value niye asbo. jate kore amra marks enter korar option show korbo kina ta decide korte pari.
    if($_GET['course_semester']=="1st semester")
    {
        $col_name = "1st_sem_status";
    }
    else 
    {
        $col_name = "2nd_sem_status";
    }
    
    $select_exam_committee = "SELECT `$col_name` FROM `exam_committee_information` WHERE `session` = '$_GET[session]' AND `course_year` = '$_GET[course_year]'";
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
                    <h2>Add Internal Marks Of <?php echo "($_GET[session], $_GET[course_year], $_GET[course_semester])" ?></h2>
                    <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
                    <h4 class = "text-center text-secondary bg-white"><?php echo "Course Code: $res_id_validation_qry[course_code], Course Title: $res_id_validation_qry[course_title], Course Credit: $res_id_validation_qry[course_credit]" ?></h4>
                </div>
                <div class="card-body table-responsive">
                    <?php
                    // $exam_committee_status e 0 or 1 or 2 or 3 value thakbe. 0 mane hocche ekhno processing start hoy nai. tai student show korano jabe na. 1 mane start hoyse student show korabo. 2 mane result processing stop hoise tai student show korabo na. 3 mane result processing finish hoye gese.
                    if($exam_committee_status==0 || $exam_committee_status==2)
                    {
                        echo "<h3 class = 'text-danger text-center'>Wait For Tabulators Approval</h3>";
                    }
                    else if($num_rows>0)
                    {
                        ?>
                        <form action="" method = "POST">
                            <table class = "table  table-bordered table-hover text-center table-lg-responsive ">                 
                                <tr>
                                    <thead class ="thead-light">
                                        <th>Roll No</th>
                                        <th>Attendance</th>
                                        <th>Mid1</th>
                                        <th>Mid2</th>
                                        <th>Ass./Prese.</th>
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
                                        <td><input type="number" step = "0.01" name = "attendance[]"
                                             placeholder="Enter Marks"
                                            value = "<?php if(isset($_POST['attendance'][$i]))
                                            {
                                                echo $_POST['attendance'][$i];
                                            } ?>" required>
                                            <p  id = "attendance<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                        </td>

                                        <td><input type="number" step = "0.01" name = mid1[]
                                            placeholder="Enter Marks"
                                            value = "<?php if(isset($_POST['mid1'][$i]))
                                            {
                                                echo $_POST['mid1'][$i];
                                            } ?>" required>
                                            <p id = "mid1<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                        </td>

                                        <td><input type="number" step = "0.01" name = mid2[]
                                            placeholder="Enter Marks"
                                            value = "<?php if(isset($_POST['mid2'][$i]))
                                            {
                                                echo $_POST['mid2'][$i];
                                            } ?>" required>
                                            <p id = "mid2<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                        </td>

                                        <td><input type="number" step = "0.01" name = ass_pre[]
                                            placeholder="Enter Marks"
                                            value = "<?php if(isset($_POST['ass_pre'][$i]))
                                            {
                                                echo $_POST['ass_pre'][$i];
                                            } ?>" required>
                                            <p id = "ass_pre<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
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
<?php include('lib/teacher_footer.php') ?>
<?php
    if(isset($_POST['submit']))
    {
        // internal marks add er khetre course_credit = 1.5 or 3 jai hok attendance, mid1, mid2, ass_pre sob field ei value 10 er besi entry kora jabe na.
        
        $count_error = 0;
        for($i=0;$i<sizeof($_POST['attendance']);$i++)
        {
            if($_POST['attendance'][$i]>10 || $_POST['attendance'][$i]<0)
            {
                ?>
                <script>
                    document.getElementById('attendance<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 10 or < 0`;
                </script>
                <?php
                $count_error++;
            }
            if($_POST['mid1'][$i]>10 || $_POST['mid1'][$i]<0)
            {
                ?>
                <script>
                    document.getElementById('mid1<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 10 or < 0`;
                </script>
                <?php
                $count_error++;
            }
            if($_POST['mid2'][$i]>10 || $_POST['mid2'][$i]<0)
            {
                ?>
                <script>
                    document.getElementById('mid2<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 10 or < 0`;
                </script>
                <?php
                $count_error++;
                
            }
            if($_POST['ass_pre'][$i]>10 || $_POST['ass_pre'][$i]<0)
            {
                ?>
                <script>
                    document.getElementById('ass_pre<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 10 or < 0`;
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
    
        for($i=0;$i<sizeof($_POST['attendance']);$i++)
        {
            // Insert Query
            $student_id = $_POST['id'][$i];
            $actual_session = $_POST['actual_session'][$i];
            $current_session = $_POST['current_session'][$i];
            $attendance = $_POST['attendance'][$i];
            $mid1 = $_POST['mid1'][$i];
            $mid2 = $_POST['mid2'][$i];
            $ass_pre = $_POST['ass_pre'][$i];

            $total_internal =$attendance + $mid1 + $mid2 + $ass_pre;
            
            // insert korar age result table e oi student er jdi oi course er marks ager theke thake tahole seta invalid kore dite hobe.
            
            if($actual_session!=$current_session)
            {
                $update_result_validation_col = "UPDATE `result` SET `result_validation` = 'i' WHERE `student_id` = '$student_id' AND `course_id`= '$_GET[course_id]' AND `course_year` = '$_GET[course_year]' AND `course_semester` = '$_GET[course_semester]' AND `result_validation` = 'v'";
                $run_update_result_validation_col = mysqli_query($conn, $update_result_validation_col);
            }
            // 3rd_examinee_eligibility default vabe 0 thakbe. 0 hole 3rd_examinee er marks dorkar nai. 1 hole dorkar ache. 3rd_examinee er marks dewa hoye geleo 3rd_examinee_eligibility = 1 thakbe. karon eita dara eligibility bujhacche.
            
            $insert_result = "INSERT INTO `result`(`actual_session`, `current_session`, `course_year`, `course_semester`, `student_id`, `teacher_id`, `course_id`, `department_id`, `attendance`, `mid1`, `mid2`, `ass_pre`,`totaL_internal`,`1st_examinee`,`2nd_examinee`,`3rd_examinee`,`3rd_examinee_eligibility`,`total_final_marks`,`improvement_eligibility`,`improvement_result_status`,`total_improvement_exam`,`result_status`,`result_validation`) VALUES ('$actual_session', '$current_session', '$_GET[course_year]', '$_GET[course_semester]','$student_id','$_SESSION[teacher_id]','$_GET[course_id]','$department_id','$attendance','$mid1','$mid2','$ass_pre','$total_internal','-1','-1','-1','0','-1','N','N','0','0','v')";
            $run_insert_result = mysqli_query($conn, $insert_result);
            if($run_insert_result)
            {
                ?>
                <script>
                    window.alert("Marks Inserted Successfully");
                    window.location = "assigned_course_list.php";
                </script>
                <?php
            }
        }
    }

?>
