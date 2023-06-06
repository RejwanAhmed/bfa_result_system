<?php

    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    include('lib/db_connection.php');
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
    if(!isset($_GET['course_semester']) || !isset($_GET['course_id']) || !isset($_GET['department_id']) || !isset($_GET['teacher_id']))
    {
        ?>
        <script>
            window.location = "home.php";
        </script>
        <?php
        exit();
    }
    else if(isset($_GET['course_semester']) && isset($_GET['course_id']) && isset($_GET['department_id']) && isset($_GET['teacher_id']) && ($_GET['course_semester']=="1st semester" || $_GET['course_semester']=="2nd semester"))
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
                window.location = "1st_2nd_semester.php?semester=<?php echo $_GET['course_semester']?>&department_id=<?php echo $_GET['department_id'] ?> ";
            </script>
            <?php
            exit();
        }
        // end of department_id validation

        $id_validation_qry = "SELECT r.id, r.course_year, r.course_semester, st.roll_no, r.total_internal FROM result as r INNER JOIN student_information as st ON r.student_id = st.id WHERE r.current_session = '$_SESSION[session]' && r.course_year = '$_SESSION[course_year]' && r.course_semester = '$_GET[course_semester]' && r.teacher_id = '$_GET[teacher_id]' && r.course_id = '$_GET[course_id]' && r.department_id = '$department_id' ORDER BY r.actual_session DESC ,st.roll_no ASC";
        
        $run_id_validation_qry = mysqli_query($conn, $id_validation_qry);
        $num_rows = mysqli_num_rows($run_id_validation_qry);
        if($num_rows==0)
        {
            ?>
            <script>
                window.alert("Invalid Id or No Internal Marks Are Given For This Subject");
                window.location = "1st_2nd_semester.php?semester=<?php echo $_GET['course_semester'] ?>&department_id=<?php echo $_GET['department_id'] ?>";
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
            <th>Continuous <br />(40%)</th>
            </tr>
            </thead>';
    while($row = mysqli_fetch_assoc($run_id_validation_qry))
    {
        $totaL_internal = $row['total_internal'];
        

        $data.='<tr><td>'.$row['roll_no'].'</td><td class = "data">'.$totaL_internal.'</td></tr>';
    }
    $data.='</table>';
    // $mpdf->SetDisplayMode('fullpage');
    // $mpdf->addPage('L');
    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf();
    // $mpdf = SetHeaderByName('hudai');
    $mpdf->SetHTMLHeader('<div class = "header_part"> <h3>Jatiya Kabi Kazi Nazrul Islam University</h3> <p>Dept. of Fine Arts</p> <p>'.$_SESSION['course_year'].' '.$_GET['course_semester'].'</p> <p>Session: '.$_SESSION['session'].'</p> <p>Department/Stream: <b>'.$department_name.'</b></p> <p>Course Code: <b>'.$res_course_qry['course_code'].'</b> Course Title: <b>'.$res_course_qry['course_title'].'</b></p> </div>');

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
