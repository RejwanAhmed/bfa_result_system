<?php
    session_start();
    if(isset($_SESSION['exam_committee_id']))
    {
        ?>
        <script>
            window.location = "home.php";
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
	<link rel="stylesheet" href="css/tabulator.css" />
	<link rel="stylesheet" href="css/tabulator_sidebar.css">
    <link rel="stylesheet" href="css/all.css">

    <script defer src="js/solid.js"></script>
    <script defer src="js/fontawesome.js"></script>
    <title>Tabulator</title>
</head>
<body>
    <div class="container-fluid">
        <div class="parent_div row justify-content-center">
            <div class="child_div col-lg-6 col-md-10 col-sm-10 col-12">
                <div class="card">
                    <div class="card-header form_header text-center">
                        <h2>Tabulator Login <span><i class="fas fa-user-shield"></i></span></h2>
                    </div>
                    <div class="card-body">
                        <form action="" method = "POST">
                            <div class="row justify-content-center mt-1">
                                <div class="col-lg-6 col-md-6 col-12 mt-1">
                                    <div class="form-group">
                                        <label for=""><b>Session:</b></label>
                                        <br>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-sort-amount-down"></i></div>
                                            </div>
                                            <select class = "form-control" name="session" required>
                                                <option value="" >Please Select Session</option>
                                                <?php
                                                         $c = 2006;
                                                        $today = date("Y");
                                                         for($i=$c; $i<$today; $i++)
                                                         {
                                                             $r = $i + 1;
                                                             $session= $i."-".$r;
                                                             echo "<option value='$session'>";
                                                             echo $session;
                                                             echo "</option>";
                                                         }
                                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12 mt-1">
                                    <div class="form-group">
                                        <label for=""><b>Course Year:</b></label>
                                        <br>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-list-ol"></i></div>
                                            </div>
                                            <select class = "form-control" name="course_year"
                                            required>
                                                <option value="" selected>Please Select Course Year</option>
                                                <option <?php if(isset($_POST['course_year']) && $_POST['course_year']=="1st year") echo "selected"; ?>>1st year</option>
                                                <option <?php if(isset($_POST['course_year']) && $_POST['course_year']=="2nd year") echo "selected";?>>2nd year</option>
                                                <option <?php if(isset($_POST['course_year']) && $_POST['course_year']=="3rd year") echo "selected";?>>3rd year</option>
                                                <option <?php if(isset($_POST['course_year']) && $_POST['course_year']=="4th year") echo "selected";?>>4th year</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-1">
                                <div class="col-12 mt-1">
                                    <div class="form-group">
                                        <label for=""><b>Tabulator1 Password:</b></label>
                                        <br>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-key"></i></div>
                                            </div>
                                            <input type="password" class = "form-control" name = "tabulator1_password" placeholder="Enter Tabulator1 Password" value = "<?php if(isset($_POST['tabulator1_password']))
                                            {
                                                echo $_POST['tabulator1_password'];
                                            }?>" autocomplete="off">
                                        </div>
                                    </div>
                                    <p id = "tabulator1_password"  class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>

                            <div class="row justify-content-center mt-1">
                                <div class="col-12 mt-1">
                                    <div class="form-group">
                                        <label for=""><b>Tabulator2 Password:</b></label>
                                        <br>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-key"></i></div>
                                            </div>
                                            <input type="password" class = "form-control" name = "tabulator2_password" placeholder="Enter Tabulator2 Password" value = "<?php if(isset($_POST['tabulator2_password']))
                                            {
                                                echo $_POST['tabulator2_password'];
                                            }?>" autocomplete="off">
                                        </div>
                                    </div>
                                    <p id = "tabulator2_password"  class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>

                            <div class="row justify-content-center mt-1">
                                <div class="col-12 mt-1">
                                    <div class="form-group">
                                        <label for=""><b>Tabulator3 Password:</b></label>
                                        <br>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-key"></i></div>
                                            </div>
                                            <input type="password" class = "form-control" name = "tabulator3_password" placeholder="Enter Tabulator3 Password" value = "<?php if(isset($_POST['tabulator3_password']))
                                            {
                                                echo $_POST['tabulator3_password'];
                                            }?>" autocomplete="off">
                                        </div>
                                    </div>
                                    <p id = "tabulator3_password"  class = "font-weight-bold bg-warning text-center"></p>
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
    </div>
</body>
</html>

<?php
    if(isset($_POST['submit']))
    {
        $session = $_POST['session'];
        $course_year = $_POST['course_year'];
        $tabulator1_password = mysqli_real_escape_string($conn, $_POST['tabulator1_password']);
        $tabulator2_password = mysqli_real_escape_string($conn, $_POST['tabulator2_password']);
        $tabulator3_password = mysqli_real_escape_string($conn, $_POST['tabulator3_password']);

        $tabulator1_password = base64_encode($tabulator1_password);
        $tabulator2_password = base64_encode($tabulator2_password);
        $tabulator3_password = base64_encode($tabulator3_password);

        $select_from_exam_commiitee = "SELECT * FROM `exam_committee_information` WHERE `session` = '$session' AND `course_year` = '$course_year' AND `tabulator1_pass` = '$tabulator1_password' AND `tabulator2_pass` = '$tabulator2_password' AND `tabulator3_pass` = '$tabulator3_password'";

        $run_select_from_exam_committee = mysqli_query($conn, $select_from_exam_commiitee);

        $res = mysqli_fetch_assoc($run_select_from_exam_committee);
        if($res)
        {
            $_SESSION['exam_committee_id'] = $res['id'];
            $_SESSION['tabulator1_id'] = $res['tabulator1_id'];
            $_SESSION['tabulator2_id'] = $res['tabulator2_id'];
            $_SESSION['tabulator3_id'] = $res['tabulator3_id'];
            $_SESSION['session'] = $res['session'];
            $_SESSION['course_year'] = $res['course_year'];
            $_SESSION['exam_year'] = $res['exam_year'];

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
                window.alert("Wrong Session Or Course Year Or Tabulator Password");
            </script>
            <?php
        }
    }
?>
