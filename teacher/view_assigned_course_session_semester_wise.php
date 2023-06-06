<?php include('lib/teacher_header.php') ?>
<?php
    if(!isset($_GET['session']) || !isset($_GET['course_year']) || !isset($_GET['course_semester']))
    {
        ?>
        <script>
            window.alert("Something is missing");
            window.location = "assigned_course_list.php";
        </script>
        <?php
        exit();
    }
    if(isset($_GET['session']) && isset($_GET['course_year']) && isset($_GET['course_semester']))
    {
        if($_GET['course_year']=="1st year")
        {
            $id_validation_qry = "SELECT ac.id, ac.session, ac.course_year, ac.course_semester, ac.teacher_id, ac.indexing, ac.verification, ac.course_id, c.course_title as course_title FROM assigned_course_information as ac INNER JOIN course_information as c ON c.id = ac.course_id WHERE ac.session = '$_GET[session]' && ac.course_year = '$_GET[course_year]' && ac.course_semester = '$_GET[course_semester]' && ac.teacher_id = '$_SESSION[teacher_id]'";
        }
        else
        {
            $id_validation_qry = "SELECT ac.id, ac.session, ac.course_year, ac.course_semester, ac.teacher_id, ac.indexing, ac.verification, ac.course_id, c.course_title as course_title, d.id as department_id, d.department_name FROM assigned_course_information as ac INNER JOIN course_information as c ON c.id = ac.course_id INNER JOIN department_information as d ON d.id = ac.department_id WHERE ac.session = '$_GET[session]' && ac.course_year = '$_GET[course_year]' && ac.course_semester = '$_GET[course_semester]' && ac.teacher_id = '$_SESSION[teacher_id]' ";
        }
        $run_id_validation_qry = mysqli_query($conn, $id_validation_qry);
        $total_row = mysqli_num_rows($run_id_validation_qry);
        if($total_row==0)
        {
            
            ?>
            <script>
                window.alert("Invalid ID");
                window.location = "assigned_course_list.php";
            </script>
            <?php
            exit();
        }
    }
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header form_header text-center">
                    <h2>Assigned Course <?php echo "(".$_GET['session'].", ".$_GET['course_year']." , ".$_GET['course_semester'].")" ?></h2>
                </div>
                <form action="" method = "POST">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <?php
                            while($row = mysqli_fetch_assoc($run_id_validation_qry))
                            {
                                if($_GET['course_year']=="1st year")
                                {
                                    $department_id = 0;
                                    $department_name = "Foundation Course";
                                }
                                else
                                {
                                    $department_id = $row['department_id'];
                                    $department_name = $row['department_name'];
                                }
                                ?>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-12 mt-2">
                                    <div class="card assigned_course_card">
                                        <div class="card-header text-center">
                                            <h5 class = "font-weight-bold text-secondary">Department/Stream: <?php echo $department_name?> </h5>
                                            
                                            <h5 class = "font-weight-bold text-danger">Course Title: <?php echo $row['course_title'] ?></h5>
                                            <?php
                                            if($row['verification']==1)
                                            {
                                                ?>
                                                <p class = "form-control text-success mt-3 mb-0 text-center font-weight-bold">N.B:Verified <span><i class="fas fa-check-square"></i></span></p>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="card-body ">
                                            <?php
                                            if($row['verification']==0)
                                            {
                                                ?>
                                                <button type="button" class = "link_btn_not_verified form-control" onclick = "verify(<?php echo $row['id'];?>, '<?php echo $_GET['session'] ?>', '<?php echo $_GET['course_year'] ?>', '<?php echo $_GET['course_semester'] ?>')">Confirm</button>
                                                <?php
                                            }
                                            else if($row['verification']==1)
                                            {
                                                $add_or_update_marks_qry = "SELECT COUNT(`id`) as total_row FROM `result` WHERE `current_session` = '$_GET[session]' AND `course_year` = '$_GET[course_year]' AND `course_semester` = '$_GET[course_semester]' AND `course_id` = '$row[course_id]' AND `teacher_id` = '$_SESSION[teacher_id]'";
                                                $run_add_or_update_marks_qry = mysqli_query($conn, $add_or_update_marks_qry);
                                                $res_add_or_update_marks_qry = mysqli_fetch_assoc($run_add_or_update_marks_qry);
                                                if($res_add_or_update_marks_qry['total_row']>0)
                                                {
                                                    // Find total number of rows of result status = 1 to turn off the result
                                                    $count_result_status = "SELECT count(`id`) as `total_id` FROM `result` WHERE `result_status`='1' AND `current_session` = '$_GET[session]' AND `course_year` = '$_GET[course_year]' AND `course_semester` = '$_GET[course_semester]'";
                                                    $run_count_result_status = mysqli_query($conn, $count_result_status);
                                                    $res_count_result_status = mysqli_fetch_assoc($run_count_result_status);
                                                    ?>
                                                    <a <?php if($res_count_result_status['total_id']>0)
                                                        {
                                                            ?>
                                                                style = "pointer-events:none;"
                                                            <?php 
                                                        }
                                                     ?>href="update_internal_marks.php?session=<?php echo $_GET['session'] ?>&course_year=<?php echo $_GET['course_year'] ?>&course_semester=<?php echo $_GET['course_semester'] ?>&course_id=<?php echo $row['course_id'] ?>&department_id=<?php echo $department_id ?>" class = "form-control link_btn text-center">Update Internal Marks</a>
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <a href="add_internal_marks.php?id=<?php echo $row['id'] ?>&session=<?php echo $_GET['session'] ?>&course_year=<?php echo $_GET['course_year'] ?>&course_semester=<?php echo $_GET['course_semester'] ?>&course_id=<?php echo $row['course_id'] ?>&department_id=<?php echo $department_id ?>" class = "form-control link_btn text-center">Add Internal Marks</a>
                                                    <?php
                                                }
                                                ?>

                                                <?php
                                            }
                                            ?>
                                            <button class = " mt-3 link_btn form-control text-center">
                                                <a href = "course_wise_internal_marks_pdf.php?session=<?php echo $_GET['session'] ?>&course_year=<?php echo $_GET['course_year'] ?>&course_semester=<?php echo $_GET['course_semester'] ?>&course_id=<?php echo $row['course_id'] ?>&department_id=<?php echo $department_id ?>"><span class = "sidebar-icon"><i class="fas fa-file-pdf"></i></span>Internal Marks PDF</a>
                                            </button>
                                        </div>
                                    
                                    </div>

                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('lib/teacher_footer.php') ?>

<script>
    function verify(id, session, course_year, course_semester)
    {
        var del = confirm('Are you taking this course? If not then please cancel and contact with chairman');
        if(del == true)
        {
            window.location = "verify_assigned_course.php?id="+id+'&session='+session+'&course_year='+course_year+'&course_semester='+course_semester;
        }
    }
</script>

