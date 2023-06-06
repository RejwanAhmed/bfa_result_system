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
                    <h2>Admin Login <span><i class="fas fa-user-shield"></i></span></h2>
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
                                        <input type="text" class = "form-control" name = "email" placeholder = "Enter Email" required value = "<?php
                                            if(isset($_POST['email']))
                                            {
                                                echo $_POST['email'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                    <p id = "email" class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Password:</b></label>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-key"></i></div>
                                        </div>
                                        <input type="password" class = "form-control" name = "password" placeholder="Enter password" value = "<?php if(isset($_POST['password']))
                                        {
                                            echo $_POST['password'];
                                        }?>" autocomplete="off">
                                    </div>
                                </div>
                                <p id = "invalid"  class = "font-weight-bold bg-warning text-center"></p>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12 text-center mb-2">
                                <a href="forgot_password.php" class = "text-danger"><b>Forgot your password?</b></a>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <input type="submit" class = "form-control btn" name = "submit" value = "Login">
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
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $password = md5($password);
        $select_from_admin_info = "SELECT * FROM `admin_info` WHERE `email` = '$email' AND `password` = '$password'";
        $run_select_from_admin_info = mysqli_query($conn, $select_from_admin_info);

        $res = mysqli_fetch_assoc($run_select_from_admin_info);
        if($res)
        {
            $_SESSION['id'] = $res['id'];

            ?>
                <script>
                    window.alert("Login Successfully Done");
                    window.location = "home.php";
                </script>
            <?php
        }
        else
        {
            ?>
            <script>
                document.getElementById("invalid").innerHTML = "Wrong email or Password";
            </script>
            <?php
        }
    }
?>
