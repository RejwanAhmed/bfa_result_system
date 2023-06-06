<?php include('lib/header.php') ?>
<?php
    if(!isset($_GET['id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
    }
    else if(isset($_GET['id']))
    {
        // department_id jdi 0 hoy tahole department_information table er sathe join kora jabe na karon oikhane 0 namer kono id nai. amra 0 = Foundation Course Dibo eita assumption korechi. Database e rakhi nai tmn kichu. tai first year er student der jonno department_id thakbe na ebong tkhn nicher query cholbe. jeta 0 return korbe jdi department_id 0 hoy ar 1 return korbe jdi department_id 0 na hoy student_information table e.
        $select_dept_id_from_student_information = "SELECT IF(department_id=0, 0, 1) as true_false FROM `student_information` WHERE `id` = '$_GET[id]'";
        $run_select_id_from_student_information = mysqli_query($conn, $select_dept_id_from_student_information);
        $res_select_id_from_student_information = mysqli_fetch_assoc($run_select_id_from_student_information);
        
        $dept_id = $res_select_id_from_student_information['true_false'];
        // Start of Whether an id is valid or not
        if($dept_id==0)
        {
           $student_id_validation_qry = "SELECT * FROM `student_information` WHERE `id` = '$_GET[id]'";
        }
        else
        {
            $student_id_validation_qry = "SELECT st.id, st.name, st.father_name, st.mother_name, st.roll_no, st.registration_no, st.actual_session, st.current_session, st.date_of_birth, st.student_type, st.contact_no, st.status, d.department_name FROM `student_information` as st INNER JOIN `department_information` as d ON st.department_id = d.id WHERE st.id = '$_GET[id]'";
        }       
        $student_id_validation_qry_run = mysqli_query($conn, $student_id_validation_qry);
        $student_id_validation_qry_run_res = mysqli_fetch_assoc($student_id_validation_qry_run);
        if($student_id_validation_qry_run_res==false)
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
                    <h2><?php echo $student_id_validation_qry_run_res['name']." Information"; ?></h2>
                </div>
                <div class="card-body">
                    <form action="" method = "POST">
                        <div class="row m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Student Name:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" readonly value = "<?php
                                            echo $student_id_validation_qry_run_res['name'];
                                        ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Father Name:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-male"></i></div>
                                        </div>
                                        <input type="text" class = "form-control"  value = "<?php
                                            echo $student_id_validation_qry_run_res['father_name'];
                                        ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Mother Name:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-female"></i></div>
                                        </div>
                                        <input type="text" class = "form-control"  value = "<?php
                                            echo $student_id_validation_qry_run_res['mother_name'];
                                        ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Roll No:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-list-ol"></i></div>
                                        </div>
                                        <input type="text" class = "form-control"  value = "<?php
                                            echo $student_id_validation_qry_run_res['roll_no'];
                                        ?>" readonly>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Registration No:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-list-ol"></i></div>
                                        </div>
                                        <input type="number" class = "form-control" value = "<?php
                                        echo $student_id_validation_qry_run_res['registration_no'];
                                        ?>" readonly>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Date of Birth:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="far fa-calendar-check"></i></div>
                                        </div>
                                        <input type="date" class = "form-control"  value = "<?php
                                            echo $student_id_validation_qry_run_res['date_of_birth'];
                                        ?>" readonly>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Actual Session:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-sort-amount-down"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" value = "<?php echo $student_id_validation_qry_run_res['actual_session']; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Current Session:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-sort-amount-down"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" value = "<?php echo $student_id_validation_qry_run_res['current_session']; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Student Type:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-alt"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" readonly value = "<?php echo $student_id_validation_qry_run_res['student_type']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-lg-4 col-md-4 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Contact No:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                        </div>
                                        <input type="text" class = "form-control"  value = "<?php
                                            echo $student_id_validation_qry_run_res['contact_no'];
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
                                            echo $student_id_validation_qry_run_res['department_name'];
                                        }?>
                                       ">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-3 ">
                            <div class="col-lg-2 col-md-4 col-12 mt-2  ">
                                <a class = "form-control btn text-center" href="modify_student.php?id=<?php echo $student_id_validation_qry_run_res['id']?>&page=<?php echo $page_number; ?>"><b><span><i class="far fa-edit"></i></span> Modify</b>
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 col-12 mt-2">
                                <button type = "button" class = "form-control btn" onclick = "deleteConfirmation(<?php echo $student_id_validation_qry_run_res['id'];?>, <?php echo $page_number;?>)"><b><span><i class="fas fa-eraser"></i></span> Remove</b></button>
                            </div>
                            
                            <div class="col-lg-2 col-md-4 col-12 mt-2">
                                <?php
                                    if($student_id_validation_qry_run_res['status']==0)
                                    {
                                        ?>
                                        <button type = "button" class = "form-control btn-danger" onclick = "changeStatus(<?php echo $student_id_validation_qry_run_res['id'];?>, <?php echo $page_number;?>, <?php echo $student_id_validation_qry_run_res['status'] ?>)"><b><span><i class="fas fa-user-slash"></i></span> Inactive</b></button>
                                        <?php 
                                    }
                                    else
                                    {
                                        ?>
                                        <button type = "button" class = "form-control btn-success" onclick = "changeStatus(<?php echo $student_id_validation_qry_run_res['id'];?>, <?php echo $page_number;?>, <?php echo $student_id_validation_qry_run_res['status'] ?>)"><b><span><i class="fas fa-user"></i></span>  Active</b></button>
                                        <?php 
                                    }
                                ?>
                                
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
            window.location='delete_student.php?id='+id+'&page='+page;
        }
    }
    function changeStatus(id, page, status)
    {
        if(status==0)
        {
            var del = confirm("Are You Sure Want To Deactivate Student?");
        }
        else
        {
            var del = confirm("Are You Sure Want To Activate Student?");
        }
        if(del==true)
        {
            window.location = 'change_student_status.php?id='+id+'&status='+status+'&page='+page;
        }
    }
</script>
<?php include('lib/footer.php') ?>
