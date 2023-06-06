<?php include('lib/header.php') ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header text-center form_header">
                    <h2>Enter Course Details</h2>
                </div>
                <div class="card-body">
                    <form action="" method = "POST">
                        <div class="row m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Course Code:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-list-ol"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" name = "course_code" placeholder = "Enter Course Code" value = "<?php
                                            if(isset($_POST['course_code']))
                                            {
                                                echo $_POST['course_code'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                    <p id = "course_code" class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Course Title:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-book-open"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" name = "course_title" placeholder = "Enter Course Title" value = "<?php
                                            if(isset($_POST['course_title']))
                                            {
                                                echo $_POST['course_title'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                    <p id = "course_title" class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Course Credit:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-list-ol"></i></div>
                                        </div>
                                        <select class = "form-control" name="course_credit"
                                        required>
                                            <option value="" selected>Please Select Course Credit</option>
                                            <option <?php if(isset($_POST['course_credit']) && $_POST['course_credit']=="3") echo "selected"; ?>>3</option>
                                            <option <?php if(isset($_POST['course_credit']) && $_POST['course_credit']=="1.5") echo "selected";?>>1.5</option>
                                            
                                        </select>
                                    </div>
                                    <p id = "course_credit" class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row m-3"> 
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
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
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Course Semester:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-list-ol"></i></div>
                                        </div>
                                        <select class = "form-control" name="course_semester"
                                        required>
                                            <option value="" selected>Please Select Course Semester</option>
                                            <option <?php if(isset($_POST['course_semester']) && $_POST['course_semester']=="1st semester") echo "selected"; ?>>1st semester</option>
                                            <option <?php if(isset($_POST['course_semester']) && $_POST['course_semester']=="2nd semester") echo "selected";?>>2nd semester</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Department/Stream:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-building"></i></div>
                                        </div>
                                        <select name="department_name" id="" class = "form-control" required>
                                            <option value="">Please Select Department/Stream</option>
                                            
                                            <?php 
                                                $select_from_department = "SELECT * FROM `department_information`";
                                                $run = mysqli_query($conn, $select_from_department);
                                                while($row = mysqli_fetch_assoc($run))
                                                {
                                                    ?>
                                                    <option value="<?php echo $row['id'] ?>" <?php 
                                                        if(isset($_POST['department_name']) && $_POST['department_name']==$row['id'])
                                                        {
                                                            echo "selected";
                                                        }
                                                    ?>><?php echo $row['department_name']?></option>
                                                    <?php 
                                                }
                                            ?>
                                            <option value="0" <?php if(isset($_POST['department_name']) && $_POST['department_name']=='0') echo "selected"; ?>>Foundation Course</option>
                                        </select>
                                    </div>
                                </div>
                            </div>   
                        </div>

                        <div class="row m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Course Type:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fab fa-buffer"></i></div>
                                        </div>
                                        <select class = "form-control" name="course_type"
                                        required>
                                            <option value="" selected>Please Select Course Type</option>
                                            <option <?php if(isset($_POST['course_type']) && $_POST['course_type']=="Theory") echo "selected"; ?>>Theory</option>
                                            <option <?php if(isset($_POST['course_type']) && $_POST['course_type']=="Practical") echo "selected";?>>Practical</option>
                                            <option <?php if(isset($_POST['course_type']) && $_POST['course_type']=="Viva-Voce") echo "selected";?>>Viva-Voce</option>
                                        </select>
                                    </div>
                                    <p id = "error" class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <input type="submit" class = "form-control btn" name = "submit" value = "Enter">
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
        $course_code = mysqli_real_escape_string($conn,$_POST['course_code']);
        $course_title = mysqli_real_escape_string($conn,$_POST['course_title']);
        $course_credit = mysqli_real_escape_string($conn,$_POST['course_credit']);
        $course_semester = mysqli_real_escape_string($conn,$_POST['course_semester']);
        $course_year = mysqli_real_escape_string($conn,$_POST['course_year']);
        $department_name = mysqli_real_escape_string($conn,$_POST['department_name']);
        $course_type = mysqli_real_escape_string($conn,$_POST['course_type']);
        
        // validation before inserting 
        if($course_type == "Viva-Voce")
        {
            if($course_credit!="1.5")
            {
                ?>
                <script>
                    document.getElementById('course_credit').innerHTML = `<i class="fas fa-exclamation-circle"></i> For Viva-Voce Credit Must be 1.5 `;
                </script>
                <?php
                exit();
            }
            else 
            {
                $select_from_course = "SELECT `id` FROM `course_information` WHERE `course_type` = '$course_type' AND `course_year` = '$course_year' AND `course_semester` = '$course_semester' AND `department_id` = '$department_name'";
                $run_select_from_course = mysqli_query($conn, $select_from_course);
                $num_rows = mysqli_num_rows($run_select_from_course);
                if($num_rows>0)
                {
                    ?>
                    <script>
                        document.getElementById('error').innerHTML = `<i class="fas fa-exclamation-circle"></i> Viva-Voce Has Already Added For This Year `;
                    </script>
                    <?php
                    exit();
                }
            }
           
        }

        // Insert into course_information
        $insert_course_qry = "INSERT INTO `course_information`(`course_code`, `course_title`, `course_credit`, `course_year`, `course_semester`, `department_id`, `course_type`) VALUES ('$course_code','$course_title','$course_credit','$course_year','$course_semester','$department_name','$course_type')";
        $run_insert_course_qry = mysqli_query($conn, $insert_course_qry);

        if($run_insert_course_qry)
        {
            ?>
            <script>
                window.alert("Course Added Successfully");
                window.location = "view_course_information.php";
                // window.location = "add_course.php";
            </script>
            <?php
            exit();
        }
    }
?>
