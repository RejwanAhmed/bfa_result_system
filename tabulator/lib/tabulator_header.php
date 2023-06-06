<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    if(!isset($_SESSION['exam_committee_id']))
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
	<link rel="stylesheet" href="css/tabulator.css" />
	<link rel="stylesheet" href="css/tabulator_sidebar.css">
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
	                    <a href="home.php"><span class = "sidebar-icon"><i class="fas fa-th-large"></i></span>Dashboard</a>
	                </li>
	                <?php 
	                    if(isset($_SESSION['course_year']) && $_SESSION['course_year']=="1st year")
	                    {
	                        ?>
	                         <li class="active">
        	                    <a href="#foundationcoursesubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-chalkboard-teacher"></i></span>Foundation Course</a>
        	                    <ul class="collapse list-unstyled" id="foundationcoursesubmenu">
                                    <li class="active">
                                       <a href="1st_2nd_semester.php?semester=1st semester&department_id=0"><span class = "sidebar-icon"><i class="fas fa-map"></i></span>1st Semester Result</a>
                                    </li>
                                    <li class="active">
                                       <a href="1st_2nd_semester.php?semester=2nd semester&department_id=0"><span class = "sidebar-icon"><i class="fas fa-map"></i></span>2nd Semester Result</a>
                                    </li>
        	                    </ul>
        	                </li>
	                        <?php 
	                    }
	                    else
	                    {
	                        $find_department = "SELECT * FROM `department_information`";
	                        $run_find_department = mysqli_query($conn, $find_department);
	                        while($row = mysqli_fetch_assoc($run_find_department))
	                        {
	                        ?>
	                        <li class="active">
        	                    <a href="#department<?php echo $row['id'] ?>" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-chalkboard-teacher"></i></span><?php echo $row['department_name'] ?></a>
        	                    <ul class="collapse list-unstyled" id="department<?php echo $row['id'] ?>">
                                    <li class="active">
                                       <a href="1st_2nd_semester.php?semester=1st semester&department_id=<?php echo $row['id'] ?>"><span class = "sidebar-icon"><i class="fas fa-map"></i></span>1st Semester Result</a>
                                    </li>
                                    <li class="active">
                                       <a href="1st_2nd_semester.php?semester=2nd semester&department_id=<?php echo $row['id'] ?>"><span class = "sidebar-icon"><i class="fas fa-map"></i></span>2nd Semester Result</a>
                                    </li>
        	                    </ul>
        	                </li>
	                        <?php 
	                        }
	                    }
	                ?>
                    <!-- <li class="active">
                       <a href="1st_2nd_semester.php?semester=1st semester"><span class = "sidebar-icon"><i class="fas fa-map"></i></span>1st Semester Result</a>
                    </li>
                    <li class="active">
                       <a href="1st_2nd_semester.php?semester=2nd semester"><span class = "sidebar-icon"><i class="fas fa-map"></i></span>2nd Semester Result</a>
                    </li> -->

					<li>
					   <a href="contact_developer.php"><span class = "sidebar-icon"><i class="fas fa-address-book"></i></span>Contact Developer</a>
				   </li>

					<li>
						<a href="tabulator_logout.php"><span class = "sidebar-icon"><i class="fas fa-key"></i></span>Logout</a>
	                </li>
	            </ul>
        </nav>

        <!-- Main Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-dark sticky-top">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn nav_btn">
                        <i class="fas fa-align-left"></i>

                </div>
                <h4 class = "navbar_title mt-2 text-right">Welcome Tabulators <?php echo "($_SESSION[session], $_SESSION[course_year])" ?></h4>

            </nav>
