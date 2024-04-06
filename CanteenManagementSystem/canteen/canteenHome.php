<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    if ($_SESSION["utype"] != "canteenOwner") {
        header("location: ../restricted.php");
        exit(1);
    }
    include("../connectionDB.php");
    include('../head.php');
    $canteenId = $_SESSION["canteenId"];
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <title>Canteen Owner Home | SOMAIYA</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderCanteen.php'); ?>

    <div class="d-flex text-center text-white promo-banner-bg py-3">
        <div class="p-lg-2 mx-auto my-3">
            <h1 class="display-5 fw-normal"><?php echo $_SESSION["canteenName"] ?></h1>
            <p class="lead fw-normal">Somaiya COllege Campus Canteen</p>
        </div>
    </div>

    <div class="container p-5" id="canteen-dashboard">
        <h2 class="border-bottom pb-2"><i class="bi bi-graph-up"></i> Canteen Dashboard <span class="small fw-light">
                <?php echo date("Y/m/d"); ?>
            </span></h2>

        <!-- canteen OWNER GRID DASHBOARD -->
        <div class="row row-cols-1 row-cols-lg-2 align-items-stretch g-4 py-3">
            <!-- TODAY ORDER GRID -->
            <div class="col">
                <div class="card rounded-5 border-secondary p-2">
                    <div class="card-body">
                        <p class="card-title">
                            &nbsp;&nbsp;Today Completed Order
                        </p>
                        <p class="card-text my-2">
                            <span class="display-5">
                                <?php
                                $query = "SELECT COUNT(*) AS countOrder FROM orderheader WHERE canteenId = {$canteenId} AND DATE(orderHeaderPickupTime) = CURDATE() AND orderHeaderOrderStatus = 'Finish';";
                                $result = $mysqli->query($query)->fetch_array();
                                echo $result["countOrder"];
                                ?>
                                orders
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <!-- END TODAY ORDER GRID -->
            <!-- TODAY REVENUE GRID -->
            <div class="col">
                <div class="card rounded-5 border-secondary p-2">
                    <div class="card-body">
                        <p class="card-title">
                            &nbsp;&nbsp;Today Revenue
                        </p>
                        <p class="card-text my-2">
                            <span class="display-5">
                                <?php
                                $query = "SELECT SUM(ord.orderBuyPrice*orderAmount) AS revenue FROM orderheader orh INNER JOIN orderdetail ord ON orh.orderId = ord.orderId
                                        WHERE orh.canteenId = {$canteenId} AND DATE(orh.orderHeaderPickupTime) = CURDATE() AND orh.orderHeaderOrderStatus = 'Finish';";
                                $result = $mysqli->query($query);
                                if (!is_null($result["revenue"])) {
                                    echo $result["revenue"];
                                } else {
                                    echo "0.00";
                                }
                                ?>
                                Rs
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <!-- END TODAY REVENUE GRID -->

            <!-- GRID OF ORDER NEEDED TO BE COMPLETE -->
            <div class="col">
                <a href="canteenOrderList.php" class="text-decoration-none text-dark">
                    <div class="card rounded-5 border p-2">
                        <div class="card-body">
                            <h5 class="card-title fw-light">
                                Remaining Order
                            </h5>
                            <p class="card-text my-2">
                                <span class="h6">
                                    <?php
                                    $query = "SELECT COUNT(*) AS countRemain FROM orderheader WHERE canteenId = {$canteenId} AND orderHeaderOrderStatus NOT LIKE 'Finish';";
                                    $result = $mysqli->query($query)->fetch_array();
                                    echo $result["countRemain"];
                                    ?>
                                </span>
                                orders left to be finished
                            </p>
                            <div class="text-end">
                                <a href="canteenOrderList.php" class="btn btn-sm btn-outline-dark">Go to Order List</a>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- END GRID OF ORDER NEEDED TO BE COMPLETE -->

            <!-- GRID OF ORDER NEEDED TO BE COMPLETE -->
            <div class="col">
                <a href="canteenMenuList.php" class="text-decoration-none text-dark">
                    <div class="card rounded-5 border p-2">
                        <div class="card-body">
                            <h5 class="card-title fw-light">
                                Food Menu
                            </h5>
                            <p class="card-text my-2">
                                <span class="h6">
                                    <?php
                                    $query = "SELECT COUNT(*) AS countMenu FROM food f INNER JOIN canteen c ON f.canteenId = c.canteenId 
                                    WHERE (c.canteenStatus = 1 AND (CURTIME() BETWEEN c.canteenOpenHour AND c.canteenCloseHour) AND f.foodTodayAvailable = 1) OR (c.canteenPreOrderStatus = 1 AND f.foodPreOrderAvailable = 1) AND c.canteenId = {$canteenId};";
                                    $result = $mysqli->query($query)->fetch_array();
                                    echo $result["countMenu"];
                                    ?>
                                </span>
                                Menus available to order
                            </p>
                            <div class="text-end">
                                <a href="canteenMenuList.php" class="btn btn-sm btn-outline-dark">Go to Menu List</a>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- END GRID OF ORDER NEEDED TO BE COMPLETE -->
        </div>
        <!-- END ADMIN GRID DASHBOARD -->
    </div>
</body>

</html>