<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        session_start(); 
        if($_SESSION["utype"]!="canteenOwner"){
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
    <title>Customer Order List | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderCanteen.php'); ?>

    <div class="container px-5 pt-4" id="canteen-body">
        <a class="pt-4 nav nav-item text-decoration-none text-muted mb-3" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>

        <?php
            if(isset($_GET["updateorders"])){
                if($_GET["updateorders"]==1){
                    ?>
            <!-- START SUCCESSFULLY UPDATE ORDER -->
            <div class="row row-cols-1 notibar">
                <div class="col ms-2 p-2 bg-success text-white rounded text-start">
                    <span class="ms-2 mt-2">Successfully updated order status.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="canteenOrderList.php">X</a></span>
                </div>
            </div>
            <!-- END SUCCESSFULLY UPDATE ORDER -->
            <?php }else{ ?>
            <!-- START FAILED UPDATE ORDER -->
            <div class="row row-cols-1 notibar">
                <div class="col ms-2 p-2 bg-danger text-white rounded text-start">
                    <span class="ms-2 mt-2">Failed to update order status.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="canteenOrderList.php">X</a></span>
                </div>
            </div>
            <!-- END FAILED UPDATE ORDER -->
            <?php }
                }
            ?>

        <div class="my-3 text-wrap" id="canteen-header">
            <h2 class="display-6 fw-light">Customer Order</h2>
        </div>

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active px-4" id="acpt-tab" data-bs-toggle="tab" data-bs-target="#nav-acpt"
                    type="button" role="tab" aria-controls="nav-acpt"
                    aria-selected="true">ACPT | Accepted</button>
                <button class="nav-link px-4" id="prep-tab" data-bs-toggle="tab" data-bs-target="#nav-prep"
                    type="button" role="tab" aria-controls="nav-prep"
                    aria-selected="true">PREP | Preparing</button>
                <button class="nav-link px-4" id="rdpk-tab" data-bs-toggle="tab" data-bs-target="#nav-rdpk"
                    type="button" role="tab" aria-controls="nav-rdpk"
                    aria-selected="true">RDPK | Wait for pick-up</button>
                <button class="nav-link px-4" id="fnsh-tab" data-bs-toggle="tab" data-bs-target="#nav-fnsh"
                    type="button" role="tab" aria-controls="nav-fnsh" aria-selected="false">FNSH | Finished</button>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            <!-- ONGOING ORDER TAB -->
            <div class="tab-pane fade show active p-3" id="nav-acpt" role="tabpanel" aria-labelledby="acpt-tab">
                <?php 
                $acceptQuery = "SELECT * FROM orderheader WHERE canteenId = {$canteenId} AND orderHeaderOrderStatus = 'ACPT' ORDER BY orderHeaderPickupTime ASC;";
                $acceptResult = $mysqli -> query($acceptQuery);
                $acceptNum = $acceptResult -> num_rows;
                if($acceptNum>0){
                ?>
                <div class="row row-cols-1 row-cols-md-3">
                    <!-- START EACH ORDER DETAIL -->
                    <?php while($ogRow = $acceptResult -> fetch_array()){ ?>
                    <div class="col">
                        <a href="canteenOrderDetail.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"]?>"
                            class="text-dark text-decoration-none">
                            <div class="card mb-3">
                                <div class="card-header bg-info text-dark justify-content-between">
                                    <small class="me-auto d-flex" style="font-weight: 500;">Accepted order</small>
                                </div>
                                <div class="card-body">
                                    <div class="card-text row row-cols-1">
                                        <small>
                                        <div class="col">Order #<?php echo $ogRow["orderHeaderReferenceCode"];?></div>
                                        <div class="col">Name: 
                                            <?php
                                            $customerQuery = "SELECT customerFirstName,customerLastName,customerType FROM customer WHERE customerId = {$ogRow['customerId']};";
                                            $customerArray = $mysqli -> query($customerQuery) -> fetch_array();
                                            switch($customerArray["customerType"]){
                                                case "cust": $customerType = "customer"; break;
                                                default: $customerType = "Customer";
                                               
                                            }
                                            echo "{$customerArray['customerFirstName']} {$customerArray['customerLastName']} ({$customerType})";
                                        ?>
                                        </div>
                                        <div class="col mb-2">Pick-up time: 
                                            <?php 
                                            $orderTime = (new Datetime($ogRow["orderHeaderOrderTime"])) -> format("F j, Y H:i");
                                            echo $orderTime;
                                            ?>
                                        </div>
                                        <?php 
                                        $orderQuery = "SELECT COUNT(*) AS count,SUM(orderAmount*orderBuyPrice) AS gt FROM orderDetail WHERE orderHeaderId = {$ogRow['orderHeaderId']}";
                                        $orderArray = $mysqli -> query($orderQuery) -> fetch_array();
                                    ?>
                                        <div class="col pt-2 mb-2 border-top"><?php echo $orderArray["count"]?> menus | <?php echo $orderArray["gt"]?> Rs</div>
                                        </small>
                                        <div class="col">
                                            <ul class="list-unstyled">
                                            <?php
                                                $detailQuery = "SELECT f.foodName,ord.orderAmount,ord.orderNote FROM orderDetail ord INNER JOIN food f ON ord.foodId = f.foodId WHERE ord.orderHeaderId = {$ogRow['orderHeaderId']}";
                                                $detailresult = $mysqli -> query($detailQuery);
                                                while($detailRow = $detailresult -> fetch_array()){
                                            ?>
                                            <li><strong class="h5"><?php echo $detailRow["orderAmount"]?>x</strong> <?php echo $detailRow["foodName"]; if($detailRow["orderNote"]!=""){echo " ({$detailRow['orderNote']})";}?></li>
                                            <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="col text-end">
                                            <a href="canteenOrderDetail.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"]?>" class="btn btn-sm btn-outline-secondary">More Detail</a>
                                            <a href="canteenOrderForward.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"]?>&currentStage=1" class="btn btn-sm btn-success">Mark as Preparing</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                    <!-- END EACH ORDER DETAIL -->
                </div>
                <?php }else{ ?>
                <!-- IN CASE NO ORDER -->
                <div class="row row-cols-1">
                    <div class="col pt-3 px-3 bg-danger text-white rounded text-center">
                        <p class="ms-2 mt-2">No order found.</p>
                    </div>
                </div>
                <!-- END CASE NO ORDER -->
                <?php } ?>
            </div>

            <div class="tab-pane fade p-3" id="nav-prep" role="tabpanel" aria-labelledby="prep-tab">
                <?php 
                $acceptQuery = "SELECT * FROM orderheader WHERE canteenId = {$canteenId} AND orderHeaderOrderStatus = 'PREP' ORDER BY orderHeaderPickupTime ASC;";
                $acceptResult = $mysqli -> query($acceptQuery);
                $acceptNum = $acceptResult -> num_rows;
                if($acceptNum>0){
                ?>
                <div class="row row-cols-1 row-cols-md-3">
                    <!-- START EACH ORDER DETAIL -->
                    <?php while($ogRow = $acceptResult -> fetch_array()){ ?>
                    <div class="col">
                        <a href="canteenOrderDetail.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"]?>"
                            class="text-dark text-decoration-none">
                            <div class="card mb-3">
                                <div class="card-header bg-warning justify-content-between">
                                    <small class="me-auto d-flex" style="font-weight: 500;">Preparing order</small>
                                </div>
                                <div class="card-body">
                                    <div class="card-text row row-cols-1">
                                        <small>
                                        <div class="col">Order #<?php echo $ogRow["orderHeaderReferenceCode"];?></div>
                                        <div class="col">Name: 
                                            <?php
                                            $customerQuery = "SELECT customerFirstName,customerLastName,customerType FROM customer WHERE customerId = {$ogRow['customerId']};";
                                            $customerArray = $mysqli -> query($customerQuery) -> fetch_array();
                                            switch($customerArray["customerType"]){
                                                case "cust": $customerType = "Customer"; break;
                                                default: $customerType = "Customer";
                                            }
                                            echo "{$customerArray['customerFirstName']} {$customerArray['customerLastName']} ({$customerType})";
                                        ?>
                                        </div>
                                        <div class="col mb-2">Pick-up time: 
                                            <?php 
                                            $orderTime = (new Datetime($ogRow["orderHeaderOrderTime"])) -> format("F j, Y H:i");
                                            echo $orderTime;
                                            ?>
                                        </div>
                                        <?php 
                                        $orderQuery = "SELECT COUNT(*) AS count,SUM(orderAmount*orderBuyPrice) AS gt FROM orderDetail WHERE orderHeaderId = {$ogRow['orderHeaderId']}";
                                        $orderArray = $mysqli -> query($orderQuery) -> fetch_array();
                                    ?>
                                        <div class="col pt-2 mb-2 border-top"><?php echo $orderArray["count"]?> menus | <?php echo $orderArray["gt"]?> Rs</div>
                                        </small>
                                        <div class="col">
                                            <ul class="list-unstyled">
                                            <?php
                                                $detailQuery = "SELECT f.foodName,ord.orderAmount,ord.orderNote FROM orderDetail ord INNER JOIN food f ON ord.foodId = f.foodId WHERE ord.orderHeaderId = {$ogRow['orderHeaderId']}";
                                                $detailresult = $mysqli -> query($detailQuery);
                                                while($detailRow = $detailresult -> fetch_array()){
                                            ?>
                                            <li><strong class="h5"><?php echo $detailRow["orderAmount"]?>x</strong> <?php echo $detailRow["foodName"]; if($detailRow["orderNote"]!=""){echo " ({$detailRow['orderNote']})";}?></li>
                                            <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="col text-end">
                                            <a href="canteenOrderDetail.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"]?>" class="btn btn-sm btn-outline-secondary">More Detail</a>
                                            <a href="canteenOrderForward.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"]?>&currentStage=2" class="btn btn-sm btn-success">Mark as Ready</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                    <!-- END EACH ORDER DETAIL -->
                </div>
                <?php }else{ ?>
                <!-- IN CASE NO ORDER -->
                <div class="row row-cols-1">
                    <div class="col pt-3 px-3 bg-danger text-white rounded text-center">
                        <p class="ms-2 mt-2">No order found.</p>
                    </div>
                </div>
                <!-- END CASE NO ORDER -->
                <?php } ?>
            </div>

            <div class="tab-pane fade p-3" id="nav-rdpk" role="tabpanel" aria-labelledby="rdpk-tab">
                <?php 
                $acceptQuery = "SELECT * FROM orderheader WHERE canteenId = {$canteenId} AND orderHeaderOrderStatus = 'RDPK' ORDER BY orderHeaderPickupTime ASC;";
                $acceptResult = $mysqli -> query($acceptQuery);
                $acceptNum = $acceptResult -> num_rows;
                if($acceptNum>0){
                ?>
                <div class="row row-cols-1 row-cols-md-3">
                    <!-- START EACH ORDER DETAIL -->
                    <?php while($ogRow = $acceptResult -> fetch_array()){ ?>
                    <div class="col">
                        <a href="canteenOrderDetail.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"]?>"
                            class="text-dark text-decoration-none">
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white justify-content-between">
                                    <small class="me-auto d-flex" style="font-weight: 500;">Ready to pick up</small>
                                </div>
                                <div class="card-body">
                                    <div class="card-text row row-cols-1">
                                        <small>
                                        <div class="col">Order #<?php echo $ogRow["orderHeaderReferenceCode"];?></div>
                                        <div class="col">Name: 
                                            <?php
                                            $customerQuery = "SELECT customerFirstName,customerLastName,customerType FROM customer WHERE customerId = {$ogRow['customerId']};";
                                            $customerArray = $mysqli -> query($customerQuery) -> fetch_array();
                                            switch($customerArray["customerType"]){
                                                case "cust": $customerType = "customer"; break;
                                                default: $customerType = "customer";
                                            }
                                            echo "{$customerArray['customerFirstName']} {$customerArray['customerLastName']} ({$customerType})";
                                        ?>
                                        </div>
                                        <div class="col mb-2">Pick-up time: 
                                            <?php 
                                            $orderTime = (new Datetime($ogRow["orderHeaderOrderTime"])) -> format("F j, Y H:i");
                                            echo $orderTime;
                                            ?>
                                        </div>
                                        <?php 
                                        $orderQuery = "SELECT COUNT(*) AS count,SUM(orderAmount*orderBuyPrice) AS gt FROM orderDetail WHERE orderHeaderId = {$ogRow['orderHeaderId']}";
                                        $orderArray = $mysqli -> query($orderQuery) -> fetch_array();
                                    ?>
                                        <div class="col pt-2 mb-2 border-top"><?php echo $orderArray["count"]?> menus | <?php echo $orderArray["gt"]?> Rs</div>
                                        </small>
                                        <div class="col">
                                            <ul class="list-unstyled">
                                            <?php
                                                $detailQuery = "SELECT f.foodName,ord.orderAmount,ord.orderNote FROM orderDetail ord INNER JOIN food f ON ord.foodId = f.foodId WHERE ord.orderHeaderId = {$ogRow['orderHeaderId']}";
                                                $detailresult = $mysqli -> query($detailQuery);
                                                while($detailRow = $detailresult -> fetch_array()){
                                            ?>
                                            <li><strong class="h5"><?php echo $detailRow["orderAmount"]?>x</strong> <?php echo $detailRow["foodName"]; if($detailRow["orderNote"]!=""){echo " ({$detailRow['orderNote']})";}?></li>
                                            <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="col text-end">
                                            <a href="canteenOrderDetail.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"]?>" class="btn btn-sm btn-outline-secondary">More Detail</a>
                                            <a href="canteenOrderForward.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"]?>&currentStage=3" class="btn btn-sm btn-success">Mark as Finish</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                    <!-- END EACH ORDER DETAIL -->
                </div>
                <?php }else{ ?>
                <!-- IN CASE NO ORDER -->
                <div class="row row-cols-1">
                    <div class="col pt-3 px-3 bg-danger text-white rounded text-center">
                        <p class="ms-2 mt-2">No order found.</p>
                    </div>
                </div>
                <!-- END CASE NO ORDER -->
                <?php } ?>
            </div>

            <div class="tab-pane fade p-3" id="nav-fnsh" role="tabpanel" aria-labelledby="fnsh-tab">
                <?php 
                $acceptQuery = "SELECT * FROM orderheader WHERE canteenId = {$canteenId} AND orderHeaderOrderStatus = 'FNSH' ORDER BY orderHeaderFInishedTime DESC;";
                $acceptResult = $mysqli -> query($acceptQuery);
                $acceptNum = $acceptResult -> num_rows;
                if($acceptNum>0){
                ?>
                <div class="row row-cols-1 row-cols-md-3">
                    <!-- START EACH ORDER DETAIL -->
                    <?php while($ogRow = $acceptResult -> fetch_array()){ ?>
                    <div class="col">
                        <a href="canteenOrderDetail.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"]?>"
                            class="text-dark text-decoration-none">
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white justify-content-between">
                                    <small class="me-auto d-flex" style="font-weight: 500;">Order Finished</small>
                                </div>
                                <div class="card-body">
                                    <div class="card-text row row-cols-1">
                                        <small>
                                        <div class="col">Order #<?php echo $ogRow["orderHeaderReferenceCode"];?></div>
                                        <div class="col">Name: 
                                            <?php
                                            $customerQuery = "SELECT customerFirstName,customerLastName,customerType FROM customer WHERE customerId = {$ogRow['customerId']};";
                                            $customerArray = $mysqli -> query($customerQuery) -> fetch_array();
                                            switch($customerArray["customerType"]){
                                                case "cust": $customerType = "customer"; break;
                                                default: $customerType = "customer";
                                            }
                                            echo "{$customerArray['customerFirstName']} {$customerArray['customerLastName']} ({$customerType})";
                                        ?>
                                        </div>
                                        <div class="col mb-2">Finished on  
                                            <?php 
                                            $orderTime = (new Datetime($ogRow["orderHeaderFInishedTime"])) -> format("F j, Y H:i");
                                            echo $orderTime;
                                            ?>
                                        </div>
                                        <?php 
                                        $orderQuery = "SELECT COUNT(*) AS count,SUM(orderAmount*orderBuyPrice) AS gt FROM orderDetail WHERE orderHeaderId = {$ogRow['orderHeaderId']}";
                                        $orderArray = $mysqli -> query($orderQuery) -> fetch_array();
                                    ?>
                                        <div class="col pt-2 mb-2 border-top"><?php echo $orderArray["count"]?> menus | <?php echo $orderArray["gt"]?> Rs</div>
                                        </small>
                                        <div class="col">
                                            <ul class="list-unstyled">
                                            <?php
                                                $detailQuery = "SELECT f.foodName,ord.orderAmount,ord.orderNote FROM orderDetail ord INNER JOIN food f ON ord.foodId = f.foodId WHERE ord.orderHeaderId = {$ogRow['orderHeaderId']}";
                                                $detailresult = $mysqli -> query($detailQuery);
                                                while($detailRow = $detailresult -> fetch_array()){
                                            ?>
                                            <li><strong class="h5"><?php echo $detailRow["orderAmount"]?>x</strong> <?php echo $detailRow["foodName"]; if($detailRow["orderNote"]!=""){echo " ({$detailRow['orderNote']})";}?></li>
                                            <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="col text-end">
                                            <a href="canteenOrderDetail.php?orderHeaderId=<?php echo $ogRow["orderHeaderId"]?>" class="btn btn-sm btn-outline-secondary">More Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                    <!-- END EACH ORDER DETAIL -->
                </div>
                <?php }else{ ?>
                <!-- IN CASE NO ORDER -->
                <div class="row row-cols-1">
                    <div class="col pt-3 px-3 bg-danger text-white rounded text-center">
                        <p class="ms-2 mt-2">No order found.</p>
                    </div>
                </div>
                <!-- END CASE NO ORDER -->
                <?php } ?>
            </div>

        </div>
    </div>
</body>

</html>