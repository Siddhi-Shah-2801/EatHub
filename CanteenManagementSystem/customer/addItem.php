<?php
session_start();
include('../connectionDB.php');

if (!isset($_SESSION["customerId"])) {
    header("location: customerLogin.php");
    exit(1);
}

$foodId = $_POST["foodId"];
$canteenId = $_POST["canteenId"];
$customerId = $_SESSION["customerId"];
$amount = $_POST["amount"];
$request = $_POST["request"];

$query = "SELECT canteenId FROM cart WHERE customerId = {$customerId} GROUP BY customerId";
$result = $mysqli->query($query);

if ($result->num_rows == 0) {
    //No item in cart
    $insertQuery = "INSERT INTO cart (customerId, canteenId, foodId, cartAmount, cartNote) 
        VALUES ({$customerId},{$canteenId},{$foodId},{$amount},'{$request}')";
    $atcResult = $mysqli->query($insertQuery);
} else {
    //Already have item in cart
    $resultArr = $result->fetch_array();
    $incartCanteen = $resultArr["canteenId"];
    if ($incartCanteen == $canteenId) {
        //Same canteen
        $cartSearch = "SELECT cartAmount FROM cart WHERE customerId = {$customerId} AND foodId = {$foodId}";
        $cartSearchResult = $mysqli->query($cartSearch);
        $cartSearchRow = $cartSearchResult->num_rows;
        if ($cartSearchRow == 0) {
            //No this item in cart yet
            $insertQuery = "INSERT INTO cart (customerId, canteenId, foodId, cartAmount, cartNote) 
                VALUES ({$customerId},{$canteenId},{$foodId},{$amount},'{$request}')";
            $atcResult = $mysqli->query($insertQuery);
        } else {
            //Already have item in cart
            $cartSearchArr = $cartSearchResult->fetch_array();
            $incartAmount = $cartSearchArr["cartAmount"];
            $newAmount = $incartAmount + $amount;
            $updateQuery = "UPDATE cart SET cartAmount = {$newAmount} WHERE customerId = {$customerId} AND foodId = {$foodId} AND canteenId = {$canteenId}";
            $atcResult = $mysqli->query($updateQuery);
        }
    } else {
        //Different canteen
        //Delete all items in cart from previous canteen
        $delelteQuery = "DELETE FROM cart WHERE customerId = {$customerId}";
        $deleteResult = $mysqli->query($delelteQuery);
        if ($deleteResult) {
            //Insert new item to cart of new canteen
            $insertQuery = "INSERT INTO cart (customerId, canteenId, foodId, cartAmount, cartNote) 
                VALUES ({$customerId},{$canteenId},{$foodId},{$amount},'{$request}')";
            $atcResult = $mysqli->query($insertQuery);
        } else {
            $atcResult = false;
        }
    }
}
if ($atcResult) {
    header("location: ../canteenMenu.php?canteenId={$canteenId}&atc=1");
    exit(1);
} else {
    header("location: ../canteenMenu.php?canteenId={$canteenId}&atc=0");
    exit(1);
}
?>