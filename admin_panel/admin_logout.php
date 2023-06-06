<?php
    session_start();
    if(isset($_SESSION['id']))
    {
        session_unset();
        session_destroy();
    }
?>
<script>
    window.location = "index.php";
</script>
