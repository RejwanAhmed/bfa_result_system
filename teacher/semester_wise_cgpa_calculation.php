<?php 
    // semester wise cgpa calculation er jonno ei function use kora hocche.
    function semester_wise_cgpa_calculation($course_semester, $department_id)
    {
        include('gpa_counting_function.php');
        include('lib/db_connection.php');
        $first_semester_result_qry = "SELECT r.id, r.course_id, r.actual_session, r.current_session, st.id as student_id, r.total_internal, r.total_final_marks, c.course_type FROM result as r JOIN student_information as st ON r.student_id = st.id INNER JOIN `course_information` as c ON r.course_id = c.id WHERE r.current_session = '$_GET[session]' AND r.course_year = '$_GET[course_year]' AND r.course_semester = '$course_semester' AND r.department_id = '$department_id' ORDER BY r.actual_session DESC, st.roll_no ASC, st.actual_session DESC, r.course_id ASC";
        $run_first_semester_result_qry = mysqli_query($conn, $first_semester_result_qry);

        $first_semester_assigned_course = "SELECT ac.course_id, c.course_credit  FROM assigned_course_information as ac INNER JOIN course_information as c ON ac.course_id = c.id WHERE ac.session = '$_GET[session]' AND ac.course_year = '$_GET[course_year]' AND ac.course_semester = '$course_semester' AND ac.course_id !='-1' AND ac.teacher_id !='-1' AND ac.verification = '1' AND ac.department_id = '$department_id' ORDER BY c.id ASC";
        $run_first_semester_assigned_course = mysqli_query($conn, $first_semester_assigned_course);
        $total_first_semester_assigned_course =  mysqli_num_rows($run_first_semester_assigned_course);

        $first_semester_credit_array = array();
        while($row = mysqli_fetch_assoc($run_first_semester_assigned_course))
        {
            $first_semester_credit_array[]= $row['course_credit'];
        }
        $total_first_semester_course_credit = array_sum($first_semester_credit_array);

        $i=0;
        $first_semester_cgpa = 0;
        $first_semester_cgpa_array = array();
        $stdnt_id_array = array();
        $stdnt_actual_session_array = array();
        $stdnt_current_session_array = array();
        $stdnt_failed_course_id_array = array();
        
        
        // jdi ekta student ekadhik course e fail kore thake tahole seta jate koma koma diye rakhte pari se jonno $each_stdnt_failed_course_id variable use kortesi. and proti ta student er jonno ei variabler value  $stdnt_failed_course_id_array push kore dicchi. $count_failed_course variable ta use korsi, koita course e ekta student fail korlo ta count korar jonno, jeta course_id gula koma koma diye rakhte kaje asbe.
        $each_stdnt_failed_course_id = "";
        $count_failed_course = 0;
        while($row = mysqli_fetch_assoc($run_first_semester_result_qry))
        {
            // tabulator jdi 20.33 marks diye thake tahole to setar sathe 2 gun korle 40.66 hoy and ceil use korle hoy 41 kintu marks entry hobe 20.33 ebong improvement eligible hobe na. ar marks show korar time e thiki 41 show korbe. karon marks 50 e dicche kintu calculation 100 te hocche.
            if($row['course_type']=='Viva-Voce')
            {
                $total_marks = ceil($row['total_final_marks']*2);
                $result = gpa_counting_viva_voce($total_marks);
            }
            else
            {
                $total_marks = ceil($row['total_internal'] + $row['total_final_marks']);
                $result = gpa_counting($total_marks); 
            }
            
                 
            $letter_grade = $result[0];
            $grade_point = $result[1];
            
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
        return array($stdnt_id_array, $stdnt_actual_session_array, $stdnt_current_session_array, $first_semester_cgpa_array,$first_semester_credit_array, $stdnt_failed_course_id_array);
    }
     
?>