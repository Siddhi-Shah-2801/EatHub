<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        session_start(); 
        include("../connectionDB.php"); 
        if($_SESSION["utype"]!="admin"){
            header("location: ../restricted.php");
            exit(1);
        }
        if(isset($_POST["updateConfirm"])){
            $orderHeaderId = $_POST["orderHeaderId"];
            $status = $_POST["os"];
            if($status == 'FNSH'){ $finishDate = date('Y-m-d\TH:i:s'); }else{$finishDate = "NULL";}
            $query = "UPDATE orderheader SET orderHeaderOrderStatus = '{$status}', orderHeaderFinishedTime = '{$finishDate}' WHERE orderHeaderId = {$orderHeaderId};";
            $result = $mysqli -> query($query);
            if($result){
                header("location: adminOrderList.php?updateOrders=1");
            }else{
                header("location: adminOrderList.php?updateOrders=0");
            }
            exit(1);
        }
        include('../head.php');
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/login.css" rel="stylesheet">
    <title>Update Order Status | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderAdmin.php')?>

    <div class="container form-signin mt-auto w-50">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <?php 
            //Select customer record from database
            $orderHeaderId = $_GET["orderHeaderId"];
            $query = "SELECT orh.orderHeaderReferenceCode,orh.orderHeaderOrderTime,c.customerFirstName,c.customerLastName,orh.orderHeaderOrderStatus,orh.orderHeaderPickupTime,p.paymentAmount,c.canteenName
                FROM orderheader orh INNER JOIN customer cu ON orh.customerId = cu.customerId INNER JOIN payment p ON p.paymentId = orh.paymentId 
                INNER JOIN canteen c ON orh.canteenId = c.canteenId WHERE orh.orderHeaderId = {$orderHeaderId};";
            $result = $mysqli ->query($query);
            $row = $result -> fetch_array();
        ?>
        <form method="POST" action="adminOrderUpdate.php" class="form-floating">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-pencil-square me-2"></i>Update Order Status</h2>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="orderrefcode" placeholder="Order Reference Code" value="<?php echo $row["orderHeaderReferenceCode"];?>" disabled>
                <label for="orderrefcode">Order Reference Code</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="customername" placeholder="Customer Name" value="<?php echo $row["customerFirstName"]." ".$row["customerLastName"];?>" disabled>
                <label for="customername">Customer Name</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="canteenName" placeholder="canteen Name" value="<?php echo $row["canteenName"];?>" disabled>
                <label for="canteenName">canteen Name</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="ordercost" placeholder="Order Cost" value="<?php echo $row["paymentAmount"]." Rs";?>" disabled>
                <label for="ordercost">Order Cost</label>
            </div>
            <div class="form-floating mb-2">
                <input type="datetime" class="form-control" id="pickuptime" placeholder="Pick-up time" value="<?php echo $row["orderHeaderPickupTime"];?>" disabled>
                <label for="pickuptime">Pick-up time</label>
            </div>
            <div class="form-floating mb-2">
                <select class="form-select" id="orderstatus" name="os">
                    <option selected value="">Order Status</option>
                    <option value="ACPT" <?php if($row["orderHeaderOrderStatus"]=="ACPT"){ echo "selected";}?>>ACPT | Order Accepted</option>
                    <option value="PREP" <?php if($row["orderHeaderOrderStatus"]=="PREP"){ echo "selected";}?>>PREP | Order Preparing</option>
                    <option value="RDPK" <?php if($row["orderHeaderOrderStatus"]=="RDPK"){ echo "selected";}?>>RDPK | Ready for Pick-Up</option>
                    <option value="FNSH" <?php if($row["orderHeaderOrderStatus"]=="FNSH"){ echo "selected";}?>>FNSH | Order Finished</option>
                </select>
                <label for="orderstatus">Order Status</label>
            </div>
            <input type="hidden" name="orderHeaderId" value="<?php echo $orderHeaderId;?>">
            <button class="w-100 btn btn-success mb-3" name="updateConfirm" type="submit">Update order status</button>
        </form>
    </div>
</body>

</html>