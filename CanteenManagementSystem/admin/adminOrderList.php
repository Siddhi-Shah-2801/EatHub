<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <?php 
        session_start(); 
        include("../connectionDB.php"); 
        include('../head.php');
        if($_SESSION["utype"]!="admin"){
            header("location: ../restricted.php");
            exit(1);
        }
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../images/icon.png" rel="icon">
    <link href="../css/main.css" rel="stylesheet">
    <title>Order List | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">

    <?php include('navHeaderAdmin.php')?>

    <div class="container p-2 pb-0" id="admin-dashboard">
        <div class="mt-4 border-bottom">
            <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
                <i class="bi bi-arrow-left-square me-2"></i>Go back
            </a>

            <?php
            if(isset($_GET["updateOrders"])){
                if($_GET["updateOrders"]==1){
                    ?>
            <!-- START SUCCESSFULLY UPDATE ORDER STATUS -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                    <span class="ms-2 mt-2">Successfully updated order status.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminOrderList.php">X</a></span>
                </div>
            </div>
            <!-- END SUCCESSFULLY UPDATE ORDER STATUS -->
            <?php }else{ ?>
            <!-- START FAILED UPDATE ORDER STATUS -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                    <span class="ms-2 mt-2">Failed to update order status.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminOrderList.php">X</a></span>
                </div>
            </div>
            <!-- END FAILED UPDATE ORDER STATUS -->
            <?php }
                }
            ?>

            <h2 class="pt-3 display-6">Order List</h2>
            <form class="form-floating mb-3" method="GET" action="adminOrderList.php">
                <div class="row g-2">
                    <div class="col">
                        <select class="form-select" id="customerId" name="customerId">
                            <option selected value="">Customer Name</option>
                            <?php
                                $optionQuery = "SELECT DISTINCT c.customerId, c.customerFirstName,c.customerLastName
                                FROM orderheader orh INNER JOIN customer c ON orh.customerId = c.customerId;";
                                $optionResult = $mysqli -> query($optionQuery);
                                $optionRow = $optionResult -> num_rows;
                                if($optionResult -> num_rows != 0){
                                    while($optionArray = $optionResult -> fetch_array()){
                            ?>
                            <option value="<?php echo $optionArray["customerId"]?>"><?php echo $optionArray["customerFirstName"]." ".$optionArray["customerLastName"]?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select" id="utype" name="ut">
                            <?php if(isset($_GET["search"])){?>
                            <option selected value="">Customer Type</option>
                            <option value="cust" <?php if($_GET["ut"]=="customer"){ echo "selected";}?>>customer</option>
                            <option value="adm" <?php if($_GET["ut"]=="admin"){ echo "selected";}?>>admin</option>
                            <?php }else{ ?>
                            <option selected value="">Customer Type</option>
                            <option value="cust">customer</option>
                            <option value="adm">admin</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select" id="canteenId" name="canteenId">
                            <option selected value="">canteen Name</option>
                            <?php
                                $optionQuery = "SELECT DISTINCT c.canteenId, c.canteenName
                                FROM orderheader orh INNER JOIN canteen c ON orh.canteenId = c.canteenId;";
                                $optionResult = $mysqli -> query($optionQuery);
                                $optionRow = $optionResult -> num_rows;
                                if($optionResult -> num_rows != 0){
                                    while($optionArray = $optionResult -> fetch_array()){
                            ?>
                            <option value="<?php echo $optionArray["canteenId"]?>"><?php echo $optionArray["c.canteenName"]?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select" id="orderstatus" name="os">
                            <?php if(isset($_GET["search"])){?>
                            <option selected value="">Order Status</option>
                            <option value="ACPT" <?php if($_GET["os"]=="ACPT"){ echo "selected";}?>>Order Accepted</option>
                            <option value="PREP" <?php if($_GET["os"]=="PREP"){ echo "selected";}?>>Order Preparing</option>
                            <option value="RDPK" <?php if($_GET["os"]=="RDPK"){ echo "selected";}?>>Ready for Pick-Up</option>
                            <option value="FNSH" <?php if($_GET["os"]=="FNSH"){ echo "selected";}?>>Order Finished</option>
                            <?php }else{ ?>
                            <option selected value="">Order Status</option>
                            <option value="ACPT">Order Accepted</option>
                            <option value="PREP">Order Preparing</option>
                            <option value="RDPK">Ready for Pick-Up</option>
                            <option value="FNSH">Order Finished</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="search" value="1" class="btn btn-success"
                        <?php if($optionRow==0){echo "disabled";} ?>>Search</button>
                        <button type="reset" class="btn btn-danger"
                            onclick="javascript: window.location='adminOrderList.php'">Clear</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
            if(isset($_GET["search"])){
                if($_GET["customerId"]!=''){ $customerIdClause = " AND orh.customerId = '{$_GET['customerId']}' "; }else{ $customerIdClause = " ";}
                if($_GET["canteenId"]!=''){ $canteenIdClause = " AND orh.canteenId = '{$_GET['canteenId']}' "; }else{ $canteenIdClause = " ";}
                $query = "SELECT orh.orderHeaderId,orh.orderHeaderReferenceCode,orh.orderHeaderOrderTime,cu.customerFirstName,cu.customerLastName,orh.orderHeaderOrderStatus,p.paymentAmount,c.canteenName
                FROM orderheader orh INNER JOIN customer cu ON orh.customerId = cu.customerId INNER JOIN payment p ON p.paymentId = orh.paymentId
                INNER JOIN canteen c ON orh.canteenId = c.canteenId WHERE cu.customerType LIKE '%{$_GET['ut']}%' 
                AND orderHeaderOrderStatus LIKE '%{$_GET['os']}%'".$customerIdClause.$canteenIdClause." ORDER BY orh.orderHeaderOrderTime DESC;";
            }else{
                $query = "SELECT orh.orderHeaderId,orh.orderHeaderReferenceCode,orh.orderHeaderOrderTime,cu.customerFirstName,cu.customerLastName,orh.orderHeaderOrderStatus,p.paymentAmount,c.canteenName
                FROM orderheader orh INNER JOIN customer cu ON orh.customerId = cu.customerId INNER JOIN payment p ON p.paymentId = orh.paymentId INNER JOIN canteen c ON orh.canteenId = c.canteenId ORDER BY orh.orderHeaderOrderTime DESC;";
            }
            $result = $mysqli -> query($query);
            $numrow = $result -> num_rows;
            if($numrow > 0){
        ?>
        <div class="container align-items-stretch pt-2">
            <!-- GRID EACH MENU -->
            <div class="table-responsive">
            <table class="table rounded-5 table-light table-striped table-hover align-middle caption-top mb-3">
                <caption><?php echo $numrow;?> order(s) <?php if(isset($_GET["search"])){?><br /><a
                        href="adminOrderList.php" class="text-decoration-none text-danger">Clear Search
                        Result</a><?php } ?></caption>
                <thead class="bg-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Order Ref. Code</th>
                        <th scope="col">canteen Name</th>
                        <th scope="col">Order Status</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Order Cost</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($row = $result -> fetch_array()){ ?>
                    <tr>
                        <th><?php echo $i++;?></th>
                        <td><?php echo $row["orderHeaderReferenceCode"];?></td>
                        <td><?php echo $row["c.canteenName"];?></td>
                        <td>
                            <?php if($row["orderHeaderOrderStatus"]=="ACPT"){ ?>
                                <span class="fw-bold badge rounded-pill bg-info text-dark">Accepted</span>
                            <?php }else if($row["orderHeaderOrderStatus"]=="PREP"){ ?>
                                <span class="fw-bold badge rounded-pill bg-warning text-dark">Preparing</span>
                            <?php }else if($row["orderHeaderOrderStatus"]=="RDPK"){ ?>
                                <span class="fw-bold badge rounded-pill bg-primary text-white">Ready to pick up</span>
                            <?php }else if($row["orderHeaderOrderStatus"]=="FNSH"){?>
                                <span class="fw-bold badge rounded-pill bg-success text-white">Completed</span>
                            <?php } ?>
                        </td>
                        <td><?php 
                        $orderTime = (new Datetime($row["orderHeaderOrderTime"])) -> format("F j, Y H:i");
                        echo $orderTime;
                        ?></td>
                        <td><?php echo $row["customerFirstName"]." ".$row["customerLastName"];?></td>
                        <td><?php echo $row["paymentAmount"]." Rs";?></td>
                        <td>
                            <a href="admin_orderdetail.php?orderHeaderId=<?php echo $row["orderHeaderId"]?>" class="btn btn-sm btn-primary">View</a>
                            <a href="admin_order_update.php?orderHeaderId=<?php echo $row["orderHeaderId"]?>" class="btn btn-sm btn-outline-success">Update Status</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        </div>
        <?php }else{ ?>
        <div class="container">
        <div class="row">
            <div class="col m-2 p-2 bg-danger text-white rounded text-start">
               <span class="ms-2 mt-2">No order found</span>
                <?php if(isset($_GET["search"])){ ?>
                <a href="adminOrderList.php" class="text-white">Clear Search Result</a>
                <?php } ?>
            </div>
        </div>
        </div>
        <!-- END GRID canteen cELECTION -->
        <?php } ?>
</body>

</html>