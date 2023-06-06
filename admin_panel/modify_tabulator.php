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

        $tabulator_id_validation_qry = "SELECT e.id, e.tabulator1_id, t.name as tabulator1_name, e.tabulator1_pass, t1.name as tabulator2_name, e.tabulator2_pass, t2.name as tabulator3_name, e.tabulator3_pass, e.session, e.course_year FROM exam_committee_information as e INNER JOIN teacher_information as t ON e.tabulator1_id = t.id INNER JOIN teacher_information as t1 ON e.tabulator2_id = t1.id INNER JOIN teacher_information as t2 ON e.tabulator3_id = t2.id WHERE e.id = '$_GET[id]'";

        $tabulator_id_validation_qry_run = mysqli_query($conn, $tabulator_id_validation_qry);
        $tabulator_id_validation_qry_run_res = mysqli_fetch_assoc($tabulator_id_validation_qry_run);
        if($tabulator_id_validation_qry_run_res==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "index.php";
            </script>
            <?php
        }
        //End of Whether an id is valid or not
        $page_number = $_GET['page'];
    }
?>
<?php
function select_teacher()
{
    include('lib/db_connection.php');
    $select_teacher = "SELECT * FROM `teacher_information`";
    return mysqli_query($conn, $select_teacher);
    // return $run_select_teacher;
}
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-10 col-12">
            <div class="card">
                <div class="card-header text-center form_header">
                    <h2>Update Tabulator <?php echo "(".$tabulator_id_validation_qry_run_res['session'].", ".$tabulator_id_validation_qry_run_res['course_year'].")" ?> Information</h2>
                </div>
                <div class="card-body">
                    <form action="" method = "POST">
                        <div class="row justify-content-center m-3">
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Tabulator1 Name: <span class = "text-danger">(Chairman)</span></b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <select class = "form-control"name="tabulator1" id="" readonly>
                                            <option value="<?php echo "$tabulator_id_validation_qry_run_res[tabulator1_id]" ?>"><?php  echo $tabulator_id_validation_qry_run_res['tabulator1_name'];?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Tabulator1 Password: <span class = "text-danger">*(at least 6 digits)</span></b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-unlock-alt"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" name = "tabulator1_pass" placeholder = "Enter Tabulator1 Password" required value = "<?php
                                            if(isset($_POST['tabulator1_pass']))
                                            {
                                                echo $_POST['tabulator1_pass'];
                                            }
                                            else
                                            {
                                                echo base64_decode($tabulator_id_validation_qry_run_res['tabulator1_pass']);
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center m-3">
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Tabulator2 Name:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <select name="tabulator2" id="" class = "form-control" required>
                                            <option value="" selected>Please Select Member1</option>
                                            <?php
                                            $res = select_teacher();
                                            while($row = mysqli_fetch_assoc($res))
                                            {
                                                ?>
                                                <option value="<?php echo "$row[id]";?>" <?php
                                                if($tabulator_id_validation_qry_run_res['tabulator2_name']==$row['name'])
                                                {
                                                    echo "selected";
                                                }
                                                ?>><?php echo $row['name']; ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Tabulator2 Password: <span class = "text-danger">*(at least 6 digits)</span></b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-unlock-alt"></i></div>
                                        </div>
                                        <input type="text" class = "form-control" name = "tabulator2_pass" placeholder = "Enter Tabulaor2 Password" required value = "<?php
                                            if(isset($_POST['tabulator2_pass']))
                                            {
                                                echo $_POST['tabulator2_pass'];
                                            }
                                            else
                                            {
                                                echo base64_decode($tabulator_id_validation_qry_run_res['tabulator2_pass']);
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center m-3">
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Tabulator3 Name:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <select name="tabulator3" id="" class = "form-control" required>
                                            <option value="" selected>Please Select Member2</option>
                                            <?php
                                            $res = select_teacher();
                                            while($row = mysqli_fetch_assoc($res))
                                            {
                                                ?>
                                                <option value="<?php echo "$row[id]";?>" <?php
                                                if($tabulator_id_validation_qry_run_res['tabulator3_name']==$row['name'])
                                                {
                                                    echo "selected";
                                                }
                                                ?>><?php echo $row['name']; ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Tabulator3 Password: <span class = "text-danger">*(at least 6 digits)</span></b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-unlock-alt"></i>
                                            </div>
                                        </div>
                                        <input type="text" class = "form-control" name = "tabulator3_pass" placeholder = "Enter Tabulator3 Password" required value = "<?php
                                            if(isset($_POST['tabulator3_pass']))
                                            {
                                                echo $_POST['tabulator3_pass'];
                                            }
                                            else
                                            {
                                                echo base64_decode($tabulator_id_validation_qry_run_res['tabulator3_pass']);
                                            }
                                        ?>" autocomplete="off">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <p id = "chairman_member_id"  class = "font-weight-bold bg-warning text-center"></p>

                        <p id = "chairman_member_pass"  class = "font-weight-bold bg-warning text-center"></p>

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
        $tabulator1_id = $_POST['tabulator1'];
        $tabulator1_pass  = $_POST['tabulator1_pass'];
        $tabulator2_id = $_POST['tabulator2'];
        $tabulator2_pass  = $_POST['tabulator2_pass'];
        $tabulator3_id = $_POST['tabulator3'];
        $tabulator3_pass  = $_POST['tabulator3_pass'];

        if($tabulator1_id==$tabulator2_id || $tabulator1_id==$tabulator3_id || $tabulator2_id == $tabulator3_id)
        {
            ?>
            <script>
                document.getElementById('chairman_member_id').innerHTML = `<i class="fas fa-exclamation-circle"></i> Chairman And Members Can Not Be The Same Person `;
            </script>
            <?php
            exit();
        }
        else if(strlen($tabulator1_pass)<=5 || strlen($tabulator2_pass)<=5 || strlen($tabulator3_pass)<=5)
        {
            ?>
            <script>
                document.getElementById('chairman_member_pass').innerHTML = `<i class="fas fa-exclamation-circle"></i> All Password Must Be Atleast 6 digits `;
            </script>
            <?php
            exit();
        }
        $tabulator1_pass = base64_encode($tabulator1_pass);
        $tabulator2_pass = base64_encode($tabulator2_pass);
        $tabulator3_pass = base64_encode($tabulator3_pass);

        $update_tabulator_info = "UPDATE `exam_committee_information` SET `tabulator1_id`='$tabulator1_id',`tabulator1_pass`='$tabulator1_pass',`tabulator2_id`='$tabulator2_id',`tabulator2_pass`='$tabulator2_pass',`tabulator3_id`='$tabulator3_id',`tabulator3_pass`='$tabulator3_pass' WHERE `id` = '$_GET[id]'";

        $run_update_tabulator_info = mysqli_query($conn, $update_tabulator_info);

        if($run_update_tabulator_info)
        {
            ?>
            <script>
                window.alert("Tabulator Updated Successfully");
                window.location = "view_exam_committee_information.php?page=<?php echo $page_number ?>";
            </script>
            <?php
            exit();
        }
        else
        {
            ?>
            <script>
                window.alert("Some Error");
            </script>
            <?php
            exit();
        }

    }
?>
