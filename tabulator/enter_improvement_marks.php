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
    else if(isset($_GET['course_semester']) && isset($_GET['course_id']) && ($_GET['course_semester']=="1st semester" || $_GET['course_semester']=="2nd semester"))
    {
        $semester = $_GET['course_semester'];
        
        // result tabler sathe join kora ache jate selected student der total_internal and total_final_marks ta niye aste pari. 
        
        $student_id_validation_qry = "SELECT i_r.id, i_r.result_id, i_r.actual_session, i_r.current_session, i_r.student_id, st.roll_no, i_r.previous_1st_examinee as original_1st_examinee, i_r.previous_2nd_examinee as original_2nd_examinee, i_r.previous_3rd_examinee as original_3rd_examinee, i_r.previous_3rd_examinee_eligibility as original_3rd_examinee_eligibility, i_r.previous_total_final_marks as original_total_final_marks, i_r.1st_examinee, i_r.2nd_examinee, i_r.3rd_examinee, r.total_internal as original_total_internal_marks, r.total_improvement_exam FROM improvement_result as i_r INNER JOIN student_information as st ON i_r.student_id = st.id INNER JOIN result as r ON r.id = i_r.result_id WHERE i_r.improvement_session = '$_SESSION[session]' && i_r.course_year = '$_SESSION[course_year]' && i_r.course_semester = '$semester' && i_r.course_id = '$_GET[course_id]' AND i_r.selection = 'Y' ORDER BY st.roll_no ASC";
        $run_student_id_validation_qry = mysqli_query($conn, $student_id_validation_qry);
        $num_rows = mysqli_num_rows($run_student_id_validation_qry);
        if($num_rows==0)
        {
            ?>
            <script>
                window.alert("Invalid ID");
                window.location = "selected_subject_and_students.php?course_semester=<?php echo $semester?>";
            </script>
            <?php 
            exit();
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
    
    $course_qry = "SELECT * FROM `course_information` WHERE `id` = '$_GET[course_id]' AND `course_type`!='Viva-Voce'";
    $run_course_qry = mysqli_query($conn, $course_qry);
    $res_course_qry = mysqli_fetch_assoc($run_course_qry);
    if($res_course_qry==false)
    {
        ?>
        <script>
            window.alert("Invalid Course");
            window.location = "home.php";
        </script>
        <?php
        exit();
    }
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header form_header text-center">
                    <h2>Improvement Marks Of (<?php echo $_SESSION['course_year'].", ".$semester?>)</h2>
                    <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
                    <h4 class = "text-center text-secondary bg-white"><?php echo "Course Code: $res_course_qry[course_code], Course Title: $res_course_qry[course_title], Course Credit: $res_course_qry[course_credit]" ?></h4>
                </div>
                <div class="card-body table-responsive">
                    <form action="" method = "POST">
                        <div class="table-responsive">
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
                                $marks_already_entered_array = array();
                                while($row = mysqli_fetch_assoc($run_student_id_validation_qry))
                                {
                                    // marks age theke entry ache kina ta check korechi. jate kore amra total_improvement_exam er value barabo kina ta siddhanto nite pari
                                    if($row['1st_examinee']!=-1)
                                    {
                                        array_push($marks_already_entered_array,1);
                                    }
                                    else
                                    {
                                        array_push($marks_already_entered_array,0);
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $row['roll_no'] ?></td>
                                        <input type="hidden" name = "id[]" value = "<?php echo $row['id'] ?>">
                                        
                                        <input type="hidden" name = "student_id[]" value = "<?php echo $row['student_id'] ?>">
                                        
                                        <input type="hidden" name = "actual_session[]" value = "<?php echo $row['actual_session'] ?>">
                                        
                                        <input type="hidden" name = "current_session[]" value = "<?php echo $row['current_session'] ?>">
                                        
                                        <input type="hidden" name = "result_id[]" value = "<?php echo $row['result_id']?>">
                                        
                                        <input type="hidden" name = "original_total_internal_marks[]" value = "<?php echo $row['original_total_internal_marks']?>">
                                        
                                        <input type="hidden" name = "original_1st_examinee[]" value = "<?php echo $row['original_1st_examinee']?>">
                                        <input type="hidden" name = "original_2nd_examinee[]" value = "<?php echo $row['original_2nd_examinee']?>">
                                        <input type="hidden" name = "original_3rd_examinee[]" value = "<?php echo $row['original_3rd_examinee']?>">
                                        <input type="hidden" name = "original_3rd_examinee_eligibility[]" value = "<?php echo $row['original_3rd_examinee_eligibility']?>">
                                        <input type="hidden" name = "original_total_final_marks[]" value = "<?php echo $row['original_total_final_marks']?>">
                                       
                                        
                                        <input type="hidden" name = "total_improvement_exam[]" value = "<?php echo $row['total_improvement_exam']?>">
                                        <td>
                                            <input type="number" step = "0.01" name = "1st_examinee[]"
                                            placeholder="Enter Marks" value = "<?php
                                            if(isset($_POST['1st_examinee'][$i]))
                                            {
                                                echo $_POST['1st_examinee'][$i];
                                            }
                                            else if($row['1st_examinee']==-1)
                                            {
                                                echo "";
                                            }
                                            else
                                            {
                                                echo $row['1st_examinee'];
                                            } ?>" required>
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
                                            else
                                            {
                                                echo $row['3rd_examinee'];
                                            }?>">
                                            <p id = "3rd_examinee_1st<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                            <p id = "3rd_examinee_2nd<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
                                        </td>
    
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </table>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-4 col-12">
                                <input type="submit" name = "submit" value = "Enter" class = "form-control btn">
                            </div>
                        </div>
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
        // course credit 1.5 or 3 hok fail limit 40 er nichei hobe
        $fail_limit = 40;
        
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
                if($difference>6 && $_POST['3rd_examinee'][$i]==NULL)
                {
                    ?>
                    <script>
                        document.getElementById('3rd_examinee_1st<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Please Enter 3rd Examinee Mark`;
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
                if($difference>12 && $_POST['3rd_examinee'][$i]==NULL)
                {
                    ?>
                    <script>
                        document.getElementById('3rd_examinee_1st<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Please Enter 3rd Examinee Mark`;
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
        
        if($count_error==0)
        {
            
            for($i=0;$i<sizeof($_POST['id']);$i++)
            {
                $_1st_examinee = $_POST['1st_examinee'][$i];
                $_2nd_examinee = $_POST['2nd_examinee'][$i];
                $_3rd_examinee = $_POST['3rd_examinee'][$i];
                $improvement_res_id = $_POST['id'][$i];
                
                $student_id = $_POST['student_id'][$i];
                $actual_session = $_POST['actual_session'][$i];
                $current_session = $_POST['current_session'][$i];
                
                $original_result_id = $_POST['result_id'][$i];
                $original_total_internal_marks = $_POST['original_total_internal_marks'][$i];
                $original_1st_examinee = $_POST['original_1st_examinee'][$i];
                $original_2nd_examinee = $_POST['original_2nd_examinee'][$i];
                $original_3rd_examinee = $_POST['original_3rd_examinee'][$i];
                $original_total_final_marks = $_POST['original_total_final_marks'][$i];
                $original_3rd_examinee_eligibility = $_POST['original_3rd_examinee_eligibility'][$i];
                // default vabe 0 entry hoise tai 0 ager theke initialize kore rakhsi. 1 hole 3rd_examinee er marks dorkar porbe.
                $_3rd_examinee_eligibility = 0;
                
                // marks already entry hoye thakle sekhetre $total_improvement_exam er value increase hobe na otherwise hobe
                if($marks_already_entered_array[$i]==1)
                {
                    $total_improvement_exam = $_POST['total_improvement_exam'][$i];
                }
                else 
                {
                    $total_improvement_exam = $_POST['total_improvement_exam'][$i] + 1;
                }
                
                // ceil function use korsi jate value 77.25 or 77.5 jai hok nak kn seta tar uporer value niye nibe jemon ei khetre 78.
                
                $original_total_marks = ceil($original_total_internal_marks + $original_total_final_marks);
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
                    
                    $update_improvement_result = "UPDATE `improvement_result` SET `1st_examinee` = '$_1st_examinee', `2nd_examinee` = '$_2nd_examinee', `3rd_examinee` = '-1', `3rd_examinee_eligibility` = '$_3rd_examinee_eligibility', `total_final_marks` = '$avg' WHERE `id` = '$improvement_res_id' AND `selection` = 'Y'";
                    
                    // jdi 3rd_examineer marks dewar proyojon na hoy tahole tar value -1 hoye jabe. karon je improve dicche tar khetre original marks e 3rd examineer marks thakte pare. kintu amra jehetu improve er marks ta main table e nicchi tai ager marks ta ar thakbe na.
                    $_3rd_examinee = -1;
                }
                else
                {   
                    // enter_final_marks er khetre amra 3rd_examinee_eligbility check kori then 3rd_examiner er marks jdi proyojon thakeo sekhetre amra 3rd examiner er marks na diyeo data entry korte pari. jehetu eikhane third examiner er marks na dile data entry hobe na ei kaj kortesi tai ar kono validation lagbe na. directly $_3rd_examinee_elgibility = 1 diye dibo.
                    
                    $_3rd_examinee_eligibility = 1;
                    
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
                    $update_improvement_result = "UPDATE `improvement_result` SET `1st_examinee` = '$_1st_examinee', `2nd_examinee` = '$_2nd_examinee', `3rd_examinee` = '$_3rd_examinee', `3rd_examinee_eligibility` = '$_3rd_examinee_eligibility', `total_final_marks` = '$avg' WHERE `id` = '$improvement_res_id' AND `selection` = 'Y'";
                }
                $run_update_improvement_result = mysqli_query($conn, $update_improvement_result);
                
                if($run_update_improvement_result)
                {
                    // ceil function use korsi jate value 77.25 or 77.5 jai hok nak kn seta tar uporer value niye nibe jemon ei khetre 78.
                    
                    // eita hocche improve dewar pore notun marks.
                    $new_total_marks = ceil($avg + $original_total_internal_marks);
                    
                    if($original_total_marks<$fail_limit)
                    {
                        if($new_total_marks<$fail_limit)
                        {
                            // abaro jdi fail kore tkhn nicher query kaj hobe.
                            /*  suppose $total_internal = 16 original_total_final_marks = 20 
                                so original_total_marks = 16 +20 = 36, improvement_elgi = Y, improvement_resut_stts = N hobe
                                improve dewar pore $total_internal = 16, new_total_final_marks = 30
                                so new_total_marks = 16+30=46, improvement_elgblty = N, improvement_result_stts = Y hobe
                                ekhn suppose abr new_total_final_marks change kora holo, new_total_final_marks = 15
                                so new_total_marks = 16+15=31, improvement_elgibility = Y, improvement_resul_stts = N hobe
                                
                                muloto eijonnoi result table e ager data gula update kore dicchi.    
                            */
                            $update_result_columns = "UPDATE `result` SET `improvement_eligibility` = 'Y', `improvement_result_status` = 'N', `total_improvement_exam` = '$total_improvement_exam', `1st_examinee` = '$original_1st_examinee', `2nd_examinee` = '$original_2nd_examinee', `3rd_examinee` = '$original_3rd_examinee', `3rd_examinee_eligibility` = '$original_3rd_examinee_eligibility', `total_final_marks` = '$original_total_final_marks'  WHERE `id` = '$original_result_id'";
                            
                            // echo $update_result_columns;
                            // exit();
                        }
                        else
                        {
                            // age fail korar pore jdi pore pass kore tkhn 1st_examinee, 2nd_examinee, 3rd_examinee er marks result table e update hobe jate ekta table thekei amra cgpa calculate korte pari.
                            
                            // jehetu age fail chilo ar ekhn pass korse tar mane tar grade change hoise tai ar gpa change hoise kina ta check korar dorkar nai.
                            $update_result_columns = "UPDATE `result` SET `improvement_eligibility` = 'N', `improvement_result_status` = 'Y', `total_improvement_exam` = '$total_improvement_exam', `1st_examinee` = '$_1st_examinee', `2nd_examinee` = '$_2nd_examinee', `3rd_examinee` = '$_3rd_examinee', `3rd_examinee_eligibility` = '$_3rd_examinee_eligibility', `total_final_marks` = '$avg' WHERE `id` = '$original_result_id'";
                            
                        }
                    }
                    else 
                    {
                        if($avg>$original_total_final_marks)
                        {
                            // total_internal hocche 21, ager total_final chilo 37 ar ekhn total_final jeta $avg e ache seta hocche 37.5. so uporer condition true. kintu 21+37 = 58 ar 21+37.5=58.5 ceil() korle 59 hobe. 58 ar 59 duitar grade hocche 2.75. jar mane daray total_final marks improve dewar pore barlei je CGPA barbe ta na. tai age gpa_counting() call kore check korte hobe je gpa barse naki. jdi bare then improvement_eligibility = N, improvement_result_status = Y hobe. kintu jdi nao bare tobuo improvement_eligibility = N hobe karon se exam diye felse. ar fail na korle ekbar xm dewa jay. tai tar improve dewar eligibility ses.
                            
                            // call gpa_counting using $original_total_marks;
                            $original_result = gpa_counting($original_total_marks);       
                            $original_grade_point = $original_result[1];
                            
                            // call gpa_counting using $new_total_marks;
                            $new_result = gpa_counting($new_total_marks);       
                            $new_grade_point = $new_result[1];
                            
                            if($new_grade_point>$original_grade_point)
                            {
                                // fail kore nai kintu improve dewar pore tar gpa improve hoyse tahole new final marks gula main result table e jabe.
                                $update_result_columns = "UPDATE `result` SET `improvement_eligibility` = 'N', `improvement_result_status` = 'Y', `total_improvement_exam` = '$total_improvement_exam', `1st_examinee` = '$_1st_examinee', `2nd_examinee` = '$_2nd_examinee', `3rd_examinee` = '$_3rd_examinee', `3rd_examinee_eligibility` = '$_3rd_examinee_eligibility', `total_final_marks` = '$avg' WHERE `id` = '$original_result_id'";
                                
                            }
                            else
                            {
                                // fail kore nai kintu improve dewar pore tar marks improve hoise kintu grade improve hoy nai.
                                $update_result_columns = "UPDATE `result` SET `improvement_eligibility` = 'N', `improvement_result_status` = 'N', `total_improvement_exam` = '$total_improvement_exam', `1st_examinee` = '$original_1st_examinee', `2nd_examinee` = '$original_2nd_examinee', `3rd_examinee` = '$original_3rd_examinee', `3rd_examinee_eligibility` = '$original_3rd_examinee_eligibility', `total_final_marks` = '$original_total_final_marks' WHERE `id` = '$original_result_id'";
                            }
                            
                            
                        }
                        else 
                        {
                            $update_result_columns = "UPDATE `result` SET `improvement_eligibility` = 'N', `improvement_result_status` = 'N', `total_improvement_exam` = '$total_improvement_exam', `1st_examinee` = '$original_1st_examinee', `2nd_examinee` = '$original_2nd_examinee', `3rd_examinee` = '$original_3rd_examinee', `3rd_examinee_eligibility` = '$original_3rd_examinee_eligibility', `total_final_marks` = '$original_total_final_marks' WHERE `id` = '$original_result_id'";
                        }
                        
                    }   
                    
                    $run_update_result_columns = mysqli_query($conn, $update_result_columns);
                }
                
                $course_year = $_SESSION['course_year'];
                
                // Call function for updating student result
                
                // jdi improve dewar pore improve hoye thake tahole semester_cgpa table e update korte hobe
                // semester_cgpa table e result update korar jonno.
                $semester_cgpa_total_credit = update_semester_cgpa_table($student_id,$actual_session,$current_session,$course_year,$semester,$department_id);
                
                $semester_cgpa = $semester_cgpa_total_credit[0];
                $total_each_semester_credit = $semester_cgpa_total_credit[1];
                $failed_course_id = $semester_cgpa_total_credit[2];
                
                $update_semester_cgpa = "UPDATE `semester_cgpa` SET `current_cgpa` = '$semester_cgpa', `semester_total_credit` = '$total_each_semester_credit', `failed_course_id` = '$failed_course_id' WHERE `student_id` = '$student_id' AND `actual_session` = '$actual_session' AND `current_session` = '$current_session' AND `course_year` = '$course_year' AND `course_semester` = '$semester'";
                        
                $run_update_semester_cgpa = mysqli_query($conn, $update_semester_cgpa);
                
                
            }
            $delete_selection_N_students = "DELETE FROM `improvement_result` WHERE `selection` = 'N' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$semester' AND `improvement_session` = '$_SESSION[session]' AND `course_id` = '$_GET[course_id]'";
            
            $run_delete_selection_N_students = mysqli_query($conn, $delete_selection_N_students);
            
            ?>
            <script>
                window.alert("Marks Entered Successfully");
                window.location = "view_improvement_marks.php?course_semester=<?php echo $_GET['course_semester'] ?>&course_id=<?php echo $_GET['course_id'] ?>&department_id=<?php echo $department_id ?>";
            </script>
            <?php
        }
    }

?>
