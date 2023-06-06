<?php include('lib/header.php') ?>
<?php
    if(!isset($_GET['session']) || !isset($_GET['year']) || !isset($_GET['semester']) || !isset($_GET['department_id']))
    {
        ?>
        <script>
            window.alert("Session or Year or Semester or Department Is Not Set");
            window.location ='index.php';
        </script>
        <?php
         exit();
    }
    else if(isset($_GET['session']) && isset($_GET['year']) && isset($_GET['semester']) && isset($_GET['department_id']))
    {
        // session validation
        $c = 2006;
        $count = 0;
        $today = date("Y");
        for($i=$c; $i<$today; $i++)
        {
            $r = $i + 1;
            $session= $i."-".$r;
            if($session == $_GET['session'])
            {
                $count = 1;
                break;
            }
        }
        if($count==0)
        {
            ?>
            <script>
                window.alert("Invalid Session");
                window.location = "index.php";
            </script>
            <?php
            exit();
        }
        // end of session validation
        
        // year and semester validation
        $count = 0;
        if(($_GET['year']=="1st year" || $_GET['year'] == "2nd year" || $_GET['year']=="3rd year" || $_GET['year'] == "4th year") && ($_GET['semester']=="1st semester" || $_GET['semester']=="2nd semester"))
        {
            $count = 1;
        }
        if($count==0)
        {
            ?>
            <script>
                window.alert("Invalid Year or Semester");
                window.location = "index.php";
            </script>
            <?php
            exit();
        }
        // end of year and semester validation
        
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
                window.location = "home.php";
            </script>
            <?php
            exit();
        }
        // end of department_id validation
    }
