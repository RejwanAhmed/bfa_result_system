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
                    <h2>Fogot Password <span><i class="fas fa-question-circle"></i></span></h2>
                    <h4>Enter Email To Recover Password</h4>
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
                                        <input type="text" class = "form-control" name = "email" placeholder = "Enter Your Email" required value = "<?php
                                            if(isset($_POST['email']))
                                            {
                                                echo $_POST['email'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                    <p id = "email" class = "mt-2 font-weight-bold bg-warning text-center"></p>
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
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $select_from_admin_info = "SELECT * FROM `admin_info` WHERE `email` = '$email'";
        $run_select_from_admin_info = mysqli_query($conn, $select_from_admin_info);

        $res = mysqli_fetch_assoc($run_select_from_admin_info);
        if($res)
        {
            $mail_sent = 0;
            // generate a random number and send to email
            $random_number = rand(100000,999999);
            
            // Mail Sending
            require 'phpmailer/PHPMailerAutoload.php';
            $mail = new PHPMailer;
            $sender_email = 'resultsystemjkkniu@gmail.com';
            $sender_pass = 'result_system_jkkniu';
      
            $receiver = $res['email'];
            // $mail->isSMTP(); // for localhost use enable this line otherwise don't use it
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
    
            $mail->Username = $sender_email; // Sender Email Id
            $mail->Password = $sender_pass; // password of gmail
    
            $mail->setFrom($sender_email,'JKKNIU');
    
            $mail->addAddress($receiver); // Receiver Email Address
            $mail->addReplyTo($sender_email);
    
            $mail->isHTML(true);
            $mail->Subject = "Password Recovery For Admin Panel";
            $mail->Body = '<h5>Dear Sir/Madam, <br />Please Enter The Following OTP Into Password Recovery Field To Reset Password. <br /> <br /> '.$random_number.' <br /> Best Regards, Developer</h5>';
            if($mail->send())
            {
                $mail->ClearAddresses();
                $mail->clearReplyTos();
                // mail_sent = 1 kore dilam er mane mail sent hoyse.
                $mail_sent = 1;
            }
            // Mail Sending 
            
            $_SESSION['mail_sent'] = $mail_sent;
            $_SESSION['random_number'] = $random_number;
            ?>
                <script>
                    window.location = "reset_password.php";
                </script>
            <?php 
            exit();
        }
        else
        {
            ?>
            <script>
                document.getElementById("email").innerHTML = "Wrong email";
            </script>
            <?php
            // exit();
        }
    }
?>
