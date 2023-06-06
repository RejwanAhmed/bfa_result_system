<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    if(!isset($_SESSION['id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
?>

<?php
    include("lib/db_connection.php");
    if(isset($_GET['id']))
    {
        // Start of Whether an id is valid or not
        $id_validation_qry = "SELECT * FROM `exam_committee_information` WHERE `id` = '$_GET[id]'";
        $id_validation_qry_run = mysqli_query($conn, $id_validation_qry);
        $id_validation_qry_run_res = mysqli_fetch_assoc($id_validation_qry_run);
        if($id_validation_qry_run_res==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "index.php";
            </script>
            <?php
            exit();
        }
        else if($id_validation_qry_run_res['1st_sem_status']!=0 || $id_validation_qry_run_res['2nd_sem_status']!=0)
        {
            ?>
                <script>
                    window.alert('This Committee Can Not Be Deleted! It is in use.');
                    window.location = "view_exam_committee_information.php";
                </script>
            <?php
            exit();
        }
        $id = $_GET['id'];
        $page = $_GET['page'];
        //End of Whether an id is valid or not
    }
    else if(!isset($_GET['id']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
    $delete_qry = "DELETE FROM `exam_committee_information` WHERE `id` = '$id'";
	$delete_qry_run = mysqli_query($conn, $delete_qry);
    if($delete_qry_run)
    {
        ?>
        <script type="text/javascript">
        	window.alert("Exam Committee Deleted Successfully");
        	window.location = "view_exam_committee_information.php?page=<?php echo $page; ?>";
        </script>
        <?php
    }
?>
