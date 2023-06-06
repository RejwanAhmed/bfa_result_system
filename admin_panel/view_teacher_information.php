<?php include('lib/header.php') ?>
<?php include('pagination.php') ?>
<?php
    function get_row_count()
    {
        include('lib/db_connection.php');
        $sql = "SELECT COUNT(`id`) as total_row FROM `teacher_information`";
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
            $all_teacher_details_qry = "SELECT * FROM `teacher_information` LIMIT $offset, $total";
            $run = mysqli_query($conn,$all_teacher_details_qry);
        }
        ?>
        <div class="table-responsive">
            <table class = "table table-bordered table-hover text-center">
                <tr>
                    <thead class ="thead-light">
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Modify</th>
                        <th>Delete</th>
                    </thead>
                </tr>
                <?php
                while($row = mysqli_fetch_assoc($run))
                {
                    ?>
                    <tr>
                        <td><?php echo $row['name'];?></td>
                        <td><?php echo $row['designation'];?></td>
                        <td><?php echo $row['email'];?></td>
                        <td><?php echo base64_decode($row['password']);?></td>
                        <td>
                            <button class = " link_btn">
                            <a href="modify_teacher.php?id=<?php echo $row['id']?>&page=<?php echo $page_number; ?>"><b><span><i class="far fa-edit"></i></span> Modify</b>
                            </a>
                            </button>
                        </td>
                        <td>
                            <button class = "link_btn" onclick = "deleteConfirmation(<?php echo $row['id'];?>, <?php echo $page_number;?>)"><b><span><i class="fas fa-eraser"></i></span> Remove</b></button>
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
                    <h2>Teacher Information</h2>
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
                            if($name!=NULL)
                            {
                                $search_qry = "SELECT * FROM `teacher_information` WHERE `name` like '%$name%'";
                                $run_search_qry = mysqli_query($conn, $search_qry);
                            }
                            else
                            {
                                ?>
                                <script>
                                    window.alert("Please Select At least 1 field");
                                    window.location = "view_teacher_information.php";
                                </script>
                                <?php
                            }
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
                                $page_name = 'view_teacher_information';
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

<script>
    function deleteConfirmation(id, page)
    {
        var del = confirm('Are You Sure Want To Delete?');
        if(del == true)
        {
            window.location='delete_teacher.php?id='+id+'&page='+page;
        }
    }
</script>
<?php include('lib/footer.php') ?>
