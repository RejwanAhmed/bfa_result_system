<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    if(!isset($_SESSION['teacher_id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
?>
<?php include('db_connection.php'); ?>
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

    <title>Result Processing System</title>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" style = "height: 100vh; overflow-y: auto;">
            <div class="sidebar-header align-content-center">
                <img src="images/logo.png" >
            </div>
				<ul class="list-unstyled components">

                    <li class="active">
                       <a href="assigned_course_list.php"><span class = "sidebar-icon"><i class="fas fa-map"></i></span>Assigned Course</a>
                   </li>

					<li>
					   <a href="update_teacher_profile.php" disabled><span class = "sidebar-icon"><i class="fas fa-exchange-alt"></i></span>Update Profile</a>
				   </li>
				   
				   <li>
					   <a href="contact_developer.php" disabled><span class = "sidebar-icon"><i class="fas fa-address-book"></i></span>Contact Developer</a>
				   </li>

					<li>
						<a href="teacher_logout.php"><span class = "sidebar-icon"><i class="fas fa-key"></i></span>Logout</a>
	                </li>
	            </ul>
        </nav>

        <!-- Main Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-dark sticky-top">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn nav_btn">
                        <i class="fas fa-align-left"></i>

                    <!-- <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button> -->
                </div>
                <h4 class = "navbar_title mt-2 text-right">Welcome <?php echo $_SESSION['name'] ?></h4>

            </nav>
