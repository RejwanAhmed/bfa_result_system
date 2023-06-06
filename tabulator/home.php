<?php include('lib/tabulator_header.php')?>
<?php 
    // query for number of assigned course of 1st year
    $select_assigned_course_info = "SELECT `id` FROM `assigned_course_information` WHERE `session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '1st semester' AND `teacher_id` != '-1' AND `course_id` != '-1' AND `verification` = '1' ";
    $run_select_assigned_course_info = mysqli_query($conn, $select_assigned_course_info);
    $num_rows_assigned_course = mysqli_num_rows($run_select_assigned_course_info);
    
    // query for number of assigned course of 2nd year
    $select_assigned_course_info_2 = "SELECT `id` FROM `assigned_course_information` WHERE `session` = '$_SESSION[session]' AND `course_year` = '$_SESSION[course_year]' AND `course_semester` = '1st semester' AND `teacher_id` != '-1' AND `course_id` != '-1' AND `verification` = '1' ";
    $run_select_assigned_course_info_2 = mysqli_query($conn, $select_assigned_course_info_2);
    $num_rows_assigned_course_2 = mysqli_num_rows($run_select_assigned_course_info_2);
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header form_header text-center">
            <h3>Welcome To Dashboard (<?php echo $_SESSION['session']?>, <?php echo $_SESSION['course_year']?>)</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-sm-6 col-12 mt-2">
                    <div class="card border border-primary">
                        <div class="card-body shadow dashboard_card">
                            <h3 class = "text-center">1st Semester</h3>
                            <hr>
                            <table class = "table table-borderless">
                                <tr>
                                    <th><h5><a href="1st_2nd_semester.php?semester=1st semester">Total Courses <i class="text-right fas fa-atlas"></i></a></h5> </th>
                                    <td class = "text-center" ><h5><a href="1st_2nd_semester.php?semester=1st semester" ><?php echo $num_rows_assigned_course?></a></h5></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-sm-6 col-12 mt-2">
                    <div class="card border border-primary">
                        <div class="card-body shadow dashboard_card">
                            <h3 class = "text-center">2nd Semester</h3>
                            <hr>
                            <table class = "table table-borderless">
                                <tr>
                                    <th><h5><a href="1st_2nd_semester.php?semester=2nd semester">Total Courses <i class="text-right fas fa-atlas"></i></a></h5> </th>
                                    <td class = "text-center" ><h5><a href="1st_2nd_semester.php?semester=2nd semester" ><?php echo $num_rows_assigned_course_2 ?></a></h5></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            
            </div>
        </div>  
    </div>
</div>
<?php include('lib/tabulator_footer.php');?>
