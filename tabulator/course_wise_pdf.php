<?php

    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    include('lib/db_connection.php');
    include('gpa_counting_function.php');
    include('valid_department_function.php');
    if(!isset($_SESSION['exam_committee_id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
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
    
    $course_qry = "SELECT * FROM `course_information` WHERE `id` = '$_GET[course_id]'";
    $run_course_qry = mysqli_query($conn, $course_qry);
    $res_course_qry = mysqli_fetch_assoc($run_course_qry);

    $data = '<style>
    *
    {
        margin:0;
        padding: 0;
    }
    .table{width: 100%; border-collapse: collapse}
    .table td, .table th{
        border: 1px solid black;
        padding: 5px 5px;
        font-size: 8px;
        text-align:center;
    }
    .data
    {
        font-weight: bold;
    }
    .header_part
    {
        background-color: rgba(0,0,0,.03);
        color:black;
        text-align:center;
        margin-bottom: 5px;
        border-bottom:1px solid rgba(0,0,0,.125);
        padding: 1px;
    }

    p,h3
    {
        margin: 1px;
        text-align: center;
    }

    </style>';

    $data.='<table class = "table table-bordered" style="overflow: wrap " >';
    $data .= '';
    $data .= '<thead>
            <tr>
            <th width = "10%">Roll</th>
            <th>Continuous <br />(40%)</th>
            <th>Examiner- <br />I</th>
            <th>Examiner- <br />II</th>
            <th>Examiner- <br />III</th>
            <th text-rotate = "90">Difference</th>
            <th text-rotate = "90">If 3rd Needed</th>
            <th>Final <br />(60%)</th>
            <th>Total <br />(100)</th>
            <th>Letter Grade</th>
            <th>Grade Point</th>
            </tr>
            </thead>';
    while($row = mysqli_fetch_assoc($run_id_validation_qry))
    {   
        // 3rd_examinee_elgibility = 0 thakte pare. tai first e null diye rakhsi jate 0 hole kichu print na kore.
        $_3rd_examinee_eligibility = "";
        
        $totaL_internal = $row['total_internal'];
        if($row['1st_examinee']==-1 || $row['2nd_examinee']==-1)
        {
            $_1st_examinee = "";
            $_2nd_examinee = "";
            $total_marks="";
            $difference = "";
            $total_final_marks = "";
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

        $data.='<tr><td>'.$row['roll_no'].'</td><td class = "data">'.$totaL_internal.'</td><td>'.$_1st_examinee.'</td><td>'.$_2nd_examinee.'</td><td>'.$_3rd_examinee.'</td><td>'.$difference.'</td><td>'.$_3rd_examinee_eligibility.'</td><td class = "data">'.$total_final_marks.'</td><td class = "data">'.$total_marks.'</td><td>'.$letter_grade.'</td><td class = "data">'.$grade_point.'</td></tr>';
    }
    $data.='</table>';
    // $mpdf->SetDisplayMode('fullpage');
    // $mpdf->addPage('L');
    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf();
    // $mpdf = SetHeaderByName('hudai');
    $mpdf->SetHTMLHeader('<div class = "header_part"> <h3>Jatiya Kabi Kazi Nazrul Islam University</h3> <p>Dept. of Fine Arts</p> <p>'.$_SESSION['course_year'].' '.$_GET['course_semester'].' Final Examination - '.$_SESSION['exam_year'].'</p> <p>Session: '.$_SESSION['session'].'</p> <p>Department/Stream: <b>'.$department_name.'</b></p> <p>Course Code: <b>'.$res_course_qry['course_code'].'</b> Course Title: <b>'.$res_course_qry['course_title'].'</b></p> </div>');

    $mpdf->AddPage('', // L - landscape, P - portrait
    '', '', '', '',
    5, // margin_left
    5, // margin right
    42, // margin top
    20, // margin bottom
    5, // margin header
    0); // margin footer
    $mpdf->WriteHtml($data); // will crate the pdf
    ob_clean();
    $file_name = time().'.pdf';
    $mpdf->Output($file_name,'D'); // d for download
    // echo $data;
?>
