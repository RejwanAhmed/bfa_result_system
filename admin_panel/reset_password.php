<?php
    session_start();
    if(isset($_SESSION['id']))
    {
        ?>
        <script>
            window.location = "home.php";
        </script>
        <?php
        exit();
    }
    else if(isset($_SESSION['mail_sent']))
    {
        $mail_sent = $_SESSION['mail_sent'];
        $random_number = $_SESSION['random_number'];
    }
    else
    {
        ?>
            <script>
                window.location = "forgot_password.php";
            </script>
        <?php 
        exit();
    }
?>
<?php include('lib/db_connection.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="css/admin.css" />
	<link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/all.css">

    <script defer src="js/solid.js"></script>
    <script defer src="js/fontawesome.js"></script>
    <title>Admin Login</title>
    <style>
        body
        {
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="parent_div row ">
        <div class="child_div col-lg-5 col-md-8 col-sm-8 col-10">
            <div class="card">
                <div class="card-header form_header text-center">
                    <h2>Reset Password <span><i class="fas fa-power-off"></i></span></h2>
                    <h4 class = "text-success"><i><?php if($mail_sent==1) echo "Mail Has Sent"?></i></h4>
                </div>
                <div class="card-body">
                    <form action="" method = "POST">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for=""><b>OTP:</b></label>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-list-ol"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" name = "random_number" placeholder = "Please Enter Numbers From Mail" required value = "<?php
                                            if(isset($_POST['random_number']))
                                            {
                                                echo $_POST['random_number'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for=""><b>New Password:</b></label>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                        </div>
                                        <input type="password" class = "form-control" name = "new_password" placeholder = "Please Enter New Password" required value = "<?php
                                            if(isset($_POST['new_password']))
                                            {
                                                echo $_POST['new_password'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for=""><b>Confirm New Password:</b></label>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                        </div>
                                        <input type="password" class = "form-control" name = "confirm_new_password" placeholder = "Please Enter New Password Again" required value = "<?php
                                            if(isset($_POST['confirm_new_password']))
                                            {
                                                echo $_POST['confirm_new_password'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                    <p id = "error" class = "mt-2 font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <input type="submit" class = "form-control btn" name = "submit" value = "Submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php 
    if(isset($_POST['submit']))
    {   
        
        $random_number_from_user = mysqli_real_escape_string($conn, $_POST['random_number']);
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        $confirm_new_password = mysqli_real_escape_string($conn, $_POST['confirm_new_password']);
        
        if($random_number!=$random_number_from_user)
        {
            ?>
                <script>
                    document.getElementById('error').innerHTML = "Given Value is not correct!!";
                </script>
            <?php 
            exit();
        }
        else if(strlen($new_password)<=7)
        {
            ?>
            <script>
                document.getElementById('error').innerHTML = "Password Must Be At least 8 characters!!";
            </script>
            <?php 
            exit();
        }
        else if($new_password!=$confirm_new_password)
        {
            ?>
            <script>
                document.getElementById('error').innerHTML = "New Password and Confirm Password Does Not Match!!";
            </script>
            <?php 
            exit();
        }
        else
        {
            $new_password = md5($new_password);
            $update = "UPDATE `admin_info` SET `password` = '$new_password'";
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