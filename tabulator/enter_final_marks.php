<?php include('lib/tabulator_header.php') ?>
<?php include('valid_department_function.php') ?>
<?php include('semester_wise_cgpa_calculation.php') ?>
<?php
    // Validation
    if(!isset($_GET['course_semester']) || !isset($_GET['course_id']) || !isset($_GET['department_id']))
    {
        ?>
        <script>
            window.location = "home.php";
        </script>
        <?php
        exit();
    }
    else if(isset($_GET['course_semester']) && isset($_GET['course_id']) && isset($_GET['department_id']) && ($_GET['course_semester']=="1st semester" || $_GET['course_semester']=="2nd semester"))
    {
        // previous 1st_examinee, 2nd_examinee, 3rd_examinee improvement table theke newa hocche jate ager tabulator login korle improve howar poreo ager result dekhte pare ei jonno improvement_table er sathe left join kora hoyse.
        
        $id_validation_qry = "SELECT r.id, st.roll_no, i_r.previous_1st_examinee, i_r.previous_2nd_examinee, i_r.previous_3rd_examinee, r.1st_examinee, r.2nd_examinee, r.3rd_examinee, r.total_internal, r.improvement_eligibility FROM result as r INNER JOIN student_information as st ON r.student_id = st.id LEFT JOIN improvement_result as i_r ON i_r.result_id = r.id WHERE r.current_session = '$_SESSION[session]' && r.course_year = '$_SESSION[course_year]' && r.course_semester = '$_GET[course_semester]' && r.course_id = '$_GET[course_id]' && r.result_status = '0' ORDER BY r.actual_session DESC ,st.roll_no ASC";
        $run_id_validation_qry = mysqli_query($conn, $id_validation_qry);
        $num_rows = mysqli_num_rows($run_id_validation_qry);

        if($num_rows==0)
        {
            if($_GET['course_semester']=="1st semester")
            {
                ?>
                <script>
                    window.alert("Invalid Id or No Internal Marks Are Given For This Subject");
                    window.location = "1st_2nd_semester.php?semester=1st semester&department_id=<?php echo $_GET['department_id'] ?>";
                </script>
                <?php
            }
            else
            {
                ?>
                <script>
                    window.alert("Invalid Id or No Internal Marks Are Given For This Subject");
                    window.location = "1st_2nd_semester.php?semester=2nd semester&department_id=<?php echo $_GET['department_id'] ?>";
                </script>
                <?php
            }
            exit();
        }
        else 
        {
            $semester = $_GET['course_semester'];
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
    
    $course_qry = "SELECT * FROM `course_information` WHERE `id` = '$_GET[course_id]' AND `course_type` != 'Viva-Voce'";
    $run_course_qry = mysqli_query($conn, $course_qry);
    $res_course_qry = mysqli_fetch_assoc($run_course_qry);
    
    if($res_course_qry==false)
    {
        ?>
        <script>
            window.alert("Invalid Course");
            window.location = "1st_2nd_semester.php?semester=<?php echo $_GET['course_semester'] ?>&department_id=<?php echo $_GET['department_id'] ?>";
        </script>
        <?php
        exit();
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
                    <h2>Final Marks Of (<?php echo $_SESSION['course_year'].", ".$semester?>)</h2>
                    <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
                    <h4>Total Students: </h4>
                    <h4 class = "text-center text-secondary bg-white"><?php echo "Course Code: $res_course_qry[course_code], Course Title: $res_course_qry[course_title], Course Credit: $res_course_qry[course_credit]" ?></h4>
                </div>
                <div class="card-body table-responsive">
                    <form action="" method = "POST">
                        <div class="table-responsive">
                            <?php 
                                // $exam_committee_status e 0 or 1 or 2 or 3 value thakbe. 0 mane hocche ekhno processing start hoy nai. tai student show korano jabe na. 1 mane start hoyse student show korabo. 2 mane result processing stop hoise tai student show korabo na. 3 mane result processing finish hoye gese.
                                if($exam_committee_status==0 || $exam_committee_status==2)
                                {
                                    echo "<h3 class = 'text-danger text-center'>Wait For Resuming Result Processing</h3>";
                                    exit();
                                }
                            ?>
                            <table class = "table  table-bordered table-hover text-center">
                                <tr>
                                    <thead class ="thead-light">
                                        <th>Roll No</th>
                                        <th>1st Examinee</th>
                                        <th>2nd Examinee</th>
                                        <th>3rd Examinee</th>
                                    </thead>
                                </tr>
                                <?php
                                $i=0;
                                while($row = mysqli_fetch_assoc($run_id_validation_qry))
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['roll_no'] ?></td>
                                        <input type="hidden" name = "id[]" value = "<?php echo $row['id'] ?>">
                                        <td><input type="number" step = "0.01" name = "1st_examinee[]"
                                            placeholder="Enter Marks" value = "<?php
                                            if(isset($_POST['1st_examinee'][$i]))
                                            {
                                                echo $_POST['1st_examinee'][$i];
                                            }
                                            else if($row['1st_examinee']==-1)
                                            {
                                                echo "";
                                            }
                                            else if($row['previous_1st_examinee']!=NULL)
                                            {
                                             // improve howar poreo jate ager tabulator ager result dekhte pare tai previous_1st_examinee dewa hoyse
                                                echo $row['previous_1st_examinee'];
                                            }
                                            else 
                                            {
                                                echo $row['1st_examinee'];
                                            }
                                            ?>" required>
                                            <p  id = "1st_examinee<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                        </td>
    
                                        <td><input type="number" step = "0.01" name = 2nd_examinee[]
                                            placeholder="Enter Marks"
                                            value = "<?php
                                            if(isset($_POST['2nd_examinee'][$i]))
                                            {
                                                echo $_POST['2nd_examinee'][$i];
                                            }
                                            else if($row['2nd_examinee']==-1)
                                            {
                                                echo "";
                                            }
                                            else if($row['previous_2nd_examinee']!=NULL)
                                            {
                                            // improve howar poreo jate ager tabulator ager result dekhte pare tai previous_2nd_examinee dewa hoyse
                                                echo $row['previous_2nd_examinee'];
                                            }
                                            else 
                                            {
                                                echo $row['2nd_examinee'];
                                            } ?>" required>
                                            <p id = "2nd_examinee<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                        </td>
    
                                        <td><input type="number" step = "0.01" name = 3rd_examinee[]
                                            placeholder="Enter Marks"
                                            value = "<?php
                                            if(isset($_POST['3rd_examinee'][$i]))
                                            {
                                                echo $_POST['3rd_examinee'][$i];
                                            }
                                            else if($row['3rd_examinee']==-1)
                                            {
                                                echo "";
                                            }
                                            else if($row['previous_3rd_examinee']!=NULL)
                                            {
                                                 // improve howar poreo jate ager tabulator ager result dekhte pare tai previous_3rd_examinee dewa hoyse
                                                echo $row['previous_3rd_examinee'];
                                            }
                                            else 
                                            {
                                                echo $row['3rd_examinee'];
                                            }?>">
                                            <p id = "3rd_examinee_1st<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                            <p id = "3rd_examinee_2nd<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                        </td>
                                        <input type="hidden" name = "total_internal[]" value = "<?php echo $row['total_internal'] ?>">
                                        <input type="hidden" name = "improvement_eligibility[]" value = "<?php echo $row['improvement_eligibility'] ?>">
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </table>
                        </div>
                        <?php
                        
                            // Start: jdi kono ekta student particular session(2016-2017) particular year(1st year) particular semester(2nd semester) and particular department(Foundation Course) er previous_cgpa and current_cgpa er moddhe difference thake tahole nischoy oi student oi semester er kono ekta course e improve diye result improve korse jar fole previous_cgpa and current_cgpa er moddhe parthokko hoise. ekhn ekta semester e 10 ta student er moddhe jdi ektao student er previous_cgpa and current_cgpa er moddhe difference thake tkhn oi semester er kono student er marks update korar jonno update button show korabo na.
                            
                            $previous_current_cgpa_difference = 0; 
                            
                            // previous_current_cgpa_difference  variable ta eikhane disi karon keo jdi onno konovabe form submit kore tahole jate error show na kore. onno kono vabe bolte bujhacche POSTMAN diye korte pare.
                            
                            $session = $_SESSION['session'];
                            $course_year = $_SESSION['course_year'];
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
                                        <input type="submit" name = "submit" value = "Enter" class = "form-control btn">
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
                        ?>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('lib/tabulator_footer.php') ?>

<?php
    if(isset($_POST['submit']))
    {
        if($res_course_qry['course_credit']=="1.5")
        {   
            $count_error = 0;
            
            for($i=0;$i<sizeof($_POST['id']);$i++)
            {
                $difference = abs($_POST['1st_examinee'][$i]-$_POST['2nd_examinee'][$i]);
                if($_POST['1st_examinee'][$i]>30 || $_POST['1st_examinee'][$i]<0)
                {
                    ?>
                    <script>
                        document.getElementById('1st_examinee<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 30 or < 0`;
                    </script>
                    <?php
                    $count_error++;
                }
                if($_POST['2nd_examinee'][$i]>30 || $_POST['2nd_examinee'][$i]<0)
                {
                    ?>
                    <script>
                        document.getElementById('2nd_examinee<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 30 or < 0`;
                    </script>
                    <?php
                    $count_error++;
                }
                if($_POST['3rd_examinee'][$i]>30 || $_POST['3rd_examinee'][$i]<0)
                {
                    ?>
                    <script>
                        document.getElementById('3rd_examinee_2nd<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 30 or < 0`;
                    </script>
                    <?php
                    $count_error++;
                }
                if($difference<=6 && $_POST['3rd_examinee'][$i]!=NULL)
                {
                    ?>
                    <script>
                        document.getElementById('3rd_examinee_1st<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> No Need 3rd examinee Mark`;
                    </script>
                    <?php
                    $count_error++;
                }
            }
            if($count_error>0)
            {
                exit();
            }
        }
        else
        {
            $count_error = 0;
            for($i=0;$i<sizeof($_POST['id']);$i++)
            {
                $difference = abs($_POST['1st_examinee'][$i]-$_POST['2nd_examinee'][$i]);
                if($_POST['1st_examinee'][$i]>60 || $_POST['1st_examinee'][$i]<0)
                {
                    ?>
                    <script>
                        document.getElementById('1st_examinee<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 60 or < 0`;
                    </script>
                    <?php
                    $count_error++;
                }
                if($_POST['2nd_examinee'][$i]>60 || $_POST['2nd_examinee'][$i]<0)
                {
                    ?>
                    <script>
                        document.getElementById('2nd_examinee<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 60 or < 0`;
                    </script>
                    <?php
                    $count_error++;
                }
                if($_POST['3rd_examinee'][$i]>60 || $_POST['3rd_examinee'][$i]<0)
                {
                    ?>
                    <script>
                        document.getElementById('3rd_examinee_2nd<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 60 or < 0`;
                    </script>
                    <?php
                    $count_error++;
                }
                if($difference<=12 && $_POST['3rd_examinee'][$i]!=NULL)
                {
                    ?>
                    <script>
                        document.getElementById('3rd_examinee_1st<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> No Need 3rd examinee Mark`;
                    </script>
                    <?php
                    $count_error++;
                }
            }
            if($count_error>0)
            {
                exit();
            }
        }
        
        
        // jdi previous_current_cgpa_difference er man 0 hoy(that means previous_cgpa and current_cgpa er man same ache othoba previous_cgpa and current_cgpa count hoy nai) sudhu tkhni marks update kora jabe. otherwise kora jabe na. eita validation er jonno. Enter button er khetreo ei validation disi. tarpor abr eikhaneo dilam jate keo POSTMAN diye form submit korle error jate show na kore.
        if($count_error==0 && $previous_current_cgpa_difference==0)
        {  
            for($i=0;$i<sizeof($_POST['id']);$i++)
            {
                $_1st_examinee = $_POST['1st_examinee'][$i];
                $_2nd_examinee = $_POST['2nd_examinee'][$i];
                $_3rd_examinee = $_POST['3rd_examinee'][$i];
                $res_id = $_POST['id'][$i];
                $total_internal = $_POST['total_internal'][$i];
                
                // default vabe 0 entry hoise tai 0 ager theke initialize kore rakhsi. 1 hole 3rd_examinee er marks dorkar porbe.
                $_3rd_examinee_eligibility = 0;
                
                // Start: first e check korbo je 1st_examinee and 2nd_examinee er marks er difference koto. jdi 1.5 credit er course hoy tkhn difference 6 er besi hole 3rd_examinee_eligibility = 1 hobe. ar jdi 3 credit er course hoy tkhn difference 12 er besi hole 3rd_examinee_eligibility = 1 hobe. ar jdi 6 othoba 12 er niche hoy tkhn 3rd_examinee_eligibility = 0 hobe. 3rd_examinee er marks jdi diye dewa hoy tao 3rd_examinee_eligibility er marks 1 thakbe. karon eita diye eligibility bujhay
                
                $difference = abs($_POST['1st_examinee'][$i]-$_POST['2nd_examinee'][$i]);
                if($res_course_qry['course_credit']=="1.5")
                {
                    // 1.5 credit er course er jonno difference 6 er besi hole 3rd_examinee er marks dorkar porbe.
                    if($difference>6)
                    {
                        $_3rd_examinee_eligibility = 1;
                    }
                }
                else
                {
                    // 3 credit er course er jonno difference 12 er besi hole 3rd_examinee er marks dorkar porbe.
                    if($difference>12)
                    {
                        $_3rd_examinee_eligibility = 1;
                    }
                }
                
                // End: first e check korbo je 1st_examinee and 2nd_examinee er marks er difference koto. jdi 1.5 credit er course hoy tkhn difference 6 er besi hole 3rd_examinee_eligibility = 1 hobe. ar jdi 3 credit er course hoy tkhn difference 12 er besi hole 3rd_examinee_eligibility = 1 hobe. ar jdi 6 othoba 12 er niche hoy tkhn 3rd_examinee_eligibility = 0 hobe. 3rd_examinee er marks jdi diye dewa hoy tao 3rd_examinee_eligibility er marks 1 thakbe. karon eita diye eligibility bujhay
                
                if($_POST['3rd_examinee'][$i]==NULL)
                {   
                    // jdi course credit = 3 hoy tahole avg er jonno 2ta examiner er value jog kore 2 diye vag korbo.
                    // jdi course credit = 1.5 hoy tahole avg er jonno 2ta examiner er value sudhu jog korbo karon marking 30 e dicche kintu calculation 60 e hocche.
                    if($res_course_qry['course_credit']=="1.5")
                    {
                        $avg = $_1st_examinee + $_2nd_examinee;
                    }
                    else
                    {
                        $avg = ($_1st_examinee + $_2nd_examinee)/2;
                    }
                    
                    $update_result = "UPDATE `result` SET `1st_examinee` = '$_1st_examinee', `2nd_examinee` = '$_2nd_examinee', `3rd_examinee`='-1', `3rd_examinee_eligibility` = '$_3rd_examinee_eligibility', `total_final_marks` = '$avg'";
                }
                else
                {
                    $diff1 = abs($_1st_examinee - $_3rd_examinee);
                    $diff2 = abs($_2nd_examinee - $_3rd_examinee);
                    
                    // jdi course credit = 3 hoy tahole avg er jonno 2ta examiner er value jog kore 2 diye vag korbo.
                    // jdi course credit = 1.5 hoy tahole avg er jonno 2ta examiner er value sudhu jog korbo karon marking 30 e dicche kintu calculation 60 e hocche.
                    if($diff1>$diff2)
                    {
                        if($res_course_qry['course_credit']=="1.5")
                        {
                            $avg = $_2nd_examinee + $_3rd_examinee;
                        }
                        else 
                        {
                            $avg = ($_2nd_examinee + $_3rd_examinee)/2;
                        }
                    }
                    else if($diff1<$diff2)
                    {
                        if($res_course_qry['course_credit']=="1.5")
                        {
                            $avg = $_1st_examinee + $_3rd_examinee;
                        }
                        else 
                        {
                            $avg = ($_1st_examinee + $_3rd_examinee)/2;
                        }
                        
                    }
                    else
                    {
                        if($_1st_examinee>$_2nd_examinee)
                        {
                            if($res_course_qry['course_credit']=="1.5")
                            {
                                $avg = $_1st_examinee + $_3rd_examinee;
                            }
                            else 
                            {
                                $avg = ($_1st_examinee + $_3rd_examinee)/2;
                            }
                        }
                        else
                        {
                            if($res_course_qry['course_credit']=="1.5")
                            {
                                $avg = $_2nd_examinee + $_3rd_examinee;
                            }
                            else
                            {
                                $avg = ($_2nd_examinee + $_3rd_examinee)/2;
                            }
                            
                        }
                    }
                    $update_result = "UPDATE `result` SET `1st_examinee` = '$_1st_examinee', `2nd_examinee` = '$_2nd_examinee', `3rd_examinee` = '$_3rd_examinee', `3rd_examinee_eligibility` = '$_3rd_examinee_eligibility', `total_final_marks` = '$avg'";
                }
                // $run_update_result = mysqli_query($conn, $update_result);
                
                
                // 1.5 credit er course er khetre marks 30 er niche and 3 credit er khetre 60 er niche hole improvement_eligibility = Y hoy. 1.5 credit er courser khetre jkhn final marks dewa hoy tkhn 2ta examiner er marks jog kore then final marks dewa hoy(jemon 1st_examiner = 20 and 2nd_examiner = 18 then final marks = 38). ar internal jehetu already 40 e ache tai total marks ta 100 er vitore hoy. eijonno joto credit er course hok na kn $total_marks 60 er niche hole improvement_eligibility = Y hobe.
                    
                // ceil function use korsi jate value 77.25 or 77.5 jai hok na kn seta tar uporer value niye nibe jemon ei khetre 78.
                
                $total_marks = ceil($total_internal + $avg);
                
                // Update improvement_eligibility
                if($total_marks<60)
                {
                    $update_result.= ",`improvement_eligibility` = 'Y'";
                }
                else
                {
                    $update_result.= ",`improvement_eligibility` = 'N'";
                }
                
                
                $update_result.= "WHERE `id` = '$res_id'";
            
                $run_update_result = mysqli_query($conn, $update_result);
            }
            
            //Start:  Update semester_cgpa table
            // First find any data exists or not in semester_cgpa table. If exists then update cgpa otherwise ignore
            
            // data semester_cgpa table e exist kore kina ta already enter button er age check kore nisi. oikhanei $num_rows_semester_cgpa variable ta ache.
            if($num_rows_semster_cgpa!=0)
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
                    
                    $update_semester_cgpa = "UPDATE `semester_cgpa` SET `previous_cgpa` = '$previous_cgpa', `current_cgpa` = '$current_cgpa', `semester_total_credit` = '$total_each_semester_credit', `failed_course_id` = '$failed_course_id' WHERE `student_id` = '$stdnt_id' AND `actual_session` = '$actual_session' AND `current_session` = '$current_session' AND `course_year` = '$course_year' AND `course_semester` = '$course_semester' AND `department_id` = '$department_id'";
                    
                    $run_update_semester_cgpa = mysqli_query($conn, $update_semester_cgpa);
                }
            }
            // End: Update semester_cgpa table
                
            ?>
                <script>
                    window.alert("Marks Entered Successfully");
                    window.location = "view_final_marks.php?course_semester=<?php echo $_GET['course_semester'] ?>&course_id=<?php echo $_GET['course_id'] ?>&department_id=<?php echo $department_id ?>";
                </script>
            <?php
            exit();
        }
        else
        {
            ?>
                <script>
                    window.alert("Marks Can't Be Updated Anymore Because Someone's CGPA Has Been Increased");
                    window.location = "1st_2nd_semester.php?semester=<?php echo $_GET['course_semester']?>&department_id=<?php echo $_GET['department_id'] ?> ";
                </script>
            <?php 
        }
    }

?>
