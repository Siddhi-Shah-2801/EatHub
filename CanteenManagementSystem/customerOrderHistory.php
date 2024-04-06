<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    if (!isset($_SESSION["customerId"])) {
        header("location: restricted.php");
        exit(1);
    }
    include("connectionDB.php");
    include('head.php');
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/main.css" rel="stylesheet">
    <link href="./css/menu.css" rel="stylesheet">
    <title>Order History | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderCustomer.php') ?>

    <div class="container px-5 py-4" id="canteen-body">
        <a class="nav nav-item text-decoration-none text-muted mb-3" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <div class="mb-3 text-wrap" id="canteen-header">
            <h2 class="display-6 strong fw-normal">Order History</h2>
        </div>

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active px-4" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#nav-ongoing" type="button" role="tab" aria-controls="nav-ongoing" aria-selected="true">&nbsp;Ongoing&nbsp;</button>
                <button class="nav-link px-4" id="completed-tab" data-bs-toggle="tab" data-bs-target="#nav-completed" type="button" role="tab" aria-controls="nav-completed" aria-selected="false">Completed</button>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            <!-- ONGOING ORDER TAB -->
            <div class="tab-pane fade show active p-3" id="nav-ongoing" role="tabpanel" aria-labelledby="ongoing-tab">
                <?php
                $ongoingQuery = "SELECT * FROM orderheader WHERE customerId = {$_SESSION['customerId']} AND orderHeaderOrderStatus <> 'Finish';";
                $ongoingResult = $mysqli->query($ongoingQuery);
                $ongoingNum = $ongoingResult->num_rows;
                if ($ongoingNum > 0) {
                ?>
                    <div class="row row-cols-1 row-cols-md-3">
                        <!-- START EACH ORDER DETAIL -->
                        <?php while ($ogRow = $ongoingResult->fetch_array()) { ?>
                            <div class="col">
                                <a href="customerOrderDetail.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"] ?>" class="text-dark text-decoration-none">
                                    <div class="card mb-3">
                                        <?php if ($ogRow["orderHeaderOrderStatus"] == "Accept") { ?>
                                            <div class="card-header bg-info text-dark justify-content-between">
                                                <small class="me-auto d-flex" style="font-weight: 500;">Accepted your order</small>
                                            </div>
                                        <?php } else if ($ogRow["orderHeaderOrderStatus"] == "PREP") { ?>
                                            <div class="card-header bg-warning justify-content-between">
                                                <small class="me-auto d-flex" style="font-weight: 500;">Preparing your order</small>
                                            </div>
                                        <?php } else if ($ogRow["orderHeaderOrderStatus"] == "RDPK") { ?>
                                            <div class="card-header bg-primary text-white justify-content-between">
                                                <small class="me-auto d-flex" style="font-weight: 500;">Your order is ready for
                                                    pick-up</small>
                                            </div>
                                        <?php } else { ?>
                                            <div class="card-header bg-success text-white justify-content-between">
                                                <small class="me-auto d-flex" style="font-weight: 500;">Order Finished</small>
                                            </div>
                                        <?php } ?>
                                        <div class="card-body">
                                            <div class="card-text row row-cols-1 small">
                                                <div class="col">Order #<?php echo $ogRow["orderHeaderReferenceCode"]; ?></div>
                                                <div class="col mb-2">From
                                                    <?php
                                                    $canteenQuery = "SELECT canteenName FROM canteen WHERE canteenId = {$ogRow['canteenId']};";
                                                    $canteenArr = $mysqli->query($canteenQuery)->fetch_array();
                                                    echo $canteenArr["canteenName"];
                                                    ?>
                                                </div>
                                                <?php
                                                $orderQuery = "SELECT COUNT(*) AS count,SUM(orderAmount*orderBuyPrice) AS gt FROM orderdetail
                                        WHERE orderHeaderId = {$ogRow['orderHeaderId']}";
                                                $orderArray = $mysqli->query($orderQuery)->fetch_array();
                                                ?>
                                                <div class="col pt-2 border-top"><?php echo $orderArray["count"] ?> item(s)</div>
                                                <div class="col mt-1 mb-2"><strong class="h5"><?php echo $orderArray["gt"] ?>
                                                        Rs</strong></div>
                                                <div class="col text-end">
                                                    <a href="customerOrderDetail.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"] ?>" class="text-dark text-decoration-none">
                                                        More Detail
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                        <!-- END EACH ORDER DETAIL -->
                    </div>
                <?php } else { ?>
                    <!-- IN CASE NO ORDER -->
                    <div class="row row-cols-1">
                        <div class="col pt-3 px-3 bg-danger text-white rounded text-center">
                            <p class="ms-2 mt-2">You don't have any order yet.</p>
                        </div>
                    </div>
                    <!-- END CASE NO ORDER -->
                <?php } ?>
            </div>


            <!-- COMPLETED ORDER TAB -->
            <div class="tab-pane fade p-3" id="nav-completed" role="tabpanel" aria-labelledby="completed-tab">
                <?php
                $ongoingQuery = "SELECT * FROM orderheader WHERE customerId = {$_SESSION['customerId']} AND orderHeaderOrderStatus = 'Finish';";
                $ongoingResult = $mysqli->query($ongoingQuery);
                $ongoingNum = $ongoingResult->num_rows;
                if ($ongoingNum > 0) {
                ?>
                    <div class="row row-cols-1 row-cols-md-3">
                        <!-- START EACH ORDER DETAIL -->
                        <?php while ($ogRow = $ongoingResult->fetch_array()) { ?>
                            <div class="col">
                                <a href="../customer/customerOrderDetail.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"] ?>" class="text-dark text-decoration-none">
                                    <div class="card mb-3">
                                        <?php if ($ogRow["orderHeaderOrderStatus"] == "Accept") { ?>
                                            <div class="card-header bg-info text-dark justify-content-between">
                                                <small class="me-auto d-flex" style="font-weight: 500;">Accepted your order</small>
                                            </div>
                                        <?php } else if ($ogRow["orderHeaderOrderStatus"] == "Prep") { ?>
                                            <div class="card-header bg-warning justify-content-between">
                                                <small class="me-auto d-flex" style="font-weight: 500;">Preparing your order</small>
                                            </div>
                                        <?php } else if ($ogRow["orderHeaderOrderStatus"] == "RDPK") { ?>
                                            <div class="card-header bg-primary text-white justify-content-between">
                                                <small class="me-auto d-flex" style="font-weight: 500;">Your order is ready for
                                                    pick-up</small>
                                            </div>
                                        <?php } else { ?>
                                            <div class="card-header bg-success text-white justify-content-between">
                                                <small class="me-auto d-flex" style="font-weight: 500;">Order Finished</small>
                                            </div>
                                        <?php } ?>
                                        <div class="card-body">
                                            <div class="card-text row row-cols-1 small">
                                                <div class="col">Order #<?php echo $ogRow["orderHeaderReferenceCode"]; ?></div>
                                                <div class="col mb-2">From
                                                    <?php
                                                    $canteenQuery = "SELECT canteenName FROM canteen WHERE canteenId = {$ogRow['canteenId']};";
                                                    $canteenArr = $mysqli->query($canteenQuery)->fetch_array();
                                                    echo $canteenArr["canteenName"];
                                                    ?>
                                                </div>
                                                <?php
                                                $orderQuery = "SELECT COUNT(*) AS count,SUM(orderAmount*orderBuyPrice) AS gt FROM orderdetail
                                        WHERE orderHeaderId = {$ogRow['orderHeaderId']}";
                                                $orderArray = $mysqli->query($orderQuery)->fetch_array();
                                                ?>
                                                <div class="col pt-2 border-top"><?php echo $orderArray["count"] ?> item(s)</div>
                                                <div class="col mt-1 mb-2"><strong class="h5"><?php echo $orderArray["grandTotal"] ?>
                                                        Rs</strong></div>
                                                <div class="col text-end">
                                                    <a href="../customer/customerOrderDetail.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"] ?>" class="text-dark text-decoration-none">
                                                        More Detail
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                        <!-- END EACH ORDER DETAIL -->
                    </div>
                <?php } else { ?>
                    <!-- IN CASE NO ORDER -->
                    <div class="row row-cols-1">
                        <div class="col pt-3 px-3 bg-danger text-white rounded text-center">
                            <p class="ms-2 mt-2">You don't have any order yet.</p>
                        </div>
                    </div>
                    <!-- END CASE NO ORDER -->
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>