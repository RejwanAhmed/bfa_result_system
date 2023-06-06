<?php include("lib/tabulator_header.php") ?>
<?php include("valid_department_function.php") ?>
<?php 
    // validation
    if(!isset($_GET['course_semester']) || !isset($_GET['department_id']))
    {
        ?>
            <script>
                window.location = "home.php";
            </script>
        <?php
        exit();
    }
    else if(isset($_GET['course_semester']) && $_GET['course_semester']=='1st semester')
    {
        $course_semester = $_GET['course_semester'];
    }
    else if(isset($_GET['course_semester']) && $_GET['course_semester']=='2nd semester')
    {
        $course_semester = $_GET['course_semester'];
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
                    <h2><?php echo " All Students Of ($_SESSION[session], $_SESSION[course_year], $course_semester) " ?></h2>
                    <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
                    
                     <!-- Get All Students of a session -->
                     <?php 
                         // jdi department = Foundation course hoy tahole sob student der ke show korbe. otherwise department onujayi student show korbe.
                        // status = 0 mane student active ache.
                        if($department_id==0)
                        {
                            $select_student = "SELECT * FROM `student_information` WHERE `current_session` = '$_SESSION[session]' AND `status` = '0' ORDER BY `actual_session` DESC ,`roll_no` ASC ";
                        }
                        else
                        {
                            $select_student = "SELECT s.roll_no, s.actual_session, s.current_session, d.department_name FROM `student_information` as s INNER JOIN `department_information` as d ON s.department_id = d.id WHERE `current_session` = '$_SESSION[session]' AND `status` = '0' ORDER BY `actual_session` DESC ,`roll_no` ASC, `department_id` ASC ";
                            // $select_student = "SELECT * FROM `student_information` WHERE `current_session` = '$_SESSION[session]' AND `status` = '0' AND `department_id` = '$department_id' ORDER BY `actual_session` DESC ,`roll_no` ASC ";
                        }
                        $run_select_student = mysqli_query($conn, $select_student);
                        $num_rows = mysqli_num_rows($run_select_student);
                        
                    ?>
                    <h4 class = "text-danger text-center bg-white ">Please verify if all students are listed or not<span class = "bg-dark text-white "><br>Total Students: <?php echo $num_rows ?></span></h4>
                    
                </div>
                <div class="card-body table-responsive">
                    <form action="" method = "POST">
                        <button type = "submit" class = "btn mb-2" name = "start_processing">Start Result Processing</button>
                    </form>
                    <table class = "table  table-bordered table-hover text-center ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Roll No</th>
                                <th>Actual Session</th>
                                <th>Current Session</th>
                                <?php 
                                    if($department_id!=0)
                                    {
                                        ?>
                                        <th>Department Name</th>
                                        <?php 
                                    }
                                ?>
                            </tr>
                        </thead>
                        <?php 
                            $i=1;
                            while($row = mysqli_fetch_assoc($run_select_student))
                            {
                                ?>
                                    <tr>
                                        <td><?php echo $i ?></td>
                                        <td><b><?php echo $row['roll_no']?></b></td>
                                        <?php 
                                            if($row['actual_session']!=$row['current_session'])
                                            {
                                                ?>
                                                    <td class = "text-danger"><b><?php echo $row['actual_session']?></b></td>
                                                    <td class = "text-danger"><b><?php echo $row['current_session']?></b></td>
                                                <?php 
                                            }
                                            else
                                            {
                                                ?>
                                                    <td><?php echo $row['actual_session']?></td>
                                                    <td><?php echo $row['current_session']?></td>
                                                <?php                           
                                            }
                                        ?>
                                        <?php 
                                            if($department_id!=0)
                                            {
                                                ?>
                                                    <td><i><b><?php echo $row['department_name']?></b></i></td>
                                                <?php 
                                            }
                                        ?>
                                    </tr>
                                <?php 
                                $i++;
                            }
                        ?>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("lib/tabulator_footer.php") ?>
<?php 
    if(isset($_POST['start_processing']))
    {
        if($course_semester=="1st semester")
        {
            $update = "UPDATE `exam_committee_information` SET `1st_sem_status` = '1' WHERE `id` = '$_SESSION[exam_committee_id]'";
            $run_update = mysqli_query($conn, $update);
        }
        else
        {
            $update = "UPDATE `exam_committee_information` SET `2nd_sem_status` = '1' WHERE `id` = '$_SESSION[exam_committee_id]'";
            $run_update = mysqli_query($conn, $update);
        }
        if($run_update)
        {
            ?>
                <script>
                    window.alert("Result Processing Has Been Started");
                    window.location = "1st_2nd_semester.php?semester=<?php echo $course_semester?>&department_id=<?php echo $department_id ?>";
                </script>
            <?php 
            exit();
        }
        
    }
?>