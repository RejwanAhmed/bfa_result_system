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

        $tabulator_id_validation_qry = "SELECT e.id, t.name as tabulator1_name, e.tabulator1_pass, t1.name as tabulator2_name, e.tabulator2_pass, t2.name as tabulator3_name, e.tabulator3_pass, e.session, e.course_year FROM exam_committee_information as e INNER JOIN teacher_information as t ON e.tabulator1_id = t.id INNER JOIN teacher_information as t1 ON e.tabulator2_id = t1.id INNER JOIN teacher_information as t2 ON e.tabulator3_id = t2.id WHERE e.id = '$_GET[id]'";

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

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-10 col-12">
            <div class="card">
                <div class="card-header text-center form_header">
                    <h2>Tabulator Information <?php echo "(".$tabulator_id_validation_qry_run_res['session'].", ".$tabulator_id_validation_qry_run_res['course_year'].")" ?></h2>
                </div>
                <div class="card-body">
                    <form action="" method = "POST">
                        <div class="row justify-content-center m-3">
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Session:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-sort-amount-down"></i></div>
                                        </div>
                                        <input class = "form-control" type="text" value = "<?php echo $tabulator_id_validation_qry_run_res['session'] ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Course Year:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-list-ol"></i></div>
                                        </div>
                                        <input class = "form-control" type="text" value = "<?php echo $tabulator_id_validation_qry_run_res['course_year'] ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center m-3">
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Tabulator1 Name: <span class = "text-danger">(Chairman)</span></b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <input class = "form-control" type="text" value = "<?php echo $tabulator_id_validation_qry_run_res['tabulator1_name'] ?>" readonly>
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
                                        <input class = "form-control" type="text" value = "<?php echo base64_decode($tabulator_id_validation_qry_run_res['tabulator1_pass'] )?>" readonly>
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
                                        <input class = "form-control" type="text" value = "<?php echo $tabulator_id_validation_qry_run_res['tabulator2_name'] ?>" readonly>
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
                                        <input class = "form-control" type="text" value = "<?php echo base64_decode($tabulator_id_validation_qry_run_res['tabulator2_pass'] )?>" readonly>
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
                                        <input class = "form-control" type="text" value = "<?php echo $tabulator_id_validation_qry_run_res['tabulator3_name'] ?>" readonly>
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
                                        <input class = "form-control" type="text" value = "<?php echo base64_decode($tabulator_id_validation_qry_run_res['tabulator3_pass'] )?>" readonly>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="row m-3 ">
                            <div class="col-lg-3 col-md-4 col-12 mt-2  ">
                                <a class = "form-control btn text-center" href="modify_tabulator.php?id=<?php echo $tabulator_id_validation_qry_run_res['id']?>&page=<?php echo $page_number; ?>"><b><span><i class="far fa-edit"></i></span> Modify</b>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('lib/footer.php') ?>
