<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        session_start(); 
        include("../connectionDB.php"); 
        include('../head.php');
        if($_SESSION["utype"]!="admin"){
            header("location: ../restricted.php");
            exit(1);
        }
        include("../canteen/rangeFunction.php");
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/menu.css" rel="stylesheet">
    <link href="../css/login.css" rel="stylesheet">
    <script type="text/javascript" src="../js/revenueDateSelection.js"></script>
    <title>Canteen Revenue Report | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100 bg-white">
    <?php include('navHeaderAdmin.php')?>

    <?php
        $canteenId = $_GET["canteenId"];
        $query = "SELECT canteenName,canteenLocation,canteenOpenHour,canteenCloseHour,canteenStatus,canteenPreOrderStatus,canteenContactNo,canteenPic
        FROM Canteen WHERE canteenId = {$canteenId} LIMIT 0,1";
        $result = $mysqli -> query($query);
        $canteenRow = $result -> fetch_array();
    ?>

    <div class="container px-5 py-4" id="Canteen-body">
        <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <div class="container row row-cols-6 row-cols-md-12 g-5 pt-4 mb-4" id="Canteen-header">
            <div class="rounded-25 col-6 col-md-4" id="canteenImage" style="
                    background: url(
                        <?php
                            if(is_null($canteenRow["canteenPic"])){echo "'../images/icon.png'";}
                            else{echo "'../images/{$canteenRow['canteenPic']}'";}
                        ?> 
                    ) center; height: 225px;
                    background-size: cover; background-repeat: no-repeat;
                    background-position: center;">
            </div>
            <div class="col-6 col-md-8">
                <h1 class="display-5 strong"><?php echo $canteenRow["canteenName"];?></h1>
                <ul class="list-unstyled">
                    <li class="my-2">
                        <?php 
                            $now = date('H:i:s');
                            if((($now < $canteenRow["canteenOpenHour"])||($now > $canteenRow["canteenCloseHour"]))||($canteenRow["canteenStatus"]==0)){
                        ?>
                        <span class="badge rounded-pill bg-danger">Closed</span>
                        <?php }else{ ?>
                        <span class="badge rounded-pill bg-success">Open</span>
                        <?php }
                            if($canteenRow["canteenPreOrderStatus"]==1){
                        ?>
                        <span class="badge rounded-pill bg-success">Pre-order avaliable</span>
                        <?php }else{ ?>
                        <span class="badge rounded-pill bg-danger">Pre-order Unavaliable</span>
                        <?php } ?>
                    </li>
                    <li class=""><?php echo $canteenRow["canteenLocation"];?></li>
                    <li class="">Open hours:
                        <?php 
                            $open = explode(":",$canteenRow["canteenOpenHour"]);
                            $close = explode(":",$canteenRow["canteenCloseHour"]);
                            echo $open[0].":".$open[1]." - ".$close[0].":".$close[1];
                        ?>
                    </li>
                    <li class="">Telephone number: <?php echo "(+66) ".$canteenRow["canteenContactNo"];?></li>
                </ul>
                <a class="btn btn-sm btn-outline-secondary" href="adminCanteenPassword.php?canteenId=<?php echo $canteenId?>">
                    Change password
                </a>
                <a class="btn btn-sm btn-primary mt-2 mt-md-0" href="adminCanteenEdit.php?canteenId=<?php echo $canteenId?>">
                    Update Canteen profile
                </a>
                <a class="btn btn-sm btn-danger mt-2 mt-md-0" href="adminCanteenDelete.php?canteenId=<?php echo $canteenId?>">
                    Delete this Canteen
                </a>
            </div>
        </div>

        <?php
            // Revenue Summary Preparation Part
            // 1: Indicate Range
            // revenueMode: 1 Today / 2 Yesterday / 3 This Week / 4 Monthly / 5 Specific Period
            $canteenId = $_GET["canteenId"];
            $revenueMode = $_GET["revenueMode"];
            $today = date("Y-m-d");
            $yesterday = (new Datetime()) -> sub(new DateInterval("P1D")) -> format('Y-m-d');
            $weekRange = rangeWeek(date('Y-n-j'));
            $monthrange = rangeMonth(date('Y-n-j'));
            switch($revenueMode){
                case 1: $startDate = $today; 
                        $endDate = $today; 
                        break;
                case 2: $startDate = $yesterday; 
                        $endDate = $yesterday; 
                        break;
                case 3: $startDate = (new Datetime($weekRange["start"])) -> format('Y-m-d');
                        $endDate = (new Datetime($weekRange["end"])) -> format('Y-m-d');
                        break;
                case 4: $startDate = (new Datetime($monthrange["start"])) -> format('Y-m-d');
                        $endDate = (new Datetime($monthrange["end"])) -> format('Y-m-d');
                        break;
                case 5: 
                        if(isset($_GET["startDate"])&&(isset($_GET["endDate"]))){
                            $startDate = $_GET["startDate"];
                            $endDate = $_GET["endDate"];
                        }else{
                            header("location: canteenReportSelect.php"); exit(1);
                        }
                        break;
                default: header("location: canteenReportSelect.php"); exit(1);
            }
            $formattedStart = (new Datetime($startDate)) -> format('F j, Y');
            $formattedEnd = (new Datetime($endDate)) -> format('F j, Y');
        ?>

        <div class="container">
            <h3 class="border-top pt-3 my-2">
                <a class="text-decoration-none link-secondary"
                    href="adminCanteenDetail.php?canteenId=<?php echo $canteenId?>">Menus</a>
                <span class="text-secondary">/</span>
                <a class="nav-item text-decoration-none link-secondary" href="adminCanteenOrder.php?canteenId=<?php echo $canteenId?>">Orders</a></span>
                <span class="text-secondary">/</span>
                <a class="nav-item text-decoration-none link-success"
                    href="aadminCanteenRevenue.php?canteenId=<?php echo $canteenId?>">Revenue</a></span>
            </h3>

            <a class="nav nav-item text-decoration-none text-muted my-3" href="#" onclick="history.back();">
                <i class="bi bi-arrow-left-square me-2"></i>Go back
            </a>
            <h3 class="display-6">Revenue Report</h3>
            <h4 class="fw-light">
                <?php 
                if($formattedStart==$formattedEnd){
                    echo "Of {$formattedStart}";
                }else{
                    echo "From {$formattedStart} to {$formattedEnd}";
                }
                $foodId =1;
            ?>
            </h4>
            <p class="fw-light">Generated on <?php echo date("F j, Y H:i")?>. This report only includes finished orders.
            </p>

            <h4 class="border-top fw-light pt-3 pb-2 mt-2">Overall Performance</h4>
            <div class="row row-cols-2 row-cols-md-4 mb-3 g-2">
                <div class="col">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                    $query = "SELECT SUM(ord.orderAmount*ord.orderBuyPrice) AS rev FROM orderheader orh INNER JOIN orderdetail ord ON orh.orderHeaderId = ord.orderHeaderId
                                    WHERE orh.canteenId = {$canteenId} AND orderHeaderOrderStatus = 'FNSH' AND (DATE(orderHeaderFinishedTime) BETWEEN DATE('{$startDate}') AND DATE('{$endDate}'));";
                                    $result = $mysqli -> query($query) -> fetch_array();
                                    if(is_null($result["rev"])){$grandtotal = 0;} else{$grandtotal = $result["rev"];}
                                    printf("%.2f Rs",$grandtotal);
                                ?>
                            </h5>
                            <p class="card-text small">Total revenue</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                    $query = "SELECT COUNT(*) AS cnt FROM orderheader orh 
                                    WHERE orh.canteenId = {$canteenId} AND orderHeaderOrderStatus = 'FNSH' AND (DATE(orderHeaderFinishedTime) BETWEEN DATE('{$startDate}') AND DATE('{$endDate}'));";
                                    $result = $mysqli -> query($query) -> fetch_array();
                                    if(is_null($result["cnt"])){$numOrder = 0;} else{$numOrder = $result["cnt"];}
                                    printf("%d Orders",$numOrder);
                                ?>
                            </h5>
                            <p class="card-text small">Number of orders</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                    $query = "SELECT COUNT(DISTINCT orh.customerId) AS cnt FROM orderheader orh 
                                    WHERE orh.canteenId = {$canteenId} AND orderHeaderOrderStatus = 'FNSH' AND (DATE(orderHeaderFinishedTime) BETWEEN DATE('{$startDate}') AND DATE('{$endDate}'));";
                                    $result = $mysqli -> query($query) -> fetch_array();
                                    if(is_null($result["cnt"])){echo "0 Customers";} else{echo $result["cnt"]." Customers";}
                                ?>
                            </h5>
                            <p class="card-text small">Number of customers</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                    if($numOrder == 0){echo "0.00 Rs";}
                                    else{printf("%.2f Rs",$grandtotal/$numOrder);}
                                ?>
                            </h5>
                            <p class="card-text small">Averge cost per order</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                    $query = "SELECT SUM(ord.orderAmount) AS amt FROM orderheader orh INNER JOIN orderdetail ord ON orh.orderHeaderId = ord.orderHeaderId
                                    WHERE orh.canteenId = {$canteenId} AND orderHeaderOrderStatus = 'FNSH' AND (DATE(orderHeaderFinishedTime) BETWEEN DATE('{$startDate}') AND DATE('{$endDate}'));";
                                    $result = $mysqli -> query($query) -> fetch_array();
                                    if(is_null($result["amt"])){echo "0 plates";} else{echo $result["amt"]." plates";}
                                ?>
                            </h5>
                            <p class="card-text small">Number of plates sold</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                    $query = "SELECT f.foodName,SUM(ord.orderAmount) AS amt FROM orderheader orh INNER JOIN orderdetail ord ON orh.orderHeaderId = ord.orderHeaderId INNER JOIN food f ON ord.foodId = f.foodId
                                    WHERE orh.canteenId = {$canteenId} AND orderHeaderOrderStatus = 'FNSH' AND (DATE(orderHeaderFinishedTime) BETWEEN DATE('{$startDate}') AND DATE('{$endDate}')) ORDER BY amt DESC LIMIT 0,1;";
                                    $result = $mysqli -> query($query) -> fetch_array();
                                    if(is_null($result["foodName"])){echo "-";} else{echo $result["foodName"];}
                                ?>
                            </h5>
                            <p class="card-text small">Best-Seller Menu</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                    $query = "SELECT HOUR(orh_ordertime) AS odh,COUNT(orderHeaderId) AS cnt FROM orderheader orh
                                    WHERE canteenId = {$canteenId} AND orderHeaderOrderStatus = 'FNSH' AND (DATE(orderHeaderFinishedTime) BETWEEN DATE('{$startDate}') AND DATE('{$endDate}')) GROUP BY odh ORDER BY cnt DESC;";
                                    $result = $mysqli -> query($query);
                                    $num_rows = $result -> num_rows;
                                    if($num_rows == 0){echo "-";}
                                    else{$result = $result->fetch_array(); echo "{$result['odh']}:00 - {$result['odh']}:59";}
                                ?>
                            </h5>
                            <p class="card-text small">Peak Ordering Hour</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                    $query = "SELECT HOUR(orderHeaderFinishedTime) AS odh,COUNT(orderHeaderId) AS cnt FROM orderheader orh
                                    WHERE canteenId = {$canteenId} AND orderHeaderOrderStatus = 'FNSH' AND (DATE(orderHeaderFinishedTime) BETWEEN DATE('{$startDate}') AND DATE('{$endDate}')) GROUP BY odh ORDER BY cnt DESC;";
                                    $result = $mysqli -> query($query);
                                    $num_rows = $result -> num_rows;
                                    if($num_rows == 0){echo "-";}
                                    else{$result = $result->fetch_array(); echo "{$result['odh']}:00 - {$result['odh']}:59";}
                                ?>
                            </h5>
                            <p class="card-text small">Peak Pick-Up Hour</p>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="border-top fw-light pt-3 mt-2">Menu Performance</h4>
            <?php
                $query = "SELECT f.foodName,f.foodPrice,SUM(ord.orderAmount) AS amount,SUM(ord.orderAmount*ord.orderBuyPrice) AS subtotal FROM orderheader orh INNER JOIN orderdetail ord ON orh.orderHeaderId = ord.orderHeaderId INNER JOIN food f ON ord.foodId = f.foodId
                WHERE orh.canteenId = {$canteenId} AND orderHeaderOrderStatus = 'FNSH' AND (DATE(orderHeaderFinishedTime) BETWEEN DATE('{$startDate}') AND DATE('{$endDate}'))
                GROUP BY ord.foodId ORDER BY amount DESC;";
                $result = $mysqli -> query($query);
                $num_rows = $result -> num_rows;
                if($num_rows > 0){
            ?>
            <div class="table-responsive">
                <table class="table rounded-5 table-light table-striped table-hover align-middle caption-top mb-5">
                    <caption><?php echo $num_rows;?> Menus</caption>
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">Rank</th>
                            <th scope="col">Menu name</th>
                            <th scope="col">Current Price</th>
                            <th scope="col">Amount Sold</th>
                            <th scope="col">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; while($row = $result -> fetch_array()){ ?>
                        <tr>
                            <th><?php echo $i++;?></th>
                            <td><?php echo $row["foodName"]?></td>
                            <td><?php echo $row["foodPrice"]." Rs"?></td>
                            <td><?php echo $row["amount"]." plates"?></td>
                            <td><?php echo $row["subtotal"]." Rs"?></td>
                        </tr>
                        <?php } ?>
                        <tr class="fw-bold table-info">
                            <td colspan="4" class="text-end">Grand Total</td>
                            <td><?php printf("%.2f Rs",$grandtotal);?></td>
                        </tr>
                    </tbody>
                </table>
                <?php }else{ ?>
                <p class="fw-light">No records.</p>
                <?php } ?>

            </div>
        </div>
    </div>
</body>

</html>