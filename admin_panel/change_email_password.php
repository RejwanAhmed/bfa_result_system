<?php include('lib/header.php')?>
<?php 
    $select_from_admin = "SELECT * FROM `admin_info` WHERE `id` = '$_SESSION[id]'";
    $run_select_from_admin = mysqli_query($conn, $select_from_admin);
    $row = mysqli_fetch_assoc($run_select_from_admin);
    $current_password_from_database = $row['password'];
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <div class="card">
                <div class="card-header text-center form_header">
                    <h2>Change Admin Email And Password</h2>
                </div>
                <div class="card-body">
                    <form action="" method = "POST">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for=""><b>Email:</b></label>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user"></i></div>
                                        </div>
                                        <input type="email" class = "form-control" name = "email" placeholder = "Enter Email" required value = "<?php
                                            if(isset($_POST['email']))
                                            {
                                                echo $_POST['email'];
                                            }
                                            else
                                            {
                                                echo $row['email'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Current Password:</b></label>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-key"></i></div>
                                        </div>
                                        <input type="password" class = "form-control" name = "current_password" placeholder="Enter Current Password" value = "<?php if(isset($_POST['current_password']))
                                        {
                                            echo $_POST['current_password'];
                                        }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>New Password:</b></label>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-key"></i></div>
                                        </div>
                                        <input type="password" class = "form-control" name = "new_password" placeholder="Enter New Password" value = "<?php if(isset($_POST['new_password']))
                                        {
                                            echo $_POST['new_password'];
                                        }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Confirm Password:</b></label>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-key"></i></div>
                                        </div>
                                        <input type="password" class = "form-control" name = "confirm_password" placeholder="Enter New Password Again" value = "<?php if(isset($_POST['confirm_password']))
                                        {
                                            echo $_POST['confirm_password'];
                                        }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                                <p id = "invalid"  class = "font-weight-bold bg-warning text-center"></p>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <input type="submit" class = "form-control btn" name = "submit" value = "Confirm">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('lib/footer.php')?>

<?php 
    if(isset($_POST['submit']))
    {
        $email = $_POST['email'];
        $current_password = md5($_POST['current_password']);
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if($current_password_from_database != $current_password)
        {
            ?>
                <script>
                    document.getElementById('invalid').innerHTML = "Current Password Does Not Match!!";
                </script>
            <?php 
            exit();
        }
        else if($new_password != $confirm_password)
        {
            ?>
            <script>
                document.getElementById('invalid').innerHTML = "New Password and Confirm Password Does Not Match!!";
            </script>
            <?php 
            exit();
        }
        else if(strlen($new_password)<=7)
        {
            ?>
            <script>
                document.getElementById('invalid').innerHTML = "Password Must Be At Least 8 characters!!";
            </script>
            <?php 
            exit();
        }
        else
        {
            $new_password = md5($new_password);
            $update = "UPDATE `admin_info` SET `email` = '$email', `password` = '$new_password' WHERE `id` = '$_SESSION[id]'";
            $run_update = mysqli_query($conn, $update);
            if($run_update)
            {
                session_unset();
                session_destroy();
                ?>
                    <script>
                        window.alert("New Password Set Successfully!!");
                        window.location = "index.php";
                    </script>
                <?php 
            }
        }
    }
?>