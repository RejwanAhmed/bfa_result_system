<?php include('lib/header.php') ?>
<?php
    if(!isset($_GET['id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
    }
    else if(isset($_GET['id']))
    {
        // Start of Whether an id is valid or not
        $external_id_validation_qry = "SELECT * FROM `external_member` WHERE `id` = '$_GET[id]'";
        $external_id_validation_qry_run = mysqli_query($conn, $external_id_validation_qry);
        $external_id_validation_qry_run_res = mysqli_fetch_assoc($external_id_validation_qry_run);
        if($external_id_validation_qry_run_res==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "index.php";
            </script>
            <?php
        }
        //End of Whether an id is valid or not
    }
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header text-center form_header">
                    <h2>Update <?php echo $external_id_validation_qry_run_res['name'] ?> Information</h2>
                </div>
                <div class="card-body">
                    <form action="" method = "POST">
                        <div class="row m-3">
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>External Member Name:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" name = "name" placeholder = "Enter External Member Name" required value = "<?php
                                            if(isset($_POST['name']))
                                            {
                                                echo $_POST['name'];
                                            }
                                            else
                                            {
                                                echo $external_id_validation_qry_run_res['name'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>University:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-building"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" name = "university" placeholder = "Enter University" required value = "<?php
                                            if(isset($_POST['university']))
                                            {
                                                echo $_POST['university'];
                                            }
                                            else
                                            {
                                                echo $external_id_validation_qry_run_res['university'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
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
                                        <input type="text" class = "form-control" name = "department" placeholder = "Enter Department" required value = "<?php
                                            if(isset($_POST['department']))
                                            {
                                                echo $_POST['department'];
                                            }
                                            else
                                            {
                                                echo $external_id_validation_qry_run_res['department'];
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
                                            else if ($external_id_validation_qry_run_res['designation']=="Professor"){
                                                echo "selected";
                                            }  ?>>Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Associate Professor") echo "selected";
                                            else if ($external_id_validation_qry_run_res['designation']=="Associate Professor"){
                                                echo "selected";
                                            } ?>>Associate Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Assistant Professor") echo "selected";
                                            else if ($external_id_validation_qry_run_res['designation']=="Assistant Professor"){
                                                echo "selected";
                                            } ?>>Assistant Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Lecturer") echo "selected";
                                            else if ($external_id_validation_qry_run_res['designation']=="Lecturer"){
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
                                                echo $external_id_validation_qry_run_res['email'];
                                            }
                                        ?>" autocomplete="off">

                                    </div>
                                    <p id = "email"  class = "font-weight-bold bg-warning text-center"></p>
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
<?php include('lib/footer.php') ?>
<?php
    if(isset($_POST['submit']))
    {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $university = mysqli_real_escape_string($conn, $_POST['university']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $designation = mysqli_real_escape_string($conn, $_POST['designation']);
        $email = $_POST['email'];

        // Validation
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            ?>
                <script>
                    document.getElementById("email").innerHTML = `<i class="fas fa-exclamation-circle"></i> Invalid Email Address `;
                </script>
            <?php
            exit();
        }

        // validation before inserting into external_member
        $duplicate_qry = "SELECT * FROM `external_member` WHERE `email` = '$email'";
        $run_duplicate_qry = mysqli_query($conn, $duplicate_qry);
        // $total_duplicate_qry = mysqli_num_rows($run_duplicate_qry);
        while($value = mysqli_fetch_assoc($run_duplicate_qry))
        {
            if($value['email'] == $email && $value['id'] != $_GET['id'])
            {
                ?>
                <script>
                    document.getElementById('email').innerHTML = `<i class="fas fa-exclamation-circle"></i> Email Address Already Exists`;
                </script>
                <?php
                exit();
            }
        }
        $update_external_qry = "UPDATE `external_member` SET `name` = '$name', `university` = '$university', `department` = '$department', `designation` = '$designation', `email` = '$email' WHERE `id` = '$_GET[id]'";
        $run_update_external_qry = mysqli_query($conn, $update_external_qry);

        if($run_update_external_qry)
        {
            ?>
            <script>
                window.alert("External Member Information Updated Successfully");
                window.location = "view_external_member_information.php?page=<?php echo $_GET['page']; ?>";
            </script>
            <?php
        }
    }
?>
