<?php 
    include('gpa_counting_function.php');
    // semester wise cgpa calculation er jonno ei function use kora hocche.
    function semester_wise_cgpa_calculation($course_semester, $department_id)
    {
        // include('gpa_counting_function.php');
        include('lib/db_connection.php');
        
        // Find total number of rows of result status = 1 to find whether result published or not
        $count_result_status = "SELECT count(`id`) as `total_id` FROM `result` WHERE `result_status`='1' AND `current_session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '$course_semester' AND `department_id` = '$department_id'";
        $run_count_result_status = mysqli_query($conn, $count_result_status);
        $res_count_result_status = mysqli_fetch_assoc($run_count_result_status);
        
        // jdi improvement table er sathe execute hoy tahole nicher variable 1 hobe otherwise 0 thakbe.
        $execute_with_improvement = 0;
        
        if($res_count_result_status['total_id']==0)
        {
            // jdi result publish na hoye thake tahole improve table er sathe join korar proyojon nai
            // course type newar jonno right join korsi course_info tabler sathe 
            $first_semester_result_qry = "SELECT r.id, r.course_id, r.actual_session, r.current_session, st.id as student_id, st.roll_no, r.total_internal, r.total_final_marks, c.course_type FROM result as r JOIN student_information as st ON r.student_id = st.id INNER JOIN `course_information` as c ON r.course_id = c.id WHERE r.current_session = '$_SESSION[session]' AND r.course_year = '$_SESSION[course_year]' AND r.course_semester = '$course_semester' AND r.department_id = '$department_id' ORDER BY r.actual_session DESC, st.roll_no ASC, st.actual_session DESC, r.course_id ASC";
        }
        else
        {
            // jdi result publish hoye thake tahole improvement dile improvement table e marks thakar kotha tkhn improvement table er sathe left join kore previous_total_final_marks niye ese oitar sathe calculation korte hobe semester wise cgpa dekhanor jonno.
            // course type newar jonno right join korsi course_info tabler sathe
            $first_semester_result_qry = "SELECT r.id, r.course_id, r.actual_session, r.current_session, st.id as student_id, st.roll_no, r.total_internal, r.total_final_marks, i_r.previous_total_final_marks, c.course_type FROM result as r JOIN student_information as st ON r.student_id = st.id INNER JOIN `course_information` as c ON r.course_id = c.id LEFT JOIN improvement_result as i_r ON i_r.result_id = r.id  WHERE r.current_session = '$_SESSION[session]' AND r.course_year = '$_SESSION[course_year]' AND r.course_semester = '$course_semester' AND r.department_id = '$department_id' ORDER BY r.actual_session DESC, st.roll_no ASC, st.actual_session DESC, r.course_id ASC ";
            
            $execute_with_improvement = 1;
        }
        $run_first_semester_result_qry = mysqli_query($conn, $first_semester_result_qry);
        $num_rows = mysqli_num_rows($run_first_semester_result_qry);
        if($num_rows==0)
        {
            if($_GET['course_semester']=="1st semester")
            {
                ?>
                <script>
                    window.alert("Invalid Result");
                    window.location = "1st_2nd_semester.php?semester=1st semester&department_id=<?php echo $department_id ?>";
                </script>
                <?php
            }
            else
            {
                ?>
                <script>
                    window.alert("Invalid Result");
                    window.location = "1st_2nd_semester.php?semester=2nd semester&department_id=<?php echo $department_id ?>";
                </script>
                <?php
            }
            exit();
        }

        $first_semester_assigned_course = "SELECT ac.course_id, c.course_code, c.course_credit FROM assigned_course_information as ac RIGHT JOIN course_information as c ON ac.course_id = c.id WHERE ac.session = '$_SESSION[session]' AND ac.course_year = '$_SESSION[course_year]' AND ac.course_semester = '$course_semester' AND ac.course_id !='-1' AND ac.teacher_id !='-1' AND ac.verification = '1' AND ac.department_id = '$department_id' ORDER BY c.id ASC";
        $run_first_semester_assigned_course = mysqli_query($conn, $first_semester_assigned_course);
        $total_first_semester_assigned_course =  mysqli_num_rows($run_first_semester_assigned_course);

        $first_semester_credit_array = array();
        $first_semester_course_code_array = array();
        
        while($row = mysqli_fetch_assoc($run_first_semester_assigned_course))
        {
            $first_semester_credit_array[]= $row['course_credit'];
            $first_semester_course_code_array[] = $row['course_code'];
        }
        $total_first_semester_course_credit = array_sum($first_semester_credit_array);

        $i=0;
        $first_semester_cgpa = 0;
        $first_semester_cgpa_array = array();
        $stdnt_id_array = array();
        $stdnt_actual_session_array = array();
        $stdnt_current_session_array = array();
        $stdnt_failed_course_id_array = array();
        
        
        // ei array gula send korchi final result er pdf calculate korar jonno. normally cgpa calculation kore database e rakhte hole nicher array gula lage na. kintu pdf korte hole student roll, internal, final marks, grade point, letter grade sob gula lage.
        $stdnt_roll_array = array();
        $stdnt_total_internal_array = array();
        $stdnt_total_final_marks_array = array();
        $stdnt_letter_grade_array = array();
        $stdnt_grade_point_array = array();
        // protita student je koita course korse tar sob gular type lagbe tai ei array use korsi.
        $first_semester_course_type_array = array();
        
        // jdi ekta student ekadhik course e fail kore thake tahole seta jate koma koma diye rakhte pari se jonno $each_stdnt_failed_course_id variable use kortesi. and proti ta student er jonno ei variabler value  $stdnt_failed_course_id_array push kore dicchi. $count_failed_course variable ta use korsi, koita course e ekta student fail korlo ta count korar jonno, jeta course_id gula koma koma diye rakhte kaje asbe.
        $each_stdnt_failed_course_id = "";
        $count_failed_course = 0;
        while($row = mysqli_fetch_assoc($run_first_semester_result_qry))
        {
            array_push($stdnt_roll_array,$row['roll_no']);
            array_push($stdnt_total_internal_array,$row['total_internal']);
            array_push($first_semester_course_type_array,$row['course_type']);
            // jdi improvement table e marks na thake tahole if condition run korbe otherwise else condition cholbe karon amder improve holeo ager marks show korte hobe jdi ager tabulator login kore tahole.
            
            // execute_with_improvement variable use kora hoyeche karon jdi improvement_table er sathe merge hoy tahole if condition cholbe otherwise else condition cholbe.
            if($execute_with_improvement==1)
            {
                // ceil function use korsi jate value 77.25 or 77.5 jai hok nak kn seta tar uporer value niye nibe jemon ei khetre 78.
                if($row['previous_total_final_marks']!=NULL)
                {
                    array_push($stdnt_total_final_marks_array, $row['previous_total_final_marks']);
                    // course_type = viva-voce hole age 2 diye gun korte hobe then ceil korte hobe karon marks entry hoy 50 e kintu calculation hoy 100 te.
                    if($row['course_type']=="Viva-Voce")
                    {
                        $total_marks = ceil($row['previous_total_final_marks']*2);
                    }
                    else
                    {
                        $total_marks = ceil($row['total_internal'] + $row['previous_total_final_marks']);
                    }
                }
                else
                {
                    array_push($stdnt_total_final_marks_array,$row['total_final_marks']);
                    // course_type = viva-voce hole age 2 diye gun korte hobe then ceil korte hobe karon marks entry hoy 50 e kintu calculation hoy 100 te.
                    if($row['course_type']=="Viva-Voce")
                    {
                        $total_marks = ceil($row['total_final_marks']*2);
                    }
                    else
                    {
                        $total_marks = ceil($row['total_internal'] + $row['total_final_marks']);
                    }
                }
                
            }
            else
            {
                array_push($stdnt_total_final_marks_array,$row['total_final_marks']);
                
                // course_type = viva-voce hole age 2 diye gun korte hobe then ceil korte hobe karon marks entry hoy 50 e kintu calculation hoy 100 te.
                if($row['course_type']=="Viva-Voce")
                {
                    $total_marks = ceil($row['total_final_marks']*2);
                }
                else
                {
                    $total_marks = ceil($row['total_internal'] + $row['total_final_marks']);
                }
            }
           
            // call the gpa_counting function from gpa_counting_function.php page and find the letter grade and grade point
            if($row['course_type']=="Viva-Voce")
            {
                $result = gpa_counting_viva_voce($total_marks);
            }
            else
            {
                $result = gpa_counting($total_marks);
            }
                   
            $letter_grade = $result[0];
            $grade_point = $result[1];
            
            array_push($stdnt_letter_grade_array, $letter_grade);
            array_push($stdnt_grade_point_array, $grade_point);
            
            if($i<$total_first_semester_assigned_course)
            {
                $first_semester_cgpa+= $grade_point*$first_semester_credit_array[$i];
                $i++;
                
                // jdi grade_point = 0 hoy tar mane oi course e fail korse. ekhn eita jdi first course hoy tahole if true hobe. ar jdi er age kono course e fail thake tahole prothom course_id er pore koma diye 2nd course_id rakhte hobe. eivabe bakigula thakbe.
                if($grade_point==0)
                {
                    if($count_failed_course==0)
                    {
                        $each_stdnt_failed_course_id.=$row['course_id'];
                    }
                    else 
                    {
                        $each_stdnt_failed_course_id.=",".$row['course_id'];
                    }
                    $count_failed_course++;
                }
            }
            if($i==$total_first_semester_assigned_course)
            {   
                $first_semester_cgpa = $first_semester_cgpa/$total_first_semester_course_credit;
                array_push($first_semester_cgpa_array, $first_semester_cgpa);
                array_push($stdnt_id_array, $row['student_id']);
                array_push($stdnt_actual_session_array,$row['actual_session']);
                array_push($stdnt_current_session_array,$row['current_session']);
                array_push($stdnt_failed_course_id_array, $each_stdnt_failed_course_id);
                $i=0;
                $first_semester_cgpa=0;
                $count_failed_course=0;
                $each_stdnt_failed_course_id="";
            }

        }
        return array($stdnt_id_array, $stdnt_actual_session_array, $stdnt_current_session_array, $first_semester_cgpa_array, $stdnt_roll_array, $stdnt_total_internal_array, $stdnt_total_final_marks_array, $stdnt_letter_grade_array, $stdnt_grade_point_array,$first_semester_credit_array,$first_semester_course_code_array, $total_first_semester_assigned_course, $stdnt_failed_course_id_array,$first_semester_course_type_array);    
    }
    
    
    function update_semester_cgpa_table($student_id, $actual_session, $current_session, $course_year, $course_semester, $department_id)
    {

        include('lib/db_connection.php');
        
        // ekta student er result update korar jonno use kora hocche.
        
        $select_result_qry = "SELECT `course_id`, `total_internal`, `total_final_marks` FROM `result` WHERE `student_id` = '$student_id' AND `actual_session` = '$actual_session' AND `current_session` = '$current_session' AND `course_year` = '$course_year' AND `course_semester` = '$course_semester' ORDER BY `course_id` ASC";
        $run_select_result_qry = mysqli_query($conn, $select_result_qry);
        

        $assigned_course = "SELECT ac.course_id, c.course_credit, c.course_type FROM assigned_course_information as ac INNER JOIN course_information as c ON ac.course_id = c.id WHERE ac.session = '$current_session' AND ac.course_year = '$course_year' AND ac.course_semester = '$course_semester' AND ac.course_id !='-1' AND ac.teacher_id !='-1' AND ac.verification = '1' AND ac.department_id = '$department_id' ORDER BY c.id ASC";
        $run_assigned_course = mysqli_query($conn, $assigned_course);
        $num_rows_assigned_course =  mysqli_num_rows($run_assigned_course);

        $semester_credit_array = array();
        $course_type_array = array();
        
        while($row = mysqli_fetch_assoc($run_assigned_course))
        {
            $semester_credit_array[]= $row['course_credit'];
            $course_type_array[] = $row['course_type'];
        }
        $total_course_credit = array_sum($semester_credit_array);

        $i=0;
        $semester_cgpa = 0;
        
        // jdi ekta student ekadhik course e fail kore thake tahole seta jate koma koma diye rakhte pari se jonno $each_stdnt_failed_course_id variable use kortesi. $count_failed_course variable ta use korsi, koita course e ekta student fail korlo ta count korar jonno, jeta course_id gula koma koma diye rakhte kaje asbe.
        $each_stdnt_failed_course_id = "";
        $count_failed_course = 0;
        while($row = mysqli_fetch_assoc($run_select_result_qry))
        {
            // call the gpa_counting function from gpa_counting_function.php page and find the letter grade and grade point
            if($course_type_array[$i]=="Viva-Voce")
            {
                // ceil function use korsi jate value 77.25 or 77.5 jai hok nak kn seta tar uporer value niye nibe jemon ei khetre 78.
                $total_marks = ceil($row['total_final_marks']*2);
                $result = gpa_counting_viva_voce($total_marks);
            }
            else
            {
                // ceil function use korsi jate value 77.25 or 77.5 jai hok nak kn seta tar uporer value niye nibe jemon ei khetre 78.
               
                $total_marks = ceil($row['total_internal'] + $row['total_final_marks']);
                $result = gpa_counting($total_marks);
            }
                   
            $letter_grade = $result[0];
            $grade_point = $result[1];
            
            if($i<$num_rows_assigned_course)
            {
                $semester_cgpa+= $grade_point*$semester_credit_array[$i];
                $i++;
                
                // jdi grade_point = 0 hoy tar mane oi course e fail korse. ekhn eita jdi first course hoy tahole if true hobe. ar jdi er age kono course e fail thake tahole prothom course_id er pore koma diye 2nd course_id rakhte hobe. eivabe bakigula thakbe.
                if($grade_point==0)
                {
                    if($count_failed_course==0)
                    {
                        $each_stdnt_failed_course_id.=$row['course_id'];
                    }
                    else 
                    {
                        $each_stdnt_failed_course_id.=",".$row['course_id'];
                    }
                    $count_failed_course++;
                }

            }
            if($i==$num_rows_assigned_course)
            {
                $semester_cgpa = $semester_cgpa/$total_course_credit;
            }

        }
        
        return array($semester_cgpa,$total_course_credit, $each_stdnt_failed_course_id);
    }
    
    
    function year_result_counting($course_year, $student_id)
    {
        include('lib/db_connection.php');
        // ekta student er joto year and semester er result thakbe sob gula array te push kore pathaya dite hobe.
        
        $actual_session_array = array();
        $current_session_array = array();
        $course_year_array = array();
        $course_semester_array = array();
        $previous_cgpa_array = array();
        $current_cgpa_array = array();
        $total_credit_array = array();
          
        // protita student er jekoita failed_courses_id ache ta $failed_courses_id variable e koma diye rekhe dibo jate 4-2 er result e comment column e F er pase oder course code dekhate pari. 
    
        $failed_courses_id = "";
        
        $select_from_semester_cgpa = "SELECT * FROM `semester_cgpa` WHERE `student_id` = '$student_id' AND `course_year` <= '$course_year' AND `cgpa_validation` = 'v'";
        
        $run_select_from_semester_cgpa = mysqli_query($conn, $select_from_semester_cgpa);
        while($row = mysqli_fetch_assoc($run_select_from_semester_cgpa))
        {
            array_push($actual_session_array, $row['actual_session']);
            array_push($current_session_array, $row['current_session']);
            array_push($course_year_array, $row['course_year']);
            array_push($course_semester_array, $row['course_semester']);
            array_push($previous_cgpa_array, $row['previous_cgpa']);
            array_push($current_cgpa_array, $row['current_cgpa']);
            array_push($total_credit_array, $row['semester_total_credit']);
            if($row['failed_course_id']!=NULL)
            {
               
                $failed_courses_id.=$row['failed_course_id'].",";
            }
        }
        // ekta student jdi total 1ta ba tar besi course e fail thake tkhn segula koma koma diye $failed_course_id variable e thake. last e ekta extra koma thaktese, seta soranor jonno rtrim kortesi.
        $failed_courses_id = rtrim($failed_courses_id,",");
        
        return array($actual_session_array, $current_session_array, $course_year_array, $course_semester_array, $previous_cgpa_array, $current_cgpa_array, $total_credit_array, $failed_courses_id);
    }
    
    function invalid_result_selection($student_id, $current_session, $course_year, $course_semester)
    {
        include('lib/db_connection.php');
        $select_invalid_result_from_semester_cgpa = "SELECT `current_cgpa`, `previous_cgpa`, semester_total_credit FROM `semester_cgpa` WHERE `student_id` = '$student_id' AND `current_session` = '$current_session' AND `course_year` = '$course_year' AND `course_semester` = '$course_semester'";
        $run_select_invalid_result_from_semester_cgpa = mysqli_query($conn, $select_invalid_result_from_semester_cgpa);
        
        $res_select_invalid_result_from_semester_cgpa = mysqli_fetch_assoc($run_select_invalid_result_from_semester_cgpa);
        
        return array($res_select_invalid_result_from_semester_cgpa['current_cgpa'], $res_select_invalid_result_from_semester_cgpa['previous_cgpa'], $res_select_invalid_result_from_semester_cgpa['semester_total_credit']);
        
    }
     
?>