<?php include('lib/header.php') ?>
<table class = "table table-bordered table-hover text-center table-lg-responsive">

</table>

    <?php 
     $a = 31.25;
     $b = 40;
     $c = ceil($a+$b);
     echo $c."<br>";
     
     $new = "2.75";
     $old = "3.00";
     if($old<$new)
     {
        echo $old;
     }
     
     $result = 3.4551568659;
     echo gettype($result)."<br>";
     echo "original = ".$result."<br>";
     echo bcdiv($result,1,2);
    ?>
<?php include('lib/footer.php') ?>
