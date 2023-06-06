<?php include('lib/header.php') ?>
<?php
    if(!isset($_GET['id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
    else if(isset($_GET['id']))
    {
        // Start of Whether an id is valid or not
        $course_id_validation_qry = "SELECT * FROM `course_information` WHERE `id` = '$_GET[id]'";
        $course_id_validation_qry_run = mysqli_query($conn, $course_id_validation_qry);
        $course_id_validation_qry_run_res = mysqli_fetch_assoc($course_id_validation_qry_run);
        if($course_id_validation_qry_run_res==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "index.php";
            </script>
            <?php
            exit();
        }
        //End of Whether an id is valid or not
        $page_number = $_GET['page'];
    }
    
    // row_deletion_validation($table_name, $column_name, $id)
    $num_rows = 0;
    // modification validation from assigned_course_information table
    if(row_deletion_validation('assigned_course_information','course_id',$_GET['id'])==0)
    {
        // modification validation from result table
       if(row_deletion_validation('result','course_id',$_GET['id'])==0)
       {
        // modification validation from improvement_result table
            if(row_deletion_validation('improvement_result','course_id',$_GET['id'])>0)
            {
                $num_rows = 1;
            }
       }
       else
       {
            $num_rows = 1;
       }
    }
    else
    {
       $num_rows = 1; 
    }
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header text-center form_header">
                    <h2>Update <?php if($course_id_validation_qry_run_res['course_type'] == "Viva-Voce")
                    {
                        echo "Viva-Voce Information";
                    }
                    else
                    {
                        echo $course_id_validation_qry_run_res['course_title'] ?> Information</h2>
                        <?php 
                    }
                    ?>
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
                                            else
                                            {
                                                echo $course_id_validation_qry_run_res['course_code'];
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
                                            else
                                            {
                                                echo $course_id_validation_qry_run_res['course_title'];
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
                                        <?php 
                                        if($num_rows==0)
                                        {
                                        ?>
                                         <select class = "form-control" name="course_credit"
                                        required>
                                            <option value="">Please Select Course Credit</option>
                                            <option value = "3" <?php 
                                            if(isset($_POST['course_credit']) && $_POST['course_credit'] == "3")
                                            {
                                                echo "selected";
                                            }
                                            else if($course_id_validation_qry_run_res['course_credit'] == "3")
                                            {
                                                echo "selected";
                                            }
                                            ?>>3</option>
                                            
                                            <option value = "1.5" <?php 
                                            if(isset($_POST['course_credit']) && $_POST['course_credit']=="1.5") 
                                            {
                                                echo "selected";
                                            }
                                            else if($course_id_validation_qry_run_res['course_credit'] == "1.5")
                                            {
                                                echo "selected";
                                            }
                                            ?>>1.5</option>
                                            
                                        </select>
                                        <?php 
                                        }
                                        else 
                                        {
                                            ?>
                                            <label for="" class = "form-control bg-dark text-light fw-bold"><?php echo $course_id_validation_qry_run_res['course_credit']?></label>
                                            <input type="hidden" name = "course_credit" value = "<?php echo $course_id_validation_qry_run_res['course_credit']; ?>">
                                            <?php
                                        }
                                        ?>
                                       
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
                                        <?php 
                                        if($num_rows==0)
                                        {
                                        ?>
                                        <select class = "form-control" name="course_year"
                                        required>
                                            <option value="">Please Select Course Year</option>
                                            <option value="1st year" <?php
                                            if(isset($_POST['course_year']) && $_POST['course_year']=="1st year")
                                            {
                                                echo "selected";
                                            }
                                            else if($course_id_validation_qry_run_res['course_year'] == "1st year")
                                            {
                                                echo "selected";
                                            }
                                            ?>>1st year</option>
                                            <option value="2nd year" <?php
                                            if(isset($_POST['course_year']) && $_POST['course_year']=="2nd year")
                                            {
                                                echo "selected";
                                            }
                                            else if($course_id_validation_qry_run_res['course_year'] == "2nd year")
                                            {
                                                echo "selected";
                                            }
                                            ?>>2nd year</option>
                                            <option value="3rd year" <?php
                                            if(isset($_POST['course_year']) && $_POST['course_year']=="3rd year")
                                            {
                                                echo "selected";
                                            }
                                            else if($course_id_validation_qry_run_res['course_year'] == "3rd year")
                                            {
                                                echo "selected";
                                            }
                                            ?>>3rd year</option>
                                            <option value="4th year" <?php
                                            if(isset($_POST['course_year']) && $_POST['course_year']=="4th year")
                                            {
                                                echo "selected";
                                            }
                                            else if($course_id_validation_qry_run_res['course_year'] == "4th year")
                                            {
                                                echo "selected";
                                            }
                                            ?>>4th year</option>
                                        </select>
                                        <?php 
                                        }
                                        else
                                        {
                                        ?>
                                            <label for="" class = "form-control bg-dark text-light fw-bold"><?php echo $course_id_validation_qry_run_res['course_year']?></label>
                                            <input type="hidden" name = "course_year" value = "<?php echo $course_id_validation_qry_run_res['course_year'] ?>">
                                        <?php
                                        }
                                        ?>
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
                                        <?php 
                                        if($num_rows==0)
                                        {
                                            ?>
                                            <select class = "form-control" name="course_semester"
                                            required>
                                                <option value="">Please Select Course Semester</option>
                                                <option value="1st semester" <?php
                                                if(isset($_POST['course_semester']) && $_POST['course_semester']=="1st semester")
                                                {
                                                    echo "selected";
                                                }
                                                else if($course_id_validation_qry_run_res['course_semester'] == "1st semester")
                                                {
                                                    echo "selected";
                                                }
                                                ?>>1st semester</option>
                                                <option value="2nd semester" <?php
                                                 if(isset($_POST['course_semester']) && $_POST['course_semester']=="2nd semester")
                                                 {
                                                     echo "selected";
                                                 }
                                                 else if($course_id_validation_qry_run_res['course_semester'] == "2nd semester")
                                                {
                                                    echo "selected";
                                                }
                                                ?>>2nd semester</option>
                                            </select>
                                            <?php 
                                        }
                                        else
                                        {
                                            ?>
                                            <label for="" class = "form-control bg-dark text-light fw-bold"><?php echo $course_id_validation_qry_run_res['course_semester']?></label>
                                            <input type="hidden" name = "course_semester" value = "<?php echo $course_id_validation_qry_run_res['course_semester'] ?>">
                                            <?php
                                        }
                                        ?>
                                        
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
                                        <?php 
                                        if($num_rows==0)
                                        {
                                        ?>
                                        <select name="department_name" id="" class = "form-control" required>
                                            <option value="">Please Select Department/Stream</option>
                                            <?php
                                                $selected = 0;
                                                $select_from_department = "SELECT * FROM `department_information` ";
                                                $run = mysqli_query($conn, $select_from_department);
                                                while($row = mysqli_fetch_assoc($run))
                                                {
                                                    ?>
                                                    <option value="<?php echo $row['id'] ?>" <?php 
                                                    if(isset($_POST['department_name']) && $_POST['department_name']==$row['id'])
                                                    {
                                                        echo "selected";
                                                        $selected++;
                                                    }
                                                    else if($course_id_validation_qry_run_res['department_id']==$row['id'])
                                                    {
                                                        $selected++;
                                                        if($selected==1)
                                                        {
                                                            echo "selected";
                                                        } 
                                                    }
                                                    
                                                    ?>
                                                    ><?php echo $row['department_name']?> </option>
                                                    <?php 
                                                }
                                            ?>
                                            <option value="0" <?php
                                            if(isset($_POST['department_name']) && $_POST['department_name']==0)
                                            {
                                                echo "selected";
                                            }
                                            else if($selected ==0) echo "selected";
                                            ?>>Foundation Course</option>
                                        </select>
                                        <?php 
                                        }
                                        else
                                        {
                                            if($course_id_validation_qry_run_res['department_id']==0)
                                            {
                                                $department_name = "Foundation Course";
                                            }
                                            else
                                            {
                                                $select_from_department = "SELECT * FROM `department_information` WHERE `id` = '$course_id_validation_qry_run_res[department_id]'";
                                                
                                                $run_select_from_department = mysqli_query($conn, $select_from_department);
                                                
                                                $res_select_from_departemnt = mysqli_fetch_assoc($run_select_from_department);
                                                $department_name = $res_select_from_departemnt['department_name'];
                                            }
                                            
                                        ?>
                                            <label for="" class = "form-control bg-dark text-light fw-bold"><?php echo $department_name?></label>
                                            <input type="hidden" name = "department_name" value = "<?php echo $course_id_validation_qry_run_res['department_id'] ?>">
                                        <?php
                                        }
                                        ?>
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
                                            <option value="">Please Select Course Type</option>
                                            <option value="Theory" <?php
                                            if(isset($_POST['course_type']) && $_POST['course_type']=="Theory")
                                            {
                                                echo "selected";
                                            }
                                            else if($course_id_validation_qry_run_res['course_type'] == "Theory")
                                            {
                                                echo "selected";
                                            }
                                            ?>>Theory</option>
                                            <option value="Practical" <?php
                                            if(isset($_POST['course_type']) && $_POST['course_type']=="Practical")
                                            {
                                                echo "selected";
                                            }
                                            else if($course_id_validation_qry_run_res['course_type'] == "Practical")
                                            {
                                                echo "selected";
                                            }
                                            ?>>Practical</option>
                                            <option value="Viva-Voce" <?php
                                            if(isset($_POST['course_type']) && $_POST['course_type']=="Viva-Voce")
                                            {
                                                echo "selected";
                                            }
                                            else if($course_id_validation_qry_run_res['course_type'] == "Viva-Voce")
                                            {
                                                echo "selected";
                                            }
                                            ?>>Viva-Voce</option>
                                        </select>
                                    </div>
                                    <p id = "error" class = "font-weight-bold bg-warning text-center"></p>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <input type="submit" class = "form-control btn" name = "submit" value = "Update">
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
        $course_year = mysqli_real_escape_string($conn,$_POST['course_year']);
        $course_semester = mysqli_real_escape_string($conn,$_POST['course_semester']);
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
                $select_from_course = "SELECT * FROM `course_information` WHERE `course_type` = '$course_type' AND `course_year` = '$course_year' AND `course_semester` = '$course_semester' AND `department_id` = '$department_name'";
                $run_select_from_course = mysqli_query($conn, $select_from_course);
                while($value = mysqli_fetch_assoc($run_select_from_course))
                {
                    if($value['course_year'] == $course_year && $value['course_semester'] == $course_semester && $value['department_id'] == $department_name && $value['id'] != $_GET['id'])
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
           
        }

        // Update course_information

        $update_course_qry = "UPDATE `course_information` SET `course_code`='$course_code', `course_title`='$course_title', `course_credit`='$course_credit', `course_year`='$course_year', `course_semester`='$course_semester', `department_id`='$department_name', `course_type`='$course_type' WHERE `id` = '$_GET[id]'";

        $run_update_course_qry = mysqli_query($conn, $update_course_qry);

        if($run_update_course_qry)
        {
            ?>
            <script>
                window.alert("Course Updated Successfully");
                window.location = "view_course_information.php?page=<?php echo $_GET['page']; ?>";
            </script>
            <?php
            exit();
        }
    }
?>
