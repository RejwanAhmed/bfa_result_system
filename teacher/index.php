<?php
    session_start();
    if(isset($_SESSION['teacher_id']))
    {
        ?>
        <script>
            window.location = "assigned_course_list.php";
        </script>
        <?php
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
	<link rel="stylesheet" href="css/teacher.css" />
	<link rel="stylesheet" href="css/teacher_sidebar.css">
    <link rel="stylesheet" href="css/all.css">

    <script defer src="js/solid.js"></script>
    <script defer src="js/fontawesome.js"></script>
    <title>Teacher</title>
</head>
<body>
    <div class="parent_div row ">
        <div class="child_div col-lg-5 col-md-8 col-sm-8 col-10">
            <div class="card">
                <div class="card-header form_header text-center">
                    <h2>Teacher Login <span><i class="fas fa-user-shield"></i></span></h2>
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
                                            <div class="input-group-text"><i class="far fa-envelope"></i></div>
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
                                        <input type="password" class = "form-control" name = "password" placeholder="Enter Your Password" value = "<?php if(isset($_POST['password']))
                                        {
                                            echo $_POST['password'];
                                        }?>" autocomplete="off">
                                    </div>
                                </div>
                                <p id = "invalid"  class = "font-weight-bold bg-warning text-center"></p>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-12 mt-2">
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
        $password = base64_encode($password);
        $select_from_teacher_information = "SELECT * FROM `teacher_information` WHERE `email` = '$email' AND `password` = '$password'";
        $run_select_from_teacher_information = mysqli_query($conn, $select_from_teacher_information);

        $res = mysqli_fetch_assoc($run_select_from_teacher_information);
        if($res)
        {
            $_SESSION['teacher_id'] = $res['id'];
            $_SESSION['name'] = $res['name'];

            ?>
                <script>
                    window.alert("Login Successfully Done");
                    window.location = "assigned_course_list.php";
                </script>
            <?php
        }
        else
        {
            ?>
            <script>
                document.getElementById("invalid").innerHTML = "Wrong Username or Password";
            </script>
            <?php
        }
    }
?>
