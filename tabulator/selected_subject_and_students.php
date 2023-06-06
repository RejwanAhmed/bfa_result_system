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
                    <h2><?php echo "Selected Subjects Of ($_SESSION[course_year], $semester) For Improvement"?></h2>
                    <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
                    
                </div>
                <div class="card-body table-responsive">
                    <a href="improvement_subjects.php?course_semester=<?php echo $semester?>&department_id=<?php echo $department_id ?>" class = "btn mb-2">Add Improvement Subjects</a>
                    
                    <?php 
                        // Select subject from improvement_result 
                        $select_sub_qry = "SELECT i_r.course_id, c_i.course_code, c_i.course_title, c_i.course_type FROM course_information as c_i JOIN `improvement_result` as i_r ON c_i.id = i_r.course_id  WHERE `selection`='Y' AND i_r.improvement_session = '$_SESSION[session]' AND i_r.course_year = '$_SESSION[course_year]' AND i_r.course_semester = '$semester' AND i_r.department_id = '$department_id'  GROUP BY i_r.course_id ";
                        $run_select_sub_qry = mysqli_query($conn, $select_sub_qry);
                        
                        $num_rows = mysqli_num_rows($run_select_sub_qry);
                        if($num_rows==0)
                        {
                            echo "<h2 style = 'color: red; text-align:center'>No Subjects Added For Improvement!</h2>";
                        }
                        else 
                        {
                            ?>
                            <table class = "table table-bordered table-hover text-center">
                                <tr>
                                    <thead class ="thead-light">
                                        <th>Course Code</th>
                                        <th>Course Title</th>
                                        <th>Enter Improvement Marks</th>
                                        <th>View Improvement Marks</th>
                                    </thead>
                                </tr>
                                <?php 
                                    while($row = mysqli_fetch_assoc($run_select_sub_qry))
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
                                                    if($row['course_type']=="Viva-Voce" && $row['course_title']=="")
                                                    {
                                                        echo "Viva-Voce";
                                                    }
                                                    else if($row['course_title']=="")
                                                    {
                                                        echo "--";
                                                    }
                                                    else
                                                    {
                                                        echo $row['course_title'];
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td width = "25%">
                                                <button class = "link_btn">
                                                    <?php 
                                                        if($row['course_type']=="Viva-Voce")
                                                        {
                                                            ?>
                                                             <a  href="enter_improvement_marks_viva_voce.php?course_semester=<?php echo $semester?>&course_id=<?php echo $row['course_id'] ?>&department_id=<?php echo $department_id ?>"><span><i class = "fas fa-plus-circle"></i></span> Improvement Marks</a>
                                                            <?php 
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                             <a  href="enter_improvement_marks.php?course_semester=<?php echo $semester?>&course_id=<?php echo $row['course_id'] ?>&department_id=<?php echo $department_id ?>"><span><i class = "fas fa-plus-circle"></i></span> Improvement Marks</a>
                                                            <?php 
                                                        }
                                                    ?>
                                                     
                                                </button>
                                            </td>
                                            
                                            <td width = "25%">
                                                <button class = "link_btn">
                                                    <?php 
                                                        if($row['course_type']=="Viva-Voce")
                                                        {
                                                            ?>
                                                            <a  href="view_improvement_marks_viva_voce.php?course_semester=<?php echo $semester?>&course_id=<?php echo $row['course_id'] ?>&department_id=<?php echo $department_id ?>"><span><i class = "fas fa-eye"></i></span> Improvement Marks</a>
                                                            <?php 
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <a  href="view_improvement_marks.php?course_semester=<?php echo $semester?>&course_id=<?php echo $row['course_id'] ?>&department_id=<?php echo $department_id ?>"><span><i class = "fas fa-eye"></i></span> Improvement Marks</a>
                                                            <?php 
                                                        }
                                                    ?>
                                                    
                                                </button>
                                            </td>
                                        </tr>
                                        <?php 
                                    }
                                ?>
                            </table>
                            <?php 
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include("lib/tabulator_footer.php") ?>
