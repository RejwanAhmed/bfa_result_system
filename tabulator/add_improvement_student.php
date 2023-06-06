<?php include("lib/tabulator_header.php") ?>
<?php include("valid_department_function.php") ?>
<!-- validation -->
<?php 
    if(!isset($_GET['course_id']) || !isset($_GET['course_semester']) || !isset($_GET['department_id']))
    {
        ?>
        <script>
            window.location = "home.php";
        </script>
        <?php 
        exit();
    }
    else if(isset($_GET['course_id']) && isset($_GET['course_semester']) && ($_GET['course_semester']=="1st semester" || $_GET['course_semester']=="2nd semester") )
    {
        $semester = $_GET['course_semester'];
        // Start of Whether an id is valid or not
        $course_id_validation_qry = "SELECT * FROM `course_information` WHERE `id` = '$_GET[course_id]'";
        $course_id_validation_qry_run = mysqli_query($conn, $course_id_validation_qry);
        $course_id_validation_qry_run_res = mysqli_fetch_assoc($course_id_validation_qry_run);
        if($course_id_validation_qry_run_res==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "home.php";
            </script>
            <?php
             exit();
        }
        //End of Whether an id is valid or not
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
    
?>
<!-- Start of Table to display eligible students -->
<?php
     
     // jesob student selected ache tader ke ber kore ana hoyeche
      $select_selection_Y_students = "SELECT `result_id`, `selection`, `total_final_marks` FROM `improvement_result` WHERE `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$_GET[course_semester]' AND `improvement_session` = '$_SESSION[session]' AND `course_id`='$_GET[course_id]' ORDER BY `result_id` ASC";
      
      $run_select_selection_Y_students = mysqli_query($conn, $select_selection_Y_students);
      $select_selection_Y_students_row = mysqli_fetch_all($run_select_selection_Y_students);
    
    function display_content($run)
    {
        include('lib/db_connection.php');
        $num_rows = mysqli_num_rows($run);
        if($num_rows==0)
        {
            echo "<h2 style = 'color: red; text-align:center'>No Student Found For Improvement!!</h2>";
        }
        else 
        {
            ?>
           
            <div class = "table-responsive">
                <form action="" method = "POST">
                    <table class = "table table-bordered table-hover text-center">
                        <tr>
                            <thead class ="thead-light">
                                <th>Exam Session</th>
                                <th>Student Current Session</th>
                                <th>Student Roll</th>
                                <th>Select</th>
                            </thead>
                        </tr>
                        
                        <?php
                        $previous_exam_session = "";
                        $count = 0;
                        global $select_selection_Y_students_row;
                        while($row = mysqli_fetch_assoc($run))
                        {
                            // jesob student age theke selected ache tader selection = Y ache thik taderke ber kore ana hoyeche
                            $exist = 0;
                            $selected = 0;
                           foreach($select_selection_Y_students_row as $key => $val)
                           {
                                
                                if(in_array($row['result_id'], $val) && $val[1]=='Y')
                                {
                                    // jader total_final_marks entry hoyeche taderke selected = 1 dewa hoyeche
                                    if($val[2]>-1)
                                    {
                                       $selected = 1;
                                    }
                                    $exist = 1;
                                    break;
                                }
                           }
                            // sorto dewa hoyeche jate kore amra ek ekta session ke alada alada bg-color dite pare.
                            
                            if($previous_exam_session!=NULL)
                            {
                                if($previous_exam_session!=$row['exam_session'])
                                {
                                    $previous_exam_session = $row['exam_session'];
                                    $count++;
                                }
                            }
                            else 
                            {
                                $previous_exam_session = $row['exam_session'];
                                $count = 1;
                            }
                            ?>
                                <tr 
                                <?php 
                                    if($count%2==0)
                                    {?>
                                        style = "background-color: #343A40; color: white;";
                                    <?php 
                                    }
                                  
                                ?>>
                                    <td><?php echo $row['exam_session']?></td>
                                    <td><?php echo $row['st_session']?></td>
                                    <td><?php echo $row['roll_no']?></td>
                                    <!-- <td><?php echo $row['improvement_eligibility']?></td> -->
                                    <td>
                                    <!-- exist ==1 meaning hocche student ager theke selected ache kina -->
                                    <!-- improvement_eligibility = N hole oi student ke ar select kora jabe na -->
                                    <input type="checkbox" 
                                    
                                    <?php if($exist==1)
                                    {
                                        echo "checked";
                                    }
                                    else if($row['improvement_eligibility']=='N')
                                    {
                                        echo "checked";
                                        ?>
                                        disabled = "disabled";
                                        <?php
                                    }
                                    if($selected == 1)
                                    {
                                        ?>
                                         disabled = "disabled";
                                        <?php 
                                    }
                                    ?> value = "<?php echo $row['result_id'].",".$row['student_id'].",".$row['teacher_id'].",".$row['1st_examinee'].",".$row['2nd_examinee'].",".$row['3rd_examinee'].",".$row['total_final_marks'].",".$row['actual_session'].",".$row['exam_session'].",".$row['3rd_examinee_eligibility']?>" name = "result_student_teacher_id_1st_2nd_3rd_examinee_total_final_marks_actual_session_current_session[]" >
                                    <?php 
                                        if($row['total_improvement_exam']==1 AND $row['improvement_eligibility']=='Y')
                                        {
                                            echo "<p  class = 'font-weight-bold bg-warning text-center mt-2' style = 'color: black'>Elgibile For Improvement Exam (Failed!!)</p>";
                                            echo "<p  class = 'font-weight-bold bg-warning text-center mt-2' style = 'color: black'>Attended Improvement Exam Once</p>";
                                        }
                                        else if($row['total_improvement_exam']==2 AND $row['improvement_eligibility']=='Y')
                                        {
                                            echo "<p  class = 'font-weight-bold bg-warning text-center mt-2' style = 'color: black'>Elgibile For Special Exam (Failed!!)</p>";
                                            echo "<p  class = 'font-weight-bold bg-warning text-center mt-2' style = 'color: black'>Attended Improvement Exam Twice</p>";
                                        }
                                        else if($row['total_improvement_exam']==1 AND $row['improvement_eligibility']=='N')
                                        {
                                            echo "<p class = 'font-weight-bold bg-warning text-center mt-2' style = 'color: black'>Not Eligible For Improvement Exam Anymore!!</p>";
                                        }
                                        else if($row['total_improvement_exam']==2 AND $row['improvement_eligibility']=='N')
                                        {
                                            echo "<p class = 'font-weight-bold bg-warning text-center mt-2' style = 'color: black'>Not Eligible For Improvement Exam Anymore!!</p>";
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
                            <input type="submit" class = "form-control btn" name = "submit" value = "Enter">
                        </div>
                    </div>
                </form>
            </div>
        <?php
        }
    }
    if(isset($_POST['submit']))
    {
        // first e jegula ager theke selected ache ebong jader marks entry hoy nai segular selection = N korbe
        
        $update_selection_Y_students = "UPDATE `improvement_result` SET `selection` = 'N' WHERE `course_id` = '$_GET[course_id]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$semester' AND `improvement_session`='$_SESSION[session]' AND `total_final_marks` = '-1' AND `selection` = 'Y'";
        $run_update_selection_Y_students = mysqli_query($conn, $update_selection_Y_students);
        
        // ekta studento jdi selected hoy tahole if condition run korbe
        
        if(isset($_POST['result_student_teacher_id_1st_2nd_3rd_examinee_total_final_marks_actual_session_current_session']))
        {   
            $course_id = $_GET['course_id'];
            $course_year = $_SESSION['course_year'];
            $course_semester = $semester;
            //kon session er sathe improvement dicche seta select korechi
            $improvement_session = $_SESSION['session'];
            
            $total_selected_id = count($_POST['result_student_teacher_id_1st_2nd_3rd_examinee_total_final_marks_actual_session_current_session']);
            $new_students = 0;
            $exist_students = 0;
            for($i=0;$i<$total_selected_id;$i++)
            {
                $student_info_from_result_table_array = explode(",",$_POST['result_student_teacher_id_1st_2nd_3rd_examinee_total_final_marks_actual_session_current_session'][$i]);
                
                $result_id = $student_info_from_result_table_array[0];
                $student_id = $student_info_from_result_table_array[1];
                $teacher_id = $student_info_from_result_table_array[2];
                $previous_1st_examinee = $student_info_from_result_table_array[3];
                $previous_2nd_examinee = $student_info_from_result_table_array[4];
                $previous_3rd_examinee = $student_info_from_result_table_array[5];
                $previous_total_final_marks = $student_info_from_result_table_array[6];
                $actual_session = $student_info_from_result_table_array[7];
                $current_session = $student_info_from_result_table_array[8];
                $previous_3rd_examinee_eligibility = $student_info_from_result_table_array[9];
                
                $exist = 0;
                // ager theke student selected chilo kina seta search korbe
                foreach($select_selection_Y_students_row as $key => $val)
                {
                     if(in_array($result_id, $val))
                     {
                         $exist = 1;
                         break;
                     }
                }
                // jdi ager theke student selected thake then oitar selection = Y update kore dibe
                // ar na thakle new student add korbe
                if($exist==1)
                {
                    $exist_students++;
                    $update_already_exist_students = "UPDATE `improvement_result` SET `selection` = 'Y' WHERE `result_id` = '$result_id' AND `total_final_marks` = '-1'";
                    $run_update_already_exist_students = mysqli_query($conn, $update_already_exist_students);
                }
                else 
                {
                    $new_students++;
                    $insert_improvement_result = "INSERT INTO `improvement_result` (`actual_session`, `current_session`, `result_id`, `student_id`, `teacher_id`, `course_id`, `department_id`, `course_year`, `course_semester`, `improvement_session`, `previous_1st_examinee`, `previous_2nd_examinee`, `previous_3rd_examinee`, `previous_3rd_examinee_eligibility`, `previous_total_final_marks`, `1st_examinee`, `2nd_examinee`, `3rd_examinee`, `3rd_examinee_eligibility`, `total_final_marks`,`selection`) VALUES ('$actual_session','$current_session','$result_id','$student_id','$teacher_id','$course_id','$department_id','$course_year','$course_semester','$improvement_session','$previous_1st_examinee','$previous_2nd_examinee','$previous_3rd_examinee','$previous_3rd_examinee_eligibility','$previous_total_final_marks','-1','-1','-1','0','-1','Y')";
                    $run_insert_improvement_result = mysqli_query($conn, $insert_improvement_result);
                }     
            }
            if($new_students>0)
            {
                ?>
                <script>
                    window.alert("Student Added Successfully");
                    window.location = "improvement_subjects.php?course_semester=<?php echo $semester?>&department_id=<?php echo $department_id ?>";
                </script>
                <?php
            }
            else if($exist_students>0)
            {
                ?>
                <script>
                    window.alert("Previous Selected Student Added");
                    window.location = "improvement_subjects.php?course_semester=<?php echo $semester?>&department_id=<?php echo $department_id ?>";
                </script>
                <?php
            }
            else 
            {
                ?>
                <script>
                    window.alert("No New Student Added");
                    window.location = "improvement_subjects.php?course_semester=<?php echo $semester?>&department_id=<?php echo $department_id ?>";
                </script>
                <?php
            }
        }
        else 
        {
            ?>
            <script>
                window.alert("No Student has been selected");
                window.location = "improvement_subjects.php?course_semester=<?php echo $semester?>&department_id=<?php echo $department_id ?>";
            </script>
            <?php
        }
        
    }
?>

<!-- End of Table to display eligible students -->


<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header form_header text-center">
                    <h2><?php echo "Eligible Students For Improvement Of ($_SESSION[course_year], $semester)"?></h2>
                    <h4 class = "text-center text-warning">Department/Stream: <?php echo $department_name ?></h4>
                    <h4 class = "text-center text-secondary bg-white "><?php
                    if($course_id_validation_qry_run_res['course_code']!="")
                    {
                        echo "Course Code: $course_id_validation_qry_run_res[course_code], ";
                    }
                    if($course_id_validation_qry_run_res['course_title']=="")
                    {
                        echo "Course Title: Viva-Voce, ";
                    }
                    else
                    {
                        echo "Course Title: $course_id_validation_qry_run_res[course_title], ";
                    }
                    echo "Course Credit: $course_id_validation_qry_run_res[course_credit]" ?></h4>
                    <p><i>If You Don't Find Student, Please Check Whether Previous Sessions Result Has Been Published Or Not!!</i></p>
                </div>
                <div class="card-body table-responsive">
                   <form action="" method = "POST">
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-4 col-12 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class = "input-group-text"><i class = "fas fa-search"></i></span>
                                    </div>
                                    <select class = "form-control" name="search_session_wise">
                                        <option value="" >Search Current Session Wise</option>
                                        <?php
    											$c = 2006;
    						                    // jei session ache tar ager porjonto student ra improve dite parbe tai explode kore current session porjonto newa hoyeche
                                                $current_session = explode('-',$_SESSION['session']);
    											 for($i=$c; $i<$current_session[0]; $i++)
    											 {
    												 $r = $i + 1;
                                                     $session= $i."-".$r;
    												 echo "<option value='$session'>";
                                                     echo $session;
                                                     echo "</option>";
    											 }
    										?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-4 col-12 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class = "input-group-text"><i class = "fas fa-search"></i></span>
                                    </div>
                                    <input type="text" class = "form-control" name = "search_roll_wise" id = "search_roll_wise" placeholder="Search By Roll Number....." value = "<?php if(isset($_POST['show_all']))
                                    {
                                        echo "";
                                    }
                                    else if(isset($_POST['search_roll_wise']))
                                    {
                                        echo "$_POST[search_roll_wise]";
                                    }?>" autocomplete="off">
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-4 col-12 text-center mb-3 ">
                                <input type="submit" name="search" value="Search" class = "form-control btn">
                            </div>
                            
                            <div class="col-lg-4 col-md-4  col-12 text-center mb-3 ">
                                <input type="submit" class ="form-control btn" name = "show_all" value = "Show All">
                            </div>
                        </div>
                        
                   </form>

                    <?php 
                        // Start of query for search button
                        if(isset($_POST['search']))
                        {
                            $current_session = $_POST['search_session_wise'];
                            $roll = $_POST['search_roll_wise'];
                            $count = 1;
                            // exam_session = jei session er sathe exam ta dise
                            // st_session = student er current session
                            $search_qry = " SELECT r.id as result_id, r.student_id, r.teacher_id, r.course_id, r.current_session as exam_session, r.actual_session, r.1st_examinee, r.2nd_examinee, r.3rd_examinee, r.3rd_examinee_eligibility, r.total_final_marks, r.total_improvement_exam, r.improvement_eligibility, st.roll_no, st.current_session as st_session FROM `result` as r INNER JOIN `student_information` as st ON r.student_id = st.id WHERE  r.course_id = '$_GET[course_id]' AND r.improvement_eligibility = 'Y' AND r.result_validation = 'v' AND r.result_status = '1'";
                            if($current_session!=NULL)
                            {
                                $count++;
                                $search_qry.= " AND st.current_session = '$current_session'";
                            }
                            if($roll!=NULL)
                            {
                                $search_qry.=" AND st.roll_no = '$roll'";
                                $count++;
                            }
                            
                            if($count==1)
                            {
                                ?>
                                <script>
                                    window.alert("Please Select At least 1 field");
                                </script>
                                <?php
                            }
                            $run_search_qry = mysqli_query($conn, $search_qry);
                            
                            ?>
                            <div class="col-lg-12 col-12">
                                <!-- Call display_content function -->
                                <?php 
                                    display_content($run_search_qry); 
                                ?>
                            </div>
                            <?php
                        }
                        // End of query for search button
                        else
                        {
                            ?>
                            <div class="col-lg-12 col-12">
                                <?php
                                // eikhane student er current session ta st_session hisabe nisi ar oi student jei session er sathe exam dise tar current session exam_session hisabe dhorsi.
                                $session = $_SESSION['session'];
                                $search_qry  = "SELECT r.id as result_id, r.student_id, r.teacher_id, r.course_id,r.current_session as exam_session, r.actual_session, r.1st_examinee, r.2nd_examinee, r.3rd_examinee, r.3rd_examinee_eligibility, r.total_final_marks, r.total_improvement_exam, r.improvement_eligibility, st.roll_no, st.current_session as st_session FROM `result` as r INNER JOIN `student_information` as st ON r.student_id = st.id WHERE r.course_id = '$_GET[course_id]' AND (r.improvement_eligibility = 'Y' OR r.total_improvement_exam != '0') AND r.current_session < '$session' AND r.result_validation = 'v' AND r.result_status = '1'";
                                $run_search_qry = mysqli_query($conn, $search_qry);
                                display_content($run_search_qry);
                                ?>
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include("lib/tabulator_footer.php") ?>
