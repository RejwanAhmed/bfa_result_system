<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    if(!isset($_SESSION['id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
?>

<?php
    include("lib/db_connection.php");
    // ei file include kora hocche kono ekta row delete kora jabe kina ta check korar jonno row_deletion_validation function er maddhome
    include('valid_department_function.php');
    if(isset($_GET['id']) && isset($_GET['status']))
    {
        // Start of Whether an id is valid or not
        $id_validation_qry = "SELECT * FROM `student_information` WHERE `id` = '$_GET[id]' AND `status` = '$_GET[status]'";
        $id_validation_qry_run = mysqli_query($conn, $id_validation_qry);
        $id_validation_qry_run_res = mysqli_fetch_assoc($id_validation_qry_run);
        
        if($id_validation_qry_run_res==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "index.php";
            </script>
            <?php
            exit();
        }
        $id = $_GET['id'];
        $page = $_GET['page'];
        //End of Whether an id is valid or not
    }
    else if(!isset($_GET['id']) && !isset($_GET['status']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
    
    if($_GET['status']==1)
    {
        $status = 0;
    }
    else
    {
        $status = 1;
    }
    
    // start: ekta student er status change korar age  check korbo je session er student er status change korte chacchi oi session er kono result ki processing obosthay ache naki. jdi processing  obosthay thake mane exam_committee_information table er 1st_sem_status or 2nd_sem_status er value jdi 1 thake tahole kono student er status change kora jabe na.
    // Note: Multiple year and semester er result processing obosthay thakte pare.
    $select_exam_committee = "SELECT `1st_sem_status`, `2nd_sem_status` FROM `exam_committee_information` WHERE `session` = '$id_validation_qry_run_res[current_session]' AND (`1st_sem_status` = '1' OR `2nd_sem_status` = '1')";
    $run_select_exam_committee = mysqli_query($conn, $select_exam_committee);
    
    if(mysqli_num_rows($run_select_exam_committee)>0)
    {
         ?>
             <script>
                 window.alert("Status Of This Student Can Not Be Changed At This Moment!! Result Is In Processing.");
                 window.location = "view_student_information.php?id=<?php echo $_GET['id'] ?>";
             </script>
         <?php
         exit(); 
    }
    // End: ekta student er status change korar age  check korbo je session er student er status change korte chacchi oi session er kono result ki processing obosthay ache naki. jdi processing  obosthay thake mane exam_committee_information table er 1st_sem_status or 2nd_sem_status er value jdi 1 thake tahole kono student er status change kora jabe na.
     
     
    // Again Start: status = 0 ache mane active ache. ekhn inactive e click korle 1 hoye jabe tar age check korte hobe student er kono semester jemon 1st_sem_status or 2nd_sem_status er value 2 ache naki. 2 thakle then check korbo je oi student er oi semester e kono ekta course er marks entry ache naki. jdi marks entry thake tahole student inactive kora jabe na.
    if($id_validation_qry_run_res['status']==0)
    {
        $select_exam_committee_again = "SELECT `course_year`, `1st_sem_status`, `2nd_sem_status` FROM  `exam_committee_information` WHERE `session` = '$id_validation_qry_run_res[current_session]' AND (`1st_sem_status` = '2' OR `2nd_sem_status` = '2')";
        $run_select_exam_committee_again = mysqli_query($conn, $select_exam_committee_again);
        
        if(mysqli_num_rows($run_select_exam_committee_again)>0)
        {
            // ekhn multiple year and semester er result processing obosthay thakte pare. tai while loop diye niye aschi. ekhn jekono ekta semester (jeta pause obosthay ache) er oi studenter ekta course er jdi marks entry thake tahole oi student er session change korte dewa jabe na.
                
            while($row_select_exam_committee_again = mysqli_fetch_assoc($run_select_exam_committee_again))
            {
                $search_course_year = $row_select_exam_committee_again['course_year'];
                $search_current_session = $id_validation_qry_run_res['current_session'];
                $search_student_id = $id_validation_qry_run_res['id'];
                
                // 1st_sem_status er value 2 hole 1st semester er result processing obosthay ache. otherwise 2nd semester er result processing obosthay ache.
                if($row_select_exam_committee_again['1st_sem_status']==2)
                {
                    $search_course_semester = "1st semester";
                }
                else
                {
                    $search_course_semester = "2nd semester";
                }
                // Now Search
                $search_from_result = "SELECT `id` FROM `result` WHERE `current_session` = '$search_current_session' AND `course_year` = '$search_course_year' AND `course_semester` = '$search_course_semester' AND `student_id` = '$search_student_id' AND `result_validation` = 'v'";
                $run_search_from_result = mysqli_query($conn, $search_from_result);
                if(mysqli_num_rows($run_search_from_result)>0)
                {
                    ?>
                    <script>
                        window.alert("This Student Can't Be De-Activated At This Moment Because This Student Result Is In Process!!");
                        window.location = "view_student_information.php?id=<?php echo $_GET['id'] ?>";
                    </script>
                    <?php
                    exit();
                }
            }
        }
    }

    // Again End: status = 0 ache mane active ache. ekhn inactive e click korle 1 hoye jabe tar age check korte hobe student er kono semester jemon 1st_sem_status or 2nd_sem_status er value 2 ache naki. 2 thakle then check korbo je oi student er oi semester e kono ekta course er marks entry ache naki. jdi marks entry thake tahole student inactive kora jabe na.
    
    $change_status_qry = "UPDATE `student_information` SET `status` = '$status' WHERE `id` = '$_GET[id]'";
    $change_status_qry_run = mysqli_query($conn, $change_status_qry);
    if($change_status_qry_run)
    {
        ?>
        <script type="text/javascript">
            window.alert("Student Status Updated Successfully");
            window.location = "view_student_profiles.php?id=<?php echo $_GET['id'] ?>&page=<?php echo $page; ?>";
        </script>
        <?php
        exit();         
    }

    
?>
