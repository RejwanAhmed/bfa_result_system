<?php 
include('semester_wise_cgpa_calculation.php');
include('lib/db_connection.php');

$a = "SELECT * FROM `semester_cgpa` WHERE `student_id` = '11' AND `course_year` <= '4th year' AND `cgpa_validation` = 'v'";
$failed_course_id = "";
$run_a = mysqli_query($conn, $a);
while($row = mysqli_fetch_assoc($run_a))
{
    if($row['failed_course_id']!=NULL)
    {
        $failed_course_id.=$row['failed_course_id'].",";
    }
}
$failed_course_id = rtrim($failed_course_id,",");
echo $failed_course_id;
if($failed_course_id!=NULL)
{
    echo "ache";
}
else
{
    echo "kichu nai";
}
?>