<?php
    session_start();
    if($_SESSION["utype"]!="admin"){
        header("location: ../restricted.php");
        exit(1);
    }
    include('../connectionDB.php');
    $canteenId = $_GET["canteenId"];

    $deleteQuery = "DELETE FROM canteen WHERE canteenId = '{$canteenId}';";
    $deleteResult = $mysqli -> query($deleteQuery);

    if($deleteResult){
        header("location: adminCanteenList.php?deleteCanteen=1");
    }else{
        header("location: adminCanteenList.php?deleteCanteen=0");
    }

?>