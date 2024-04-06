<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        session_start(); 
        include("../connectionDB.php"); 
        include('../head.php');
        if($_SESSION["utype"]!="canteenOwner"){
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
    <title>Order Detail | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderCanteen.php')?>

    <?php
        $orderHeaderId = $_GET["orderHeaderId"];
        $orderHeaderQuery = "SELECT * FROM orderheader WHERE orderHeaderId = {$orderHeaderId}";
        $orderHeaderArray = $mysqli -> query($orderHeaderQuery) -> fetch_array();
    ?>

    <div class="container px-5 pt-4" id="cart-body">
        <div class="row my-4 border-bottom">
            <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
                <i class="bi bi-arrow-left-square me-2"></i>Go back
            </a>
            <h2 class="pt-3 display-5">Order #<?php echo $orderHeaderArray["$orderHeaderReferenceCode"];?></h2>

            <div class="row row-cols-1 row-cols-md-2">
                <div class="col mb-2 mb-md-0">
                    <ul class="list-unstyled fw-light">
                        <li class="list-item mb-2">
                            <?php if($orderHeaderArray["orderHeaderOrderStatus"]=="ACPT"){ ?>
                            <h5>Current Status: <span class="fw-bold badge bg-info text-dark">Accepted</span></h5>
                            <?php }else if($orderHeaderArray["orderHeaderOrderStatus"]=="PREP"){ ?>
                            <h5>Current Status: <span class="fw-bold badge bg-warning text-dark">Preparing</span></h5>
                            <?php }else if($orderHeaderArray["orderHeaderOrderStatus"]=="RDPK"){ ?>
                            <h5>Current Status: <span class="fw-bold badge bg-primary text-white">Ready to pick up</span></h5>
                            <?php }else if($orderHeaderArray["orderHeaderOrderStatus"]=="FNSH"){?>
                            <h5>Current Status: <span class="fw-bold badge bg-success text-white">Completed</span></h5>
                            <?php } ?>
                        </li>
                        <li class="list-item">Order of
                            <?php
                                $customerQuery = "SELECT customerFirstName,customerLastName,customerType FROM customer WHERE customerId = {$orderHeaderArray['customerId']};";
                                $customerArray = $mysqli -> query($customerQuery) -> fetch_array();
                                switch($customerArray["customerType"]){
                                    case "customer": $customerType = "customer"; break;
                                    default: $customerType = "customer";
                                }
                                echo "{$customerArray['customerFirstName']} {$customerArray['customerLastName']} ({$customerType})";
                            ?>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <ul class="list-unstyled fw-light">
                        <?php 
                            $orderPlaceDate = (new Datetime($orderHeaderArray["orderHeaderOrderTime"])) -> format("F j, Y H:i"); 
                            $orderPickupDate = (new Datetime($orderHeaderArray["orderHeaderPickupTime"])) -> format("F j, Y H:i");
                        ?>
                        <li>Placed on <?php echo $orderPlaceDate;?></li>
                        <li>Pick-up time is <?php echo $orderPickupDate;?></li>
                        <?php if($orderHeaderArray["orderHeaderOrderStatus"]!="FNSH"){ ?>
                        <li>The order is not finish yet.</li>
                        <?php }else{
                            $orderFinishTime = (new Datetime($orderHeaderArray["orderHeaderFinishedTime"])) -> format("F j, Y H:i");
                        ?>
                        <li>Finished on <?php echo $orderFinishTime;?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                <?php if($orderHeaderArray["orderHeaderOrderStatus"]!='FNSH'){ ?>
                <a class="btn btn-sm btn-warning mt-2 mt-md-0" href="canteenOrderForward.php?orderHeaderId=<?php echo $_GET["orderHeaderId"]?>&currentStage=1">Mark as Preparing</a>
                <a class="btn btn-sm btn-primary mt-2 mt-md-0" href="canteenOrderForward.php?orderHeaderId=<?php echo $_GET["orderHeaderId"]?>&currentStage=2">Mark as Ready for pick-up</a>
                <a class="btn btn-sm btn-success mt-2 mt-md-0" href="canteenOrderForward.php?orderHeaderId=<?php echo $_GET["orderHeaderId"]?>&currentStage=3">Mark as Finished</a>
                <?php }?>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col">
                <div class="row row-cols-1">
                    <div class="col">
                        <h5 class="fw-light">Menu</h5>
                    </div>
                    <div class="col row row-cols-1 row-cols-md-2 border-bottom">
                        <?php 
                            $orderQuery = "SELECT f.foodId,f.foodName,f.foodPic,ord.orderAmount,ord.orderBuyPrice,orderNote FROM orderdetail ord INNER JOIN food f ON ord.foodId = f.foodId WHERE ord.orderHeaderId = {$orderHeaderId}";
                            $orderResult = $mysqli -> query($orderQuery);
                            while($orderRow = $orderResult -> fetch_array()){
                        ?>
                        <div class="col">
                            <ul class="list-group">
                                    <li
                                        class="list-group-item d-flex border-0 pb-3 border-bottom w-100 justify-content-between align-items-center">
                                        <div class="image-parent">
                                            <img <?php
                                            if(is_null($orderRow["foodPic"])){echo "src='../images/canteenLogo.png'";}
                                            else{echo "src=\"../images/{$orderRow['foodPic']}\"";}
                                        ?> class="img-fluid rounded"
                                                style="width: 100px; height:100px; object-fit:cover;"
                                                alt="<?php echo $orderRow["foodName"]?>">
                                        </div>
                                        <div class="ms-3 me-auto">
                                            <div class="fw-normal"><span class="h5"><?php echo $orderRow["orderAmount"]?>x
                                                </span><?php echo $orderRow["foodName"]?>
                                                <p><?php printf("%.2f Rs <small class='text-muted'>(%.2f Rs each)</small>",$orderRow["orderBuyPrice"]*$orderRow["orderAmount"],$orderRow["orderBuyPrice"]);?><br />
                                                    <span
                                                        class="text-muted small"><?php echo $orderRow["orderNote"]?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                            </ul>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col my-3">
                        <ul class="list-inline justify-content-between">
                            <li class="list-inline-item fw-light me-5">Grand Total</li>
                            <li class="list-inline-item fw-bold h4">
                                <?php
                                    $grandTotalQuery = "SELECT SUM(orderAmount*orderBuyPrice) AS gt FROM orderdetail WHERE orderHeaderId = {$orderHeaderId}";
                                    $grandTotalArray = $mysqli -> query($grandTotalQuery) -> fetch_array();
                                    printf("%.2f Rs",$grandTotalArray["gt"]);
                                ?>
                            </li>
                            <li class="list-item fw-light small">Pay by
                                <?php 
                                    $paymentQuery = "SELECT paymentType,paymentDetail FROM payment WHERE paymentId = {$orderHeaderArray['paymentId']} LIMIT 0,1;";
                                    $paymentArray = $mysqli -> query($paymentQuery) -> fetch_array();
                                    switch($paymentArray["paymentType"]){
                                        case "CRDC": echo "Credit Card"; break;
                                        case "DBTC": echo "Debit Card"; break;
                                        case "PPDC": echo "Prepaid Card"; break;
                                        case "PMTP": echo "Promptpay QR Code"; break;
                                        case "TMNY": echo "TrueMoney"; break;
                                        case "PYPL": echo "Paypal"; break;
                                        default: echo "Default Payment Channel";
                                    }
                                    echo " ".$paymentArray["paymentDetail"];
                                ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>