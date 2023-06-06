<?php include('lib/header.php')?>
<?php 
    // query for number of departments
    $select_department = "SELECT count(id) as total_department FROM `department_information`";
    $run_select_department = mysqli_query($conn, $select_department);
    $number_of_department = mysqli_fetch_assoc($run_select_department);
    
    // query for number of teachers
    $select_teacher = "SELECT count(id) as total_teacher FROM `teacher_information`";
    $run_select_teacher = mysqli_query($conn, $select_teacher);
    $number_of_teacher= mysqli_fetch_assoc($run_select_teacher);
    
     // query for number of students
     $select_student = "SELECT count(id) as total_student FROM `student_information`";
     $run_select_student = mysqli_query($conn, $select_student);
     $number_of_student= mysqli_fetch_assoc($run_select_student);
     
     // query for number of courses
     $select_course = "SELECT count(id) as total_course FROM `course_information`";
     $run_select_course = mysqli_query($conn, $select_course);
     $number_of_course = mysqli_fetch_assoc($run_select_course);
     
     
      // query for number of exam committees
      $select_exam_committee = "SELECT count(id) as total_exam_committee FROM `exam_committee_information`";
      $run_select_exam_committee = mysqli_query($conn, $select_exam_committee);
      $number_of_exam_committee = mysqli_fetch_assoc($run_select_exam_committee);
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header form_header text-center">
            <h3>Welcome To Dashboard</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-sm-6 col-12 mt-2">
                    <div class="card border border-primary">
                        <div class="card-body shadow dashboard_card">
                            <h3 class = "text-center">Department Information</h3>
                            <hr>
                            <table class = "table table-borderless">
                                <tr>
                                    <th><h5><a href="view_department_information.php">Total Department <i class="text-right fas fa-building"></i></a></h5> </th>
                                    <td class = "text-center" ><h5><a href="view_department_information.php" ><?php echo $number_of_department['total_department']?></a></h5></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            
                <div class="col-lg-4 col-sm-6 col-12 mt-2">
                    <div class="card border border-danger">
                        <div class="card-body shadow dashboard_card">
                            <h3 class = "text-center">Teacher Information</h3>
                            <hr>
                            <table class = "table table-borderless">
                                <tr>
                                    <th><h5><a href="view_teacher_information.php">Total Teacher <i class="text-right fas fa-users"></i></a></h5> </th>
                                    <td class = "text-center" ><h5><a href="view_teacher_information.php" ><?php echo $number_of_teacher['total_teacher']?></a></h5></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
        
                <div class="col-lg-4 col-sm-6 col-12 mt-2">
                    <div class="card border border-primary">
                        <div class="card-body shadow dashboard_card">
                            <h3 class = "text-center">Student Information</h3>
                            <hr>
                            <table class = "table table-borderless">
                                <tr>
                                    <th><h5><a href="view_student_information.php">Total Student <i class="text-right fas fa-user-graduate"></i></a></h5> </th>
                                    <td class = "text-center" ><h5><a href="view_student_information.php" ><?php echo $number_of_student['total_student']?></a></h5></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
        
                <div class="col-lg-4 col-sm-6 col-12 mt-2">
                    <div class="card border border-danger">
                        <div class="card-body shadow dashboard_card">
                            <h3 class = "text-center">Course Information</h3>
                            <hr>
                            <table class = "table table-borderless">
                                <tr>
                                    <th><h5><a href="view_course_information.php">Total Course <i class="text-right fas fa-atlas"></i></a></h5> </th>
                                    <td class = "text-center" ><h5><a href="view_course_information.php" ><?php echo $number_of_course['total_course']?></a></h5></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
        
                <div class="col-lg-4 col-sm-6 col-12 mt-2">
                    <div class="card border border-primary">
                        <div class="card-body shadow dashboard_card">
                            <h3 class = "text-center">Exam Committee</h3>
                            <hr>
                            <table class = "table table-borderless">
                                <tr>
                                    <th><h5><a href="view_exam_committee_information.php">Total Committee <i class="text-right fas fa-chalkboard-teacher"></i></a></h5> </th>
                                    <td class = "text-center" ><h5><a href="view_exam_committee_information.php" ><?php echo $number_of_exam_committee['total_exam_committee']?></a></h5></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
<?php include('lib/footer.php');?>
