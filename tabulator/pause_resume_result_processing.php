<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    include("lib/db_connection.php");
    if(!isset($_SESSION['exam_committee_id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
    
    // Note: eikhane department_id er validation nai karon department_id niye kono kaj nai
    
    
    // Start: url er course semester validation er jonno
    if(!isset($_GET['course_semester']) || !isset($_GET['committee_status']))
    {
        ?>
            <script>
                window.location = "home.php";
            </script>
        <?php
        exit();
    }
    else if((isset($_GET['course_semester']) && $_GET['course_semester']=='1st semester'))
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
    // End: url er course semester validation er jonno
    
    // Start: url er Committee_status validation er jonno
    if(isset(($_GET['committee_status'])) && ($_GET['committee_status']==1 || $_GET['committee_status']==2) )
    {
        $committee_status = $_GET['committee_status'];
    }
    else
    {
        ?>
            <script>
                window.alert("Invalid Committee Status");
                window.location = "home.php";
            </script>
        <?php
        exit();
    }
    // End: url er Committee_status validation er jonno
    
    // Start: exam_committee_information tabler 1st_sem_status or 2nd_sem_status change korbo. jdi status = 1 thake tahole 2 korbo ebong 2 thakle 1 korbo
    
    $update_exam_committee = "UPDATE `exam_committee_information` SET";
    if($course_semester == "1st semester")
    {
        $update_exam_committee.= "`1st_sem_status` = ";
    }
    else
    {
        $update_exam_committee.= "`2nd_sem_status` = ";
    }
    
    if($committee_status==1)
    {
        $update_exam_committee.="'2'";
    }
    else
    {
        $update_exam_committee.="'1'";
    }
    $update_exam_committee.=" WHERE `id` = '$_SESSION[exam_committee_id]'";
    $run_update_exam_committee = mysqli_query($conn, $update_exam_committee);
    if($run_update_exam_committee)
    {
        if($committee_status==1)
        {
            ?>
                <script>
                    window.alert("Result Processing Has Been Paused");
                    window.location = "1st_2nd_semester.php?semester=<?php echo $course_semester ?>&department_id=<?php echo $_GET['department_id'] ?>";
                </script>
            <?php
        }
        else
        {
            ?>
                <script>
                    window.alert("Result Processing Has Been Resumed");
                    window.location = "1st_2nd_semester.php?semester=<?php echo $course_semester ?>&department_id=<?php echo $_GET['department_id'] ?>";
                </script>
            <?php
        }
        exit();
    }
   
    // End: exam_committee_information tabler 1st_sem_status or 2nd_sem_status change korbo. jdi status = 1 thake tahole 2 korbo ebong 2 thakle 1 korbo 
?>