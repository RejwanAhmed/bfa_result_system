<?php include('lib/teacher_header.php') ?>
<?php include('valid_department_function.php') ?>
<?php include('semester_wise_cgpa_calculation.php') ?>
<?php
    if(!isset($_GET['session']) || !isset($_GET['course_year']) || !isset($_GET['course_semester']) || !isset($_GET['course_id']) || !isset($_GET['department_id']))
    {
        ?>
        <script>
            window.location = "assigned_course_list.php";
        </script>
        <?php
        exit();
    }
    else if(isset($_GET['session']) && isset($_GET['course_year']) && isset($_GET['course_semester']) && isset($_GET['course_id']) && isset($_GET['department_id']))
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
        
        // jdi department = Foundation Course hoy tahole sob student er data anbe. otherwise department onujayi data anbe.
        if($department_id==0)
        {   
            $id_validation_qry = "SELECT r.id, r.actual_session, r.current_session, r.course_year, r.course_semester, st.roll_no, r.teacher_id,  r.course_id, r.attendance, r.mid1, r.mid2, r.ass_pre,r.total_internal, r.improvement_eligibility, r.total_final_marks, c.course_title as course_title, c.course_code as course_code FROM result as r INNER JOIN course_information as c ON  r.course_id = c.id INNER JOIN student_information as st ON r.student_id = st.id WHERE r.current_session = '$_GET[session]' && r.course_year = '$_GET[course_year]' && r.course_semester = '$_GET[course_semester]' && r.teacher_id = '$_SESSION[teacher_id]' && r.course_id = '$_GET[course_id]' && r.result_status = '0' ORDER BY r.actual_session DESC , st.roll_no ASC";
        }
        else
        {
            $id_validation_qry = "SELECT r.id, r.actual_session, r.current_session, r.course_year, r.course_semester, st.roll_no, r.teacher_id,  r.course_id, r.attendance, r.mid1, r.mid2, r.ass_pre,r.total_internal, r.improvement_eligibility, r.total_final_marks, c.course_title as course_title, c.course_code as course_code FROM result as r INNER JOIN course_information as c ON  r.course_id = c.id INNER JOIN student_information as st ON r.student_id = st.id WHERE r.current_session = '$_GET[session]' && r.course_year = '$_GET[course_year]' && r.course_semester = '$_GET[course_semester]' && r.teacher_id = '$_SESSION[teacher_id]' && r.course_id = '$_GET[course_id]' && r.department_id = '$department_id' && r.result_status = '0' ORDER BY r.actual_session DESC , st.roll_no ASC";
        }

        $run_id_validation_qry = mysqli_query($conn, $id_validation_qry);
        $num_rows = mysqli_num_rows($run_id_validation_qry);
        if($num_rows==0)
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
            
            $select_exam_committee = "SELECT `$col_name` FROM `exam_committee_information` WHERE `session` = '$_GET[session]' AND `course_year` = '$_GET[course_year]'";
            $run_select_exam_committee = mysqli_query($conn, $select_exam_committee);
            $res_select_exam_committee = mysqli_fetch_assoc($run_select_exam_committee);
            $exam_committee_status = $res_select_exam_committee[$col_name];
            //end: exam_committee theke 1st semester hole 1st_sem_status ar 2nd semester hole 2nd_sem_status er value niye asbo. jate kore amra marks enter korar option show korbo kina ta decide korte pari.
            
            
            // Start: sob student niye astesi karon jdi kono student notun kore add kore marks dewar pore tahole jate oi student keo show kore
            // current session er joto student ache sobar id, roll, actual_session, current_session eigula array te push korbo jate pore student der roll dekhe id, roll, actual_session, current_session gula result table e push korte pari.
            
            // jdi department = Foundation course hoy tahole sob student der ke show korbe. otherwise department onujayi student show korbe.
            // status = 0 mane student active ache.
            if($exam_committee_status!=3)
            {   
                // exam_committe_status = 3 hole kono student ei session theke sorano hok ba kono student ei session e readd kora hok eikhane ar show korbe na. 3 na hole all student niye asbo jate kore notun student add hole show kore.
                
                if($department_id==0)
                {
                    $get_students_qry = "SELECT * FROM `student_information` WHERE `current_session` = '$_GET[session]' AND `status` = '0' ORDER BY `actual_session` DESC ,`roll_no` ASC ";
                }
                else
                {
                    $get_students_qry = "SELECT * FROM `student_information` WHERE `current_session` = '$_GET[session]' AND `status` = '0' AND `department_id` = '$department_id' ORDER BY `actual_session` DESC ,`roll_no` ASC ";
                }
                
                $run_get_students_qry = mysqli_query($conn, $get_students_qry);
                $students_roll = array();
                $students_id = array();
                $students_actual_session = array();
                $students_current_session = array();
                array_push($students_roll,-1);  // as first index could not find
                array_push($students_id,-1); // as first index could not find
                array_push($students_actual_session,-1); // as first index could not find
                array_push($students_current_session,-1); // as first index could not find
                while($row = mysqli_fetch_assoc($run_get_students_qry))
                {
                    array_push($students_id,$row['id']);
                    array_push($students_roll,$row['roll_no']);
                    array_push($students_actual_session,$row['actual_session']);
                    array_push($students_current_session,$row['current_session']);
                }
            }
        }
        // End: sob student niye astesi karon jdi kono student notun kore add kore marks dewar pore tahole jate oi student keo show kore
    }
   

