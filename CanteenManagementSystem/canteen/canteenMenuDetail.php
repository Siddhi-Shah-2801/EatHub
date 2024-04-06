<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("../connectionDB.php");
    include('../head.php');
    if ($_SESSION["utype"] != "canteenOwner") {
        header("location: ../restricted.php");
        exit(1);
    }
    $canteenId = $_SESSION["canteenId"];
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/menu.css" rel="stylesheet">
    <title>Menu Detail | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderCanteen.php') ?>

    <?php
    $foodId = $_GET["foodId"];
    $query = "SELECT f.foodName,f.foodPrice,f.foodTodayAvailable,f.foodPreOrderAvailable,f.foodPic FROM food f WHERE f.foodId = $foodId LIMIT 0,1;";
    $result = $mysqli->query($query);
    $foodRow = $result->fetch_array();
    ?>

    <div class="container px-5 py-4" id="canteen-body">
        <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <?php
        if (isset($_GET["up_fdt"])) {
            if ($_GET["up_fdt"] == 1) {
        ?>
                <!-- START SUCCESSFULLY UPDATE DETAIL -->
                <div class="row row-cols-1 notibar">
                    <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                        <span class="ms-2 mt-2">Successfully updated menu detail.</span>
                    </div>
                </div>
                <!-- END SUCCESSFULLY UPDATE DETAIL -->
            <?php } else { ?>
                <!-- START FAILED UPDATE DETAIL -->
                <div class="row row-cols-1 notibar">
                    <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                        <span class="ms-2 mt-2">Failed to update menu detail.</span>
                    </div>
                </div>
                <!-- END FAILED UPDATE DETAIL -->
        <?php }
        }
        ?>
        <div class="container row row-cols-6 row-cols-md-12 g-5 pt-4 mb-4" id="canteen-header">
            <div class="rounded-25 col-6" id="canteenImage" style="
                    background: url(
                        <?php
                        if (is_null($foodRow["foodPic"])) {
                            echo "'../images/somaiyaCanteenLogo.png'";
                        } else {
                            echo "'../images/{$foodRow['foodPic']}'";
                        }
                        ?> 
                    ) center; height: 225px;
                    background-size: cover; background-repeat: no-repeat;
                    background-position: center;">
            </div>
            <div class="col-6">
                <h1 class="display-5 strong"><?php echo $foodRow["foodName"]; ?></h1>
                <h3 class="fw-light"><?php echo $foodRow["foodPrice"] ?> Rs</h3>
                <ul class="list-unstyled">
                    <li class="my-2">
                        <?php
                        if ($foodRow["foodTodayAvailable"] == 1) {
                        ?>
                            <span class="badge rounded-pill bg-success">Avaliable</span>
                        <?php } else { ?>
                            <span class="badge rounded-pill bg-danger">Unavaliable</span>
                        <?php }
                        if ($foodRow["foodPreOrderAvailable"] == 1) {
                        ?>
                            <span class="badge rounded-pill bg-success">Pre-order avaliable</span>
                        <?php } else { ?>
                            <span class="badge rounded-pill bg-danger">Pre-order Unavaliable</span>
                        <?php } ?>
                    </li>
                </ul>
                <a class="btn btn-sm btn-primary mt-2 mt-md-0" href="canteenMenuEdit.php?foodId=<?php echo $foodId ?>">
                    Update this menu
                </a>
                <a class="btn btn-sm btn-danger mt-2 mt-md-0" href="canteenMenuDelete.php?foodId=<?php echo $foodId ?>">
                    Delete this menu
                </a>
            </div>
        </div>

        <div class="container">
            <h3 class="border-top pt-3 pb-2 mt-2">All-time Performance</h3>
            <div class="row row-cols-2 row-cols-md-4 mb-3 g-2">
                <div class="col">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                $countQuery = "SELECT COUNT(DISTINCT orh.customerId) AS count FROM orderheader orh INNER JOIN orderdetail ord ON orh.orderHeaderId = ord.orderHeaderId
                                    WHERE ord.foodId = {$foodId};";
                                $countResult = $mysqli->query($countQuery)->fetch_array();
                                echo $countResult["count"];
                                ?> people
                            </h5>
                            <p class="card-text small">Customers ordered this menu</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title"><?php
                                                    $countQuery = "SELECT COUNT(*) AS count FROM orderdetail ord WHERE ord.foodId = {$foodId};";
                                                    $countResult = $mysqli->query($countQuery)->fetch_array();
                                                    echo $countResult["count"];
                                                    ?> orders</h5>
                            <p class="card-text small">Order included this menu</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title"><?php
                                                    $countQuery = "SELECT SUM(ord.orderBuyPrice*ord.orderAmount) AS profit FROM orderdetail ord WHERE ord.foodId = {$foodId};";
                                                    $countResult = $mysqli->query($countQuery)->fetch_array();
                                                    if (is_null($countResult["profit"])) {
                                                        echo "0.00";
                                                    } else {
                                                        echo $countResult["profit"];
                                                    }
                                                    ?> Rs</h5>
                            <p class="card-text small">Gained from this menu</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title"><?php
                                                    $rankQuery = "SELECT f.foodId,RANK() OVER (ORDER BY SUM(ord.orderBuyPrice*ord.orderAmount) DESC) AS rank FROM food f 
                                    INNER JOIN orderdetail ord ON f.foodId = ord.foodId WHERE f.canteenId = {$canteenId} GROUP BY f.foodId;";
                                                    $rankResult = $mysqli->query($rankQuery);
                                                    $exists = 0;
                                                    while ($rowRank = $rankResult->fetch_array()) {
                                                        if ($rowRank["foodId"] == $_GET["foodId"]) {
                                                            echo "#" . $rowRank["rank"];
                                                            $exists = 1;
                                                            break;
                                                        }
                                                    }
                                                    if ($exists == 0) {
                                                        echo "-";
                                                    }
                                                    ?></h5>
                            <p class="card-text small">Menu's ranking in the canteen</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>