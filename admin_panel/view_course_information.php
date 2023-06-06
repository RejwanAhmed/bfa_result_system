<?php include('lib/header.php') ?>
<?php include('pagination.php') ?>
<?php
    function get_row_count()
    {
        include('lib/db_connection.php');
        $sql = "SELECT COUNT(`id`) as total_row FROM `course_information`";
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
            $all_course_details_qry = "SELECT * FROM `course_information` LIMIT $offset, $total";
            $run = mysqli_query($conn,$all_course_details_qry);
        }
        
        // department/stream er name show koranor jonno ei query use kora hoise.
        $department_info_query = "SELECT * FROM `department_information`";
        $run_department_info_query = mysqli_query($conn, $department_info_query);
        // $dept_id_array = array();
        $dept_array = array();
        while($row = mysqli_fetch_assoc($run_department_info_query))
        {
            // $dept_array[$row['id']] = $row['department_name'];
            $dept_array[$row['department_name']] = $row['id'];
        }
        ?>
        
        <div class="table-responsive">
            <table class = "table table-bordered table-hover text-center">
                <tr>
                    <thead class ="thead-light">
                        <th>Department/Stream</th>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Course Credit</th>
                        <th>Course Type</th>
                        <th>View</th>
                    </thead>
                </tr>
                <?php
                while($row = mysqli_fetch_assoc($run))
                {
                    ?>
                    <tr>
                        <?php
                            // course_information er table er dept_id er sathe department_information er id jdi match kore tahole oi department er name show korbe otherwise foundation course show korbe.
                            $dept_name = array_search($row['department_id'], $dept_array);
                            if($dept_name!=NULL)
                            {
                                ?>
                                <td><?php echo $dept_name;?></td>
                                <?php 
                            }
                            else 
                            {
                                ?>
                                <td><?php echo "Foundation Course";?></td>
                                <?php
                            }
                        ?>
                        
                        <td>
                            <?php
                            if($row['course_code'] == "")
                            {
                                echo "--";
                            }
                            else
                            {
                                echo $row['course_code'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if($row['course_title'] == "")
                            {
                                echo "--";
                            }
                            else
                            {
                                echo $row['course_title'];
                            }
                            ?>
                        </td>
                        <td><?php echo $row['course_credit'];?></td>
                        <td><?php echo $row['course_type'];?></td>
                        <td>
                            <button class = "link_btn">
                                <a  href="view_course_profiles.php?id=<?php echo $row['id'] ?>&page=<?php echo $page_number ?>"><b><span><i class = "fas fa-eye"></i></span> View</b></a>
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
                    <h2>Course Information</h2>
                </div>
                <div class="card-body">
                    <form action="" method = "POST">
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-4 col-12 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class = "input-group-text"><i class = "fas fa-search"></i></span>
                                    </div>
                                    <input type="text" class = "form-control" name = "search_code_wise" id = "search_code_wise" placeholder="Search By Course Code....." value = "<?php
                                    if(isset($_POST['show_all']))
                                    {
                                        echo "";
                                    }
                                    else if(isset($_POST['search_code_wise']))
                                    {
                                        echo "$_POST[search_code_wise]";
                                    }?>" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-12 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class = "input-group-text"><i class = "fas fa-search"></i></span>
                                    </div>
                                    <input type="text" class = "form-control" name = "search_title_wise" id = "search_title_wise" placeholder="Search By Course Title....." value = "<?php
                                    if(isset($_POST['show_all']))
                                    {
                                        echo "";
                                    }
                                    else if(isset($_POST['search_title_wise']))
                                    {
                                        echo "$_POST[search_title_wise]";
                                    }?>" autocomplete="off">
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-4 col-12 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class = "input-group-text"><i class = "fas fa-search"></i></span>
                                    </div>
                                    <select class = "form-control" name="search_course_type_wise"
                                    >
                                        <option value="" selected>Search By Course Type</option>
                                        <option <?php  
                                        if(isset($_POST['show_all']))
                                        {
                                            echo "";
                                        } 
                                        else if(isset($_POST['search_course_type_wise']) && $_POST['search_course_type_wise']=="Theory") echo "selected"; ?>>Theory</option>
                                        
                                        <option <?php
                                         if(isset($_POST['show_all']))
                                         {
                                             echo "";
                                         }
                                         else if(isset($_POST['search_course_type_wise']) && $_POST['search_course_type_wise']=="Practical") echo "selected";?>>Practical</option>
                                          
                                        <option <?php
                                         if(isset($_POST['show_all']))
                                         {
                                             echo "";
                                         }
                                         else if(isset($_POST['search_course_type_wise']) && $_POST['search_course_type_wise']=="Viva-Voce") echo "selected";?>>Viva-Voce</option>
                                        
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
                            $code = $_POST['search_code_wise'];
                            $title = $_POST['search_title_wise'];
                            $search_course_type_wise = $_POST['search_course_type_wise'];
                            $count = 1;
                            $search_qry = "SELECT * FROM `course_information` WHERE ";
                            if($code!=NULL)
                            {
                                $count++;
                                $search_qry.= "`course_code` like '%$code%'";
                            }
                            if($title!=NULL)
                            {
                                if($count>1)
                                {
                                    $search_qry.=" && ";
                                }
                                $search_qry.="`course_title` like '%$title%'";
                                $count++;
                            }
                            if($search_course_type_wise!=NULL)
                            {
                                if($count>1)
                                {
                                    $search_qry.=" && ";
                                }
                                $search_qry.="`course_type` = '$search_course_type_wise'";
                                $count++;
                            }
                            if($count==1)
                            {
                                ?>
                                <script>
                                    window.alert("Please Select At least 1 field");
                                    window.location = "view_course_information.php";
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
                                $page_name = 'view_course_information';
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
