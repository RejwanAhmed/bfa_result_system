<?php include('lib/header.php') ?>
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
                    <h2>Exam Committee Registration Form</h2>
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
                            <div class="col-lg-6 col-md-6 col-12 mt-2">
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
                                            <option value="" selected>Please Select Chairman</option>
                                            <?php
                                            $res = select_teacher();
                                            while($row = mysqli_fetch_assoc($res))
                                            {
                                                ?>
                                                <option value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?></option>
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
                                                <option value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row  m-3">
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
                                                <option value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?></option>
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
                                                <option value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?></option>
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
                                                     echo "<option value='$exam_year'>";
                                                     echo $exam_year;
                                                     echo "</option>";
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
                                <input type="submit" class = "form-control btn" name = "submit" value = "Register">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <?php include('lib/footer.php') ?> -->

<?php
    if(isset($_POST['submit']))
    {
        $session = $_POST['session'];
        $course_year = $_POST['course_year'];
        $exam_year = $_POST['exam_year'];
        $chairman_id = $_POST['chairman'];
        $member1_id = $_POST['member1'];
        $member2_id = $_POST['member2'];
        $external_member_id = $_POST['external_member'];
        $tabulator1_id = $chairman_id;
        $tabulator2_id = $member1_id;
        $tabulator3_id = $member2_id;
        $tabulator1_pass = rand(100000,999999);
        $tabulator2_pass = rand(100000,999999);
        $tabulator3_pass = rand(100000,999999);

        $tabulator1_pass = base64_encode($tabulator1_pass);
        $tabulator2_pass = base64_encode($tabulator2_pass);
        $tabulator3_pass = base64_encode($tabulator3_pass);

        // Validation
        $select_exam_committee = "SELECT count(`id`) as `total_row` FROM `exam_committee_information` WHERE `session` = '$session' AND `course_year` = '$course_year'";
        $run_select_exam_committee = mysqli_query($conn, $select_exam_committee);
        $row = mysqli_fetch_assoc($run_select_exam_committee);
        if($row['total_row']>=1)
        {
            ?>
            <script>
                document.getElementById('session_year').innerHTML = `<i class="fas fa-exclamation-circle"></i> Same Session And Course Year Already Exists `;
            </script>
            <?php
            exit();
        }
        else if($chairman_id==$member1_id || $chairman_id==$member2_id || $member1_id == $member2_id)
        {
            ?>
            <script>
                document.getElementById('chairman_member_id').innerHTML = `<i class="fas fa-exclamation-circle"></i> Chairman And Members Can Not Be The Same Person `;
            </script>
            <?php
            exit();
        }
        
        
        // start: 1st_sem_status ebong 2nd_sem_status use kora hoyeche karon amra eita diye tabulator result processing start korse kina ta jante parbo. committe jkhn kora hobe tkhn value thakbe 0. jkhn start korbe tkhn value thakbe 1, jkhn puase/stop korbe student add korar jonno tkhn value hobe 2 ar jkhn finish korbe tkhn value hobe 3 ebong 3 mane oi session er notun kono student er result process hobe na ebong notun kono teacher ke course assign kora jabe na kintu marks update kora jabe student der.
        $insert_exam_committe_info = "INSERT INTO `exam_committee_information`(`chairman_id`, `member1_id`, `member2_id`, `session`, `course_year`,`1st_sem_status`,`2nd_sem_status`,`exam_year`, `tabulator1_id`, `tabulator1_pass`, `tabulator2_id`, `tabulator2_pass`, `tabulator3_id`, `tabulator3_pass`, `external_member_id`) VALUES ('$chairman_id','$member1_id','$member2_id','$session','$course_year','0','0','$exam_year','$tabulator1_id','$tabulator1_pass','$tabulator2_id','$tabulator2_pass','$tabulator3_id','$tabulator3_pass','$external_member_id')";

        $run_insert_exam_committee_info = mysqli_query($conn, $insert_exam_committe_info);

        if($run_insert_exam_committee_info)
        {
            ?>
            <script>
                window.alert("Exam Committee Registered Successfully");
                window.location = "view_exam_committee_information.php";
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
        // end: 1st_sem_status ebong 2nd_sem_status use kora hoyeche karon amra eita diye tabulator result processing start korse kina ta jante parbo. committe jkhn kora hobe tkhn value thakbe 0. jkhn start korbe tkhn value thakbe 1, jkhn puase/stop korbe student add korar jonno tkhn value hobe 2 ar jkhn finish korbe tkhn value hobe 3 ebong 3 mane oi session er notun kono student er result process hobe na ebong notun kono teacher ke course assign kora jabe na kintu marks update kora jabe student der.
        
    }
?>
