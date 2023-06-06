<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    include('lib/db_connection.php');
    // include('gpa_counting_function.php');
    include('semester_wise_cgpa_calculation.php');
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
    else if(isset($_GET['course_semester']) && ($_GET['course_semester']=='1st semester' || $_GET['course_semester'] == '2nd semester'))
    {
        $course_semester = $_GET['course_semester'];
        
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
        
        
        // ei function theke $stdnt_id_array, $stdnt_actual_session_array, $stdnt_current_session_array, $first_semester_cgpa_array, $stdnt_roll_array, $stdnt_total_internal_array, $stdnt_total_final_marks_array, $stdnt_letter_grade_array, $stdnt_grade_point_array,$first_semester_credit_array,$first_semester_course_code_array, $total_first_semester_assigned_course, $$first_semester_course_type_array ei data gula return kortese. amra $stdnt_roll_array theke suru kore ses porojonto data use korbo pdf genereate korar jonno. ar ager gula result calculate button click korar pore result calculate korar kaje lage.
        $result = semester_wise_cgpa_calculation($course_semester, $department_id);
    }
    else
    {
        ?>
            <script>
                window.alert("Invalid Semster");
                window.location = "home.php";
            </script>
        <?php 
    }
    if($course_semester=="2nd semester")
    {
        // 2nd semester er result calculate korar tm e 1st semester er total number of credit dekhanor jonno ei query use kora hoise.
        $first_semester_assigned_course = "SELECT ac.course_id, c.course_code, c.course_credit  FROM assigned_course_information as ac INNER JOIN course_information as c ON ac.course_id = c.id WHERE ac.session = '$_SESSION[session]' AND ac.course_year = '$_SESSION[course_year]' AND ac.course_semester = '1st semester' AND ac.course_id !='-1' AND ac.teacher_id !='-1' AND ac.verification = '1' AND ac.department_id = '$department_id' ORDER BY c.id ASC";
        $run_first_semester_assigned_course = mysqli_query($conn, $first_semester_assigned_course);
        $total_first_semester_assigned_course =  mysqli_num_rows($run_first_semester_assigned_course);


        $first_semester_credit_array = array();
        while($row = mysqli_fetch_assoc($run_first_semester_assigned_course))
        {
            $first_semester_credit_array[]= $row['course_credit'];
        }
        $total_first_semester_course_credit = array_sum($first_semester_credit_array);
        // End of 1st semester total credit
        
        // Start of year wise and overall result calculation 
        
        // jei sob student 2nd semester exam attend korche sudhu matro tader roll er result show korabo.
        $total_student_of_second_semester = $result[0];
        
        $gpa_in_1st_semester_array = array();
        $gpa_in_2nd_semester_array = array();
        
        $overall_cgpa_in_1_array = array();
        $overall_cgpa_in_1_without_improvement_array = array();
        $overall_cgpa_in_2_array = array();
        $overall_cgpa_in_2_without_improvement_array = array();
        $overall_cgpa_in_3_array = array();
        $overall_cgpa_in_3_without_improvement_array = array();
        $overall_cgpa_in_4_array = array();
        $cgpa_till_now_array = array();
        $total_credit_array = array();
        $tota_earned_credit_array = array();
        // jdi kono ekta student er kono ekta semester e ekta ba tar besi course e fail thake tahole oi course er id semester_cgpa table e thake. tai oi student jekoyta semester e fail kore tar value ta $count_failed_semester_courses_array te thakbe. proti ta student er jonno alada alda value hobe tai array te push korbo.
        $count_failed_semester_courses_array = array();
        
        for($i=0;$i<sizeof($total_student_of_second_semester);$i++)
        {
            // eikhane ekta particular student er id aand loggedin year parameter hisabe pathano hocche binimoye oi student er loggedin year er somosto valid information semester_cgpa table theke pawa jacche.
            $result_from_semester_table = year_result_counting($_SESSION['course_year'], $total_student_of_second_semester[$i]);
            $first_year_cgpa_each_student = 0;
            $first_year_cgpa_each_student_without_improvement = 0;
            $second_year_cgpa_each_student = 0;
            $second_year_cgpa_each_student_without_improvement = 0;
            $third_year_cgpa_each_student = 0;
            $third_year_cgpa_each_student_without_improvement = 0;
            $fourth_year_cgpa_each_student = 0;
            $total_credit_1st_year = 0;
            $total_credit_2nd_year = 0;
            $total_credit_3rd_year = 0;
            $total_credit_4th_year = 0;
            
            array_push($count_failed_semester_courses_array, $result_from_semester_table[7]);
            
            // sizeof($result_from_semester_table[1]) = ekta student er koyta semester er data ache ta bujhacche. amra 1 er jaygya 0,2,3 use korte partam.
            for($j=0;$j<sizeof($result_from_semester_table[1]);$j++)
            {
                // ekta student er ekoi year (1st year) ekoi semester (1st semester) er total 2 result thakte pare. jdi se nijer batch er sathe fail korar pore abr onno batch er sathe dey tahole. oi khetre jdi ager tabulator login kore tahole tar jei result ta invalid chilo seta dekhabe. r notun je batch er sathe dise seta hole tar valid result dekhabe.
                $previous_cgpa_from_semester_table_semester_wise = $result_from_semester_table[4][$j];
                $current_cgpa_from_semester_table_semester_wise = $result_from_semester_table[5][$j];
                $credit_from_semester_table_semester_wise = $result_from_semester_table[6][$j];
                
                $current_session_each_student = $result_from_semester_table[1][$j];
                
                if($_SESSION['session'] < $current_session_each_student)
                {
                    // jei invalid result ache seta fetch kore niye asbe.
                    $student_id_each_student = $total_student_of_second_semester[$i];
                    $current_session_each_student = $_SESSION['session'];
                    $course_year_each_student = $result_from_semester_table[2][$j];
                    $course_semester_each_student = $result_from_semester_table[3][$j];
                    
                    $invalid_result = invalid_result_selection($student_id_each_student,$current_session_each_student,$course_year_each_student, $course_semester_each_student);
                    
                    $previous_cgpa_from_semester_table_semester_wise = $invalid_result[0];
                    $current_cgpa_from_semester_table_semester_wise = $invalid_result[1];
                    $credit_from_semester_table_semester_wise = $invalid_result[2];
                    
                    // jdi first semester result hoy tahole gpa_in_1_1 array te push korbo ar second semester hoy tahole gpa_in_1_2 array te push korbo.
                    
                    // jdi course year = 1st year hoy tahole nicher data gula lagbe
                }
                
                if($_SESSION['course_year'] == "1st year")
                {
                    if($result_from_semester_table[3][$j] == "1st semester")
                    {
                        array_push($gpa_in_1st_semester_array, $previous_cgpa_from_semester_table_semester_wise);
                        
                    }
                    else if($result_from_semester_table[3][$j] == "2nd semester")
                    {
                        array_push($gpa_in_2nd_semester_array, $previous_cgpa_from_semester_table_semester_wise);
                    }
                    // first year er total credite rakhtesi.
                    $total_credit_1st_year = $total_credit_1st_year + $credit_from_semester_table_semester_wise;
                    
                    // first year e koto paise ta dekhabe
                    $first_year_cgpa_each_student = $first_year_cgpa_each_student + ($previous_cgpa_from_semester_table_semester_wise*$credit_from_semester_table_semester_wise);
                } 
                else if($_SESSION['course_year']=="2nd year")
                {
                    if($result_from_semester_table[2][$j]=="1st year")
                    {
                        $total_credit_1st_year = $total_credit_1st_year + $credit_from_semester_table_semester_wise;
                        
                        $first_year_cgpa_each_student = $first_year_cgpa_each_student + ($current_cgpa_from_semester_table_semester_wise * $credit_from_semester_table_semester_wise);
                        
                        $first_year_cgpa_each_student_without_improvement = $first_year_cgpa_each_student_without_improvement + ($previous_cgpa_from_semester_table_semester_wise * $credit_from_semester_table_semester_wise);
                    }
                    else
                    {
                        if($result_from_semester_table[3][$j] == "1st semester")
                        {
                            array_push($gpa_in_1st_semester_array, $previous_cgpa_from_semester_table_semester_wise);
                        }
                        else if($result_from_semester_table[3][$j] == "2nd semester")
                        {
                            array_push($gpa_in_2nd_semester_array, $previous_cgpa_from_semester_table_semester_wise);
                        }
                        // 2nd year er total credit koto hocche ta rakhtesi.
                        $total_credit_2nd_year = $total_credit_2nd_year + $credit_from_semester_table_semester_wise;
                        
                        // 2nd year e koto paise ta dekhabe
                        $second_year_cgpa_each_student = $second_year_cgpa_each_student + ($previous_cgpa_from_semester_table_semester_wise*$credit_from_semester_table_semester_wise);
                    }
                }
                else if($_SESSION['course_year'] == "3rd year")
                {
                    if($result_from_semester_table[2][$j] == "1st year")
                    {
                        $total_credit_1st_year = $total_credit_1st_year + $credit_from_semester_table_semester_wise; 
                        
                        $first_year_cgpa_each_student = $first_year_cgpa_each_student + ($current_cgpa_from_semester_table_semester_wise*$credit_from_semester_table_semester_wise);
                        
                    }
                    else if($result_from_semester_table[2][$j]=="2nd year")
                    {
                        $total_credit_2nd_year = $total_credit_2nd_year + $credit_from_semester_table_semester_wise;
                        
                        $second_year_cgpa_each_student = $second_year_cgpa_each_student + ($current_cgpa_from_semester_table_semester_wise * $credit_from_semester_table_semester_wise);
                        
                        $second_year_cgpa_each_student_without_improvement = $second_year_cgpa_each_student_without_improvement + ($previous_cgpa_from_semester_table_semester_wise*$credit_from_semester_table_semester_wise);
                    }
                    else
                    {
                        if($result_from_semester_table[3][$j] == "1st semester")
                        {
                            array_push($gpa_in_1st_semester_array, $previous_cgpa_from_semester_table_semester_wise);
                        }
                        else if($result_from_semester_table[3][$j] == "2nd semester")
                        {
                            array_push($gpa_in_2nd_semester_array, $previous_cgpa_from_semester_table_semester_wise);
                        }
                        
                        // 3rd year er total credit koto hocche ta rakhtesi.
                        $total_credit_3rd_year = $total_credit_3rd_year + $credit_from_semester_table_semester_wise;
                        
                        // 3rd year e koto paise ta dekhabe
                        $third_year_cgpa_each_student = $third_year_cgpa_each_student + ($previous_cgpa_from_semester_table_semester_wise*$credit_from_semester_table_semester_wise);
                    }
                }
                else if($_SESSION['course_year'] == "4th year")
                {
                    if($result_from_semester_table[2][$j]=="1st year")
                    {
                        $total_credit_1st_year = $total_credit_1st_year + $credit_from_semester_table_semester_wise; 
                        
                        $first_year_cgpa_each_student = $first_year_cgpa_each_student + ($current_cgpa_from_semester_table_semester_wise*$credit_from_semester_table_semester_wise);
                    }
                    else if($result_from_semester_table[2][$j] == "2nd year")
                    {
                        $total_credit_2nd_year = $total_credit_2nd_year + $credit_from_semester_table_semester_wise;
                        
                        $second_year_cgpa_each_student = $second_year_cgpa_each_student + ($current_cgpa_from_semester_table_semester_wise*$credit_from_semester_table_semester_wise);
                    }
                    else if($result_from_semester_table[2][$j]=="3rd year")
                    {
                        $total_credit_3rd_year = $total_credit_3rd_year + $credit_from_semester_table_semester_wise;
                    
                        $third_year_cgpa_each_student = $third_year_cgpa_each_student + ($current_cgpa_from_semester_table_semester_wise*$credit_from_semester_table_semester_wise);
                        
                        $third_year_cgpa_each_student_without_improvement = $third_year_cgpa_each_student_without_improvement + ($previous_cgpa_from_semester_table_semester_wise*$credit_from_semester_table_semester_wise);
                    }
                    else
                    {
                        if($result_from_semester_table[3][$j] == "1st semester")
                        {
                            array_push($gpa_in_1st_semester_array, $previous_cgpa_from_semester_table_semester_wise);
                        }
                        else if($result_from_semester_table[3][$j] == "2nd semester")
                        {
                            array_push($gpa_in_2nd_semester_array, $previous_cgpa_from_semester_table_semester_wise);
                        }
                        // 4th year er total credit koto hocche ta rakhtesi.
                        $total_credit_4th_year = $total_credit_4th_year + $credit_from_semester_table_semester_wise;
                        // 4th year e koto paise ta dekhabe
                        $fourth_year_cgpa_each_student = $fourth_year_cgpa_each_student + ($previous_cgpa_from_semester_table_semester_wise*$credit_from_semester_table_semester_wise);
                    }
                }
            }
            if($_SESSION['course_year'] == "1st year")
            {
                $first_year_overall_cgpa = $first_year_cgpa_each_student/$total_credit_1st_year;
                array_push($overall_cgpa_in_1_array, $first_year_overall_cgpa);
            }
            else if($_SESSION['course_year'] == "2nd year")
            {
                // first_year_each_cgpa_student er vitore proti ta student er 1st and second semester of first year er result er jogfol astese 
                // first_year_cgpa = first year porjonto with improvement koto paise ta dekhabe
                $first_year_cgpa = $first_year_cgpa_each_student/$total_credit_1st_year;
                
                // first_year_cgpa_without_improvement = first year porjonto without improvement koto paise ta dekhabe.
                $first_year_cgpa_without_improvement = $first_year_cgpa_each_student_without_improvement/$total_credit_1st_year;
                
                // second_year_each_cgpa_student er vitore proti ta student er 1st and second semester of second year er result er jogfol astese 
                // second_year_cgpa = second year e 2ta semester milaya koto paise ta dekhabe.
                $second_year_cgpa = $second_year_cgpa_each_student/$total_credit_2nd_year;
                
                // second_year_overall_cgpa = cgpa till 2nd year porjonto koto ache ta dekhabe.
                $second_year_overall_cgpa = ($first_year_cgpa_each_student + $second_year_cgpa_each_student)/($total_credit_1st_year + $total_credit_2nd_year);
                
                array_push($overall_cgpa_in_2_array, $second_year_cgpa);
                array_push($overall_cgpa_in_1_array, $first_year_cgpa);
                array_push($overall_cgpa_in_1_without_improvement_array, $first_year_cgpa_without_improvement);
                array_push($cgpa_till_now_array, $second_year_overall_cgpa);
            }
            else if($_SESSION['course_year'] == "3rd year")
            {
                // second_year_cgpa = second year porjonto with improvement koto paise ta dekhabe.
                $second_year_cgpa = ($first_year_cgpa_each_student + $second_year_cgpa_each_student)/($total_credit_1st_year+$total_credit_2nd_year);
                
                // second_year_cgpa_without_improvement = second year porjonto improvement chara koto paise ta dekhabe
                $second_year_cgpa_without_improvement = ($first_year_cgpa_each_student + $second_year_cgpa_each_student_without_improvement)/($total_credit_1st_year+$total_credit_2nd_year);
                
                // third_year_cgpa = third year e 2ta semester result er sathe credit er gunfol kore third year er total credit diye vag kore result koto paise ta dekhabe.
                $third_year_cgpa = $third_year_cgpa_each_student/$total_credit_3rd_year;
                
                // third_year_overall_cgpa = cgpa till 3rd year porjonto koto paise ta dekhabe.
                $third_year_overall_cgpa = ($first_year_cgpa_each_student + $second_year_cgpa_each_student + $third_year_cgpa_each_student)/($total_credit_1st_year + $total_credit_2nd_year + $total_credit_3rd_year);
                
                array_push($overall_cgpa_in_3_array, $third_year_cgpa);
                array_push($overall_cgpa_in_2_array, $second_year_cgpa);
                array_push($overall_cgpa_in_2_without_improvement_array, $second_year_cgpa_without_improvement);
                array_push($cgpa_till_now_array, $third_year_overall_cgpa);
            }
            else if($_SESSION['course_year'] == "4th year")
            {
                // third_year_cgpa = third year porjonto with improvement koto paise ta dekhabe.
                $third_year_cgpa = ($first_year_cgpa_each_student + $second_year_cgpa_each_student + $third_year_cgpa_each_student)/($total_credit_1st_year + $total_credit_2nd_year + $total_credit_3rd_year);
                
                // third_year_cgpa_without_improveent = third year porjonto without improvement koto paise ta dekhabe.
                $third_year_cgpa_without_improvement = ($first_year_cgpa_each_student + $second_year_cgpa_each_student + $third_year_cgpa_each_student_without_improvement)/($total_credit_1st_year + $total_credit_2nd_year + $total_credit_3rd_year);
                
                // fourth_year_cgpa = fourth year e 2ta semester milaya koto paise ta dekhabe.
                $fourth_year_cgpa = $fourth_year_cgpa_each_student/$total_credit_4th_year;
                
                // fourth_year_overall_cgpa = cgpa till fourth year porjonto koto paise ta dekhabe.
                $fourth_year_overall_cgpa = ($first_year_cgpa_each_student + $second_year_cgpa_each_student + $third_year_cgpa_each_student + $fourth_year_cgpa_each_student)/($total_credit_1st_year + $total_credit_2nd_year + $total_credit_3rd_year + $total_credit_4th_year);
                
                array_push($total_credit_array, ($total_credit_1st_year + $total_credit_2nd_year + $total_credit_3rd_year + $total_credit_4th_year));
                array_push($overall_cgpa_in_4_array, $fourth_year_cgpa);
                array_push($overall_cgpa_in_3_array, $third_year_cgpa);
                array_push($overall_cgpa_in_3_without_improvement_array, $third_year_cgpa_without_improvement);
                array_push($cgpa_till_now_array, $fourth_year_overall_cgpa);
            }
            
        }
        // End of year wise and overall result calculation S
    }
   
    $data = '<style>
    *
    {
        margin:0;
        padding: 0;
    }
    .table{width: 100%; border-collapse: collapse;}
    .table td, .table th{
        border: 1px solid black;
        padding: 5px 5px;
        font-size: 8px;
        text-align:center;
        // font-size: 20px;
    }
    p,h3
    {
        margin: 5px;
        text-align: center;
    }
    .main_header
    {
        background-color: rgba(0,0,0,.03);
        color:black;
        text-align:center;
        border-bottom:1px solid rgba(0,0,0,.125);
        width: 100%;
        padding: 10px;
    }
    .second_header
    {
        color:black;
        text-align:center;
        width: 100%;
        padding: 10px;
    }
    .right_header
    {
        width: 25%;
        float: left;
    }
    .left_header
    {
        width: 50%;
        float: left;
    }
    </style>';
    $data.='<table class = "table table-bordered" style="overflow: wrap">';
    $data .= '<thead>
                <tr>
                    <th width = "5%">Roll</th>';
    $credit_array = array();
    $course_credit_len = sizeof($result[9]);
    for($i=0;$i<$course_credit_len;$i++)
    {
        $course_credit = $result[9][$i];
        $course_code = $result[10][$i];
        
        echo $course_credit. " ". $course_code."<br>";
        $data.= '<th style = "padding: 0px;">'.$course_code.'('.$course_credit.' Cr.)
                <table class = "table" style = "overflow: wrap; width: 100%;" >
                    <tr text-rotate="90">
                        <td style = "border-left:0px; border-bottom: 0px; width: 20px;">Theory Continuous(40%)</td>
                        <td style = "border-left:0px; border-bottom: 0px; width: 20px;">Theory Final Exam(60%)</td>
                        <td style = "border-left:0px; border-bottom: 0px; width: 20px;">Total(100%)</td>
                        <td  style = "border-left:0px; border-bottom: 0px; width: 20px;">LG</td>
                        <td  style = " border-left:0px; border-right:0px; border-bottom: 0px; width: 20px;">GP</td>
                    </tr>
                </table>
                </th>';
    }
    // $result[9] er vitore ekta semester er sokol credit ache.
    $total_course_credit = array_sum($result[9]);
    
    $data.='<th width = "5%">GPA in ' .$_SESSION['course_year'].' '.$course_semester.'<br> (Total Cr. Hr '.$total_course_credit. ')</th>';
    if($_GET['course_semester']=="2nd semester")
    {
        $data.='<th width = "5%">GPA in ' .$_SESSION['course_year'].' '.'1st semester'.'<br> (Total Cr. Hr '.$total_first_semester_course_credit. ')</th>';
        $data.='<th width = "5%" text-rotate = "90">CGPA in ' .$_SESSION['course_year']. ' (Total Cr. Hr '.$total_credit_array[0] .')</th>';
        
         // jdi 2nd year er result calculation korte hoy tkhn first year er result and 2nd year porjonto cgpa show korte hobe.
        if($_SESSION['course_year'] == "2nd year")
        {
            $data.='<th width = "5%">CGPA in 1st year with improvement</th>';
            $data.='<th width = "5%">CGPA in 1st year without improvement</th>';
            $data.='<th width = "5%" text-rotate = "90">CGPA Till Now</th>';
        }
        else if($_SESSION['course_year'] == "3rd year")
        {
            $data.='<th width = "5%">CGPA in 2nd year with improvment</th>';
            $data.='<th width = "5%">CGPA in 2nd year without improvment</th>';
            $data.='<th width = "5%" text-rotate = "90">CGPA Till Now</th>';
        }
        else if($_SESSION['course_year'] == "4th year")
        {
            $data.='<th width = "5%">CGPA in 3rd year with improvment</th>';
            $data.='<th width = "5%">CGPA in 3rd year without improvment</th>';
            $data.='<th width = "5%" text-rotate = "90">CGPA Till Now</th>';
            $data.='<th width = "5%" text-rotate = "90">Comment</th>';
        }
    }
    
