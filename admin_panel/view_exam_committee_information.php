<?php include('lib/header.php') ?>
<?php include('pagination.php') ?>
<?php
    function get_row_count()
    {
        include('lib/db_connection.php');
        $sql = "SELECT COUNT(`id`) as total_row FROM `exam_committee_information`";
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
            $all_exam_committee_details_qry = "SELECT * FROM `exam_committee_information` ORDER BY id DESC LIMIT $offset, $total ";
            $run = mysqli_query($conn,$all_exam_committee_details_qry);
        }
        ?>     
        <div class="table-responsive">
            <table class = "table table-bordered table-hover text-center">
            <tr>
                <thead class ="thead-light">
                    <th>Session</th>
                    <th>Year</th>
                    <th>View Exam Committee</th>
                    <th>View Tabulator</th>
                </thead>
            </tr>
            <?php
            while($row = mysqli_fetch_assoc($run))
            {
                ?>
                <tr>
                    <td><?php echo $row['session'];?></td>
                    <td><?php echo $row['course_year'];?></td>
    
                    <td width = "22%">
                        <button  class = "link_btn" >
                            <a href="view_exam_committee_profiles.php?id=<?php echo $row['id'] ?>&page=<?php echo $page_number ?>"><b><span><i class = "fas fa-eye"></i></span> View Committee</b></a>
                        </button>
                    </td>
    
                    <td width = "22%">
                        <button class = "link_btn">
                            <a  href="view_tabulator_profiles.php?id=<?php echo $row['id'] ?>&page=<?php echo $page_number ?>"><b><span><i class = "fas fa-eye"></i></span> View Tabulator</b></a>
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
                    <h2>Exam Committee & Tabulator Information</h2>
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

                            <div class="col-lg-4 col-md-4 col-12 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class = "input-group-text"><i class = "fas fa-search"></i></span>
                                    </div>
                                    <select class = "form-control" name="search_course_year_wise">
                                        <option value="" selected>Please Select Course Year</option>
                                        <option <?php if(isset($_POST['show_all']))
                                        {
                                            echo "";
                                        }
                                        else if(isset($_POST['search_course_year_wise']) && $_POST['search_course_year_wise']=="1st year") echo "selected"; ?>>1st year</option>
                                        <option <?php if(isset($_POST['show_all']))
                                        {
                                            echo "";
                                        }
                                        else if(isset($_POST['search_course_year_wise']) && $_POST['search_course_year_wise']=="2nd year") echo "selected"; ?>>2nd year</option>
                                        <option <?php if(isset($_POST['show_all']))
                                        {
                                            echo "";
                                        }
                                        else if(isset($_POST['search_course_year_wise']) && $_POST['search_course_year_wise']=="3rd year") echo "selected"; ?>>3rd year</option>
                                        <option <?php if(isset($_POST['show_all']))
                                        {
                                            echo "";
                                        }
                                        else if(isset($_POST['search_course_year_wise']) && $_POST['search_course_year_wise']=="4th year") echo "selected"; ?>>4th year</option>
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
                            $course_year = $_POST['search_course_year_wise'];
                            $count = 1;
                            $search_qry = "SELECT * FROM `exam_committee_information` WHERE ";
                            if($session!=NULL)
                            {
                                $count++;
                                $search_qry.= "`session` = '$session'";
                            }
                            if($course_year!=NULL)
                            {
                                if($count>1)
                                {
                                    $search_qry.=" && ";
                                }
                                $search_qry.="`course_year` = '$course_year'";
                                $count++;
                            }
                            if($count==1)
                            {
                                ?>
                                <script>
                                    window.alert("Please Select At least 1 field");
                                    window.location = "view_exam_committee_information.php";
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
                                $page_name = 'view_exam_committee_information';
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