?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header form_header text-center">
                    <h2>Update Internal Marks Of <?php echo "($_GET[session], $_GET[course_year], $_GET[course_semester])" ?></h2>
                    <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
                    <h4 class = "text-center text-secondary bg-white"><?php echo "Course Code: $res_course_qry[course_code], Course Title: $res_course_qry[course_title], Course Credit: $res_course_qry[course_credit]" ?></h4>
                </div>
                <div class="card-body table-responsive">
                    <?php 
                        // $exam_committee_status e 0 or 1 or 2 or 3 value thakbe. 0 mane hocche ekhno processing start hoy nai. tai student show korano jabe na. 1 mane start hoyse student show korabo. 2 mane result processing stop hoise tai student show korabo na. 3 mane result processing finish hoye gese.
                        if($exam_committee_status==0 || $exam_committee_status==2)
                        {
                            echo "<h3 class = 'text-danger text-center'>Wait For Tabulators Approval</h3>";
                            exit();
                        }
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
                                    <th>Total Marks</th>
                                </thead>
                            </tr>
                            <?php
                            $i=0;
                            while($row = mysqli_fetch_assoc($run_id_validation_qry))
                            {
                                // prothome array search korbo je roll no ta ache kina tarpor jdi roll number thake tahole $student_roll $student_id, $student_actual_session, $student_current_session eigula theke oi roll, id, actual_session, current_session eigula delete kore dibo array_splice er maddhome. pore jegula theke jabe segula entry korbo.
                                if($exam_committee_status!=3)
                                {
                                    // exam_committee_status = 3 na hole newly added student er jonno kaj korbe. otherwise kaj korbe na
                                    if(array_search($row['roll_no'],$students_roll))
                                    {
                                        $index = array_search($row['roll_no'],$students_roll);
                                        array_splice($students_roll, $index, 1);
                                        array_splice($students_id, $index, 1);
                                        array_splice($students_actual_session, $index, 1);
                                        array_splice($students_current_session, $index, 1);
                                    }
                                }
                                
                                // jdi tabulator internal marks add kore tahole course teacher der update korar option dibo na ebong sudhu sudhu total internal ta show korbo.
                                // tabulators jdi marks diye thake tahole $count_marks_by_tabulators er value increment hobe. ar $count_marks_by_tabulators er value 1 ba er besi hole update button show korbe na and new added student der marks entry korar option o thakbe na.
                                $count_marks_by_tabulators = 0;
                                if($row['attendance']==-1)
                                {
                                    ?>
                                        <tr>
                                        <td><?php echo $row['roll_no'] ?></td>
                                            <td>--</td>
                                            <td>--</td>
                                            <td>--</td>
                                            <td>--</td>
                                            <td><?php echo $row['total_internal']?></td>
                                        </tr>
                                    <?php
                                    $count_marks_by_tabulators++;
                                }
                                else if($count_marks_by_tabulators==0)
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['roll_no'] ?></td>
                                        <input type="hidden" name = "id[]" value = "<?php echo $row['id'] ?>">
                                        
                                        <td><input type="number" step = "0.01" name = "attendance[]"
                                            placeholder="Enter Marks"
                                            value = "<?php
                                            if(isset($_POST['attendance'][$i]))
                                            {
                                                echo $_POST['attendance'][$i];
                                            }
                                            else
                                            {
                                                echo $row['attendance'];
                                            } ?>" required>
                                            <p  id = "attendance<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                        </td>
    
                                        <td><input type="number" step = "0.01" name = mid1[]
                                            placeholder="Enter Marks"
                                            value = "<?php
                                            if(isset($_POST['mid1'][$i]))
                                            {
                                                echo $_POST['mid1'][$i];
                                            }
                                            else
                                            {
                                                echo $row['mid1'];
                                            } ?>" required>
                                            <p id = "mid1<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                        </td>
    
                                        <td><input type="number" step = "0.01" name = mid2[]
                                            placeholder="Enter Marks"
                                            value = "<?php
                                            if(isset($_POST['mid2'][$i]))
                                            {
                                                echo $_POST['mid2'][$i];
                                            }
                                            else
                                            {
                                                echo $row['mid2'];
                                            }?>" required>
                                            <p id = "mid2<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                        </td>
    
                                        <td><input type="number" step = "0.01" name = ass_pre[]
                                            placeholder="Enter Marks"
                                            value = "<?php if(isset($_POST['ass_pre'][$i]))
                                            {
                                                echo $_POST['ass_pre'][$i];
                                            }
                                            else
                                            {
                                                echo $row['ass_pre'];
                                            }?>" required>
                                            <p id = "ass_pre<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                        </td>
                                        <td><b><?php echo $row['total_internal'] ?></b></td>
                                        <input type="hidden" name= total_final_marks[] value = "<?php echo $row['total_final_marks'];?>">
                                    </tr>
                                    <?php 
                                }
                                
                                $i++;
                            }
                            ?>
                            
                            <!-- eikhan theke jesob student er data entry nai othoba jegula marks entry howar pore notun kore add hoise tader roll number thakbe -->
                            <?php 
                                if($exam_committee_status!=3)
                                {
                                    // / exam_committee_status = 3 na hole newly added student der ke show korbe
                                    for($i=1;$i<sizeof($students_roll);$i++)
                                    {
                                        // $count_marks_by_tabulators er man 1 bar er besi hole newly added student der marks entry korar kono option dewa hobe na. karon puraton studentder marks tabulator entry korse ebong notun dero marks tabulator entry korbe.
                                        if($count_marks_by_tabulators>=1)
                                        {
                                            ?>
                                                <tr>
                                                    <td><?php echo $students_roll[$i] ?></td>
                                                    <td class = "text-danger">Tabulators need to entry marks</td>
                                                    <td class = "text-danger">Tabulators need to entry marks</td>
                                                    <td class = "text-danger">Tabulators need to entry marks</td>
                                                    <td class = "text-danger">Tabulators need to entry marks</td>
                                                </tr>
                                            <?php 
                                        }
                                        else 
                                        {
                                            ?>
                                            <!--loop er vitore $i=1 diye suru korechi karon $i=0 position e -1 value ache. body er vitore $i-1 use korchi karon eikhane $i=1 theke suru hoyse kintu form er array te data $i=0 theke thaktese. data jate sothik input field e thake tai amra $i-1 use korsi -->
                                            
                                            <tr>
                                                <td><?php echo $students_roll[$i] ?></td>
                                                <input type="hidden" name = "new_student_id[]" value = "<?php echo $students_id[$i] ?>">
                                                <input type="hidden" name = "new_student_current_session[]" value = "<?php echo $students_current_session[$i] ?>">
                                                <input type="hidden" name = "new_student_actual_session[]" value = "<?php echo $students_actual_session[$i] ?>">
                                                <td><input type="number" step = "0.01" name = "new_student_attendance[]"
                                                     placeholder="Enter Marks"
                                                    value = "<?php if(isset($_POST['new_student_attendance'][$i-1]))
                                                    {
                                                        echo $_POST['new_student_attendance'][$i-1];
                                                    } ?>" required>
                                                    <p  id = "new_student_attendance<?php echo $i-1 ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                                </td>
        
                                                <td><input type="number" step = "0.01" name = new_student_mid1[]
                                                    placeholder="Enter Marks"
                                                    value = "<?php if(isset($_POST['new_student_mid1'][$i-1]))
                                                    {
                                                        echo $_POST['new_student_mid1'][$i-1];
                                                    } ?>" required>
                                                    <p id = "new_student_mid1<?php echo $i-1 ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                                </td>
        
                                                <td><input type="number" step = "0.01" name = new_student_mid2[]
                                                    placeholder="Enter Marks"
                                                    value = "<?php if(isset($_POST['new_student_mid2'][$i-1]))
                                                    {
                                                        echo $_POST['new_student_mid2'][$i-1];
                                                    } ?>" required>
                                                    <p id = "new_student_mid2<?php echo $i-1 ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                                </td>
        
                                                <td><input type="number" step = "0.01" name = new_student_ass_pre[]
                                                    placeholder="Enter Marks"
                                                    value = "<?php if(isset($_POST['new_student_ass_pre'][$i-1]))
                                                    {
                                                        echo $_POST['new_student_ass_pre'][$i-1];
                                                    } ?>" required>
                                                    <p id = "new_student_ass_pre<?php echo $i-1 ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                                </td>
                                            </tr>
                                            <?php 
                                        }
                                       
                                    }
                                }
                                
                            ?>
                        </table>
                        <?php
                            // tabulators jdi marks entry kore thake tahole update er button dewa jabe na
                            
                            $previous_current_cgpa_difference = 0; 
                            
                            // previous_current_cgpa_difference  variable ta eikhane disi karon keo jdi onno konovabe form submit kore tahole jate error show na kore. onno kono vabe bolte bujhacche POSTMAN diye korte pare.
                            if($count_marks_by_tabulators>=1)
                            {
                                ?>
                                    <div class = "text-center">
                                        <h4 class = "text-warning btn">You Can Not Update Marks Given By Tabulators </h4>
                                    </div>
                                <?php 
                            }
                            else
                            {
                                // Start: jdi kono ekta student particular session(2016-2017) particular year(1st year) particular semester(2nd semester) and particular department(Foundation Course) er previous_cgpa and current_cgpa er moddhe difference thake tahole nischoy oi student oi semester er kono ekta course e improve diye result improve korse jar fole previous_cgpa and current_cgpa er moddhe parthokko hoise. ekhn ekta semester e 10 ta student er moddhe jdi ektao student er previous_cgpa and current_cgpa er moddhe difference thake tkhn oi semester er kono student er marks update korar jonno update button show korabo na.
                                $session = $_GET['session'];
                                $course_year = $_GET['course_year'];
                                $course_semester = "$_GET[course_semester]";
                                
                                $select_semester_cgpa = "SELECT `id`, `previous_cgpa`, `current_cgpa` FROM `semester_cgpa` WHERE `current_session` = '$session' AND `course_year` = '$course_year' AND `course_semester` = '$course_semester' AND `department_id` = '$department_id'";
                                $run_select_semester_cgpa = mysqli_query($conn, $select_semester_cgpa);
                                $num_rows_semster_cgpa = mysqli_num_rows($run_select_semester_cgpa);
                                
                                // prothom dekhbe je semester_cgpa table e data ache naki. thakle nicher kaj korbe.
                                if($num_rows_semster_cgpa!=0)
                                {
                                    while($row_select_semester_cgpa = mysqli_fetch_assoc($run_select_semester_cgpa))
                                    {
                                        if($row_select_semester_cgpa['previous_cgpa']!=$row_select_semester_cgpa['current_cgpa'])
                                        {
                                            $previous_current_cgpa_difference++;
                                            break;
                                        }
                                    }
                                }               
                                
                                // ekhn previous_current_cgpa_difference er man 1 hole update button show korabo na. 0 hole show korabo.
                               
                                if($previous_current_cgpa_difference==0)
                                {
                                    ?>
                                    <div class="row justify-content-center">
                                        <div class="col-lg-4 col-md-4 col-12">
                                            <input type="submit" name = "submit" value = "Update" class = "form-control btn">
                                        </div>
                                    </div>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <div class = "text-center">
                                        <h4 class = "text-warning btn">Marks Can't Be Updated Anymore Because Someone's CGPA Has Been Increased</h4>
                                    </div>
                                    <?php
                                }
                                
                            }
                        ?>
                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('lib/teacher_footer.php') ?>
