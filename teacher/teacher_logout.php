<?php
    session_start();
    if(isset($_SESSION['teacher_id']))
    {
        session_unset();
        session_destroy();
    }
?>
<script>
    window.location = "index.php";
</script>
