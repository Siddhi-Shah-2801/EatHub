<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include('../connectionDB.php');
    if (!isset($_SESSION["customerId"]) || !isset($_GET["orderheader"])) {
        header("location: ../restricted.php");
        exit(1);
    }
    include('head.php');
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/customerLogin.css" rel="stylesheet">

    <title>Successfully Order | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderCustomer.php') ?>
    <div class="mt-5"></div>
    <div class="container form-signin text-center reg-success mt-auto">
        <i class="mt-4 bi bi-cart-check text-success h1 display-1"></i>
        <h4 class="mt-2 fw-normal text-bold">Your order has been placed.</h4>
        <?php
        //generate order reference code
        $orderHeaderId = $_GET["orderheaderId"];
        $orderHeaderQuery = "SELECT orderHeaderReferenceCode FROM orderheader WHERE orderHeaderId = {$orderHeaderId} LIMIT 0,1;";
        $orderHeaderArray = $mysqli->query($orderHeaderQuery)->fetch_array();
        $orderHeaderReferenceCode = $orderHeaderArray["orderHeaderReferenceCode"];
        ?>
        <h5 class="mb-3 fw-normal text-bold">Order #<?php echo $orderHeaderReferenceCode; ?></h5>
        <p class="mb-3 fw-normal text-bold text-wrap">We'll notify the canteen in a few moments. <br />You can check status in order history menu.</p>
        <a class="btn btn-outline-secondary btn-sm" href="viewPage.php">Return to home</a>
        <a class="btn btn-success btn-sm" href="customerOrderHistory.php">Go to order history page</a>
    </div>
</body>

</html>