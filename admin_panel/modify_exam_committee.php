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
<?php
function select_teacher()
{
    include('lib/db_connection.php');
    $select_teacher = "SELECT * FROM `teacher_information`";
    return mysqli_query($conn, $select_teacher);
    // return $run_select_teacher;
}
function select_external_member()
{
    include('lib/db_connection.php');
    $select_external = "SELECT * FROM `external_member`";
    return mysqli_query($conn, $select_external);
}
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-10 col-12">
            <div class="card">
                <div class="card-header text-center form_header">
                    <h2>Update Exam Committee <?php echo "(".$exam_committee_id_validation_qry_run_res['session'].", ".$exam_committee_id_validation_qry_run_res['course_year'].")" ?> Information</h2>
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
                                        <label for="" class = "form-control bg-dark text-white"><?php echo $exam_committee_id_validation_qry_run_res['session']?></label>
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
                                        <label for="" class = "form-control bg-dark text-white"><?php echo $exam_committee_id_validation_qry_run_res['course_year']?></label>
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
                                        <select name="chairman" id="" class = "form-control" required>
                                            <option value="" >Please Select Chairman</option>
                                            <?php
                                            $res = select_teacher();
                                            while($row = mysqli_fetch_assoc($res))
                                            {
                                                ?>
                                                <option value="<?php echo "$row[id]";?>" <?php
                                                if($exam_committee_id_validation_qry_run_res['chairman_name']==$row['name'])
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
                                    <label for=""><b>Member1:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <select name="member1" id="" class = "form-control" required>
                                            <option value="" selected>Please Select Member1</option>
                                            <?php
                                            $res = select_teacher();
                                            while($row = mysqli_fetch_assoc($res))
                                            {
                                                ?>
                                                <option value="<?php echo "$row[id]";?>" <?php
                                                if($exam_committee_id_validation_qry_run_res['member1_name']==$row['name'])
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
                                        <select name="member2" id="" class = "form-control" required>
                                            <option value="" selected>Please Select Member2</option>
                                            <?php
                                            $res = select_teacher();
                                            while($row = mysqli_fetch_assoc($res))
                                            {
                                                ?>
                                                <option value="<?php echo "$row[id]";?>" <?php
                                                if($exam_committee_id_validation_qry_run_res['member2_name']==$row['name'])
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
                                    <label for=""><b>External Member:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <select name="external_member" id="" class = "form-control" required>
                                            <option value="" selected>Please Select External</option>
                                            <?php
                                            $res = select_external_member();
                                            while($row = mysqli_fetch_assoc($res))
                                            {
                                                ?>
                                                <option value="<?php echo "$row[id]";?>" <?php
                                                if($exam_committee_id_validation_qry_run_res['external_member_name']==$row['name'])
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
                                    <label for=""><b>Exam Year:</b></label>
                                    <br>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-list-ol"></i></div>
                                        </div>
                                        <select class = "form-control" name="exam_year" required>
                                            <option value="" >Please Select Exam Year</option>
                                            <?php
        											 $c = 2007;
        											$today = date("Y");
                                                    for($i=$c; $i<$today; $i++)
                                                   {

                                                        $exam_year= $i;
                                                        ?>
                                                        <option value="<?php echo "$exam_year";?>" <?php
                                                        if($exam_committee_id_validation_qry_run_res['exam_year']==$exam_year)
                                                        {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $exam_year; ?>
                                                        </option>
                                                        <?php
                                                   }
        										?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p id = "session_year"  class = "font-weight-bold bg-warning text-center"></p>

                        <p id = "chairman_member_id"  class = "font-weight-bold bg-warning text-center"></p>

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
        $session = $_POST['session'];
        $chairman_id = $_POST['chairman'];
        $member1_id = $_POST['member1'];
        $member2_id = $_POST['member2'];
        $external_member_id = $_POST['external_member'];
        $tabulator1_id =$chairman_id;
        $tabulator2_id = $member1_id;
        $tabulator3_id = $member2_id;

        if(($chairman_id==$member1_id || $chairman_id==$member2_id || $member1_id == $member2_id))
        {
            ?>
            <script>
                document.getElementById('chairman_member_id').innerHTML = `<i class="fas fa-exclamation-circle"></i> Chairman And Members Can Not Be The Same Person `;
            </script>
            <?php
            exit();
        }
        
        $update_exam_committee_info = "UPDATE `exam_committee_information` SET `chairman_id`='$chairman_id',`member1_id`='$member1_id',`member2_id`='$member2_id', `exam_year`='$exam_year',`tabulator1_id` = '$tabulator1_id',`tabulator2_id` = '$tabulator2_id',`tabulator3_id` = '$tabulator3_id', `external_member_id` = '$external_member_id' WHERE `id` = '$_GET[id]'";

        $run_update_exam_committee_info = mysqli_query($conn, $update_exam_committee_info);

        if($run_update_exam_committee_info)
        {
            ?>
            <script>
                window.alert("Exam Committee Updated Successfully");
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