<?php
    if(isset($_POST['submit']))
    {
        // Find total number of rows of result status = 1 to find whether result published or not
        $count_result_status = "SELECT count(`id`) as `total_id` FROM `result` WHERE `result_status`='1' AND `current_session` = '$_GET[session]' AND `course_year` = '$_GET[course_year]' AND `course_semester` = '$_GET[course_semester]' AND `department_id` = '$department_id'";
        $run_count_result_status = mysqli_query($conn, $count_result_status);
        $res_count_result_status = mysqli_fetch_assoc($run_count_result_status);
         
        // jdi result publish hoye thake tahole internal marks ar update korar jabe na
        if($res_count_result_status['total_id']>0)
        {
            ?>
                <script>
                    window.alert("Marks Can Not Be Updated!! Result Already Published");
                    window.location = "assigned_course_list.php";
                </script>
            <?php
            exit();
        }
         
        // internal marks update er khetre course_credit = 1.5 or 3 jai hok attendance, mid1, mid2, ass_pre sob field ei value 10 er besi entry kora jabe na.  
   
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
        
        // new student jdi abar add hoy tkhn abar validation check korte hobe
        if($exam_committee_status!=3 && sizeof($students_roll)>1)
        {
            for($i=0;$i<sizeof($_POST['new_student_attendance']);$i++)
            {
                if($_POST['new_student_attendance'][$i]>10 || $_POST['new_student_attendance'][$i]<0)
                {
                    ?>
                    <script>
                        document.getElementById('new_student_attendance<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 10 or < 0`;
                    </script>
                    <?php
                    $count_error++;
                }
                if($_POST['new_student_mid1'][$i]>10 || $_POST['new_student_mid1'][$i]<0)
                {
                    ?>
                    <script>
                        document.getElementById('new_student_mid1<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 10 or < 0`;
                    </script>
                    <?php
                    $count_error++;
                }
                if($_POST['new_student_mid2'][$i]>10 || $_POST['new_student_mid2'][$i]<0)
                {
                    ?>
                    <script>
                        document.getElementById('new_student_mid2<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 10 or < 0`;
                    </script>
                    <?php
                    $count_error++;
                }
                if($_POST['new_student_ass_pre'][$i]>10 || $_POST['new_student_ass_pre'][$i]<0)
                {
                    ?>
                    <script>
                        document.getElementById('new_student_ass_pre<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 10 or < 0`;
                    </script>
                    <?php
                    $count_error++;
                }
            }
        }
        
        // sob gula error eksathe dekhanor jonno count_erro use korsi.
        if($count_error>0)
        {
            exit();
        }
        
        // jdi previous_current_cgpa_difference er man 0 hoy(that means previous_cgpa and current_cgpa er man same ache othoba previous_cgpa and current_cgpa count hoy nai) sudhu tkhni marks update kora jabe. otherwise kora jabe na. eita validation er jonno. update button er khetreo ei validation disi. tarpor abr eikhaneo dilam jate keo POSTMAN diye form submit korle error jate show na kore.
        if($previous_current_cgpa_difference==0)
        {
            for($i=0;$i<sizeof($_POST['attendance']);$i++)
            {
                // Update Query
                $res_id = $_POST['id'][$i];
                $attendance = $_POST['attendance'][$i];
                $mid1 = $_POST['mid1'][$i];
                $mid2 = $_POST['mid2'][$i];
                $ass_pre = $_POST['ass_pre'][$i];
                
                // total final marks newa hoyeche jate student improvement eligible hobe kina ta count kore update korar jonno
                $total_final_marks = $_POST['total_final_marks'][$i];
                $total_internal = $attendance + $mid1 + $mid2 + $ass_pre;    
                
                $update_result = "UPDATE `result` SET `attendance`='$attendance', `mid1`='$mid1', `mid2`='$mid2', `ass_pre`='$ass_pre',`totaL_internal` = '$total_internal'";
                
                // improvement eligibility deafult = N
                // keo jdi final marks dewar pore internal marks update kore then ei logic kaj korbe and improvement eligibility update korbe. 
                if($total_final_marks!=-1)
                {
                    // 1.5 credit er course er khetre marks 30 er niche and 3 credit er khetre 60 er niche hole improvement_eligibility = Y hoy. 1.5 credit er courser khetre jkhn final marks dewa hoy tkhn 2ta examiner er marks jog kore then final marks dewa hoy(jemon 1st_examiner = 20 and 2nd_examiner = 18 then final marks = 38). ar internal jehetu already 40 e ache tai total marks ta 100 er vitore hoy. eijonno joto credit er course hok na kn $total_marks 60 er niche hole improvement_eligibility = Y hobe.
                    
                    // ceil function use korsi jate value 77.25 or 77.5 jai hok nak kn seta tar uporer value niye nibe jemon ei khetre 78.
                    
                    $total_marks = ceil($total_final_marks + $total_internal);       
                    
                    // Update improvement_eligibility 
                    if($total_marks<60)
                    {
                        $update_result.= ",`improvement_eligibility` = 'Y' ";
                    }
                    else
                    {
                        $update_result.= ",`improvement_eligibility` = 'N'";
                    }
                    
                    
                }
                $update_result.= "WHERE `id` = '$res_id'";
            
                $run_update_result = mysqli_query($conn, $update_result);
            }
            
            // Start: Update semester_cgpa table
            //  First find any data exists or not in semester_cgpa table. If exists then update cgpa otherwise ignore
            
            // data semester_cgpa table e exist kore kina ta already update button er age check kore nisi. oikhanei $num_rows_semester_cgpa variable ta ache.
            if($num_rows_semster_cgpa!=0)
            {
                // get cgpa, student_id, actual_session, current_session from semester_wise_cgpa_calculation function which is in semester_wise_cgpa_calculation.php page
                $stdnt_id_actual_session_current_session_cgpa = semester_wise_cgpa_calculation($course_semester, $department_id);
                $len = sizeof($stdnt_id_actual_session_current_session_cgpa[0]);
                
                $total_each_semester_credit = array_sum($stdnt_id_actual_session_current_session_cgpa[4]);
                
                for($i=0;$i<$len;$i++)
                {
                    $stdnt_id = $stdnt_id_actual_session_current_session_cgpa[0][$i];
                    $actual_session = $stdnt_id_actual_session_current_session_cgpa[1][$i];
                    $current_session = $stdnt_id_actual_session_current_session_cgpa[2][$i];
                    $previous_cgpa = $stdnt_id_actual_session_current_session_cgpa[3][$i];
                    $current_cgpa = $previous_cgpa;
                    $failed_course_id = $stdnt_id_actual_session_current_session_cgpa[5][$i];
                    
                    $update_semester_cgpa = "UPDATE `semester_cgpa` SET `previous_cgpa` = '$previous_cgpa', `current_cgpa` = '$current_cgpa', `semester_total_credit` = '$total_each_semester_credit', `failed_course_id` = '$failed_course_id' WHERE `student_id` = '$stdnt_id' AND `actual_session` = '$actual_session' AND `current_session` = '$current_session' AND `course_year` = '$course_year' AND `course_semester` = '$course_semester' AND `department_id` = '$department_id'";
                    
                    $run_update_semester_cgpa = mysqli_query($conn, $update_semester_cgpa);
                }
            }
            // End: Update semester_cgpa table
            
            
            // notun je student gula ache tader marks add korar jonno jaderke kichu student er marks entry korar pore add kora hoise
            if($exam_committee_status!=3 && sizeof($students_roll)>1)
            {
                for($i=0;$i<sizeof($_POST['new_student_attendance']);$i++)
                {
                    // Insert Query
                    $new_student_id = $_POST['new_student_id'][$i];
                    $new_student_actual_session = $_POST['new_student_actual_session'][$i];
                    $new_student_current_session = $_POST['new_student_current_session'][$i];
                    $new_student_attendance = $_POST['new_student_attendance'][$i];
                    $new_student_mid1 = $_POST['new_student_mid1'][$i];
                    $new_student_mid2 = $_POST['new_student_mid2'][$i];
                    $new_student_ass_pre = $_POST['new_student_ass_pre'][$i];
            
                    $new_student_total_internal =$new_student_attendance + $new_student_mid1 + $new_student_mid2 + $new_student_ass_pre;
                    
                    // insert korar age result table e oi student er jdi oi course er marks ager theke thake tahole seta invalid kore dite hobe.
                    
                    if($new_student_actual_session!=$new_student_current_session)
                    {
                        $new_student_update_result_validation_col = "UPDATE `result` SET `result_validation` = 'i' WHERE `student_id` = '$new_student_id' AND `course_id`= '$_GET[course_id]' AND `course_year` = '$_GET[course_year]' AND `course_semester` = '$_GET[course_semester]' AND `result_validation` = 'v'";
                        $run_new_student_update_result_validation_col = mysqli_query($conn, $new_student_update_result_validation_col);
                    }
                    
                    $new_student_insert_result = "INSERT INTO `result`(`actual_session`, `current_session`, `course_year`, `course_semester`, `student_id`, `teacher_id`, `course_id`, `department_id`, `attendance`, `mid1`, `mid2`, `ass_pre`,`totaL_internal`,`1st_examinee`,`2nd_examinee`,`3rd_examinee`,`total_final_marks`,`improvement_eligibility`,`improvement_result_status`,`total_improvement_exam`,`result_status`,`result_validation`) VALUES ('$new_student_actual_session', '$new_student_current_session', '$_GET[course_year]', '$_GET[course_semester]','$new_student_id','$_SESSION[teacher_id]','$_GET[course_id]','$department_id','$new_student_attendance','$new_student_mid1','$new_student_mid2','$new_student_ass_pre','$new_student_total_internal','-1','-1','-1','-1','N','N','0','0','v')";
                    $run_new_student_insert_result = mysqli_query($conn, $new_student_insert_result);
                    
                }
                if($run_new_student_insert_result)
                {
                    ?>
                    <script>
                        window.alert("Marks Inserted and Updated Successfully");
                        window.location = "assigned_course_list.php";
                    </script>
                    <?php
                    exit();
                }
            }
            
            if($run_update_result)
            {
                ?>
                <script>
                    window.alert("Marks Updated Successfully");
                    window.location = "assigned_course_list.php";
                </script>
                <?php
                exit();
            }
        }
        else
        {
            ?>
                <script>
                    window.alert("Marks Can't Be Updated Anymore Because Someone's CGPA Has Been Increased");
                    window.location = "assigned_course_list";
                </script>
            <?php
            exit();
        }
    }
    

?>
