<?php
function gpa_counting($total_marks)
{
    // course credit = 1.5 or 3 jai hok na kn cgpa 100 er moddhei count hobe. 1.5 credit er courser khetre jkhn final marks dewa hoy tkhn 2ta examiner er marks jog kore then final marks dewa hoy(jemon 1st_examiner = 20 and 2nd_examiner = 18 then final marks = 38). ar internal jehetu already 40 e ache tai total marks ta 100 er vitore hoy. 
    
    if ($total_marks >= 80) {
        $letter_grade = "A+";
        $grade_point = "4.00";
    } else if ($total_marks >= 75 && $total_marks < 80) {
        $letter_grade = "A";
        $grade_point = "3.75";
    } else if ($total_marks >= 70 && $total_marks < 75) {
        $letter_grade = "A-";
        $grade_point = "3.50";
    } else if ($total_marks >= 65 && $total_marks < 70) {
        $letter_grade = "B+";
        $grade_point = "3.25";
    } else if ($total_marks >= 60 && $total_marks < 65) {
        $letter_grade = "B";
        $grade_point = "3.00";
    } else if ($total_marks >= 55 && $total_marks < 60) {
        $letter_grade = "B-";
        $grade_point = "2.75";
    } else if ($total_marks >= 50 && $total_marks < 55) {
        $letter_grade = "C+";
        $grade_point = "2.50";
    } else if ($total_marks >= 45 && $total_marks < 50) {
        $letter_grade = "C";
        $grade_point = "2.25";
    } else if ($total_marks >= 40 && $total_marks < 45) {
        $letter_grade = "D";
        $grade_point = "2.00";
    } else {
        $letter_grade = "F";
        $grade_point = "0";
    }
    $result_ar = array($letter_grade,$grade_point);
    return $result_ar;
}
function gpa_counting_viva_voce($total_marks)
{
    // jdi course_type = viva-voce hoy tkhn ei function kaj korbe gpa counting er jonno
    // if ($total_marks >= 40) {
    //     $letter_grade = "A+";
    //     $grade_point = "4.00";
    // } else if ($total_marks >= 37.5 && $total_marks < 40) {
    //     $letter_grade = "A";
    //     $grade_point = "3.75";
    // } else if ($total_marks >= 35 && $total_marks < 37.5) {
    //     $letter_grade = "A-";
    //     $grade_point = "3.50";
    // } else if ($total_marks >= 32.5 && $total_marks < 35) {
    //     $letter_grade = "B+";
    //     $grade_point = "3.25";
    // } else if ($total_marks >= 30 && $total_marks < 32.5) {
    //     $letter_grade = "B";
    //     $grade_point = "3.00";
    // } else if ($total_marks >= 27.5 && $total_marks < 30) {
    //     $letter_grade = "B-";
    //     $grade_point = "2.75";
    // } else if ($total_marks >= 25 && $total_marks < 27.5) {
    //     $letter_grade = "C+";
    //     $grade_point = "2.50";
    // } else {
    //     $letter_grade = "F";
    //     $grade_point = "0";
    // }
    
    
    // viva-voce and normal courser cgpa calculation same kintu eita ami age jantam na.
    if ($total_marks >= 80) {
        $letter_grade = "A+";
        $grade_point = "4.00";
    } else if ($total_marks >= 75 && $total_marks < 80) {
        $letter_grade = "A";
        $grade_point = "3.75";
    } else if ($total_marks >= 70 && $total_marks < 75) {
        $letter_grade = "A-";
        $grade_point = "3.50";
    } else if ($total_marks >= 65 && $total_marks < 70) {
        $letter_grade = "B+";
        $grade_point = "3.25";
    } else if ($total_marks >= 60 && $total_marks < 65) {
        $letter_grade = "B";
        $grade_point = "3.00";
    } else if ($total_marks >= 55 && $total_marks < 60) {
        $letter_grade = "B-";
        $grade_point = "2.75";
    } else if ($total_marks >= 50 && $total_marks < 55) {
        $letter_grade = "C+";
        $grade_point = "2.50";
    } else if ($total_marks >= 45 && $total_marks < 50) {
        $letter_grade = "C";
        $grade_point = "2.25";
    } else if ($total_marks >= 40 && $total_marks < 45) {
        $letter_grade = "D";
        $grade_point = "2.00";
    } else {
        $letter_grade = "F";
        $grade_point = "0";
    }
    $result_ar = array($letter_grade,$grade_point);
    return $result_ar;
}
