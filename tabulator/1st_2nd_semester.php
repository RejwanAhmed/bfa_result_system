<?php include("lib/tabulator_header.php") ?>
<?php include("valid_department_function.php") ?>
<?php include("semester_wise_cgpa_calculation.php") ?>
<?php 
    // validation
    if(!isset($_GET['semester']) || !isset($_GET['department_id']))
    {
        ?>
            <script>
                window.location = "home.php";
            </script>
        <?php
        exit();
    }
    else if(isset($_GET['semester']) && $_GET['semester']=='1st semester')
    {
        $course_semester = $_GET['semester'];
    }
    else if(isset($_GET['semester']) && $_GET['semester']=='2nd semester')
    {
        $course_semester = $_GET['semester'];
    }
    else
    {
        ?>
            <script>
                window.alert("Invalid Semester");
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
    
    // select distinct id from result so that we can find which courses marks has been entered
    // push the course_id into array so that we can match
    $select_from_result = "SELECT DISTINCT `course_id` FROM `result` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$_GET[semester]' ORDER BY `course_id` ASC";
    $run_select_from_result = mysqli_query($conn, $select_from_result);
    $course_id_from_result = array();
    array_push($course_id_from_result,-1);
    while($row = mysqli_fetch_assoc($run_select_from_result))
    {
        array_push($course_id_from_result,$row['course_id']);
    }
    // end of select distinct id from result
    
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header form_header text-center">
                    <h2><?php echo " Assigned Course List ($_SESSION[session], $_SESSION[course_year], $course_semester) " ?></h2>
                    <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
                    <h4 class = "text-danger text-center bg-white ">Before Starting Result Processing Please Check Whether All Subjects Are Included Or Not</h4>
                    
                    <?php 
                        //  jdi 3rd_examinee_eligibility = 1 hoy and 3rd_examinee er marks na dewa thake(that means 3rd_examinee = -1 hoy) tahole nicher query ta run korbe and error show korbe.
                        $count_3rd_examinee_eligibility = "SELECT count(`id`) as `total_id` FROM `result` WHERE `3rd_examinee_eligibility`='1' AND `3rd_examinee` = '-1' AND `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' AND `department_id` = '$department_id'";
                        $run_count_3rd_examinee_eligibility = mysqli_query($conn, $count_3rd_examinee_eligibility);
                        $res_count_3rd_examinee_eligibility = mysqli_fetch_assoc($run_count_3rd_examinee_eligibility);
                        if($res_count_3rd_examinee_eligibility['total_id']>0)
                        {
                            ?>
                                <h3 class = "text-white text-center bg-danger">3rd Examiner Marks Needed In Some Courses!!</h3>
                            <?php 
                        }
                    ?>
                </div>
                <div class="card-body table-responsive">
                    <?php
                        // Select From semester_cgpa table jate amra check korte pari je oi session er (2015-16) oi year (1st year) oi semester (1st semester) er result ache naki 
                        
                        // Result available ache kina or download kora jabe kina tar jonno lagbe ei query.
                        // maximum query te department_id use kori nai kintu eikhane department_id use korsi karon alada alada deprartment er result alada alada vabe calculate hobe.
                        $select_semester_cgpa = "SELECT `id`, `previous_cgpa`, `current_cgpa` FROM `semester_cgpa` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' AND `department_id` = '$department_id'";
                        
                        // $select_semester_cgpa = "SELECT `id`, `previous_cgpa`, `current_cgpa` FROM `semester_cgpa` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' AND `department_id` = '$department_id'";
                        $run_select_semester_cgpa = mysqli_query($conn, $select_semester_cgpa);
                        $num_rows_semster_cgpa = mysqli_num_rows($run_select_semester_cgpa);
                        
                        // Stat: Result Turn off and turn on  ebong calculation button er kaj korar jonno lagbe ei query
                        
                        $result_validation_qry = "SELECT DISTINCT `course_id` FROM `result` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' AND `total_final_marks` !='-1'";
                        
                        // $result_validation_qry = "SELECT DISTINCT `course_id` FROM `result` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' AND `department_id` = '$department_id' AND `total_final_marks` !='-1'";
                        $run_result_validation_qry = mysqli_query($conn,$result_validation_qry);
                        $num_rows_result = mysqli_num_rows($run_result_validation_qry);
                        
                        
                        $select_assigned_course_info = "SELECT c.course_code as course_code, c.course_title as course_title, c.course_type as course_type, ac.course_id, ac.teacher_id, c.department_id, ac.verification, t.id as teacher_id, t.name as teacher_name FROM `assigned_course_information` as ac INNER JOIN `course_information` as c ON ac.course_id = c.id LEFT JOIN `teacher_information` as t ON ac.teacher_id = t.id WHERE ac.session = '$_SESSION[session]' AND ac.course_year = '$_SESSION[course_year]' AND ac.course_semester = '$course_semester' AND ac.teacher_id != '-1' AND ac.course_id != '-1' ORDER BY `course_id` ASC";
                        
                        // $select_assigned_course_info = "SELECT c.course_code as course_code, c.course_title as course_title, c.course_type as course_type, ac.course_id, ac.teacher_id, ac.verification, t.id as teacher_id, t.name as teacher_name FROM `assigned_course_information` as ac INNER JOIN `course_information` as c ON ac.course_id = c.id LEFT JOIN `teacher_information` as t ON ac.teacher_id = t.id WHERE ac.session = '$_SESSION[session]' AND ac.course_year = '$_SESSION[course_year]' AND ac.course_semester = '$course_semester' AND ac.teacher_id != '-1' AND ac.course_id != '-1' AND ac.department_id = '$department_id' ORDER BY `course_id` ASC";

                        $run_select_assigned_course_info = mysqli_query($conn, $select_assigned_course_info);

                        $num_rows_assigned_course = mysqli_num_rows($run_select_assigned_course_info);
                        
                        // Find total number of rows of result status = 1 to turn off the result
                        $count_result_status = "SELECT count(`id`) as `total_id` FROM `result` WHERE `result_status`='1' AND `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' AND `department_id` = '$department_id'";
                        
                        // $count_result_status = "SELECT count(`id`) as `total_id` FROM `result` WHERE `result_status`='1' AND `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' AND `department_id` = '$department_id'";
                        
                        $run_count_result_status = mysqli_query($conn, $count_result_status);
                        $res_count_result_status = mysqli_fetch_assoc($run_count_result_status);
                        
                    ?>
                    <form action="" method = "POST">
                    
                        <!-- PDF Generate Button -->
                        <button type = "button" <?php if($num_rows_semster_cgpa==0)
                        {
                            echo "disabled";
                        } ?> onclick = "generate_pdf()" class = "btn mb-2"><i class="fas fa-file-download"></i> <?php if($num_rows_semster_cgpa==0)
                        {
                            echo "Result Not Available";
                        }
                        else
                        {
                            echo "Download Result";
                        } ?></button>
                        <!-- End of PDF Generate Button -->
                        
                        <!-- Calculate Result Button -->
                         
                        <!-- Joto Gula course assign hoise ebong joto gula course er marks entry hoise duita jodi soman hoy tahole ei calculate result button active hobe -->
                        <!-- jdi ekbar semester_cgpa calculated hoye jay tkhn calcualte result button ar dorkar porbe na tai show hobe na  -->
                        <!-- calculate result button click korle calculate_cgpa_semester_wise.php er function call hobe -->
                        <button type = "submit" <?php 
                            if($num_rows_result!=$num_rows_assigned_course || $num_rows_result ==0 || $res_count_result_status['total_id']>0 || $num_rows_semster_cgpa!=0)   
                            {
                                echo "hidden";
                            } ?> name = "calculate_result" class = "btn mb-2"><i class=" fas fa-calculator"></i> Calculate Result
                        </button>
                        <!-- End of Calculate Result Button -->
                       
                        <!-- Publish Result Button -->
                        <button  type = "button" <?php if($num_rows_result==0 || $num_rows_result!=$num_rows_assigned_course)   
                        {
                            echo "hidden";
                        }
                        if($num_rows_result!=0 && $res_count_result_status['total_id']>0)
                        {
                            echo "disabled";
                        }
                        ?> class = "btn mb-2" onclick="publish_result_to_turn_off_result()"><i class=" fa fa-power-off"></i> <?php if($num_rows_result!=0 && $res_count_result_status['total_id']==0)
                        {
                            echo "Publish Result";
                        }
                        else 
                        {
                            echo "Result Already Published";
                        } ?></button>
                        <!-- End Of Publish Result Button -->
                            
                        
                        <!-- start of result processing er jonno button -->
                        <!-- age dekhbo je 1st_sem_status or 2nd_sem_status exam_committee_information tabler value 0 ache naki. 0 thakle start_processing button show korbe that means ekhno processing start hoy nai. 1 thakle Pause Processing Show korbe that means eita ke pause kora jabe tkhn admin course assign or student add ba remove korte parbe. 2 thakle Resume Processing Show korbe that means abr processing resume hoye jabe.-->
                        <?php 
                            $_1st_2nd_sem_status_qry = "SELECT * FROM `exam_committee_information` WHERE `id` = '$_SESSION[exam_committee_id]'";
                            $run_1st_2nd_sem_status_qry = mysqli_query($conn, $_1st_2nd_sem_status_qry);
                            $res_1st_2nd_sem_status_qry = mysqli_fetch_assoc($run_1st_2nd_sem_status_qry);
                            
                            // 1st semester er jonno alada ebong 2nd semester er jonno alada
                            if($course_semester=="1st semester")
                            {
                                // ektao jdi course assign kora na thake that means $num_rows_assigned_course =0 hoy tahole to start_processing button dekhanor dorkar nai
                                if($res_1st_2nd_sem_status_qry['1st_sem_status']==0 && $num_rows_assigned_course!=0)
                                {
                                    ?>
                                    <a href="start_result_processing.php?course_semester=<?php echo $course_semester ?>&department_id=<?php echo $department_id ?>" class = "btn mb-2 btn-primary">Start Processing</a>
                                    <?php 
                                }
                                
                                else if($res_1st_2nd_sem_status_qry['1st_sem_status']==1 && $num_rows_assigned_course!=0)
                                {
                                    ?>
                                    <a href="pause_resume_result_processing.php?course_semester=<?php echo $course_semester ?>&committee_status=1&department_id=<?php echo $department_id ?>" class = "btn mb-2 btn-primary">Pause Processing</a>
                                    <?php 
                                }
                                else if($res_1st_2nd_sem_status_qry['1st_sem_status']==2 && $num_rows_assigned_course!=0)
                                {
                                    ?>
                                    <a href="pause_resume_result_processing.php?course_semester=<?php echo $course_semester ?>&committee_status=2&department_id=<?php echo $department_id ?>" class = "btn mb-2 btn-primary">Resume Processing</a>
                                    <?php 
                                }
                            }
                            else if($course_semester=="2nd semester")
                            {
                                // ektao jdi course assign kora na thake that means $num_rows_assigned_course =0 hoy tahole to start_processing button dekhanor dorkar nai  
                                if($res_1st_2nd_sem_status_qry['2nd_sem_status']==0 && $num_rows_assigned_course!=0)
                                {
                                    ?>
                                    <a href="start_result_processing.php?course_semester=<?php echo $course_semester ?>&department_id=<?php echo $department_id ?>" class = "btn mb-2 btn-primary">Start Processing</a>
                                    <?php 
                                }
                                else if($res_1st_2nd_sem_status_qry['2nd_sem_status']==1 && $num_rows_assigned_course!=0)
                                {
                                    ?>
                                    <a href="pause_resume_result_processing.php?course_semester=<?php echo $course_semester ?>&committee_status=1&department_id=<?php echo $department_id ?>" class = "btn mb-2 btn-primary">Pause Processing</a>
                                    <?php 
                                }
                                else if($res_1st_2nd_sem_status_qry['2nd_sem_status']==2 && $num_rows_assigned_course!=0)
                                {
                                    ?>
                                    <a href="pause_resume_result_processing.php?course_semester=<?php echo $course_semester ?>&committee_status=2&department_id=<?php echo $department_id ?>" class = "btn mb-2 btn-primary">Resume Processing</a>
                                    <?php 
                                }
                            }
                            
                        ?>
                        <!-- end of result processing er jonno button -->
                        
                        <!-- Start: Finish Processing er jonno button -->
                        
                        <!-- jekoita course assigne kora hoise ebong je course gular internal makrs entry hoise (mane internal marks!=-1) ekta particular session (2015-2016) er particular year(1st year) er particular semester(1st semester) er duita jdi soman hoy tahole ebong je semester (jemon 1st semestr) er result processing korchi tar col (1st_sem_status) er value 1 thake tahole Finish Result Processing Button Dekhabe -->
                        <?php 
                            // internal marks sob courser hoye gelei finish processing button show korabo jate student der ke free kora jay. onno session e move or onno session theke ei session e readd korar jonno.
                            $result_validation_qry_for_finish_button = "SELECT DISTINCT `course_id` FROM `result` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' AND `total_internal` !='-1'";
                            
                            // $result_validation_qry_for_finish_button = "SELECT DISTINCT `course_id` FROM `result` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' AND `department_id` = '$department_id' AND `total_internal` !='-1'";
                             
                            $run_result_validation_qry_for_finish_button = mysqli_query($conn,$result_validation_qry_for_finish_button);
                            $num_rows_result_for_finish_button = mysqli_num_rows($run_result_validation_qry_for_finish_button);
                           
                            if($course_semester=="1st semester" && $res_1st_2nd_sem_status_qry['1st_sem_status']==1 && $num_rows_assigned_course==$num_rows_result_for_finish_button)
                            {
                                ?>
                                <button type = "submit" class = "btn mb-2 btn-primary" name = "finish_result_processing">Finish Processing</button>
                                <?php
                            }
                            else if($course_semester=="2nd semester" && $res_1st_2nd_sem_status_qry['2nd_sem_status']==1 && $num_rows_assigned_course==$num_rows_result_for_finish_button)
                            {
                                ?>
                                <button type = "submit" class = "btn mb-2 btn-primary" name = "finish_result_processing">Finish Processing</button>
                                <?php
                            }
                        ?>
                        <!-- Finish: Finish Processing er jonno button -->
                        
                        <!-- Start: Button For Improvement Exam -->
                        
                        <a href="selected_subject_and_students.php?course_semester=<?php echo $course_semester?>&department_id=<?php echo $department_id ?>" class = "btn mb-2 bg-danger">Improvement Exam</a>
                        
                        <!-- End: Button For Improvement Exam -->
                        
                        <!-- Start: Button For Student List of 3rd_examiner marks -->
                        <a href="student_list_of_3rd_examiner_pdf.php?course_semester=<?php echo $course_semester?>&department_id=<?php echo $department_id ?>" class = "btn mb-2 bg-primary"> <span><i class="fas fa-file-pdf"></i></span> PDF: Students List of 3rd Examiner</a>
                        <!-- End: Button For Student List of 3rd_examiner marks -->
                        
                    </form>
                    <!-- To Show Some Errors -->
                    <h3 id = "error_under_buttons" class = "text-white text-center bg-danger"></h3>
                    <table class = "table  table-bordered table-hover text-center ">
                        <?php

                        if($num_rows_assigned_course==0)
                        {
                            echo "<h2 class = 'text-danger text-center'>No Course Has Been Assigned Yet</h2>";
                        }
                        else
                        {
                            ?>
                            <tr>
                                <thead class ="thead-light">
                                    <th>Course Code</th>
                                    <th>Course Title</th>
                                    <th>Course Teacher</th>
                                    <th>Enter Internal Marks</th>
                                    <th>View Internal Marks</th>
                                    <th>Enter Final Marks</th>
                                    <th>View Final Marks</th>
                                    <th></th>
                                </thead>
                            </tr>
                            <?php
                            while($row = mysqli_fetch_assoc($run_select_assigned_course_info))
                            {
                                if($row['department_id']==$department_id)
                                {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php 
                                            if($row['course_code']==NULL)
                                            {
                                                echo "--";
                                            }
                                            else
                                            {
                                                echo $row['course_code'];    
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                           if($row['course_type']=="Viva-Voce" && $row['course_title']=="")
                                           {
                                               echo "Viva-Voce";
                                           }
                                           else if($row['course_title']=="")
                                           {
                                               echo "--";
                                           }
                                           else
                                           {
                                               echo $row['course_title'];
                                           }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if($row['teacher_name']=="")
                                            {
                                                echo "--";
                                            }
                                            else
                                            {
                                                echo $row['teacher_name'];    
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                // course_type != Viva-Voce na hole internal marks er option dekhabe. karon viva-voce er kono internal marks nai.
                                                if($row['course_type']!="Viva-Voce")
                                                {
                                                    ?>
                                                    <button class="link_btn text-center">
                                                        <?php 
                                                            if(in_array($row['course_id'],$course_id_from_result))
                                                            {
                                                                ?>
                                                                <a <?php if($res_count_result_status['total_id']>0)
                                                                {
                                                                    ?>
                                                                        style = "pointer-events:none;"
                                                                    <?php 
                                                                }
                                                                ?> href="update_internal_marks.php?course_id=<?php echo $row['course_id'] ?>&course_semester=<?php echo $course_semester ?>&teacher_id=<?php echo $row['teacher_id'] ?>&department_id=<?php echo $department_id ?>"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span> Update Internal Marks</a>
                                                                <?php 
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                <a <?php if($res_count_result_status['total_id']>0)
                                                                {
                                                                    ?>
                                                                        style = "pointer-events:none;"
                                                                    <?php 
                                                                }
                                                                ?> href="add_internal_marks.php?course_id=<?php echo $row['course_id'] ?>&course_semester=<?php echo $course_semester ?>&teacher_id=<?php echo $row['teacher_id'] ?>&department_id=<?php echo $department_id ?>"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span> Add Internal Marks</a>
                                                                <?php 
                                                            }
                                                        ?>  
                                                    </button>
                                                    <?php 
                                                }
                                            ?>
                                           
                                        </td>
            
                                        <td>
                                            <?php
                                                // course_type != Viva-Voce na hole internal marks pdf er option dekhabe. karon viva-voce er kono internal marks nai.
                                                if($row['course_type']!='Viva-Voce')
                                                {
                                                    ?>
                                                    <button class = "link_btn text-center">
                                                        <a href="course_wise_internal_marks_pdf.php?course_id=<?php echo $row['course_id'] ?>&course_semester=<?php echo $course_semester ?>&teacher_id=<?php echo $row['teacher_id'] ?>&department_id=<?php echo $department_id ?>"><span class = "sidebar-icon"><i class="fas fa-file-pdf"></i></span> Internal Marks PDF</a>
                                                    </button>
                                                    <?php 
                                                }
                                            ?>
                                            
                                        </td>
                                        
                                        <td>
                                            <button  class = "link_btn text-center" >
                                                <?php 
                                                if($row['course_type'] == "Viva-Voce")
                                                {
                                                    ?>
                                                    <a <?php if($res_count_result_status['total_id']>0)
                                                    {
                                                        ?>
                                                            style = "pointer-events:none;"
                                                        <?php 
                                                    }
                                                    ?> href="enter_final_marks_viva_voce.php?course_semester=<?php echo $course_semester ?>&course_id=<?php echo $row['course_id'] ?>&department_id=<?php echo $department_id ?>"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span> Add Final Marks</a>
                                                    <?php 
                                                }
                                                else
                                                {
                                                    ?>
                                                    <a <?php if($res_count_result_status['total_id']>0)
                                                    {
                                                        ?>
                                                            style = "pointer-events:none;"
                                                        <?php 
                                                    }
                                                    ?> href="enter_final_marks.php?course_semester=<?php echo $course_semester ?>&course_id=<?php echo $row['course_id'] ?>&department_id=<?php echo $department_id ?>"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span> Add Final Marks</a>
                                                    <?php
                                                }
                                                ?>
                                                
                                            </button>
                                        </td>
    
                                        <td>
                                            <button class = "link_btn" >
                                                <?php 
                                                    if($row['course_type'] == "Viva-Voce")
                                                    {
                                                        ?>
                                                        <a href="view_final_marks_viva_voce.php?course_semester=<?php echo $course_semester?>&course_id=<?php echo $row['course_id'] ?>&department_id=<?php echo $department_id ?>"><span><i class = "fas fa-eye"></i></span> View Final Marks</a>
                                                        <?php 
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <a href="view_final_marks.php?course_semester=<?php echo $course_semester?>&course_id=<?php echo $row['course_id'] ?>&department_id=<?php echo $department_id ?>"><span><i class = "fas fa-eye"></i></span> View Final Marks</a>
                                                        <?php 
                                                    }
                                                ?>
                                                
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                
                            }
                            ?>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function generate_pdf()
    {
        window.location = "final_result_pdf.php?course_semester=<?php echo $course_semester ?>&department_id=<?php echo $department_id ?>";
    }
   
</script>
<script>
    function publish_result_to_turn_off_result()
    {
        var del = confirm('Sure Want To Pulish Result? You Can Not Change It Further! If Not Then Please Cancel');
        if(del == true)
        {
            window.location = "publish_result.php?course_semester=<?php echo $_GET['semester'] ?>&department_id=<?php echo $department_id ?>";
        }
    }
</script>

<?php 
    
    if(isset($_POST['calculate_result']))
    {
        // result jdi already calculate hoye jay tkhn calculate result button onno vabe show koraya jdi click kora hoy tkhn ei error show korbe.
        if($num_rows_semster_cgpa>0)
        {
            ?>
                <script>
                    window.alert("Result Already Calculated!!");
                    window.location = "1st_2nd_semester.php?semester=<?php echo $course_semester?>&department_id=<?php echo $department_id ?>"
                </script>
            <?php 
            exit();
        }
        // Start: result calculate korar purbe obossoi check korte hobe je Finish Processing Button Ta Click hoyse naki. 
        if($course_semester=="1st semester")
        {
            if($res_1st_2nd_sem_status_qry['1st_sem_status']!=3)
            {
                ?>
                    <script>
                        document.getElementById('error_under_buttons').innerHTML = "Please First Finish Result Processing By Clicking On Finish Processing Button!!";
                    </script>
                <?php 
                exit();
            }
        }
        else if($course_semester=="2nd semester")
        {
            if($res_1st_2nd_sem_status_qry['2nd_sem_status']!=3)
            {
                ?>
                    <script>
                        document.getElementById('error_under_buttons').innerHTML = "Please First Finish Result Processing By Clicking On Finish Processing Button!!";
                    </script>
                <?php 
                exit();
            }
        }
        // End: result calculate korar purbe obossoi check korte hobe je Finish Processing Button Ta Click hoyse naki.
        
        // result calculate korar age check kore nibo je 3rd_examiner er marks dewar dorkar ache naki. thakle error show korbo. otherwise calculate korte dibo. 3rd_examinee_eligibility er jonno query ta agei kore felsi.
        if($res_count_3rd_examinee_eligibility['total_id']>0)
        {
            ?>
                <script>
                    window.alert("You Can't Calculate Result. Some Coursed Needed 3rd Examiner Marks!");
                </script>
            <?php
            exit();
        }
        
        // Start: kono ekta year(2nd year), ekta semester (1st semester) er result calculate korar age check korbo je tar previous semester (mane 1st year 2nd semester) er result semester_cgpa table e ache naki.
        $check_semester="";
        $check_year="";
        if($_SESSION['course_year']=="1st year")
        {
            if($_GET['semester'] =="2nd semester")
            {
                $check_year = "1st year";
                $check_semester = "1st semester";
            }
        }
        else if($_SESSION['course_year']=="2nd year")
        {
            if($_GET['semester'] =="1st semester")
            {
                $check_year = "1st year";
                $check_semester = "2nd semester";
            }
            else if($_GET['semester'] =="2nd semester")
            {
                $check_year = "2nd year";
                $check_semester = "1st semester";
            }
        }
        else if($_SESSION['course_year']=="3rd year")
        {
            if($_GET['semester'] =="1st semester")
            {
                $check_year = "2nd year";
                $check_semester = "2nd semester";
            }
            else if($_GET['semester'] =="2nd semester")
            {
                $check_year = "3rd year";
                $check_semester = "1st semester";
            }
        }
        else if($_SESSION['course_year']=="4th year")
        {
            if($_GET['semester'] =="1st semester")
            {
                $check_year = "3rd year";
                $check_semester = "2nd semester";
            }
            else if($_GET['semester'] =="2nd semester")
            {
                $check_year = "4th year";
                $check_semester = "1st semester";
            }
        }
        // 1st year and 1st semester er jonno null diye rakhsi. jdi 1st year, 1st semester hoy tahole $check_semester and $check_year duitai null hobe. tkhn ar validation check korar dorkar nai.
        if($check_semester!="" AND $check_year!="")
        {
            $check_semester_cgpa = "SELECT COUNT(`id`) as `total_cgpa_id` FROM `semester_cgpa` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$check_year' AND `course_semester` = '$check_semester' AND `cgpa_validation` = 'v'";
            // $check_semester_cgpa = "SELECT COUNT(`id`) as `total_cgpa_id` FROM `semester_cgpa` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$check_year' AND `course_semester` = '$check_semester' AND `department_id` = '$department_id' AND `cgpa_validation` = 'v'";
            $run_check_semester_cgpa = mysqli_query($conn, $check_semester_cgpa);
            $total_results_semester_cgpa = mysqli_fetch_assoc($run_check_semester_cgpa);
            if($total_results_semester_cgpa['total_cgpa_id']==0)
            {
                ?>
                    <script>
                        document.getElementById('error_under_buttons').innerHTML = "Previous Results Are Not Calculated!!";
                    </script>
                <?php 
                exit();
            }
        }
        // End: kono ekta year(2nd year), ekta semester (1st semester) er result calculate korar age check korbo je tar previous semester (mane 1st year 2nd semester) er result semester_cgpa table e ache naki.        
        
        $session = $_SESSION['session'];
        $course_year = $_SESSION['course_year'];
        $course_semester = $_GET['semester'];
        // semester_cgpa table theke data agei ana hoyeche
        // card-body er prothom query ta hocche cgpa_semester er query oita eikhane use kora hobe
        // oi query ta use korbo semester_cgpa table e ei session(2015-16) ei year(1st year) ei semester(1st semester) er data ache kina. data thakle update korte hobe na thakle insert korte hobe.
        if($num_rows_semster_cgpa==0)
        {
            // get cgpa, student_id, actual_session, current_session from semester_wise_cgpa_calculation function which is in semester_wise_cgpa_calculation.php page
            $stdnt_id_actual_session_current_session_cgpa = semester_wise_cgpa_calculation($course_semester, $department_id);
            $len = sizeof($stdnt_id_actual_session_current_session_cgpa[0]);
        
           $total_each_semester_credit = array_sum($stdnt_id_actual_session_current_session_cgpa[9]);
           
            // Insert into semester_cgpa table
            $insert_semester_cgpa = "INSERT INTO `semester_cgpa`(`student_id`, `department_id`, `actual_session`, `current_session`, `course_year`, `course_semester`, `semester_total_credit`, `previous_cgpa`, `current_cgpa`, `failed_course_id`, `cgpa_validation`) VALUES ";
            for($i=0;$i<$len;$i++)
            {
                $stdnt_id = $stdnt_id_actual_session_current_session_cgpa[0][$i];
                $actual_session = $stdnt_id_actual_session_current_session_cgpa[1][$i];
                $current_session = $stdnt_id_actual_session_current_session_cgpa[2][$i];
                $previous_cgpa = $stdnt_id_actual_session_current_session_cgpa[3][$i];
                $current_cgpa = $previous_cgpa;
                $failed_course_id = $stdnt_id_actual_session_current_session_cgpa[12][$i];
                if($i==$len-1)
                {
                    $insert_semester_cgpa.= "('$stdnt_id','$department_id','$actual_session','$current_session','$course_year','$course_semester','$total_each_semester_credit','$previous_cgpa','$current_cgpa','$failed_course_id','v')";
                }
                else 
                {
                    $insert_semester_cgpa.= "('$stdnt_id','$department_id','$actual_session','$current_session','$course_year','$course_semester','$total_each_semester_credit','$previous_cgpa','$current_cgpa','$failed_course_id','v'),";
                }
                
                // jdi ager kono data semester_table e thake mane data jdi duplicate hoy tahole ager data invalid kore dite hobe. Ex: 14-15 session er ekta student 1st year er exam dile tkhn tar 1st semester and second semester er result oi session er sathe ache semester_cgpa table e. ekhn se re add niye 15-16 er sathe abr first year er xm dilo tkhn tar ager data invalid kore dite hobe.
                
                // update cgpa_validation column in semester_cgpa table
                if($actual_session!=$current_session)
                {
                        
                    // ekoi result 2bar ba 3 bar entry howar agei ager result invalid kore dibe.
                    $update_cgpa_validation_col = "UPDATE `semester_cgpa` SET `cgpa_validation` = 'i' WHERE `student_id` = '$stdnt_id' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' AND `cgpa_validation` = 'v'";
                    $run_update_cgpa_validation_col = mysqli_query($conn, $update_cgpa_validation_col);
                    
                }
            }
            $run_insert_semester_cgpa = mysqli_query($conn, $insert_semester_cgpa);
        }
        else
        {
            // get cgpa, student_id, actual_session, current_session from semester_wise_cgpa_calculation function which is in semester_wise_cgpa_calculation.php page
            $stdnt_id_actual_session_current_session_cgpa = semester_wise_cgpa_calculation($course_semester, $department_id);
            $len = sizeof($stdnt_id_actual_session_current_session_cgpa[0]);
            
            $total_each_semester_credit = array_sum($stdnt_id_actual_session_current_session_cgpa[9]);
            
            for($i=0;$i<$len;$i++)
            {
                $stdnt_id = $stdnt_id_actual_session_current_session_cgpa[0][$i];
                $actual_session = $stdnt_id_actual_session_current_session_cgpa[1][$i];
                $current_session = $stdnt_id_actual_session_current_session_cgpa[2][$i];
                $previous_cgpa = $stdnt_id_actual_session_current_session_cgpa[3][$i];
                $current_cgpa = $previous_cgpa;
                $failed_course_id = $stdnt_id_actual_session_current_session_cgpa[12][$i];
                
                $update_semester_cgpa = "UPDATE `semester_cgpa` SET `previous_cgpa` = '$previous_cgpa', `current_cgpa` = '$current_cgpa', `semester_total_credit` = '$total_each_semester_credit', `failed_course_id` = '$failed_course_id' WHERE `student_id` = '$stdnt_id' AND `actual_session` = '$actual_session' AND `current_session` = '$current_session' AND `course_year` = '$course_year' AND `course_semester` = '$course_semester'";
                
                $run_update_semester_cgpa = mysqli_query($conn, $update_semester_cgpa);
            }
        }
        // if($run_insert_semester_cgpa || $run_update_semester_cgpa)
        // {
            ?>
                <script>
                    window.alert("Result Calculated Successfully");
                    window.location = "1st_2nd_semester.php?semester=<?php echo $course_semester?>&department_id=<?php echo $department_id ?>";
                </script>
            <?php 
        // }
    }
    if(isset($_POST['finish_result_processing']))
    {
        // 1st_sem_status or 2nd_sem_status update korar age check korbo, protita dept er je koita course er marks entry hoise tate same roll er student soman sonkhok bar ache kina. jdi same roll soman sonkhok bar na thake tahole error show korbe.
        
        // protita department er koita kore course er marks dewa ache setar jonno age query korbo.
        $find_each_dept_num_of_courses = "SELECT COUNT(DISTINCT `course_id`) as `each_dept_total_courses`, `department_id` FROM `result` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' AND `total_internal` !='-1' GROUP BY `department_id` ORDER BY `department_id` ASC ";
        $run_find_each_dept_num_of_courses = mysqli_query($conn, $find_each_dept_num_of_courses);
        
        // suppose jdi 2nd year hoy tar mane 3 ta dept ache. tkhn per dept er id and koita course ache sei info array te rekhe dibo.
        // suppose department_id 15 hoy and tar 5 ta course thake tahole $dept_id_and_num_of_courses array te 15 index er value 5 hobe.
        $dept_id_and_num_of_courses = array();
        while($row_find_each_dept_num_of_courses = mysqli_fetch_assoc($run_find_each_dept_num_of_courses))
        {
            $dept_id_and_num_of_courses[$row_find_each_dept_num_of_courses['department_id']] = $row_find_each_dept_num_of_courses['each_dept_total_courses'];
        }
        
        // course_id onujayi sob student_id niye aschi ASCENDING akare je koita result table e ache. eita ekta particualr session , course year, course semester and department er.
        $select_result_for_validation = "SELECT `student_id`, `department_id` FROM `result` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' ORDER BY `department_id` ASC, `student_id` ASC";
        
        // $select_result_for_validation = "SELECT `student_id` FROM `result` WHERE `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' AND `department_id` = '$department_id' ORDER BY `course_id` ASC";
        $run_select_result_for_validation = mysqli_query($conn, $select_result_for_validation);
        
        // sob roll gula array te rakhbo then sort korbo jate kore je koita course assign kora ache thik se poriman student protita courser jonno ache kina.
        $student_id_array = array();
        $student_dept_array = array();
        while($row_select_result_for_validation = mysqli_fetch_assoc($run_select_result_for_validation))
        {
            array_push($student_id_array, $row_select_result_for_validation['student_id']);
            array_push($student_dept_array, $row_select_result_for_validation['department_id']);
        }
      
        // eibar protita course er same student koita ache ta count korbo
        $count_student = 1;
        $count_error = 0;
        
        for($i=0;$i<sizeof($student_id_array);$i++)
        {
            if($i+1<sizeof($student_id_array))
            {
                if($student_id_array[$i]==$student_id_array[$i+1])
                {
                    $count_student++;
                }
                else if($count_student==$dept_id_and_num_of_courses[$student_dept_array[$i]])
                {
                    // $student_dept_array[$i] er vitore particular student er dept_id ache, oitar value index hisabe use korsi $dept_id_and_num_of_courses array te, jar fole oi particular dept er total course peye gese.
                    $count_student = 1;
                }
                else
                {
                    $count_error++;
                    break;
                }
            }
            if($i+1==sizeof($student_id_array))
            {
                if($count_student!=$dept_id_and_num_of_courses[$student_dept_array[$i]])
                {
                    $count_error++;
                }
            }
           
        }
        if($count_error>0)
        {
            ?>
                <script>
                    document.getElementById('error_under_buttons').innerHTML = "Some Errors In Result. Please Check Numbers of Students In Each Course!!";
                </script>
            <?php 
            exit();
        }
        else
        {
           
            // soman sonkhok student thakle 1st_sem_status or 2nd_sem_status er value 3 kore dibo
            $update_1st_or_2nd_sem_status = "UPDATE `exam_committee_information` SET ";
            if($course_semester=="1st semester")
            {
                $update_1st_or_2nd_sem_status.= "`1st_sem_status` = '3'";
            }
            else
            {
                $update_1st_or_2nd_sem_status.= "`2nd_sem_status` = '3'";
            }
            $update_1st_or_2nd_sem_status.= " WHERE `id` = '$_SESSION[exam_committee_id]'";
            $run_update_1st_or_2nd_sem_status = mysqli_query($conn, $update_1st_or_2nd_sem_status);
            
            if($run_update_1st_or_2nd_sem_status)
            {
                ?>
                    <script>
                        window.alert("Result Processing Has Been Finished");
                        window.location = "1st_2nd_semester.php?semester=<?php echo $course_semester ?>&department_id=<?php echo $department_id ?>";
                    </script>
                <?php 
                exit();
            }
        }
    }
?>
<?php include("lib/tabulator_footer.php") ?>
