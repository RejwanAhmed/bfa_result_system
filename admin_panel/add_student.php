<?php include('lib/header.php') ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header text-center form_header">
                    <h2>Student Registration Form</h2>
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
                                        ?>" autocomplete="off">
                                    </div>
                                    <p id = "registration_no"  class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Actual Session:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-sort-amount-down"></i></div>
                                        </div>
                                        <select class = "form-control" name="session" required>
                                            <option value="" >Please Select Session</option>
                                            <?php
        											$c = 2006;
        											$today = date("Y");
        											 for($i=$c; $i<$today; $i++)
        											 {
        												 $r = $i + 1;
                                                         $session= $i."-".$r;
                                                         ?>
                                                            <option value="<?php echo $session ?>" <?php if(isset($_POST['session']) && $_POST['session'] == $session)
                                                            echo "selected"?>><?php echo $session?></option>
                                                         <?php 
        											 }
        										?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-3">
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
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>

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
                                        <select name="department_name" id="" class = "form-control" required>
                                            <option value="">Please Select Department/Stream</option>
                                            
                                            <?php 
                                                $select_from_department = "SELECT * FROM `department_information`";
                                                $run = mysqli_query($conn, $select_from_department);
                                                while($row = mysqli_fetch_assoc($run))
                                                {
                                                    ?>
                                                    <option value="<?php echo $row['id'] ?>" <?php 
                                                        if(isset($_POST['department_name']) && $_POST['department_name']==$row['id'])
                                                        {
                                                            echo "selected";
                                                        }
                                                    ?>><?php echo $row['department_name']?></option>
                                                    <?php 
                                                }
                                            ?>
                                            <option value="0" <?php if(isset($_POST['department_name']) && $_POST['department_name']=='0') echo "selected"; ?>>Foundation Course</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <input type="submit" class = "form-control btn" name = "submit" value = "Register">
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
        $actual_session = mysqli_real_escape_string($conn, $_POST['session']);
        $current_session = mysqli_real_escape_string($conn, $_POST['session']);
        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
        $student_type = 'Regular';
        $contact_no = mysqli_real_escape_string($conn, $_POST['contact_no']);
        $department_name = mysqli_real_escape_string($conn, $_POST['department_name']);

        // status = 0 mane student active, status = 1 mane student freeze
        // default vabe student er status = 0 hobe. jdi student tar batch er sathe continue na kore tahole student er status 1 hobe.
        $status = 0;
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
        $duplicate_qry = "SELECT * FROM `student_information` WHERE `roll_no` = '$roll_no' OR `registration_no` = '$registration_no' OR `contact_no` = '$contact_no'" ;
        $run_duplicate_qry = mysqli_query($conn, $duplicate_qry);
        while($value = mysqli_fetch_assoc($run_duplicate_qry))
        {
            if($value['roll_no'] == $roll_no)
            {
                ?>
                <script>
                    document.getElementById('roll_no').innerHTML = `<i class="fas fa-exclamation-circle"></i> Roll number already exists `;
                </script>
                <?php
                exit();
            }
            else if($value['registration_no'] == $registration_no)
            {
                ?>
                <script>
                    document.getElementById('registration_no').innerHTML = `<i class="fas fa-exclamation-circle"></i> Registration number already exists `;
                </script>
                <?php
                exit();
            }
            else if($value['contact_no'] == $contact_no)
            {
                ?>
                <script>
                    document.getElementById('contact_no').innerHTML = `<i class="fas fa-exclamation-circle"></i> Contact no already exists `;
                </script>
                <?php
                exit();
            }
        }
        // start: Kono ekta session er student der jdi kono ekta course er 1st year 2nd semester ba tar porer year and semester er result hoye thake taohole oi session e new student add kora jabe na
            
        $validation = "SELECT COUNT(id) as `total_student` FROM `result` WHERE `actual_session` = '$actual_session' AND ((`course_year` = '1st year' AND `course_semester` = '2nd semester') OR (`course_year` >= '2nd year' AND `course_semester` >= '1st year'))";
        $run_validation = mysqli_query($conn, $validation);
        $res_validation = mysqli_fetch_assoc($run_validation);
        if($res_validation['total_student']>=1)
        {
            ?>
                <script>
                    window.alert("No New Student Can Be Added In This Session!!");
                    window.location = "add_student.php";
                </script>
            <?php
            exit();
        }
        // End: Kono ekta session er student der jdi kono ekta course er 1st year 2nd semester ba tar porer year and semester er result hoye thake taohole oi session e new student add kora jabe na
        
        // start: ekta student add korar age check korbo je session add korte chacchi oi session er kono result ki processing obosthay ache naki. jdi processing obosthay thake mane exam_committee_information table er 1st_sem_status or 2nd_sem_status er value jdi 1 thake tahole kono student add kora jabe na.
        
        $select_exam_committee = "SELECT `1st_sem_status`, `2nd_sem_status` FROM `exam_committee_information` WHERE `session` = '$actual_session' AND (`1st_sem_status` = '1' OR `2nd_sem_status` = '1')";
        $run_select_exam_committee = mysqli_query($conn, $select_exam_committee);
        $row_select_exam_committee = mysqli_fetch_assoc($run_select_exam_committee);
        if($row_select_exam_committee)
        {
            ?>
                <script>
                    window.alert("No New Student Can Be Added In This Session At This Moment!!");
                    window.location = "add_student.php";
                </script>
            <?php
            exit(); 
        }
        // End: ekta student add korar age check korbo je session add korte chacchi oi session er kono result ki processing obosthay ache naki. jdi processing obosthay thake mane exam_committee_information table er 1st_sem_status or 2nd_sem_status er value jdi 1 thake tahole kono student add kora jabe na. 
        
        
        $insert_student_qry = "INSERT INTO `student_information`(`name`, `father_name`, `mother_name`, `roll_no`, `registration_no`, `actual_session`, `current_session`, `date_of_birth`, `student_type`, `contact_no`, `department_id`, `status`) VALUES ('$name','$father_name','$mother_name','$roll_no','$registration_no','$actual_session','$current_session','$date_of_birth','$student_type','$contact_no','$department_name','$status')";
        $run_insert_student_qry = mysqli_query($conn, $insert_student_qry);
        if($run_insert_student_qry)
        {
            ?>
            <script>
                window.alert("Student Registered Successfully");
                window.location = "view_student_information.php";
            </script>
            <?php
            exit();
        }
    }

?>
