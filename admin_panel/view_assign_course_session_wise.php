<?php include('lib/header.php') ?>
<?php 
    // start of department validation
    if(isset($_GET['department_id']))
    {
        // valid department url e pass hocche kina ta check korar jonno.
        // department_id == 0 mane hocche foundation course. jehetu array_search fail korle 0 dekhay tai oita alada vabe $_GET['department_id] == 0 diye check korsi
        $valid_department_info = valid_department();
        $department_id_array = $valid_department_info[0];
        $department_name_array = $valid_department_info[1];
        if(array_search($_GET['department_id'],$department_id_array) || ($_GET['department_id']==0))
        {
            $department_name = $department_name_array[array_search($_GET['department_id'],$department_id_array)];
        }
        else
        {
            ?>
            <script>
                window.alert("Invalid Department");
                window.location = "home.php";
            </script>
            <?php
            exit();
        }
    }
    else
    {
        ?>
            <script>
                window.alert("Invalid Department");
                window.location = "home.php";
            </script>
        <?php 
    }
    // End of department validation
?>
<?php
    function display_content($run)
    {
        include('lib/db_connection.php');
        $row = array();
        if(empty($run))
        {
            $c = 2006;
            $today = date("Y");
            for($i=$c; $i<$today; $i++)
            {
                $r = $i + 1;
                $session= $i."-".$r;
                array_push($row, $session);
            }
        }
        else
        {
            $row[0] = $run;
        }
        ?>
        <?php
            $len = sizeof($row);
            $i=$len;
            while($i>=1)
            {
                $i--;
                if($i%2==0)
                {
                    $value = "border-danger";
                }
                else
                {
                    $value = "border-primary";
                }
                ?>
                 <div class="col-lg-4 col-sm-6 col-12 mt-3">
                    <div class="card text-center border <?php echo $value ?>">
                        <div class="card-body shadow dashboard_card">
                            <h4>Session: <?php echo $row[$i]?></h4>
                            <a href="view_assign_course_semester_wise.php?session=<?php echo $row[$i] ?>&department_id=<?php echo $_GET['department_id'] ?>" class = " form-control link_btn"><b><span><i class = "fas fa-folder-plus"></i></span> Assign</b></a>
                        </div>
                    </div>
                </div>         
                <?php
            }
    }
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header form_header text-center">
            <h2>Assigned Course Session Information</h2>
            <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
        </div>
        <div class="card-body">
            <form action="" method = "POST">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-4 col-12 mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class = "input-group-text"><i class = "fas fa-search"></i></span>
                            </div>
                            <select class = "form-control" name="search_session_wise">
                                <option value="" >Search Session Wise</option>
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

                    <div class="col-lg-4 col-md-4 col-12 text-center mb-3 ">
                        <input type="submit" name="search" value="Search" class = "form-control btn">
                    </div>
                    <div class="col-lg-4 col-md-4  col-12 text-center mb-3 ">
                        <input type="submit" class ="form-control btn" name = "show_all" value = "Show All">
                    </div>
                </div>
            </form>
            <!-- Start of qry for search button -->
            <?php
                if(isset($_POST['search']))
                {
                    $session = $_POST['search_session_wise'];

                    if($session==NULL)
                    {
                        ?>
                        <script>
                            window.alert("Please Select Session");
                            window.location = "view_assign_course_session_wise.php";
                        </script>
                        <?php
                         exit();
                    }

                    ?>
                    <!-- <div class="col-lg-12 col-12"> -->
                        <!-- Call display_content function -->
                        <?php display_content($session); ?>
                    <!-- </div> -->
                    <?php
                }
                 //End of qry for search button
                else
                {
                    ?>
                    <div class="row">
                        <?php
                        display_content(0);
                        ?>
                    </div>
                    <?php
                }
            ?>
        </div>
    </div>
</div>

<?php include('lib/footer.php') ?>
