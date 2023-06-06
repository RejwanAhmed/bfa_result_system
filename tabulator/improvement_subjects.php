<?php include("lib/tabulator_header.php") ?>
<?php include("valid_department_function.php") ?>
<!-- Validation -->
<?php 
    if(isset($_GET['course_semester']) && ($_GET['course_semester']=="1st semester" || $_GET['course_semester'] == "2nd semester") && isset($_GET['department_id']))
    {
        $semester = $_GET['course_semester'];
    }
    else 
    {
        ?>
            <script>
                window.alert("Invalid Semester");
                window.location = "home.php";
            </script>
        <?php 
        exit();
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
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header form_header text-center">
                    <h2><?php echo "Improvement Subjects of ($_SESSION[course_year], $semester) "?></h2>
                    <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
                    
                </div>
                <div class="card-body table-responsive">
                    <!-- <a href="selected_subject_and_students.php?course_semester=<?php echo $semester?>" class = "btn mb-2">Back</a> -->
                   <table class = "table table-bordered table-hover text-center">
                         <tr>
                            <thead class ="thead-light">
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Add Student</th>
                            </thead>
                        </tr>
                        <?php 
                            $select_course = "SELECT `id`, `course_code`, `course_title` FROM `course_information` WHERE `course_semester` = '$semester' AND `course_year` = '$_SESSION[course_year]' AND `department_id` = '$department_id'";
                            
                            $run_select_course = mysqli_query($conn, $select_course);
                            
                            while($row = mysqli_fetch_assoc($run_select_course))
                            {
                                ?>
                                <tr>
                                    <td>
                                        <?php 
                                        if($row['course_code']=="")
                                        {
                                            echo "--";
                                        }
                                        else
                                        {
                                            echo $row['course_code'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if($row['course_title']=="")
                                            {
                                                echo "Viva-Voce";
                                            }
                                            else
                                            {
                                                echo $row['course_title'];
                                            }
                                        ?>
                                    </td>
                                    <td width = "20%">
                                        <button class = "link_btn text-center">
                                            <a  href="add_improvement_student.php?course_semester=<?php echo $semester ?>&course_id=<?php echo $row['id'] ?>&department_id=<?php echo $department_id ?>"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span> Add Student</a>
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                   </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include("lib/tabulator_footer.php") ?>
