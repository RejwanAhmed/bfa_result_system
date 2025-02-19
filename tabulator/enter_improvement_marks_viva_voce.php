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
        
        $student_id_validation_qry = "SELECT i_r.id, i_r.result_id, i_r.actual_session, i_r.current_session, i_r.student_id, st.roll_no, i_r.previous_total_final_marks as original_total_final_marks, i_r.total_final_marks, r.total_improvement_exam FROM improvement_result as i_r INNER JOIN student_information as st ON i_r.student_id = st.id INNER JOIN result as r ON r.id = i_r.result_id WHERE i_r.improvement_session = '$_SESSION[session]' && i_r.course_year = '$_SESSION[course_year]' && i_r.course_semester = '$semester' && i_r.course_id = '$_GET[course_id]' AND i_r.selection = 'Y' ORDER BY st.roll_no ASC";
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
    
    $course_qry = "SELECT * FROM `course_information` WHERE `id` = '$_GET[course_id]' AND `course_type` = 'Viva-Voce'";
    $run_course_qry = mysqli_query($conn, $course_qry);
    $num_rows_course_qry_viva_voce = mysqli_num_rows($run_course_qry);
    // jdi valid course kintu viva-voce na tkhn to asole invalid course hisabe dhora hobe tai ei validation
    if($num_rows_course_qry_viva_voce==0)
    {
        ?>
        <script>
            window.alert("Invalid Course");
            window.location = "1st_2nd_semester.php?semester=<?php echo $_GET['course_semester'] ?>&department_id=<?php echo $_GET['department_id'] ?>";
        </script>
        <?php
        exit();
    }
    else
    {
        $res_course_qry = mysqli_fetch_assoc($run_course_qry);
    }
    
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header form_header text-center">
                    <h2>Improvement Marks Of (<?php echo $_SESSION['course_year'].", ".$semester?>)</h2>
                    <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
                    <h4 class = "text-center text-secondary bg-white"><?php 
                    if($res_course_qry['course_code']!=NULL)
                    {
                        echo "Course Code: $res_course_qry[course_code],";
                    }
                    if($res_course_qry['course_title']==NULL)
                    {
                        echo "Course Title: Viva Voce, ";
                    }
                    else
                    {
                        echo "Course Title: $res_course_qry[course_title], ";
                    }
                    echo "Course Credit: $res_course_qry[course_credit]"; ?></h4>
                </div>
                <div class="card-body table-responsive">
                    <form action="" method = "POST">
                        <div class="table-responsive">
                            <table class = "table  table-bordered table-hover text-center">
                                <tr>
                                    <thead class ="thead-light">
                                        <th>Roll No</th>
                                        <th>Viva Voce Marks</th>
                                    </thead>
                                </tr>
                                <?php
                                $i=0;
                                $marks_already_entered_array = array();
                                while($row = mysqli_fetch_assoc($run_student_id_validation_qry))
                                {
                                    // marks age theke entry ache kina ta check korechi. jate kore amra total_improvement_exam er value barabo kina ta siddhanto nite pari
                                    if($row['total_final_marks']!=-1)
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
                                        
                                        <input type="hidden" name = "original_total_final_marks[]" value = "<?php echo $row['original_total_final_marks']?>">
                                        
                                        <input type="hidden" name = "total_improvement_exam[]" value = "<?php echo $row['total_improvement_exam']?>">
                                        <td>
                                            <input type="number" step = "0.01" name = "total_final_marks[]"
                                            placeholder="Enter Marks" value = "<?php
                                            if(isset($_POST['total_final_marks'][$i]))
                                            {
                                                echo $_POST['total_final_marks'][$i];
                                            }
                                            else if($row['total_final_marks']==-1)
                                            {
                                                echo "";
                                            }
                                            else
                                            {
                                                echo $row['total_final_marks'];
                                            } ?>" required>
                                            <p  id = "total_final_marks<?php echo $i ?>"  class = "font-weight-bold bg-warning text-center mt-2"></p>
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
        $count_error = 0;
        for($i=0;$i<sizeof($_POST['id']);$i++)
        {
            if($_POST['total_final_marks'][$i]>50 || $_POST['total_final_marks'][$i]<0)
            {
                ?>
                <script>
                    document.getElementById('total_final_marks<?php echo $i ?>').innerHTML = `<i class="fas fa-exclamation-circle"></i> Marks can not > 50 or < 0`;
                </script>
                <?php
                $count_error++;
            }
        }
        if($count_error>0)
        {
            exit();
        } 
        else if($count_error==0)
        {
            for($i=0;$i<sizeof($_POST['id']);$i++)
            {
                $improvement_res_id = $_POST['id'][$i];
                
                $student_id = $_POST['student_id'][$i];
                $actual_session = $_POST['actual_session'][$i];
                $current_session = $_POST['current_session'][$i];
                
                $original_result_id = $_POST['result_id'][$i];
                $original_total_final_marks = $_POST['original_total_final_marks'][$i];
                $total_final_marks = $_POST['total_final_marks'][$i];
                
                // marks already entry hoye thakle sekhetre $total_improvement_exam er value increase hobe na otherwise hobe
                if($marks_already_entered_array[$i]==1)
                {
                    $total_improvement_exam = $_POST['total_improvement_exam'][$i];
                }
                else 
                {
                    $total_improvement_exam = $_POST['total_improvement_exam'][$i] + 1;
                }
                
                $update_improvement_result = "UPDATE `improvement_result` SET `total_final_marks` = '$total_final_marks' WHERE `id` = '$improvement_res_id' AND `selection` = 'Y'";
                $run_update_improvement_result = mysqli_query($conn, $update_improvement_result);
                if($run_update_improvement_result)
                {
                    // jdi total_final_marks original_total_final_marks er cheye besi hoy ebong total_final_marks er value 24 er besi hoy taholei improve hoise dhora hoy. karon tkhn cgpa change hoy.
                    if($total_final_marks>$original_total_final_marks && ceil($total_final_marks*2)>=40)
                    {
                        $update_result_columns = "UPDATE `result` SET `improvement_eligibility` = 'N', `improvement_result_status` = 'Y', `total_improvement_exam` = '$total_improvement_exam', `total_final_marks` = '$total_final_marks' WHERE `id` = '$original_result_id'";
                       
                    }
                    else 
                    {
                        $update_result_columns = "UPDATE `result` SET `improvement_eligibility` = 'Y', `improvement_result_status` = 'N', `total_improvement_exam` = '$total_improvement_exam', `total_final_marks` = '$original_total_final_marks' WHERE `id` = '$original_result_id'";
                    }
                    $run_update_result_columns = mysqli_query($conn, $update_result_columns);
                }
                
                $course_year = $_SESSION['course_year'];
                
                // Call function for updating student result
                // jdi improve dewar pore improve hoye thake tahole semester_cgpa table e update korte hobe
               
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
                window.location = "view_improvement_marks_viva_voce.php?course_semester=<?php echo $_GET['course_semester'] ?>&course_id=<?php echo $_GET['course_id'] ?>&department_id=<?php echo $department_id ?>";
            </script>
            <?php
        }
    }

?>
