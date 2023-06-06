<?php include('lib/header.php') ?>
<?php
    if(!isset($_GET['session']) || !isset($_GET['department_id']))
    {
        ?>
        <script>
            window.alert("Session Or Department Id Is Not Set");
            window.location = 'index.php';
        </script>
        <?php
    }
    else if(isset($_GET['session']) && isset($_GET['department_id']))
    {
        // session validation
        $c = 2006;
        $count = 0;
        $today = date("Y");
        for($i=$c; $i<$today; $i++)
        {
            $r = $i + 1;
            $session= $i."-".$r;
            if($session == $_GET['session'])
            {
                $count = 1;
                break;
            }
        }
        if($count==0)
        {
            ?>
            <script>
                window.alert("Invalid Session");
                window.location = "index.php";
            </script>
            <?php
            exit();
        }
        // end of session validation
        
        // department_id validation
        // valid department url e pass hocche kina ta check korar jonno.
        // department_id == 0 mane hocche foundation course. jehetu array_search fail korle 0 dekhay tai oita alada vabe $_GET['department_id] == 0 diye check korsi
        $valid_department_info = valid_department();
        $department_id_array = $valid_department_info[0];
        $department_name_array = $valid_department_info[1];
        if(array_search($_GET['department_id'],$department_id_array) || ($_GET['department_id']==0))
        {
            $department_id = $_GET['department_id'];
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
        // end of department_id validation
    }
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-12">
            <div class="card">
                <div class="card-header form_header text-center">
                    <h2>Assigned Course Semester Information</h2>
                    <h4 class = "text-warning fw-bold">Department/Stream:(<?php echo $department_name?>)</h4>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center table-responsive">
                        <table class = "table table-bordered table-hover text-center ">
                            <tr>
                                <thead class ="thead-light">
                                    <th>Session</th>
                                    <th>Year</th>
                                    <th>Semester</th>
                                    <th>Assign</th>
                                </thead>
                            </tr>
                            <?php 
                                // jdi foundation course hoy tahole 1st year er 2ta semester show korte hobe
                                if($department_id==0)
                                {
                                ?>
                                    <tr>
                                        <td><?php echo $_GET['session'] ?></td>
                                        <td>1st Year</td>
                                        <td>1st Semester</td>
                                        <td width = "15%">
                                            <button class = "link_btn" >
                                                <a href="assign_course_to_session.php?session=<?php echo $_GET['session']?>&year=1st year&semester=1st semester&department_id=<?php echo $department_id ?>"><b><span><i class = "fas fa-folder-plus"></i></span> Assign</b></a>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $_GET['session'] ?></td>
                                        <td>1st Year</td>
                                        <td>2nd Semester</td>
                                        <td width = "15%">
                                            <button class = "link_btn" >
                                                <a href="assign_course_to_session.php?session=<?php echo $_GET['session']?>&year=1st year&semester=2nd semester&department_id=<?php echo $department_id ?>"><b><span><i class = "fas fa-folder-plus"></i></span> Assign</b></a>
                                            </button>
                                        </td>
                                    </tr>
                                <?php 
                                }
                                else
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $_GET['session'] ?></td>
                                        <td>2nd Year</td>
                                        <td>1st Semester</td>
                                        <td width = "15%">
                                            <button class = "link_btn" >
                                                <a href="assign_course_to_session.php?session=<?php echo $_GET['session']?>&year=2nd year&semester=1st semester&department_id=<?php echo $department_id ?>"><b><span><i class = "fas fa-folder-plus"></i></span> Assign</b></a>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $_GET['session'] ?></td>
                                        <td>2nd Year</td>
                                        <td>2nd Semester</td>
                                        <td width = "15%">
                                            <button class = "link_btn" >
                                                <a href="assign_course_to_session.php?session=<?php echo $_GET['session']?>&year=2nd year&semester=2nd semester&department_id=<?php echo $department_id ?>"><b><span><i class = "fas fa-folder-plus"></i></span> Assign</b></a>
                                            </button>
                                        </td>
                                    </tr>
        
                                    <tr>
                                        <td><?php echo $_GET['session'] ?></td>
                                        <td>3rd Year</td>
                                        <td>1st Semester</td>
                                        <td width = "15%">
                                            <button class = "link_btn" >
                                                <a href="assign_course_to_session.php?session=<?php echo $_GET['session']?>&year=3rd year&semester=1st semester&department_id=<?php echo $department_id ?>"><b><span><i class = "fas fa-folder-plus"></i></span> Assign</b></a>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $_GET['session'] ?></td>
                                        <td>3rd Year</td>
                                        <td>2nd Semester</td>
                                        <td width = "15%">
                                            <button class = "link_btn" >
                                                <a href="assign_course_to_session.php?session=<?php echo $_GET['session']?>&year=3rd year&semester=2nd semester&department_id=<?php echo $department_id ?>"><b><span><i class = "fas fa-folder-plus"></i></span> Assign</b></a>
                                            </button>
                                        </td>
                                    </tr>
        
                                    <tr>
                                        <td><?php echo $_GET['session'] ?></td>
                                        <td>4th Year</td>
                                        <td>1st Semester</td>
                                        <td width = "15%">
                                            <button class = "link_btn" >
                                                <a  href="assign_course_to_session.php?session=<?php echo $_GET['session']?>&year=4th year&semester=1st semester&department_id=<?php echo $department_id ?>"><b><span><i class = "fas fa-folder-plus"></i></span> Assign</b></a>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $_GET['session'] ?></td>
                                        <td>4th Year</td>
                                        <td>2nd Semester</td>
                                        <td width = "15%">
                                            <button class = "link_btn" >
                                                <a href="assign_course_to_session.php?session=<?php echo $_GET['session']?>&year=4th year&semester=2nd semester&department_id=<?php echo $department_id ?>"><b><span><i class = "fas fa-folder-plus"></i></span> Assign</b></a>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php 
                                }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('lib/footer.php') ?>