//    echo "<pre>";
//     print_r($result);
//    echo "</pre>";
// exit();
    $data.='</tr>
            </thead>';
    $i=0;
    $j=0;
    $semester_wise_cgpa = 0;
    
    // $result[4] er vitore student der roll ache
    $total_student = sizeof($result[4]);
    // $result[11] er vitore total number assigned course ache ekta semester er.
    $total_assigned_course = $result[11];
    for($k=0;$k<$total_student;$k++)
    {
        $student_roll = $result[4][$k];
        $student_total_internal = $result[5][$k];
        $student_total_final_marks = $result[6][$k];
        $letter_grade = $result[7][$k];
        $grade_point = $result[8][$k];
        $course_type = $result[13][$k];
        
        // ceil function use korsi jate value 77.25 or 77.5 jai hok nak kn seta tar uporer value niye nibe jemon ei khetre 78.
        if($course_type == "Viva-Voce")
        {
            $total_marks = ceil($student_total_final_marks*2);
        }
        else
        {
            $total_marks = ceil($student_total_internal + $student_total_final_marks);
        }
        
        if($i==0)
        {
            $data.='<tr>
                    <td>'.$student_roll.'</td>';

        }
        if($i<$total_assigned_course)
        {
            $data.='<td style = "padding: 0px;">
            <table class = "table" style = "overflow:wrap; width: 100%;" autosize="1">
            <tr>
            <td style = " border-left:0px; border-bottom: 0px; width: 100%;">'.$student_total_internal.'</td>
            <td style = " border-left:0px; border-bottom: 0px; width: 100%;">'.$student_total_final_marks.'</td>
            <td style = " border-left:0px; border-bottom: 0px; width: 100%;">'.$total_marks.'</td>
            <td style = " border-left:0px; border-bottom: 0px; width: 100%;">'.$letter_grade.'</td>
            <td style = " border-left:0px; border-right:0px; border-bottom: 0px; width: 100%;">'.$grade_point.'</td>
            </tr>
            </table>
            </td>';
            $course_credit = $result[9][$i];
            $semester_wise_cgpa+= $grade_point*$course_credit;
            $i++;
        }
        if($i==$total_assigned_course)
        {
            if($_GET['course_semester']=="1st semester")
            {
                // jdi first semester hoy tahole semester_wise_cgpa_calculation function theke je cgpa pacchi seta dekhabo. otherwise 2nd semester hole amra array er vitore re result pacchi seta dekhabo.
                
                $semester_wise_cgpa = $semester_wise_cgpa/$total_course_credit;
                // $semester_wise_cgpa = round($semester_wise_cgpa,2);
                $data.='<td style = "font-weight: bold">'.round($semester_wise_cgpa,2).'</td>';
            }
            else if($_GET['course_semester']=="2nd semester")
            {
                $data.='<td style = "font-weight: bold">'.round($gpa_in_2nd_semester_array[$j],2).'</td>';
                $data.='<td style = "font-weight: bold">'.round($gpa_in_1st_semester_array[$j],2).'</td>';
                
                if($_SESSION['course_year']=="1st year")
                {
                    $data.='<td style = "font-weight: bold">'.round($overall_cgpa_in_1_array[$j],2).'</td>';
                }
                else if($_SESSION['course_year']=="2nd year")
                {
                    $data.='<td style = "font-weight: bold">'.round($overall_cgpa_in_2_array[$j],2).'</td>';
                    $data.='<td style = "font-weight: bold">'.round($overall_cgpa_in_1_array[$j],2).'</td>';
                    $data.='<td style = "font-weight: bold">'.round($overall_cgpa_in_1_without_improvement_array[$j],2).'</td>';
                    $data.='<td style = "font-weight: bold">'.round($cgpa_till_now_array[$j],2).'</td>';
                }
                else if($_SESSION['course_year']=="3rd year")
                {
                    $data.='<td style = "font-weight: bold">'.round($overall_cgpa_in_3_array[$j],2).'</td>';
                    $data.='<td style = "font-weight: bold">'.round($overall_cgpa_in_2_array[$j],2).'</td>';
                    $data.='<td style = "font-weight: bold">'.round($overall_cgpa_in_2_without_improvement_array[$j],2).'</td>';
                    $data.='<td style = "font-weight: bold">'.round($cgpa_till_now_array[$j],2).'</td>';
                }
                else if($_SESSION['course_year']=="4th year")
                {
                    $data.='<td style = "font-weight: bold">'.round($overall_cgpa_in_4_array[$j],2).'</td>';
                    $data.='<td style = "font-weight: bold">'.round($overall_cgpa_in_3_array[$j],2).'</td>';
                    $data.='<td style = "font-weight: bold">'.round($overall_cgpa_in_3_without_improvement_array[$j],2).'</td>';
                    // final year er final value te jdi result thake 3.459468856. output e dekhabe 3.45. output 3.46 hobe na. eita university er rules. tai amra bcdiv function use korbo. eita amder kankhito result ta show korbe.
                    // $data.='<td style = "font-weight: bold">'.bcdiv($cgpa_till_now_array[$j],1,2).'</td>';
                    $data.='<td style = "font-weight: bold">'.round($cgpa_till_now_array[$j],2).'</td>';
                    if($count_failed_semester_courses_array[$j]>0)
                    {
                        $data.='<td style = "font-weight: bold">F</td>';
                    }
                    else
                    {
                        $data.='<td style = "font-weight: bold">--</td>';
                    }
                    
                }
                $j++;
                
            }
            
            $data.='</tr>';
            $i=0;
            $semester_wise_cgpa=0;
        }

    }
    $data.='</table>';
    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->SetHTMLHeader('<div class = "main_header">
                            <div align = "left" class = "left_header" >
                                 <h3>Jatiya Kabi Kazi Nazrul Islam University</h3> <p>Dept. of Fine Arts</p> <p>Tabulation Sheet of B.F.A (Honours) '.$_SESSION['course_year'].' '.$_GET['course_semester'].' Final Examination - '.$_SESSION['exam_year'].'</p> <p>Session: '.$_SESSION['session'].'</p> <p>Department/Stream: '.$department_name.'</p> 
                            </div>
                            <div align="left" class = "right_header">
                                <table class = "table">
                                    <thead>
                                        <tr>
                                            <th>Numerical Grade</th>
                                            <th>Grade Point</th>
                                            <th>Letter Grade</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>80% and above</td>
                                        <td>A+</td>
                                        <td>4.00</td>
                                    </tr>
                                    <tr>
                                        <td>75% to less than 80%</td>
                                        <td>A</td>
                                        <td>3.75</td>
                                    </tr>
                                    <tr>
                                        <td>70% to less than 75%</td>
                                        <td>A-</td>
                                        <td>3.50</td>
                                    </tr>
                                    <tr>
                                        <td>65% to less than 70%</td>
                                        <td>B+</td>
                                        <td>3.25</td>
                                    </tr>
                                    <tr>
                                        <td>60% to less than 65%</td>
                                        <td>B</td>
                                        <td>3.00</td>
                                    </tr>
                                </table>
                            </div>
                            <div align="left" class = "right_header">
                                <table class = "table">
                                    <thead>
                                        <tr>
                                            <th>Numerical Grade</th>
                                            <th>Grade Point</th>
                                            <th>Letter Grade</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>55% to less than 60%</td>
                                        <td>B-</td>
                                        <td>2.75</td>
                                    </tr>
                                    <tr>
                                        <td>50% to less than 55%</td>
                                        <td>C+</td>
                                        <td>2.50</td>
                                    </tr>
                                    <tr>
                                        <td>45% to less than 50%</td>
                                        <td>C</td>
                                        <td>2.25</td>
                                    </tr>
                                    <tr>
                                        <td>40% to less than 45%</td>
                                        <td>D</td>
                                        <td>2.00</td>
                                    </tr>
                                    <tr>
                                        <td>less than 40%</td>
                                        <td>F</td>
                                        <td>00</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class = "second_header">
                        <h3>Courses And Credits</h3>
                        <h3>'.$_SESSION['course_year'].' '.$_GET['course_semester'].' Final Examination</h3>
                        </div>');

    $mpdf->AddPage('L', // L - landscape, P - portrait
'', '', '', '',
5, // margin_left
5, // margin right
70, // margin top
20, // margin bottom
5, // margin header
0); // margin footer
    $mpdf->WriteHtml($data); // will crate the pdf
    ob_clean();
    $file_name = time().'.pdf';
    $mpdf->Output($file_name,'D'); // d for download
    // echo $data;
    // echo $total_course_credit;
    // echo "<pre>";
    // print_r($credit_array);
    // echo "</pre>";
?>
