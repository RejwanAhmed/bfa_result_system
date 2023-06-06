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
        // department_id jdi 0 hoy tahole department_information table er sathe join kora jabe na karon oikhane 0 namer kono id nai. amra 0 = Foundation Course Dibo eita assumption korechi. Database e rakhi nai tmn kichu. tai first year er course gulor jonno department_id thakbe na ebong tkhn nicher query cholbe. jeta 0 return korbe jdi department_id 0 hoy ar 1 return korbe jdi department_id 0 na hoy student_information table e.
        $select_dept_id_from_course_information = "SELECT IF(department_id=0, 0, 1) as true_false FROM `course_information` WHERE `id` = '$_GET[id]'";
        $run_select_dept_id_from_course_information = mysqli_query($conn, $select_dept_id_from_course_information);
        $res_select_dept_id_from_course_information = mysqli_fetch_assoc($run_select_dept_id_from_course_information);
        
        $dept_id = $res_select_dept_id_from_course_information['true_false'];
        
        // Start of Whether an id is valid or not
        
        if($dept_id==0)
        {
            $course_id_validation_qry = "SELECT * FROM `course_information` WHERE `id` = '$_GET[id]'";
        }
        else 
        {
            $course_id_validation_qry = "SELECT c.id, c.course_code, c.course_title, c.course_credit, c.course_year, c.course_semester, c.course_type, d.department_name FROM `course_information` as c INNER JOIN `department_information` as d ON c.department_id = d.id WHERE c.id = '$_GET[id]'";
        }
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
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header text-center form_header">
                    <h2><?php echo $course_id_validation_qry_run_res['course_title']." Information"; ?></h2>
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
                                        <input type="text" class = "form-control" readonly value = "<?php
                                            echo $course_id_validation_qry_run_res['course_code'];
                                        ?>">
                                    </div>
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
                                        <input type="text" class = "form-control"  value = "<?php
                                            echo $course_id_validation_qry_run_res['course_title'];
                                        ?>" readonly>
                                    </div>
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
                                        <input type="text" class = "form-control"  value = "<?php
                                            echo $course_id_validation_qry_run_res['course_credit'];
                                        ?>" readonly>
                                    </div>
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
                                        <input type="text" class = "form-control" value = "<?php
                                        echo $course_id_validation_qry_run_res['course_year'];
                                        ?>" readonly>
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
                                        <input type="text" class = "form-control"  value = "<?php
                                            echo $course_id_validation_qry_run_res['course_semester'];
                                        ?>" readonly>
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
                                        <input type="text" class = "form-control" readonly value = "<?php if($dept_id==0)
                                        {
                                            echo "Foundation Course";
                                        }
                                        else
                                        {
                                            echo $course_id_validation_qry_run_res['department_name'];
                                        }?>
                                       ">
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
                                        <input type="text" class = "form-control"  value = "<?php
                                            echo $course_id_validation_qry_run_res['course_type'];
                                        ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-3 ">
                            <div class="col-lg-2 col-md-4 col-12 mt-2  ">
                                <a class = "form-control btn text-center" href="modify_course.php?id=<?php echo $course_id_validation_qry_run_res['id']?>&page=<?php echo $page_number; ?>"><b><span><i class="far fa-edit"></i></span> Modify</b>
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 col-12 mt-2">
                                <button type = "button" class = "form-control btn" onclick = "deleteConfirmation(<?php echo $course_id_validation_qry_run_res['id'];?>, <?php echo $page_number;?>)"><b><span><i class="fas fa-eraser"></i></span> Remove</b></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function deleteConfirmation(id, page)
    {
        var del = confirm('Are You Sure Want To Delete?');
        if(del == true)
        {
            window.location='delete_course.php?id='+id+'&page='+page;
        }
    }
</script>
<?php include('lib/footer.php') ?>
