<?php
    session_start();
    if($_SESSION["utype"]!="admin"){
        header("location: ../restricted.php");
        exit(1);
    }
    include('../connectionDB.php');
    $customerId = $_GET["customerId"];

    $deleteQuery = "DELETE FROM customer WHERE customerId = '{$customerId}';";
    $deleteResult = $mysqli -> query($deleteQuery);

    if($deleteResult){
        header("location: adminCustomerList.php?deleteCustomer=1");
    }else{
        header("location: adminCustomerList.php?deleteCustomer=0");
    }

?>