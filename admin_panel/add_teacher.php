<?php include('lib/header.php') ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header text-center form_header">
                    <h2>Teacher Registration Form</h2>
                </div>
                <div class="card-body">
                    <form action="" method = "POST">
                        <div class="row m-3">
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Teacher Name:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" name = "name" placeholder = "Enter Teacher Name" required value = "<?php
                                            if(isset($_POST['name']))
                                            {
                                                echo $_POST['name'];
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
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Professor") echo "selected"; ?>>Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Associate Professor") echo "selected";?>>Associate Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Assistant Professor") echo "selected";?>>Assistant Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Lecturer") echo "selected";?>>Lecturer</option>
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
                                        ?>" autocomplete="off">

                                    </div>
                                    <p id = "email"  class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>
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
                                        ?>" autocomplete="off">
                                    </div>
                                    <p id = "contact_no"  class = "font-weight-bold bg-warning text-center"></p>
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
        $designation = mysqli_real_escape_string($conn, $_POST['designation']);
        $contact_no = mysqli_real_escape_string($conn, $_POST['contact_no']);
        $email = $_POST['email'];
        
        // random password generation
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
        $password = substr( str_shuffle( $chars ), 0, 8 );;
        // end of random password generation
        
        // $password = rand(100000,999999);
        $password = base64_encode($password);
        // validation before inserting into teacher_information
        $duplicate_qry = "SELECT * FROM `teacher_information` WHERE `email` = '$email' OR `contact_no` = '$contact_no'";
        $run_duplicate_qry = mysqli_query($conn, $duplicate_qry);
        $total_duplicate_qry = mysqli_num_rows($run_duplicate_qry);
        if($total_duplicate_qry>=1)
        {
            $value = mysqli_fetch_assoc($run_duplicate_qry);
            if($value['email'] == $email)
            {
                ?>
                <script>
                    document.getElementById('email').innerHTML = `<i class="fas fa-exclamation-circle"></i> Email address already exists `;
                </script>
                <?php
            }
            if($value['contact_no'] == $contact_no)
            {
                ?>
                <script>
                    document.getElementById('contact_no').innerHTML = `<i class="fas fa-exclamation-circle"></i> Contact no already exists`;
                </script>
                <?php
            }
            exit();
        }

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

        $insert_teacher_qry = "INSERT INTO `teacher_information` (`name`,`designation`,`email`,`contact_no`,`password`) VALUES ('$name','$designation','$email','$contact_no','$password')";
        $run_insert_teacher_qry = mysqli_query($conn, $insert_teacher_qry);

        if($run_insert_teacher_qry)
        {
            ?>
            <script>
                window.alert("Teacher Registered Successfully");
                window.location = "view_teacher_information.php";
            </script>
            <?php
            exit();
        }
    }
?>
