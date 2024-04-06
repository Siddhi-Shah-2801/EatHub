<?php
    session_start();
    if($_SESSION["utype"]!="canteenOwner"){
        header("location: ../restricted.php");
        exit(1);
    }
    include('../connectionDB.php');
    $foodId = $_GET["foodId"];
    //DISABLE FOOD ITEM INSTEAD OF DELETE IT
    $deleteQuery = "DELETE FROM food WHERE foodId = '{$foodId}';";
    $deleteResult = $mysqli -> query($deleteQuery);
    if($deleteResult){
        header("location: canteenMenuList.php?deleteFoodItem=1");
    }else{
        header("location: canteenMenuList.php?deleteFoodItem=0");
    }
?>