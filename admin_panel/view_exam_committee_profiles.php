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

        $exam_committee_id_validation_qry = "SELECT e.id, e.exam_year, t.name as chairman_name, t1.name as member1_name, t2.name as member2_name, e.session, e.course_year, ext.name as external_member_name FROM exam_committee_information as e INNER JOIN teacher_information as t ON e.chairman_id = t.id INNER JOIN teacher_information as t1 ON e.member1_id = t1.id INNER JOIN teacher_information as t2 ON e.member2_id = t2.id INNER JOIN external_member as ext ON e.external_member_id = ext.id WHERE e.id = '$_GET[id]'";

        $exam_committee_id_validation_qry_run = mysqli_query($conn, $exam_committee_id_validation_qry);
        $exam_committee_id_validation_qry_run_res = mysqli_fetch_assoc($exam_committee_id_validation_qry_run);
        if($exam_committee_id_validation_qry_run_res==false)
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
                    <h2>Exam Committee Information <?php echo "(".$exam_committee_id_validation_qry_run_res['session']. ", ".$exam_committee_id_validation_qry_run_res['course_year'].")"; ?></h2>
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
                                        <input type="text" value = "<?php echo $exam_committee_id_validation_qry_run_res['session'] ?>" readonly class = "form-control"/>
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
                                        <input type="text" value = "<?php echo $exam_committee_id_validation_qry_run_res['course_year'] ?>" readonly class = "form-control"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center m-3">
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Chairman:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <input type="text" value = "<?php echo $exam_committee_id_validation_qry_run_res['chairman_name'] ?>" readonly class = "form-control"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Member1:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <input type="text" value = "<?php echo $exam_committee_id_validation_qry_run_res['member1_name'] ?>" readonly class = "form-control"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Member2:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <input type="text" value = "<?php echo $exam_committee_id_validation_qry_run_res['member2_name'] ?>" readonly class = "form-control"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>External Member:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <input type="text" value = "<?php echo $exam_committee_id_validation_qry_run_res['external_member_name'] ?>" readonly class = "form-control"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label for=""><b>Exam Year:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-list-ol"></i></div>
                                        </div>
                                        <input type="text" value = "<?php echo $exam_committee_id_validation_qry_run_res['exam_year'] ?>" readonly class = "form-control"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-3 ">
                            <div class="col-lg-3 col-md-4 col-12 mt-2  ">
                                <a class = "form-control btn text-center" href="modify_exam_committee.php?id=<?php echo $exam_committee_id_validation_qry_run_res['id']?>&page=<?php echo $page_number; ?>"><b><span><i class="far fa-edit"></i></span> Modify</b>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 mt-2">
                                <button type = "button" class = "form-control btn" onclick = "deleteConfirmation(<?php echo $exam_committee_id_validation_qry_run_res['id'];?>, <?php echo $page_number;?>)"><b><span><i class="fas fa-eraser"></i></span> Remove</b></button>
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
            window.location='delete_exam_committee.php?id='+id+'&page='+page;
        }
    }
</script>
<?php include('lib/footer.php') ?>
