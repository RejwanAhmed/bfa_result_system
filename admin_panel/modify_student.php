<?php include('lib/header.php') ?>
<?php
    if(!isset($_GET['id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
    else if(isset($_GET['id']))
    {
        // Start of Whether an id is valid or not
        $student_id_validation_qry = "SELECT * FROM `student_information` WHERE `id` = '$_GET[id]'";
        $student_id_validation_qry_run = mysqli_query($conn, $student_id_validation_qry);
        $student_id_validation_qry_run_res = mysqli_fetch_assoc($student_id_validation_qry_run);
        if($student_id_validation_qry_run_res==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "index.php";
            </script>
            <?php
            exit();
        }
        //End of Whether an id is valid or not
        $page_number = $_GET['page'];
    }
    // ekta student jdi foundation course chara ekbar ekta department e assign hoy ebong or kono marks result table e thake tahole oi student er department ar modify korte dewa jabe na
    if($student_id_validation_qry_run_res['department_id']!=0)
    {
        $search_department_id_from_result = "SELECT count(id) as total_id FROM `result` WHERE `student_id` = '$_GET[id]' AND `department_id` = '$student_id_validation_qry_run_res[department_id]'";
        $run_search_department_id_from_result = mysqli_query($conn, $search_department_id_from_result);
        $run = mysqli_fetch_assoc($run_search_department_id_from_result);
        if($run['total_id'] >0)
        {
            $get_dept_name = "SELECT `department_name` FROM `department_information` WHERE `id` = '$student_id_validation_qry_run_res[department_id]'";
            $run_get_dept_name = mysqli_query($conn, $get_dept_name);
            $department_name = mysqli_fetch_assoc($run_get_dept_name);
            $num_rows_from_result = $run['total_id'];
        }
        else
        {
            $num_rows_from_result = 0;
        }
    }
    else 
    {
        $num_rows_from_result = 0;
    }
    
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header text-center form_header">
                    <h2>Update <?php echo $student_id_validation_qry_run_res['name'] ?> Information</h2>
                </div>
                <div class="card-body">
                    <form action="" method = "POST">
                        <div class="row m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Student Name:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" name = "name" placeholder = "Enter Student Name" required value = "<?php
                                            if(isset($_POST['name']))
                                            {
                                                echo $_POST['name'];
                                            }
                                            else
                                            {
                                                echo $student_id_validation_qry_run_res['name'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Father Name:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-male"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" name = "father_name" placeholder = "Enter Student Father Name" required value = "<?php
                                            if(isset($_POST['father_name']))
                                            {
                                                echo $_POST['father_name'];
                                            }
                                            else
                                            {
                                                echo $student_id_validation_qry_run_res['father_name'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Mother Name:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-female"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" name = "mother_name" placeholder = "Enter Student Mother Name" required value = "<?php
                                            if(isset($_POST['mother_name']))
                                            {
                                                echo $_POST['mother_name'];
                                            }
                                            else
                                            {
                                                echo $student_id_validation_qry_run_res['mother_name'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Roll No:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-list-ol"></i></div>
                                        </div>
                                        <input type="number" class = "form-control" name = "roll_no" placeholder = "Enter Student Roll" required value = "<?php
                                            if(isset($_POST['roll_no']))
                                            {
                                                echo $_POST['roll_no'];
                                            }
                                            else
                                            {
                                                echo $student_id_validation_qry_run_res['roll_no'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                    <p id = "roll_no"  class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Registration No:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-list-ol"></i></div>
                                        </div>
                                        <input type="number" class = "form-control" name = "registration_no" placeholder = "Enter Student Registration No" required value = "<?php
                                            if(isset($_POST['registration_no']))
                                            {
                                                echo $_POST['registration_no'];
                                            }
                                            else
                                            {
                                                echo $student_id_validation_qry_run_res['registration_no'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                    <p id = "registration_no"  class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Date of Birth:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="far fa-calendar-check"></i></div>
                                        </div>
                                        <input type="date" class = "form-control" name = "date_of_birth" required placeholder="dd-mm-yyyy" value = "<?php
                                            if(isset($_POST['date_of_birth']))
                                            {
                                                echo $_POST['date_of_birth'];
                                            }
                                            else
                                            {
                                                echo $student_id_validation_qry_run_res['date_of_birth'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Actual Session:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-sort-amount-down"></i></div>
                                        </div>
                                        <?php 
                                            // jdi ekta student er result, result table e thake tahole oi student er actual_session freeze kore dite hobe. jate oita modify na kora jay.
                                            
                                            $find_student_in_result_table = "SELECT count(`id`) as total_id FROM `result` WHERE `student_id` = '$_GET[id]'";
                                            $run_find_student_in_result_table = mysqli_query($conn, $find_student_in_result_table);
                                            $num_rows_student = mysqli_fetch_assoc($run_find_student_in_result_table);
                                            if($num_rows_student['total_id']>0)
                                            {
                                               ?>
                                               <label for="" class = "form-control bg-dark text-light fw-bold"><?php echo $student_id_validation_qry_run_res['actual_session']?></label>
                                                <input type="hidden" name = "actual_session" value = "<?php echo $student_id_validation_qry_run_res['actual_session'] ?>">
                                                <?php 
                                            }
                                            else
                                            {
                                                ?>
                                                <select id = "actual_session" class = "form-control" name="actual_session" required>
                                                    <option value="" >Please Select Actual Session</option>
                                                    <?php
                											$c = 2006;
                											$today = date("Y");
                											$selected = 0;
                                                            for($i=$c; $i<$today; $i++)
                                                           {
                                                               $r = $i + 1;
                                                                $session= $i."-".$r;
                                                                ?>
                                                                <option value="<?php echo "$session";?>" <?php
                                                                if(isset($_POST['actual_session']) && $_POST['actual_session']==$session)
                                                                {   
                                                                    echo "selected";
                                                                    $selected++;
                                                                }
                                                                else if($student_id_validation_qry_run_res['actual_session']==$session)
                                                                {
                                                                    $selected++;
                                                                    if($selected==1)
                                                                    {
                                                                        echo "selected";
                                                                    }
                                                                    
                                                                }
                                                                ?>><?php echo $session; ?>
                                                                </option>
                                                                <?php
                                                           }
                										?>
                                                </select>
                                                <?php 
                                            }
                                        ?>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Current Session:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-sort-amount-down"></i></div>
                                        </div>
                                        <select class = "form-control <?php   if($student_id_validation_qry_run_res['student_type']=='Re-admitted') echo 'text-danger font-weight-bold'?>" name="current_session" required>
                                        
                                            <option value="" >Please Select Current Session</option>
                                            <?php
    											$c = 2006;
    											$today = date("Y");
    											$selected = 0; 
    											
    											
    											//Start: ekta student 15-16 er jdi readd hoy 16-17 er sathe and 16-17 er ekta semester e jdi tar marks thake tahole 15-16 te take ar pathano jabe na. 
    											// tai amra nicher query korbo. jekhane ekta student er sorboccho session ta pabo and oikhane thekeo ac_session ta suru hobe.
    											
    											$select_highest_session_from_result = "SELECT MAX(`current_session`) as `highest_current_session` FROM `result` WHERE `student_id` = '$student_id_validation_qry_run_res[id]'";
    											$run_select_highest_session_from_result = mysqli_query($conn, $select_highest_session_from_result);
    											$res_select_highest_session_from_result = mysqli_fetch_assoc($run_select_highest_session_from_result);
    											if($res_select_highest_session_from_result['highest_current_session']!=NULL)
    											{
                                                    $pos = strpos($res_select_highest_session_from_result['highest_current_session'], "-");
    											
                                                    $ac_session =substr($res_select_highest_session_from_result['highest_current_session'],0,$pos);
    											
    											}
    											else
    											{ 
                                                    // actual session er prothom part ber kora hoyese karon readmit jehetu porer batch er sathe hobe tai ager batch gula jate na dekhay tai loop ta jate current session theke chalano jay 
        											// Start of first part of current session
        											$pos = strpos($student_id_validation_qry_run_res['actual_session'], "-");
        											
        											$ac_session =substr($student_id_validation_qry_run_res['actual_session'],0,$pos);
        											// end of first part of current session
                                                }
    											
    											//End: ekta student 15-16 session er jdi readd hoy 16-17 session er sathe and 16-17 er ekta semester e jdi tar marks thake tahole 15-16 te take ar pathano jabe na. 
    											
                                                for($i=$ac_session; $i<$today; $i++)
                                                {
                                                   $r = $i + 1;
                                                    $session= $i."-".$r;
                                                    ?>
                                                    <option value="<?php echo "$session";?>" <?php
                                                    if(isset($_POST['current_session']) && $_POST['current_session']==$session)
                                                    {   
                                                        echo "selected";
                                                        $selected++;
                                                    }
                                                    else if($student_id_validation_qry_run_res['current_session']==$session)
                                                    {
                                                        $selected++;
                                                        if($selected==1)
                                                        {
                                                            echo "selected";
                                                        }
                                                    }
                                                    ?>><?php echo $session; ?>
                                                    </option>
                                                    <?php
                                                }
                                                
        										?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Student Type:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-alt"></i></div>
                                        </div>
                                        <input type="text" class = "form-control <?php   if($student_id_validation_qry_run_res['student_type']=='Re-admitted') echo 'text-danger font-weight-bold'?>" readonly value = "<?php echo $student_id_validation_qry_run_res['student_type']; ?>">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Contact No:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                        </div>
                                        <input type="number" class = "form-control" name = "contact_no" placeholder = "Enter Student Contact No" required value = "<?php
                                            if(isset($_POST['contact_no']))
                                            {
                                                echo $_POST['contact_no'];
                                            }
                                            else
                                            {
                                                echo $student_id_validation_qry_run_res['contact_no'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                    <p id = "contact_no"  class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Department/Stream:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-building"></i></div>
                                        </div>
                                        <?php 
                                        // ekta student jdi foundation course chara ekbar ekta department e assign hoy ebong or kono marks result table e thake tahole oi student er department ar modify korte dewa jabe na
                                        if($num_rows_from_result==0)
                                        {
                                            ?>
                                            <select name="department_name" id="" class = "form-control" required>
                                                <option value="">Please Select Department/Stream</option>
                                                <?php
                                                    $selected = 0;
                                                    $select_from_department = "SELECT * FROM `department_information` ";
                                                    $run = mysqli_query($conn, $select_from_department);
                                                    while($row = mysqli_fetch_assoc($run))
                                                    {
                                                        ?>
                                                        <option value="<?php echo $row['id'] ?>" <?php 
                                                        if(isset($_POST['department_name']) && $_POST['department_name']==$row['id'])
                                                        {
                                                            echo "selected";
                                                            $selected++;
                                                        }
                                                        else if($student_id_validation_qry_run_res['department_id']==$row['id'])
                                                        {
                                                            $selected++;
                                                            if($selected==1)
                                                            {
                                                                echo "selected";
                                                            } 
                                                        }
                                                        
                                                        ?>
                                                        ><?php echo $row['department_name']?> </option>
                                                        <?php 
                                                    }
                                                ?>
                                                <option value="0" <?php
                                                if(isset($_POST['department_name']) && $_POST['department_name']==0)
                                                {
                                                    echo "selected";
                                                }
                                                else if($selected ==0) echo "selected";?>>Foundation Course</option>
                                            </select>                                            
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <label for="" class = "form-control bg-dark text-light fw-bold"><?php echo $department_name['department_name']?></label>
                                            <input type="hidden" name = "department_name" value = "<?php echo $student_id_validation_qry_run_res['department_id'] ?>">
                                            <?php
                                        }
                                        ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <?php 
                                    // start: ekta student modify korar age check korbo je session er student modify korte chacchi oi session er kono result ki processing obosthay ache naki. jdi processing obosthay thake mane exam_committee_information table er 1st_sem_status or 2nd_sem_status er value jdi 1 thake tahole kono student modify kora jabe na.
            
                                     $select_exam_committee = "SELECT `1st_sem_status`, `2nd_sem_status` FROM `exam_committee_information` WHERE `session` = '$student_id_validation_qry_run_res[current_session]' AND (`1st_sem_status` = '1' OR `2nd_sem_status` = '1')";
                                     $run_select_exam_committee = mysqli_query($conn, $select_exam_committee);
                                     $row_select_exam_committee = mysqli_fetch_assoc($run_select_exam_committee);
                                     if($row_select_exam_committee == false)
                                     {
                                          ?>
                                          <input type="submit" class = "form-control btn" name = "submit" value = "Update">
                                          <?php 
                                     }
                                     else
                                     {
                                        echo "<h5 class = 'bg-danger text-center text-white'>Modification Disabled At This Moment</h5>";
                                     }
                                     // End: ekta student modify korar age check korbo je session er student modify korte chacchi oi session er kono result ki processing obosthay ache naki. jdi processing obosthay thake mane exam_committee_information table er 1st_sem_status or 2nd_sem_status er value jdi 1 thake tahole kono student modify kora jabe na. 
                                ?>
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('lib/footer.php') ?>

<?php
    if(isset($_POST['submit']))
    {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $father_name = mysqli_real_escape_string($conn, $_POST['father_name']);
        $mother_name = mysqli_real_escape_string($conn, $_POST['mother_name']);
        $roll_no = mysqli_real_escape_string($conn, $_POST['roll_no']);
        $registration_no = mysqli_real_escape_string($conn, $_POST['registration_no']);
        $actual_session = mysqli_real_escape_string($conn, $_POST['actual_session']);
        $current_session = mysqli_real_escape_string($conn, $_POST['current_session']);
        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
        $contact_no = mysqli_real_escape_string($conn, $_POST['contact_no']);
        $department_name = mysqli_real_escape_string($conn, $_POST['department_name']);
        // Validation
        if($roll_no<=0)
        {
            ?>
            <script>
                document.getElementById('roll_no').innerHTML = `<i class="fas fa-exclamation-circle"></i> Roll number can not be negative `;
            </script>
            <?php
            exit();
        }
        else if($registration_no<=0)
        {
            ?>
            <script>
                document.getElementById('registration_no').innerHTML = `<i class="fas fa-exclamation-circle"></i> Registration number can not be negative `;
            </script>
            <?php
            exit();
        }
        else if(strlen($contact_no)!=11 OR $contact_no<=0)
        {
            ?>
            <script>
                document.getElementById('contact_no').innerHTML = `<i class="fas fa-exclamation-circle"></i> Contact Number must be 11 digit and can not be negative `;
            </script>
            <?php
            exit();
        }

        // validation before inserting into student_information
        $duplicate_qry = "SELECT * FROM `student_information` WHERE `roll_no` = '$roll_no' OR `registration_no` = '$registration_no' OR `contact_no` = '$contact_no'";
        $run_duplicate_qry = mysqli_query($conn, $duplicate_qry);
        while($value = mysqli_fetch_assoc($run_duplicate_qry))
        {
            if($value['roll_no'] == $roll_no && $value['id'] != $_GET['id'])
            {
                ?>
                <script>
                    document.getElementById('roll_no').innerHTML = `<i class="fas fa-exclamation-circle"></i> Roll number already exists `;
                </script>
                <?php
                exit();
            }
            else if($value['registration_no'] == $registration_no && $value['id'] != $_GET['id'])
            {
                ?>
                <script>
                    document.getElementById('registration_no').innerHTML = `<i class="fas fa-exclamation-circle"></i> Registration number already exists `;
                </script>
                <?php
                exit();
            }
            else if($value['contact_no'] == $contact_no && $value['id'] != $_GET['id'])
            {
                ?>
                <script>
                    document.getElementById('contact_no').innerHTML = `<i class="fas fa-exclamation-circle"></i> Contact no already exists `;
                </script>
                <?php
                exit();
            }
        }
        if($actual_session == $current_session)
        {
            $student_type = "Regular";
        }
        else
        {
            $student_type = "Re-admitted";
        }
        
        // start: ekta student modify korar age check korbo jei session e student update korte chacchi oi session er kono result ki processing obosthay ache naki. jdi processing obosthay thake mane exam_committee_information table er 1st_sem_status or 2nd_sem_status er value jdi 1 thake tahole kono student update kora jabe na.
            
        $select_exam_committee = "SELECT `1st_sem_status`, `2nd_sem_status` FROM `exam_committee_information` WHERE `session` = '$current_session' AND (`1st_sem_status` = '1' OR `2nd_sem_status` = '1')";
        $run_select_exam_committee = mysqli_query($conn, $select_exam_committee);
        $row_select_exam_committee = mysqli_fetch_assoc($run_select_exam_committee);
        if($row_select_exam_committee)
        {
            ?>
            <script>
                window.alert("Student Can Not Be Updated At This Moment");
                window.location = "view_student_information.php?page=<?php echo $_GET['page']; ?>";
            </script>
            <?php
            exit();
        }
        // End: ekta student modify korar age check korbo je session er student modify korte chacchi oi session er kono result ki processing obosthay ache naki. jdi processing obosthay thake mane exam_committee_information table er 1st_sem_status or 2nd_sem_status er value jdi 1 thake tahole kono student modify kora jabe na. 
        
        
        // Start: ekta student er current session change korar age check korte hobe je oi student er oi current session mane student table e jeta ekhn ache oi session er kono result pause obosthay ache naki mane 1st_sem_status or 2nd_sem_status er value 2 ache naki. jdi 2 thake tahole je student ke update kortesi tar age check kore nibo je ekhn je current session select korsi er ager je current session jeta student table e chilo 2ta same naki. jdi same hoy tahole kichu korte hobe na. kintu jdi same na hoy tahole oi student er je session ta ekhn student table e ache oi session er protita year and semester check korte hobe (jesob semester er value 2) je kono marks entry kora ache naki. jdi kono marks entry kora thake ar result pause obosthay thake tahole oi student er session change kora jabe na. jemon ekta student 2016-2017 session er 1st year, 2nd semester er value 2 ache mane result pause obosthay ache ebong oi student er oi session oi year and oi semester er atleast ekta course er marks result table e ache. tkhn oi student er session change kora jabe na.
        
        $select_exam_committee_again = "SELECT `course_year`, `1st_sem_status`, `2nd_sem_status` FROM  `exam_committee_information` WHERE `session` = '$student_id_validation_qry_run_res[current_session]' AND (`1st_sem_status` = '2' OR `2nd_sem_status` = '2')";
        $run_select_exam_committee_again = mysqli_query($conn, $select_exam_committee_again);
        
        // Start: department/stream validation
        if(mysqli_num_rows($run_select_exam_committee_again)>0)
        {
            // jdi ei student er 1st semester othoba 2nd semester er result processing obosthay thake tahole er department/stream change kora jabe na
            if($student_id_validation_qry_run_res['department_id'] != $department_name)
            {
                ?>
                    <script>
                        window.alert("You can't change department/stream at this moment!");
                    </script>
                <?php 
                exit();
            }
            
        }
        // End: department/stream validation
           
        if($current_session!=$student_id_validation_qry_run_res['current_session'])
        {
            if(mysqli_num_rows($run_select_exam_committee_again)>0)
            {
                // ekhn multiple year and semester er result processing obosthay thakte pare. tai while loop diye niye aschi. ekhn jekono ekta semester (jeta pause obosthay ache) er oi studenter ekta course er jdi marks entry thake tahole oi student er session change korte dewa jabe na.
                
                while($row_select_exam_committee_again = mysqli_fetch_assoc($run_select_exam_committee_again))
                {
                    $search_course_year = $row_select_exam_committee_again['course_year'];
                    $search_current_session = $student_id_validation_qry_run_res['current_session'];
                    $search_student_id = $student_id_validation_qry_run_res['id'];
                    
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
                            window.alert("Student Session Can't Be Changed At This Moment Because This Student Result Is In Process!!");
                            window.location = "view_student_information.php?page=<?php echo $_GET['page']; ?>";
                        </script>
                        <?php
                        exit();
                    }
                }
            }
        }
        
        // End: ekta student er current session change korar age check korte hobe je oi student er oi current session mane student table e jeta ekhn ache oi session er kono result pause obosthay ache naki mane 1st_sem_status or 2nd_sem_status er value 2 ache naki. jdi 2 thake tahole je student ke update kortesi tar age check kore nibo je ekhn je current session select korsi er ager je current session jeta student table e chilo 2ta same naki. jdi same hoy tahole kichu korte hobe na. kintu jdi same na hoy tahole oi student er je session ta ekhn student table e ache oi session er protita year and semester check korte hobe (jesob semester er value 2) je kono marks entry kora ache naki. jdi kono marks entry kora thake ar result pause obosthay thake tahole oi student er session change kora jabe na. jemon ekta student 2016-2017 session er 1st year, 2nd semester er value 2 ache mane result pause obosthay ache ebong oi student er oi session oi year and oi semester er atleast ekta course er marks result table e ache. tkhn oi student er session change kora jabe na.
        
        $update_student_qry = "UPDATE `student_information` SET `name`='$name',`father_name`='$father_name',`mother_name`='$mother_name',`roll_no`='$roll_no',`registration_no`='$registration_no',`actual_session`='$actual_session',`current_session` = '$current_session', `date_of_birth`='$date_of_birth', `student_type` = '$student_type',`contact_no`='$contact_no',`department_id`='$department_name' WHERE `id` = '$_GET[id]'";

        $run_update_student_qry = mysqli_query($conn, $update_student_qry);
        if($run_update_student_qry)
        {
            ?>
            <script>
                window.alert("Student Updated Successfully");
                window.location = "view_student_information.php?page=<?php echo $_GET['page']; ?>";
            </script>
            <?php
            exit();
        }
    }

?>
