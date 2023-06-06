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
    if(!isset($_GET['course_semester']) || !isset($_GET['department_id']))
    {
        ?>
        <script>
            window.location = "home.php";
        </script>
        <?php
        exit();
    }
    else if(isset($_GET['course_semester']) && isset($_GET['department_id']))
    {
        // jdi kono student improve dey tkhn tar result(with 3rd_examinee_eligibility) improve table e chole jay. ar oi student er jdi result improve hoy tahole(with 3rd_examinee_eligibility) original table e chole ase. ekta student improve dewar age tar 3rd_examinee_elgibility er value 1 chilo kintu improve dewar pore tar 3rd_examinee_eligibility er value 0 holo karon tkhn 3rd_examinee er marks er proyojon hoy nai. ekhn original tabulator jkhn asbe tkhn jate se original result dekhte pare tai improvement_result tabler sathe join korsi. abar emno hote pare ekta student er original result e 3rd_examinee_eligibility er value 0 kintu improve dewar time e tar 3rd_examinee_eligibility er value 1 chilo. jehetu original e chilo na tai tar value dekhano jabe na.
        $id_validation_qry = "SELECT r.id, st.roll_no, r.3rd_examinee, i_r.previous_3rd_examinee, i_r.previous_3rd_examinee_eligibility, r.3rd_examinee_eligibility, c.course_code, c.course_title, c.course_credit FROM result as r INNER JOIN student_information as st ON r.student_id = st.id INNER JOIN course_information as c ON c.id = r.course_id LEFT JOIN improvement_result as i_r ON i_r.result_id = r.id WHERE r.current_session = '$_SESSION[session]' AND r.course_year = '$_SESSION[course_year]' AND r.course_semester = '$_GET[course_semester]' AND (r.3rd_examinee_eligibility = '1' OR i_r.previous_3rd_examinee_eligibility = '1') AND r.department_id = '$_GET[department_id]' ORDER BY r.course_id ASC, st.roll_no ASC";
        
    
        $run_id_validation_qry = mysqli_query($conn, $id_validation_qry);
        $num_rows = mysqli_num_rows($run_id_validation_qry);
        if($num_rows==0)
        {
            if($_GET['course_semester']=="1st semester")
            {
                ?>
                <script>
                    window.alert("No Student Found For 3rd Examiner Marks!!");
                    window.location = "1st_2nd_semester.php?semester=1st semester&department_id=<?php echo $_GET['department_id'] ?>";
                </script>
                <?php
            }
            else
            {
                ?>
                <script>
                    window.alert("No Student Found For 3rd Examiner Marks!!");
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
            <th>Course Code</th>
            <th>Course Title</th>
            <th>Course Credit</th>
            <th>3rd Examiner</th>
            </tr>
            </thead>';
    while($row = mysqli_fetch_assoc($run_id_validation_qry))
    {
        // jdi improvement tabler sathe join na hoy othoba join hoise kintu original result er time e 3rd_examinee_eligibility 0 chilo tai previous_3rd_examinee_eligibility = NULL hobe and original data show korte hobe. 
        // ar jdi improvement_tabler sathe join hoy tkhn jdi previous_3rd_examinee_eligibility  = 1 hoy mane original result e 3rd_examinee_eligibility = 1 chilo tkhn se data dekhate hobe.
        // abar emno hote pare ekta student er original result e 3rd_examinee_eligibility er value 0 kintu improve dewar time e tar 3rd_examinee_eligibility er value 1 chilo. jehetu original e chilo na tai tar value dekhano jabe na. ei case ta ignore korte hobe tai else er moddhe continue diye disi.
        if($row['previous_3rd_examinee_eligibility']==NULL)
        {
            if($row['3rd_examinee']==-1)
            {
                $_3rd_examinee = "--";
            }
            else
            {
                $_3rd_examinee = $row['3rd_examinee'];
            }
        }
        else if($row['previous_3rd_examinee_eligibility']==1)
        {
            $_3rd_examinee = $row['previous_3rd_examinee'];
        }
        else 
        {
            continue;
        }
        $data.='<tr><td>'.$row['roll_no'].'</td><td class = "data">'.$row['course_code'].'</td><td>'.$row['course_title'].'</td><td>'.$row['course_credit'].'</td><td>'.$_3rd_examinee.'</td></tr>';
    }
    $data.='</table>';
    // $mpdf->SetDisplayMode('fullpage');
    // $mpdf->addPage('L');
    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf();
    // $mpdf = SetHeaderByName('hudai');
    $mpdf->SetHTMLHeader('<div class = "header_part"> <h3>Jatiya Kabi Kazi Nazrul Islam University</h3> <p>Dept. of Fine Arts</p> <p>'.$_SESSION['course_year'].' '.$_GET['course_semester'].' Final Examination - '.$_SESSION['exam_year'].'</p> <p>Session: '.$_SESSION['session'].'</p> <p>Department/Stream: <b>'.$department_name.'</b></p> <p>List of Students of 3rd Examiner</p> </div>');

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
