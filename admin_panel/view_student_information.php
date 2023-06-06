<?php include('lib/header.php') ?>
<?php include('pagination.php') ?>
<?php
    function get_row_count()
    {
        include('lib/db_connection.php');
        $sql = "SELECT COUNT(`id`) as total_row FROM `student_information`";
        $res = mysqli_query($conn, $sql);
        if(mysqli_num_rows($res))
        {
            $row = mysqli_fetch_assoc($res);
            return $row['total_row'];
        }
        return 0;
    }
    function display_content($run,$offset,$total,$page_number)
    {
        include('lib/db_connection.php');
        if(empty($run))
        {
            $all_student_details_qry = "SELECT * FROM `student_information` LIMIT $offset, $total";
            $run = mysqli_query($conn,$all_student_details_qry);
        }
        ?>
        <div class="table-responsive">
            <table class = "table table-bordered table-hover text-center">
                <tr>
                    <thead class ="thead-light">
                        <th>Name</th>
                        <th>Roll No</th>
                        <th>Registration No</th>
                        <th>Actual Session</th>
                        <th>Current Session</th>
                        <th>Student Type</th>
                        <th>View</th>
                    </thead>
                </tr>
                <?php
                while($row = mysqli_fetch_assoc($run))
                {
                    ?>
                    <tr>
                        <td><?php echo $row['name'];?></td>
                        <td><?php echo $row['roll_no'];?></td>
                        <td><?php echo $row['registration_no'];?></td>
                        <td><?php echo $row['actual_session'];?></td>
                        <td class = "<?php  if($row['student_type']=='Re-admitted') echo 'text-danger font-weight-bold'  ?>"><?php echo $row['current_session'];?></td>
                        <td class = "<?php  if($row['student_type']=='Re-admitted') echo 'text-danger font-weight-bold'  ?>"><?php echo $row['student_type'];?></td>
    
                        <td width = "12%">
                            <button  class = "link_btn">
                                <a href="view_student_profiles.php?id=<?php echo $row['id'] ?>&page=<?php echo $page_number ?>"><b><span><i class = "fas fa-eye"></i></span> View</b></a>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
       
        <?php
    }
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header form_header text-center">
                    <h2>Student Information</h2>
                </div>
                <div class="card-body">
                    <form action="" method = "POST">
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-4 col-12 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class = "input-group-text"><i class = "fas fa-search"></i></span>
                                    </div>
                                    <input type="text" class = "form-control" name = "search_name_wise" id = "search_name_wise" placeholder="Search By Name....." value = "<?php
                                    if(isset($_POST['show_all']))
                                    {
                                        echo "";
                                    }
                                    else if(isset($_POST['search_name_wise']))
                                    {
                                        echo "$_POST[search_name_wise]";
                                    }?>" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-12 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class = "input-group-text"><i class = "fas fa-search"></i></span>
                                    </div>
                                    <select class = "form-control" name="search_session_wise">
                                        <option value="" >Search Actual Session Wise</option>
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

                            <div class="col-lg-4 col-md-4 col-12 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class = "input-group-text"><i class = "fas fa-search"></i></span>
                                    </div>
                                    <input type="text" class = "form-control" name = "search_roll_wise" id = "search_roll_wise" placeholder="Search By Roll Number....." value = "<?php if(isset($_POST['show_all']))
                                    {
                                        echo "";
                                    }
                                    else if(isset($_POST['search_roll_wise']))
                                    {
                                        echo "$_POST[search_roll_wise]";
                                    }?>" autocomplete="off">
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
                            $name = $_POST['search_name_wise'];
                            $session = $_POST['search_session_wise'];
                            $roll = $_POST['search_roll_wise'];
                            $count = 1;
                            $search_qry = "SELECT * FROM `student_information` WHERE ";
                            if($name!=NULL)
                            {
                                $count++;
                                $search_qry.= "`name` like '%$name%'";
                            }
                            if($session!=NULL)
                            {
                                if($count>1)
                                {
                                    $search_qry.=" && ";
                                }
                                $search_qry.="`actual_session` = '$session'";
                                $count++;
                            }
                            if($roll!=NULL)
                            {
                                if($count>1)
                                {
                                    $search_qry.=" && ";
                                }
                                $search_qry.="`roll_no` = '$roll'";
                                $count++;
                            }
                            if($count==1)
                            {
                                ?>
                                <script>
                                    window.alert("Please Select At least 1 field");
                                    window.location = "view_student_information.php";
                                </script>
                                <?php
                            }
                            $run_search_qry = mysqli_query($conn, $search_qry);
                            ?>
                            <div class="col-lg-12 col-12">
                                <!-- Call display_content function -->
                                <?php display_content($run_search_qry,0,0,1); ?>
                                <!-- Offset and $total_data has been sent as 0 0 -->
                                <!-- Here no pagination applied -->
                            </div>
                            <?php
                        }
                         //End of qry for search button
                        else
                        {
                            ?>
                            <div class="col-lg-12 col-12">
                                <?php
                                $run = 0;
                                $page_name = 'view_student_information';
                                pagination($run,$page_name);
                                ?>
                            </div>
                            <?php
                        }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>


<?php include('lib/footer.php') ?>
