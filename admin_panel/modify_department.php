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
        // Start of Whether an id is valid or not
        $department_id_validation_qry = "SELECT * FROM `department_information` WHERE `id` = '$_GET[id]'";
        $department_id_validation_qry_run = mysqli_query($conn, $department_id_validation_qry);
        $department_id_validation_qry_run_res = mysqli_fetch_assoc($department_id_validation_qry_run);
        if($department_id_validation_qry_run_res==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "index.php";
            </script>
            <?php
        }
        //End of Whether an id is valid or not
    }
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header text-center form_header">
                    <h2>Update <?php echo $department_id_validation_qry_run_res['department_name'] ?> Information</h2>
                </div>
                <div class="card-body">
                    <form action="" method = "POST">
                        <div class="row m-3 justify-content-center">
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Department:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-building"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" name = "department_name" placeholder = "Enter department name" required value = "<?php
                                            if(isset($_POST['department_name']))
                                            {
                                                echo $_POST['department_name'];
                                            }
                                            else
                                            {
                                                echo $department_id_validation_qry_run_res['department_name'];
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                    <p id = "dept_name" class = "font-weight-bold bg-warning text-center"></p>
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
        $dept_name = mysqli_real_escape_string($conn,$_POST['department_name']);
        $select_dept_qry = "SELECT * FROM  `department_information` WHERE `department_name` = '$dept_name'";
        $run_select_dept_qry = mysqli_query($conn, $select_dept_qry);
        $row = mysqli_fetch_assoc($run_select_dept_qry);
        if($row['department_name']==$dept_name && $row['id'] != $_GET['id'])
        {
            ?>
            <script>
                document.getElementById('dept_name').innerHTML = `<i class="fas fa-exclamation-circle"></i> Department already exists `;
            </script>
            <?php
            exit();
        }
        $update_dept_qry = "UPDATE `department_information` SET `department_name`='$dept_name' WHERE `id` = '$_GET[id]'";
        $run_update_dept_qry = mysqli_query($conn, $update_dept_qry);

        if($run_update_dept_qry)
        {
            ?>
            <script>
                window.alert("Department Information Updated Successfully");
                window.location = "view_department_information.php?page=<?php echo $_GET['page']; ?>";
            </script>
            <?php
        }
    }
?>
