<?php
    session_start();
    if($_SESSION["utype"]!="canteenOwner"){
        header("location: ../restricted.php");
        exit(1);
    }
    include('../connectionDB.php');
    $orderHeaderId = $_GET["orderHeaderId"];
    $currentStage = $_GET["currentStage"];
    switch($currentStage){
        case 1: $nextStage = "PREP"; $time = NULL; break;
        case 2: $nextStage = "RDPK"; $time = NULL; break;
        case 3: $nextStage = "FNSH"; $time = date("Y-m-d\TH:i:s"); break;
        default: header("location: canteenOrderList?updateOrders=0"); exit(1);
    }
    $updateQuery = "UPDATE orderheader SET orderHeaderOrderStatus = '{$nextStage}',orderHeaderFinishedTime = '{$time}' WHERE orderHeaderId = {$orderHeaderId};";
    $updateResult = $mysqli -> query($updateQuery);
    if($updateQuery){
        header("location: canteenOrderList.php?updateOrders=1");
    }else{
        header("location: canteenOrderList.php?updateOrders=0");
    }
    exit(1);
?>