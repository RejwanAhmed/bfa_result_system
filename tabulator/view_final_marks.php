<?php include('lib/tabulator_header.php') ?>
<?php include('valid_department_function.php') ?>
<?php include('gpa_counting_function.php') ?>
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
    else if(isset($_GET['course_semester']) && isset($_GET['course_id']) && isset($_GET['department_id']))
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
        // previous 1st_examinee, 2nd_examinee, 3rd_examinee improvement table theke newa hocche jate ager tabulator login korle improve howar poreo ager result dekhte pare ei jonno improvement_table er sathe left join kora hoyse.
         
        $id_validation_qry = "SELECT r.id, st.roll_no, r.total_internal, i_r.previous_1st_examinee, i_r.previous_2nd_examinee, i_r.previous_3rd_examinee, i_r.previous_3rd_examinee_eligibility, i_r.previous_total_final_marks, r.1st_examinee, r.2nd_examinee, r.3rd_examinee,r.total_final_marks, r.3rd_examinee_eligibility FROM result as r INNER JOIN student_information as st ON r.student_id = st.id LEFT JOIN improvement_result as i_r ON i_r.result_id = r.id WHERE r.current_session = '$_SESSION[session]' && r.course_year = '$_SESSION[course_year]' && r.course_semester = '$_GET[course_semester]' && r.course_id = '$_GET[course_id]' ORDER BY r.actual_session DESC ,st.roll_no ASC";
        $run_id_validation_qry = mysqli_query($conn, $id_validation_qry);
        $num_rows = mysqli_num_rows($run_id_validation_qry);
        if($num_rows==0)
        {
            if($_GET['course_semester']=="1st semester")
            {
                ?>
                <script>
                    window.alert("Invalid Id or No Internal Marks Are Given For This Subject");
                    window.location = "1st_2nd_semester.php?semester=1st semester&department_id=<?php echo $department_id ?>";
                </script>
                <?php
            }
            else
            {
                ?>
                <script>
                    window.alert("Invalid Id or No Internal Marks Are Given For This Subject");
                    window.location = "1st_2nd_semester.php?semester=2nd semester&department_id=<?php echo $department_id ?>";
                </script>
                <?php
            }
            exit();
        }
    }
    
    $course_qry = "SELECT * FROM `course_information` WHERE `id` = '$_GET[course_id]'";
    $run_course_qry = mysqli_query($conn, $course_qry);
    $res_course_qry = mysqli_fetch_assoc($run_course_qry);
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header form_header text-center">
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <h2>Final Marks Of (<?php echo $_SESSION['course_year'].", $_GET[course_semester]" ?>)</h2>
                            <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
                            <h4 class = "text-center text-secondary bg-white"><?php echo "Course Code: $res_course_qry[course_code], Course Title: $res_course_qry[course_title], Course Credit: $res_course_qry[course_credit]" ?></h4>
                        </div>
                        <div class="col-lg-2">
                            <a class = "btn link_btn" href="course_wise_pdf.php?course_semester=<?php echo $_GET['course_semester'] ?>&course_id=<?php echo $_GET['course_id'] ?>&department_id=<?php echo $department_id ?>"><span><i class="fas fa-file-pdf"></i></span> Generate PDF</a>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <form action="" method = "POST">
                        <table class = "table  table-bordered table-hover text-center table-lg-responsive ">
                            <tr>
                                <thead class ="thead-light">
                                    <th>Roll No</th>
                                    <th>Continuous <br>(40%)</th>
                                    <th>Examiner- <br>I</th>
                                    <th>Examiner- <br>II</th>
                                    <th>Examiner- <br>III</th>
                                    <th>Difference</th>
                                    <th>If 3rd Needed</th>
                                    <th>Final <br>(60%)</th>
                                    <th>Total <br>(100)</th>
                                    <th>Letter Grade</th>
                                    <th>Grade Point</th>
                                </thead>
                            </tr>
                            <?php
                            while($row = mysqli_fetch_assoc($run_id_validation_qry))
                            {
                                // 3rd_examinee_elgibility = 0 thakte pare. tai first e null diye rakhsi jate 0 hole kichu print na kore.
                                $_3rd_examinee_eligibility = "";
                                if($row['1st_examinee']==-1 || $row['2nd_examinee']==-1)
                                {
                                    $_1st_examinee = "";
                                    $_2nd_examinee = "";
                                    $total_marks="";
                                    $total_final_marks = "";
                                    $difference = "";
                                    $_3rd_examinee = "";
                                }
                                else
                                {
                                    // impprove hole jate ager tabulator ager result dekhte pare tai previous 1st_examinee, 2nd_examinee, 3rd_examinee improvement_result table theke newa hoyse.
                                    
                                    // ceil function use korsi jate value 77.25 or 77.5 jai hok nak kn seta tar uporer value niye nibe jemon ei khetre 78.
                                    if($row['previous_1st_examinee']!=NULL)
                                    {
                                        $_1st_examinee = $row['previous_1st_examinee'];
                                        $_2nd_examinee = $row['previous_2nd_examinee'];
                                        $total_final_marks = $row['previous_total_final_marks'];
                                        $total_marks = ceil($row['total_internal'] + $row['previous_total_final_marks']);
                                        
                                        if($row['previous_3rd_examinee']==-1)
                                        {
                                            $_3rd_examinee = "";
                                        }
                                        else
                                        {
                                            $_3rd_examinee = $row['previous_3rd_examinee'];
                                        }
                                        // improve howar pore 3rd_exminee_eligibility er man nibe. karon original tabulator dekhtese.
                                        if($row['previous_3rd_examinee_eligibility']==1)
                                        {
                                            $_3rd_examinee_eligibility = "Y";
                                        }
                                    }
                                    else
                                    {
                                        $_1st_examinee = $row['1st_examinee'];
                                        $_2nd_examinee = $row['2nd_examinee'];
                                        $total_final_marks = $row['total_final_marks'];
                                        $total_marks = ceil($row['total_internal'] + $row['total_final_marks']);
                                        
                                        if($row['3rd_examinee']==-1)
                                        {
                                            $_3rd_examinee = "";
                                        }
                                        else
                                        {
                                            $_3rd_examinee = $row['3rd_examinee'];
                                        }
                                        
                                        // improve jdi na hoy othoba improve howar age tabulator ager 3rd_examinee_eligibility er value dekhbe.
                                        if($row['3rd_examinee_eligibility']==1)
                                        {
                                            $_3rd_examinee_eligibility = "Y";
                                        }
                                    }
                                    $difference = abs($_1st_examinee-$_2nd_examinee);
                                }
                                

                                // Grading Count
                                if($row['1st_examinee']==-1 || $row['2nd_examinee']==-1)
                                {
                                    $letter_grade = "";
                                    $grade_point = "";
                                }
                                else
                                {
                                    // call the gpa_counting function from gpa_counting_function.php page and find the letter grade and grade point
                                    $result = gpa_counting($total_marks);       
                                    $letter_grade = $result[0];
                                    $grade_point = $result[1];      
                                }
                                
                                ?>
                                <tr>
                                    <td><?php echo $row['roll_no'] ?></td>
                                    </td>
                                    <td class = "font-weight-bold">
                                        <?php echo $row['total_internal'] ?>
                                    </td>
                                    <td>
                                        <?php echo $_1st_examinee; ?>
                                    </td>

                                    <td>
                                        <?php echo $_2nd_examinee; ?>
                                    </td>
                                    <td><?php echo $_3rd_examinee ?></td>
                                    <td><?php echo $difference?></td>
                                    <td><?php echo $_3rd_examinee_eligibility?></td>
                                    <td class = "font-weight-bold">
                                        <?php echo $total_final_marks; ?>
                                    </td>
                                    
                                    <td class = "font-weight-bold">
                                        <?php echo $total_marks ?>
                                    </td>
                                    <td>
                                        <?php
                                            echo $letter_grade;
                                        ?>
                                    </td>
                                    <td class = "font-weight-bold">
                                        <?php
                                            echo $grade_point;
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('lib/tabulator_footer.php') ?>
