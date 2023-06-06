<?php

    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    include('lib/db_connection.php');
    include('valid_department_function.php');
    if(!isset($_SESSION['teacher_id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
    // Validation
    if(!isset($_GET['session']) && !isset($_GET['course_year']) && !isset($_GET['course_semester']) || !isset($_GET['course_id']) || !isset($_GET['department_id']))
    {
        ?>
        <script>
            window.location = "assigned_course_list.php";
        </script>
        <?php
        exit();
    }
    else if(isset($_GET['session']) && isset($_GET['course_year']) && isset($_GET['course_semester']) && isset($_GET['course_id']) && isset($_GET['department_id']) && ($_GET['course_semester']=="1st semester" || $_GET['course_semester']=="2nd semester"))
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

        $id_validation_qry = "SELECT r.id, r.course_year, r.course_semester, st.roll_no, r.attendance, r.mid1, r.mid2, r.ass_pre, r.total_internal FROM result as r INNER JOIN student_information as st ON r.student_id = st.id WHERE r.current_session = '$_GET[session]' && r.course_year = '$_GET[course_year]' && r.course_semester = '$_GET[course_semester]' && r.course_id = '$_GET[course_id]' && r.department_id = '$department_id' ORDER BY r.actual_session DESC ,st.roll_no ASC";
        
        $run_id_validation_qry = mysqli_query($conn, $id_validation_qry);
        $num_rows = mysqli_num_rows($run_id_validation_qry);
        if($num_rows==0)
        {
            ?>
            <script>
                window.alert("Invalid Id or No Internal Marks Are Given For This Subject");
                window.location = "assigned_course_list.php";
            </script>
            <?php
            exit();
        }
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
            <th width = "30%">Roll</th>
            <th>Attendance</th>
            <th>Mid1</th>
            <th>Mid2</th>
            <th>Ass./Pre.</th>
            <th>Continuous <br />(40%)</th>
            </tr>
            </thead>';
    while($row = mysqli_fetch_assoc($run_id_validation_qry))
    {
        $totaL_internal = $row['total_internal'];
        $attendance = $row['attendance'];
        $mid1 = $row['mid1'];
        $mid2 = $row['mid2'];
        $ass_pre = $row['ass_pre'];
        if($attendance==-1)
        {
            $attendance = "--";
            $mid1 = "--";
            $mid2 = "--";
            $ass_pre = "--";
        }
    

        $data.='<tr><td>'.$row['roll_no'].'</td><td>'.$attendance.'</td><td>'.$mid1.'</td><td>'.$mid2.'</td><td>'.$ass_pre.'</td><td class = "data">'.$totaL_internal.'</td></tr>';
    }
    $data.='</table>';
    // $mpdf->SetDisplayMode('fullpage');
    // $mpdf->addPage('L');
    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf();
    // $mpdf = SetHeaderByName('hudai');
    $mpdf->SetHTMLHeader('<div class = "header_part"> <h3>Jatiya Kabi Kazi Nazrul Islam University</h3> <p>Dept. of Fine Arts</p> <p>'.$_GET['course_year'].' '.$_GET['course_semester'].'</p> <p>Session: '.$_GET['session'].'</p> <p>Department/Stream: <b>'.$department_name.'</b></p> <p>Course Code: <b>'.$res_course_qry['course_code'].'</b> Course Title: <b>'.$res_course_qry['course_title'].'</b></p> </div>');

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
