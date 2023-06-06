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
    else if(isset($_GET['course_semester']) && isset($_GET['course_id']) && ($_GET['course_semester']=="1st semester" || $_GET['course_semester']=="2nd semester"))
    {
        $semester = $_GET['course_semester'];
        
        $id_validation_qry = "SELECT i_r.id, i_r.result_id,  st.roll_no, i_r.total_final_marks, i_r.previous_total_final_marks FROM improvement_result as i_r INNER JOIN student_information as st ON i_r.student_id = st.id INNER JOIN result as r ON r.id = i_r.result_id WHERE i_r.improvement_session = '$_SESSION[session]' && i_r.course_year = '$_SESSION[course_year]' && i_r.course_semester = '$semester' && i_r.course_id = '$_GET[course_id]' ORDER BY st.roll_no ASC";
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
            <th>Total <br />(50)</th>
            <th>Letter Grade</th>
            <th>Improvement Grade Point</th>
            <th>Actual Grade Point</th>
            <th>Comment</th>
            </tr>
            </thead>';
    while($row = mysqli_fetch_assoc($run_id_validation_qry))
    {
        if($row['total_final_marks']==-1)
        {
            $_1st_examinee = "";
            $_2nd_examinee = "";
            $total_marks="";
            $total_final_marks = "";
            $previous_total_final_marks = "";
        }
        else
        {
            $total_final_marks = ceil($row['total_final_marks']*2);
            $previous_total_final_marks = ceil($row['previous_total_final_marks']*2);

        }

        // Grading Count
        if($row['total_final_marks']==-1)
        {
            $letter_grade = "";
            $grade_point = "";
            $previous_grade_point = "";
        }
        else
        {
            // call the gpa_counting function from gpa_counting_function.php page and find the letter grade and grade point
            $result = gpa_counting_viva_voce($total_final_marks);       
            $letter_grade = $result[0];
            $grade_point = $result[1];
            
            // Call gpa to get previous grade point before improvement
            $previous_result = gpa_counting_viva_voce($previous_total_final_marks);
            $previous_grade_point = $previous_result[1];
            
            // To Add comment column
            $comment = "Not Improved";
            $comment_text_color = "red";
            if($grade_point > $previous_grade_point)
            {
                $comment = "Improved";
                $comment_text_color = "green";
            }
        }
       

        $data.='<tr><td>'.$row['roll_no'].'</td><td class = "data">'.$total_final_marks.'</td><td>'.$letter_grade.'</td><td class = "data">'.$grade_point.'</td><td class = "data">'.$previous_grade_point.'</td><td class = "data" style = "color: '.$comment_text_color.'">'.$comment.'</td></tr>';
    }
    $data.='</table>';
    // $mpdf->SetDisplayMode('fullpage');
    // $mpdf->addPage('L');
    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf();
    
    if($res_course_qry['course_code']!=NULL)
    {
        $course_code = "Course Code: $res_course_qry[course_code]";
    }
    if($res_course_qry['course_title']==NULL)
    {
        $course_title = "Course Title: Viva Voce";
    }
    else
    {
        $course_title = "Course Title: $res_course_qry[course_title], ";
    }
    // $mpdf = SetHeaderByName('hudai');
    $mpdf->SetHTMLHeader('<div class = "header_part"> <h3>Jatiya Kabi Kazi Nazrul Islam University</h3> <p>Dept. of Fine Arts</p> <p>'.$_SESSION['course_year'].' '.$_GET['course_semester'].' Final Examination - '.$_SESSION['exam_year'].'</p> <p>Session: '.$_SESSION['session'].'</p><p>Department/Stream: <b>'.$department_name.'</b></p> <p>'.$course_code.' '.$course_title.'</p> <p>Improvement Result</p> </div>');

    $mpdf->AddPage('', // L - landscape, P - portrait
    '', '', '', '',
    5, // margin_left
    5, // margin right
    48, // margin top
    20, // margin bottom
    5, // margin header
    0); // margin footer
    $mpdf->WriteHtml($data); // will crate the pdf
    ob_clean();
    $file_name = time().'.pdf';
    $mpdf->Output($file_name,'D'); // d for download
    // echo $data;
?>
