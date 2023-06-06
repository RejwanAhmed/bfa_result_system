<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    if(!isset($_SESSION['id']))
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
<?php include('valid_department_function.php'); ?>
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

    <title>Admin Panel</title>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" style = "height: 100vh; overflow-y: auto;">
            <div class="sidebar-header align-content-center">
                <img src="images/logo.png" >
            </div>
				<ul class="list-unstyled components">
	                <p class = "text-center text-uppercase text-bold" >Welcome Admin</p>
	                <li class="active">
	                    <a href="home.php"><span class = "sidebar-icon"><i class="fas fa-th-large"></i></span>Dashboard</a>
	                </li>
	                <li class="active">
	                    <a href="#departmentSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-building"></i></span>Department</a>
	                    <ul class="collapse list-unstyled" id="departmentSubmenu">
	                        <li>
	                            <a href="add_department.php"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span>Add Department</a>
	                        </li>
	                        <li>
	                            <a href="view_department_information.php"><span class = "sidebar-icon"><i class="fas fa-eye"></i></span>View Department</a>
	                        </li>
	                    </ul>
	                </li>

                    <li class="active">
	                    <a href="#teacherSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-users"></i></span>Teacher</a>
	                    <ul class="collapse list-unstyled" id="teacherSubmenu">
	                        <li>
	                            <a href="add_teacher.php"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span>Add Teacher</a>
	                        </li>
	                        <li>
	                            <a href="view_teacher_information.php"><span class = "sidebar-icon"><i class="fas fa-eye"></i></span>View Teacher</a>
	                        </li>
	                    </ul>
	                </li>
	                
	                <li class="active">
	                    <a href="#externalSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-user-secret"></i></span>External Member</a>
	                    <ul class="collapse list-unstyled" id="externalSubmenu">
	                        <li>
	                            <a href="add_external_member.php"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span>Add External</a>
	                        </li>
	                        <li>
	                            <a href="view_external_member_information.php"><span class = "sidebar-icon"><i class="fas fa-eye"></i></span>View External</a>
	                        </li>
	                    </ul>
	                </li>

	                <li class="active">
	                    <a href="#studentSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-user-graduate"></i></span>Student</a>
	                    <ul class="collapse list-unstyled" id="studentSubmenu">
	                        <li>
	                            <a href="add_student.php"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span>Add Student</a>
	                        </li>
	                        <li>
	                            <a href="view_student_information.php"><span class = "sidebar-icon"><i class="fas fa-eye"></i></span>View Student</a>
	                        </li>
	                    </ul>
	                </li>

                    <li class="active">
	                    <a href="#courseSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-atlas"></i></span>Course Information</a>
	                    <ul class="collapse list-unstyled" id="courseSubmenu">
	                        <li>
	                            <a href="add_course.php"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span>Add Course</a>
	                        </li>
	                        <li>
	                            <a href="view_course_information.php"><span class = "sidebar-icon"><i class="fas fa-eye"></i></span>View Course</a>
	                        </li>
	                    </ul>
	                </li>
                    <li class="active">
                        <a href="#assignCourseSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-map"></i></span>Assign Course</a>
                       <!-- valid department function theke valid department gulo niye aschi -->
                       <?php 
                            $valid_department_info = valid_department();
                            $department_id_array = $valid_department_info[0];
                            $department_name_array = $valid_department_info[1];
                            for($i=0;$i<sizeof($department_id_array);$i++)
                            {
                                ?>
                                <ul class="collapse list-unstyled" id="assignCourseSubmenu">
			                        <li>
			                            <a href="view_assign_course_session_wise.php?department_id=<?php echo $department_id_array[$i] ?>"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span><?php echo $department_name_array[$i] ?></a>
			                        </li>
			                    </ul>
                                <?php 
                            }
                       ?>
                   </li>

                    <li class="active">
	                    <a href="#exam_committeeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-chalkboard-teacher"></i></span>Exam Committee</a>
	                    <ul class="collapse list-unstyled" id="exam_committeeSubmenu">
	                        <li>
	                            <a href="add_exam_committee.php"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span>Add Committee</a>
	                        </li>
	                        <li>
	                            <a href="view_exam_committee_information.php"><span class = "sidebar-icon"><i class="fas fa-eye"></i></span>View Committee</a>
	                        </li>
	                    </ul>
	                </li>
					
					<li class="active">
	                    <a href="#settingSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-cogs"></i></span>Settings</a>
	                    <ul class="collapse list-unstyled" id="settingSubmenu">
	                        
	                        <li>
	                            <a href="change_email_password.php"><span class = "sidebar-icon"><i class="fas fa-exchange-alt"></i></span>Change Email and Password</a>
	                        </li>
	                    </ul>
	                </li>

	                <li>
	                    <a href="contact_developer.php"><span class = "sidebar-icon"><i class="fas fa-address-book"></i></span>Contact Developer</a>
	                </li>
					<li>
						<a href="admin_logout.php"><span class = "sidebar-icon"><i class="fas fa-key"></i></span>Logout</a>
	                </li>
	            </ul>
        </nav>

        <!-- Main Content -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-dark sticky-top">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn nav_btn">
                        <i class="fas fa-align-left"></i>

                    </button>
                    <h3 class = "text-center text-white mt-1"><i>Bachelor of Fine Arts Result System</i></h3>
                </div>
            </nav>
