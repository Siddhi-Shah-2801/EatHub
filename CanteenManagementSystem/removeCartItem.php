<?php
session_start();
include('connectionDB.php');
if (isset($_GET["remove"])) {
    //Remove item pressed
    $targetCanteenId = $_GET["canteenId"];
    $targetCustomerId = $_SESSION["customerId"];
    $targetFoodId = $_GET["foodId"];
    $cartDeleteQuery = "DELETE FROM cart WHERE customerId = {$targetCustomerId} AND canteenId = {$targetCanteenId} AND foodId = {$targetFoodId}";
    $cartDeleteResult = $mysqli->query($cartDeleteQuery);
    if ($cartDeleteResult) {
        header("location: customerCart.php?removeCart=1");
    } else {
        header("location: customerCart.php?removeCart=0");
    }
    exit(1);
}
