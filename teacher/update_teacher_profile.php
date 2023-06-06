<?php include('lib/teacher_header.php')?>
<?php 
    // Start of Whether an id is valid or not
    $teacher_id_validation_qry = "SELECT * FROM `teacher_information` WHERE `id` = '$_SESSION[teacher_id]'";
    $teacher_id_validation_qry_run = mysqli_query($conn, $teacher_id_validation_qry);
    $teacher_id_validation_qry_run_res = mysqli_fetch_assoc($teacher_id_validation_qry_run);
    if($teacher_id_validation_qry_run_res==false)
    {
        ?>
        <script>
            window.alert('Invalid Id');
            window.location = "assigned_course_list.php";
        </script>
        <?php
    }
    //End of Whether an id is valid or not
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header text-center form_header">
                    <h2>Update Profile</h2>
                </div>
                <div class="card-body">
                    <form action="" method = "POST">
                        <div class="row m-3">
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Name:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" name = "name" placeholder = "Enter Your Name" required value = "<?php
                                            if(isset($_POST['name']))
                                            {
                                                echo $_POST['name'];
                                            }
                                            else
                                            {
                                                echo $teacher_id_validation_qry_run_res['name'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Designation:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-graduate"></i></div>
                                        </div>
                                        <select class = "form-control" name="designation" id="designation"
                                        required>
                                            <option value="" selected>Please Select Designation</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Professor") echo "selected";
                                            else if ($teacher_id_validation_qry_run_res['designation']=="Professor"){
                                                echo "selected";
                                            }  ?>>Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Associate Professor") echo "selected";
                                            else if ($teacher_id_validation_qry_run_res['designation']=="Associate Professor"){
                                                echo "selected";
                                            } ?>>Associate Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Assistant Professor") echo "selected";
                                            else if ($teacher_id_validation_qry_run_res['designation']=="Assistant Professor"){
                                                echo "selected";
                                            } ?>>Assistant Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Lecturer") echo "selected";
                                            else if ($teacher_id_validation_qry_run_res['designation']=="Lecturer"){
                                                echo "selected";
                                            } ?>>Lecturer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Email:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="far fa-envelope"></i></div>
                                        </div>
                                        <input type="email" class = "form-control" name = "email" placeholder = "Enter Email" required value = "<?php
                                            if(isset($_POST['email']))
                                            {
                                                echo $_POST['email'];
                                            }
                                            else
                                            {
                                                echo $teacher_id_validation_qry_run_res['email'];
                                            }
                                        ?>" autocomplete="off">

                                    </div>
                                    <p id = "email"  class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Department:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-building"></i></div>
                                        </div>
                                        <select name="department" id="department" class = "form-control" required>
                                            <option value="">Please Select Department</option>
                                        <?php
                                            $qry1 = "select * from `department_information`";
                                            $res1 = mysqli_query($conn, $qry1);
                                            while($row1 = mysqli_fetch_assoc($res1))
                                            {
                                                ?>
                                                    <option value = "<?php echo $row1['id'] ?>"
                                                        <?php
                                                        if($row1['id'] == $teacher_id_validation_qry_run_res['department'])
                                                        {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $row1['department_name'];?></option>
                                                <?php
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Contact No:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                        </div>
                                        <input type="number" class = "form-control" name = "contact_no" placeholder = "Enter Contact No" required value = "<?php
                                            if(isset($_POST['contact_no']))
                                            {
                                                echo $_POST['contact_no'];
                                            }
                                            else
                                            {
                                                echo $teacher_id_validation_qry_run_res['contact_no'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                    <p id = "contact_no"  class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Password:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-key"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" value = "<?php echo base64_decode($teacher_id_validation_qry_run_res['password']); ?>" name = 'password'>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <input type="submit" class = "form-control btn" name = "submit" value = "Update">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('lib/teacher_footer.php')?>

<?php
    if(isset($_POST['submit']))
    {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $designation = mysqli_real_escape_string($conn, $_POST['designation']);
        $contact_no = mysqli_real_escape_string($conn, $_POST['contact_no']);
        $email = $_POST['email'];
        $dept_name = $_POST['department'];
        $password = $_POST['password'];
        $password = base64_encode($password);
        // Validation
        if(strlen($contact_no)!=11 )
        {
            ?>
            <script>
                document.getElementById('contact_no').innerHTML = `<i class="fas fa-exclamation-circle"></i> Contact Number must be 11 digit `;
            </script>
            <?php
            exit();
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            ?>
                <script>
                    document.getElementById("email").innerHTML = `<i class="fas fa-exclamation-circle"></i> Invalid Email Address `;
                </script>
            <?php
            exit();
        }

        // validation before inserting into teacher_information
        $duplicate_qry = "SELECT * FROM `teacher_information` WHERE (`email` = '$email') OR (`contact_no` = '$contact_no')";
        $run_duplicate_qry = mysqli_query($conn, $duplicate_qry);
        // $total_duplicate_qry = mysqli_num_rows($run_duplicate_qry);
        while($value = mysqli_fetch_assoc($run_duplicate_qry))
        {
            if($value['email'] == $email && $value['id'] != $_SESSION['teacher_id'])
            {
                ?>
                <script>
                    document.getElementById('email').innerHTML = `<i class="fas fa-exclamation-circle"></i> Email Address Already Exists`;
                </script>
                <?php
                exit();
            }
            if($value['contact_no'] == $contact_no && $value['id'] != $_SESSION['teacher_id'])
            {
                ?>
                <script>
                    document.getElementById('contact_no').innerHTML = `<i class="fas fa-exclamation-circle"></i> Contact No Already Exists`;
                </script>
                <?php
                exit();
            }
        }
        $update_teacher_qry = "UPDATE `teacher_information` SET `name` = '$name', `designation` = '$designation', `email` = '$email', `department` = '$dept_name', `contact_no` = '$contact_no', `password` = '$password' WHERE `id` = '$_SESSION[teacher_id]'";
        $run_update_teacher_qry = mysqli_query($conn, $update_teacher_qry);

        if($run_update_teacher_qry)
        {
            ?>
            <script>
                window.alert("Teacher Information Updated Successfully");
                window.location = "update_teacher_profile.php";
            </script>
            <?php
        }
    }
?>