?>
<?php
    // Qry to fetch course according to year, semester and department
    $select_assigned_course_info = "SELECT * FROM `assigned_course_information` WHERE `session` = '$_GET[session]' AND `course_year` = '$_GET[year]' AND `course_semester` = '$_GET[semester]' AND `department_id` = '$_GET[department_id]' ORDER BY `indexing` ASC";
    
    $run_select_assigned_course_info = mysqli_query($conn, $select_assigned_course_info);
    $all_course_id = array();
    $all_teacher_id = array();
    $indexing = array();
    $verification = array();
    array_push($all_course_id,-1);  // as first index could not find
    array_push($all_teacher_id,-1); // as first index could not find
    array_push($indexing,-1); // as first index could not find
    array_push($verification, -1);
    while($row = mysqli_fetch_assoc($run_select_assigned_course_info))
    {
        array_push($all_course_id,$row['course_id']);
        array_push($all_teacher_id,$row['teacher_id']);
        array_push($indexing,$row['indexing']);
        array_push($verification, $row['verification']);
    }
    // exit();
    
    
    // select distinct id from result so that we can find which courses marks has been entered
    // push the course_id into array so that we can match
    $select_from_result = "SELECT DISTINCT `course_id` FROM `result` WHERE `current_session` = '$_GET[session]' AND `course_year` = '$_GET[year]' AND `course_semester` = '$_GET[semester]' ORDER BY `course_id` ASC";
    $run_select_from_result = mysqli_query($conn, $select_from_result);
    $course_id_from_result = array();
    array_push($course_id_from_result,-1);
    while($row = mysqli_fetch_assoc($run_select_from_result))
    {
        array_push($course_id_from_result,$row['course_id']);
    }
    // end of select distinct id from result
    
    // Start of find whether any committee has been formed for particular session(2015-2016) and particular year(1st year)
    
    $select_exam_committee = "SELECT * FROM `exam_committee_information` WHERE `session` = '$_GET[session]' AND `course_year` = '$_GET[year]'";
    $run_select_exam_committee = mysqli_query($conn, $select_exam_committee);
    $num_rows_committee = mysqli_num_rows($run_select_exam_committee);
    $row_select_exam_committee = mysqli_fetch_assoc($run_select_exam_committee);
    // End of find whether any committee has been formed for particular session(2015-2016) and particular year(1st year)
    
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header form_header text-center">
                    <h2>Assign Course To Session <?php echo "(".$_GET['session'].", $_GET[year], $_GET[semester])"?></h2>
                    <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
                </div>
                <div class="card-body table-responsive">
                    <!-- Start: jdi kono committee form na thake tahole nicher warning show korbe ar kichui na -->
                    <?php 
                        if($num_rows_committee==0)
                        {
                            ?>
                                <h3 class = "text-danger text-center">No Committee Has Been Formed. Please First Form A Committee For This Session And Year.</h3>
                            <?php 
                            exit();
                        }
                       
                    ?>
                    <!-- End: jdi kono committee form na thake tahole nicher warning show korbe ar kichui na -->
                    
                    <?php
                        // qry to check whether viva-voce already exists or not for this session, course_year, course_semester, department
                        // viva-voce er teacher_id 0 hobe tai teacher_id = 0 diye check korsi.
                        $select_assigned_course_info_viva_voce = "SELECT * FROM `assigned_course_information` WHERE `session` = '$_GET[session]' AND `course_year` = '$_GET[year]' AND `course_semester` = '$_GET[semester]' AND `department_id` = '$_GET[department_id]' AND `teacher_id` = '0'";
                        $run_select_assigned_course_info_viva_voce = mysqli_query($conn, $select_assigned_course_info_viva_voce);
                        $num_rows_assigned_course_info_viva_voce =mysqli_num_rows($run_select_assigned_course_info_viva_voce);
                        
                        // jdi viva-voce add na hoye thake tahole add korar jonno query kora hobe
                        if($num_rows_assigned_course_info_viva_voce==0)
                        {
                            // jdi viva-voce add na hoye thake tahole add korar jonno query kora hobe
                            
                            $select_course_info_viva_voce = "SELECT * FROM `course_information` WHERE `course_semester` = '$_GET[semester]' AND `course_year` = '$_GET[year]' AND `department_id` = '$department_id' AND `course_type` = 'Viva-Voce'" ;
                            
                            $run_select_course_info_viva_voce = mysqli_query($conn, $select_course_info_viva_voce);
                            $num_course_viva_voce = mysqli_num_rows($run_select_course_info_viva_voce);
                            if($num_course_viva_voce==1)
                            {
                                $viva_voce_value = mysqli_fetch_assoc($run_select_course_info_viva_voce);
                                ?>
                                    <form action = "" method = "POST">
                                        <div class="row">
                                            <div class = "col-lg-4 col-md-4 col-12">
                                                <input type = "submit" class = "form-control btn-primary mb-2" name = "viva-voce" value = "Click To Add Viva-Voce" style = "margin-top: -7px; font-weight: bold">
                                                <input type = "hidden" name = "viva_voce_course_id" value = "<?php echo $viva_voce_value['id'] ?>">
                                                <input type = "hidden" name = "viva_voce_dept_id" value = "<?php echo $viva_voce_value['department_id'] ?>">
                                            </div>                                     
                                        </div>
                                    </form>
                                <?php 
                            }
                        }
                        else
                        {
                            echo "<label style = 'margin-top:-7px' class = 'text-center form-control text-success font-weight-bold'>Viva Voce Already Added</label>";
                        }
                    ?>
                    
                    <form action="" method = "POST">
                        <table class = "table table-bordered table-hover text-center">
                            <tr>
                                <thead class ="thead-light">
                                    <th>Course Code</th>
                                    <th>Course Title</th>
                                    <th>Course Year</th>
                                    <th>Course Semester</th>
                                    <th>Teacher</th>
                                    <th>Status</th>
                                </thead>
                            </tr>
                            <?php
                            $select_course_info = "SELECT * FROM `course_information` WHERE `course_semester` = '$_GET[semester]' AND `course_year` = '$_GET[year]' AND `department_id` = '$department_id' AND `course_type` != 'Viva-Voce'" ;
                            $run_select_course_info = mysqli_query($conn, $select_course_info);
                            $num_course = mysqli_num_rows($run_select_course_info);
                            while($row = mysqli_fetch_assoc($run_select_course_info))
                            {
                                ?>
                                <tr>
                                    <td><?php echo $row['course_code']; ?></td>
                                    <td><?php echo $row['course_title']; ?></td>
                                    <td><?php echo $row['course_year']; ?></td>
                                    <td><?php echo $row['course_semester']; ?></td>
                                    <td>
                                        <?php
                                        $select_from_teacher = "SELECT * FROM `teacher_information`";
                                        $run_select_from_teacher = mysqli_query($conn, $select_from_teacher);
                                        ?>
                                        <select name="teacher_id[]" class = "form-control"
                                        <?php
                                        // Start: This code is for hiding the select tag of verified course
                                        $disabled = 0;
                                        // for($j=0;$j<sizeof($indexing);$j++)
                                        // {
                                        //     if($verification[$j]==1 && $all_course_id[$j]==$row['id'])
                                        //     {
                                        //         echo "hidden";
                                        //         $disabled = 1;
                                        //         break;
                                        //     }
                                        // }
                                        // End: This code is for hiding the select tag of verified course
                                        
                                        // start: jdi kono course er marks entry hoye thake tar mane seta verified course and sekhane teacher ke change kora jabe na tai select tag hide kore dite hobe
                                        if(in_array($row['id'], $course_id_from_result))
                                        {
                                            echo "hidden";
                                            $disabled = 1;
                                        }
                                        // end: jdi kono course er marks entry hoye thake tar mane seta verified course and sekhane teacher ke change kora jabe na tai select tag hide kore dite hobe
                                        
                                        ?>
                                        >
                                            <option value="">
                                                <?php
                                                $i=0;
                                                $count = 0;
                                                if(in_array($row['id'],$all_course_id))
                                                {
                                                    $count = 1;
                                                }
                                                // while($i<sizeof($all_course_id))
                                                // {
                                                //     if($all_course_id[$i]==$row['id'])
                                                //     {
                                                //         $count = 1;
                                                //         break;
                                                //     }
                                                //     $i++;
                                                // }
                                                if($count==0)
                                                {
                                                    echo "Please Select Teacher";
                                                }
                                                else
                                                {
                                                    echo "Unassign Teacher";
                                                }
                                                ?>
                                                </option>
                                            <?php
                                            while($row_teacher = mysqli_fetch_assoc($run_select_from_teacher))
                                            {
                                                $val = $row['id'].",".$row_teacher['id'];
                                                ?>
                                                <option value="<?php echo $val ?>"
                                                <?php
                                                $i = 1;
                                                while($i<sizeof($all_course_id))
                                                {
                                                    if($all_course_id[$i]==$row['id'] && $all_teacher_id[$i]==$row_teacher['id'])
                                                    {
                                                        echo "selected";
                                                        $teacher_name = $row_teacher['name'];
                                                        break;
                                                    }
                                                    $i++;
                                                }
                                                 ?>><?php echo $row_teacher['name']
                                                ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <?php
                                        // Start: as select tag is hidden then print the teacher name
                                        if($disabled==1)
                                        {
                                            echo $teacher_name." (Selected)";
                                        }
                                        // End: as select tag is hidden then print the teacher name
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            if(in_array($row['id'],$course_id_from_result))
                                            {
                                                ?>
                                                <span class = "text-success"><i class="fas fa-check-square"></i></span>
                                                <?php
                                            }
                                            else if(array_search($row['id'],$all_course_id))
                                            {
                                                ?>
                                                <!-- <span class = "text-success"><i class="fas fa-check-square"></i></span> -->
                                                <span class = "text-primary"><i class="fa fa-spinner"></i></span> 
                                               
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <span class = "text-danger"><i class="fas fa-window-close"></i></span>
                                                <?php
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <div class="row justify-content-center m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <?php
                                // Start: jdi particular semeseter (1st year, 1st semester) er course thake tahole Enter Button Show korbe.
                                if($num_course!=0)
                                {
                                    // start: jdi 1st semester er course hoy ebong 1st_sem_status = 0 or 2 hoy othoba 2nd semester er course hoy ebong 2nd_sem_status = 0 or 2 hoy tahole button show korbe. 0 mane hocche ekhno result processing suru hoy nai ar 2 mane hocche result processing ta pause kora hoyse kichu karonbosoto.
                                    $show_enter_button = 0;
                                    if($_GET['semester']=="1st semester")
                                    {
                                        if($row_select_exam_committee['1st_sem_status']==0 || $row_select_exam_committee['1st_sem_status']==2)
                                        {
                                            $show_enter_button = 1;
                                        }
                                        
                                    }
                                    else 
                                    {
                                        if($row_select_exam_committee['2nd_sem_status']==0 || $row_select_exam_committee['2nd_sem_status']==2)
                                        {
                                            $show_enter_button = 1;
                                        }
                                    }
                                    // end: jdi 1st semester er course hoy ebong 1st_sem_status = 0 or 2 hoy othoba 2nd semester er course hoy ebong 2nd_sem_status = 0 or 2 hoy tahole button show korbe. 0 mane hocche ekhno result processing suru hoy nai ar 2 mane hocche result processing ta pause kora hoyse kichu karonbosoto.
                                    
                                    if($show_enter_button==1)
                                    {
                                        ?>
                                        <input type="submit" class = "form-control btn" name = "submit" value = "Enter">
                                        <?php
                                    }
                                       
                                }
                                // End: jdi particular semeseter 1st year, 1st semester) er course thake tahole Enter Button Show korbe.
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    if(isset($_POST['viva-voce']))
    {
        
        // jdi department = Foundation course hoy tahole sob student der ke show korbe. otherwise department onujayi student show korbe.
        //  jei session er viva-voce add kortesi oi session er jdi student add thake tahole viva-voce add kora jabe karon amder result table e oi student der internal marks add korte hobe.
         if($department_id==0)
         {
             $select_student = "SELECT * FROM `student_information` WHERE `current_session` = '$_GET[session]' AND `status` = '0' ORDER BY `actual_session` DESC ,`roll_no` ASC ";
         }
         else
         {
             $select_student = "SELECT * FROM `student_information` WHERE `current_session` = '$_GET[session]' AND `status` = '0' AND `department_id` = '$department_id' ORDER BY `actual_session` DESC ,`roll_no` ASC ";
         }
         $run_select_student = mysqli_query($conn, $select_student);
         $num_rows_student = mysqli_num_rows($run_select_student);
         
        // teacher_id 0 dicchi karon viva-voce e kono teacher assigned hobe na. ar normally kono teacher er id 0 thakbe na. tai 0 diyechi. empty rakha jabe na karon teacher_id column int type dewa
         
        //  indexing 1000 diyechi karon 1000 tar moto subject ekta semester e thakbe na.
        // verification 1 diyechi karon course ta ar verify korar dorkar nai
        
        if($num_rows_student>0)
        {
            $session = $_GET['session'];
            $course_year = $_GET['year'];
            $course_semester = $_GET['semester'];
            $teacher_id = "0";
            $course_id = $_POST['viva_voce_course_id'];
            $department_id = $_POST['viva_voce_dept_id'];
            $indexing = 1000;
            $verification = 1;
            
            $insert_assigned_course = "INSERT INTO `assigned_course_information`(`session`, `course_year`, `course_semester`, `course_id`, `teacher_id`,`department_id`,`indexing`,`verification`) VALUES ('$session','$course_year','$course_semester','$course_id','$teacher_id','$department_id','$indexing','$verification')";
            $run_insert_assigned_course = mysqli_query($conn, $insert_assigned_course);
            if($run_insert_assigned_course)
            {
                // assigned course e data insert howar pore result table e oi session er current student der internal marks add kore dite hobe.
                $i=1;
                $insert_result = "INSERT INTO `result`(`actual_session`, `current_session`, `course_year`, `course_semester`, `student_id`, `teacher_id`, `course_id`, `department_id`, `attendance`, `mid1`, `mid2`, `ass_pre`,`totaL_internal`,`1st_examinee`,`2nd_examinee`,`3rd_examinee`,`total_final_marks`,`improvement_eligibility`,`improvement_result_status`,`total_improvement_exam`,`result_status`,`result_validation`) VALUES";
                while($row = mysqli_fetch_assoc($run_select_student))
                {
                    // jdi current_session and actual_session same na hoy mane readd thake tahole oi student er ager result gula invalid kore dite hobe.
                    if($row['current_session']!=$row['actual_session'])
                    {
                        $update_result_validation_col = "UPDATE `result` SET `result_validation` = 'i' WHERE `student_id` = '$row[id]' AND `course_id`= '$course_id' AND `course_year` = '$course_year' AND `course_semester` = '$course_semester' AND `result_validation` = 'v'";
                        $run_update_result_validation_col = mysqli_query($conn, $update_result_validation_col);
                    }
                    if($i==$num_rows_student)
                    {
                        $insert_result.= "('$row[actual_session]','$row[current_session]','$course_year','$course_semester','$row[id]','$teacher_id','$course_id','$department_id','-1','-1','-1','-1','0','-1','-1','-1','-1','N','N','0','0','v')";
                    }
                    else
                    {
                        $insert_result.= "('$row[actual_session]','$row[current_session]','$course_year','$course_semester','$row[id]','$teacher_id','$course_id','$department_id','-1','-1','-1','-1','0','-1','-1','-1','-1','N','N','0','0','v'),";
                    }
                    $i++;
                    
                }
                $run_insert_result = mysqli_query($conn, $insert_result);
                if($run_insert_result)
                {
                    ?>
                    <script>
                        window.alert("Viva-Voce Added Successfully");
                        window.location = "assign_course_to_session.php?session=<?php echo $_GET['session'] ?>&year=<?php echo $_GET['year'] ?>&semester=<?php echo $_GET['semester'] ?>&department_id=<?php echo $department_id ?>";
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
                window.alert("Please Add Student First");
                window.location = "assign_course_to_session.php?session=<?php echo $_GET['session'] ?>&year=<?php echo $_GET['year'] ?>&semester=<?php echo $_GET['semester'] ?>&department_id=<?php echo $department_id ?>";
            </script>
            <?php
            exit();
        }
        
    }
    if(isset($_POST['submit']))
    {
        $session = $_GET['session'];
        $course_year = $_GET['year'];
        $course_semester = $_GET['semester'];
        for($i=0;$i<count($_POST['teacher_id']);$i++)
        {
            if($_POST['teacher_id'][$i]!=NULL)
            {
                // echo $i."<br />";
                $value = $_POST['teacher_id'][$i];
                $pos_of_comma = strpos($value,",");
                $course_id = substr($value,0,$pos_of_comma);
                $teacher_id = substr($value,$pos_of_comma+1);
                
                $index = array_search($i,$indexing);
                $index_of_registered_course = array_search($course_id,$all_course_id);
                
                if(!empty($index_of_registered_course))
                {
                    
                    // Update query
                    $update_assigned_course = "UPDATE `assigned_course_information` SET  `teacher_id` = '$teacher_id' WHERE `session` = '$session' AND `course_year` = '$course_year' AND `course_semester` = '$course_semester' AND `course_id`= '$course_id' AND `department_id` = '$department_id'";
                    $run_update_assigned_course = mysqli_query($conn, $update_assigned_course);
                }
                else if(!empty($index))
                {
                    // If store prevoiusly stored but now no teacher is selected then update that value according to index.
                    $index = $index-1;
                    $update_assigned_course_indexing = "UPDATE `assigned_course_information` SET  `teacher_id` = '$teacher_id', `course_id`= '$course_id' WHERE `session` = '$session' AND `course_year` = '$course_year' AND `course_semester` = '$course_semester' AND `indexing` = '$i' AND `department_id` = '$department_id'";
                    $run_update_assigned_course_indexing = mysqli_query($conn, $update_assigned_course_indexing);
                }
                else
                {
                    // Insert Qry
                    $insert_assigned_course = "INSERT INTO `assigned_course_information`(`session`, `course_year`, `course_semester`, `course_id`, `teacher_id`,`department_id`,`indexing`) VALUES ('$session','$course_year','$course_semester','$course_id','$teacher_id','$department_id','$i')";
                    $run_insert_assigned_course = mysqli_query($conn, $insert_assigned_course);
                }
            }
            else
            {
                $index = array_search($i,$indexing);
                echo $index;
                if(!empty($index))
                {
                    $index = $index-1;
                    // Update for unassigning query
                    $update_assigned_course = "UPDATE `assigned_course_information` SET `course_id` ='-1', `teacher_id` = '-1' WHERE `session` = '$session' AND `course_year` = '$course_year' AND `course_semester` = '$course_semester' AND `indexing`= '$i' AND `department_id` = '$department_id'";
                    $run_update_assigned_course = mysqli_query($conn, $update_assigned_course);
                }
            }
        }
        ?>
        <script>
            window.alert("Course Assigned Successfully");
            window.location = "view_assign_course_semester_wise.php?session=<?php echo $_GET['session'] ?>&department_id=<?php echo $department_id ?>";
        </script>
        <?php
    }
?>

<?php include('lib/footer.php') ?>
