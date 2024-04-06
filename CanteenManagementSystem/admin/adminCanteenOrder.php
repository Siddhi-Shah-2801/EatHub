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
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/menu.css" rel="stylesheet">
    <title>canteen Profile | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderAdmin.php')?>

    <?php
        $canteenId = $_GET["canteenId"];
        $query = "SELECT canteenName,canteenLocation,canteenOpenHour,canteenCloseHour,canteenStatus,canteenPreOrderStatus,canteenContactNo,canteenPic
        FROM canteen WHERE canteenId = {$canteenId} LIMIT 0,1";
        $result = $mysqli -> query($query);
        $canteenRow = $result -> fetch_array();
    ?>

    <div class="container px-5 py-4" id="canteen-body">
        <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <div class="container row row-cols-6 row-cols-md-12 g-5 pt-4 mb-4" id="canteen-header">
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
                    <li class="">Telephone number: <?php echo "(+91) ".$canteenRow["canteenContactNo"];?></li>
                </ul>
                <a class="btn btn-sm btn-outline-secondary" href="adminCanteenPassword.php?canteenId=<?php echo $canteenId?>">
            Change password
        </a>
        <a class="btn btn-sm btn-primary mt-2 mt-md-0" href="adminCanteenEdit.php?canteenId=<?php echo $canteenId?>">
            Update canteen profile
        </a>
        <a class="btn btn-sm btn-danger mt-2 mt-md-0" href="adminCanteenDelete.php?canteenId=<?php echo $canteenId?>">
            Delete this canteen
        </a>
            </div>
        </div>

        <!-- GRID MENU SELECTION -->
        <div class="container">
        <h3 class="border-top pt-3 my-2">
            <a class="text-decoration-none link-secondary" href="adminCanteenDetail.php?canteenId=<?php echo $canteenId?>">Menus</a>
            <span class="text-secondary">/</span> 
            <a class="nav-item text-decoration-none link-success" href="#">Orders</a></span>
            <span class="text-secondary">/</span> 
            <a class="nav-item text-decoration-none link-secondary" href="adminCanteenRevenue.php?canteenId=<?php echo $canteenId?>">Revenue</a></span>
        </h3>
            <form class="form-floating mb-3" method="GET" action="adminCanteenOrder.php">
                <input type="hidden" name="canteenId" value="<?php echo $canteenId;?>">
                <div class="row g-2">
                    <div class="col">
                        <select class="form-select" id="customerId" name="customerId">
                            <option selected value="">Customer Name</option>
                            <?php
                                $optionQuery = "SELECT DISTINCT cu.customerId, cu.customerFirstName,cu.customerLastName
                                FROM orderheader orh INNER JOIN customer c ON orh.customerId = c.customerId 
                                WHERE orh.canteenId = {$canteenId};";
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
                            <option value="adm" <?php if($_GET["ut"]=="adm"){ echo "selected";}?>>Admin</option>
                            <option value="cust" <?php if($_GET["ut"]=="cust"){ echo "selected";}?>>Customer</option>
                            <?php }else{ ?>
                            <option value="adm">admin</option>
                            <option value="cust">customer</option>
                            <?php } ?>
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
                            onclick="javascript: window.location='adminCanteenOrder.php?foodId=<?php echo $foodId?>'">Clear</button>
                    </div>
                </div>
            </form>
        </div>
        <?php
            $result -> free_result();
            if(isset($_GET["search"])){
                if($_GET["customerId"]!=''){ $customerIdClause = " AND orh.customerId = '{$_GET['customerId']}';"; }else{ $customerIdClause = ";";}
                $query = "SELECT orh.orderHeaderId,orh.orderHeaderReferenceCode,orh.orderHeaderOrderTime,cu.customerFirstName,c.customerLastName,orh.orderHeaderOrderStatus,p.paymentAmount
                FROM orderheader orh INNER JOIN customer cu ON orh.customerId = cu.customerId INNER JOIN payment p ON p.paymentId = orh.paymentId
                WHERE orh.canteenId = {$canteenId} AND c.customerType LIKE '%{$_GET['ut']}%' AND orderHeaderOrderStatus LIKE '%{$_GET['os']}%'".$customerIdClause;
            }else{
                $query = "SELECT orh.orderHeaderId,orh.orderHeaderReferenceCode,orh.orderHeaderOrderTime,c.customerFirstName,c.customerLastName,orh.orderHeaderOrderStatus,p.paymentAmount
                FROM orderheader orh INNER JOIN customer c ON orh.customerId = c.customerId INNER JOIN payment p ON p.paymentId = orh.paymentId WHERE orh.canteenId = {$canteenId};";
            }
            $result = $mysqli -> query($query);
            $numrow = $result -> num_rows;
            if($numrow > 0){
        ?>
        <div class="container align-items-stretch">
            <!-- GRID EACH MENU -->
            <div class="table-responsive">
            <table class="table rounded-5 table-light table-striped table-hover align-middle caption-top mb-3">
                <caption><?php echo $numrow;?> order(s) <?php if(isset($_GET["search"])){?><br /><a
                        href="adminCanteenOrder.php?canteenId=<?php echo $canteenId?>" class="text-decoration-none text-danger">Clear Search
                        Result</a><?php } ?></caption>
                <thead class="bg-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Order Ref. Code</th>
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
                        <td>
                            <?php if($row["orderHeaderOrderStatus"]=="ACPT"){ ?>
                                <h5><span class="fw-bold badge bg-info text-dark">Accepted</span></h5>
                            <?php }else if($row["orderHeaderOrderStatus"]=="PREP"){ ?>
                                <h5><span class="fw-bold badge bg-warning text-dark">Preparing</span></h5>
                            <?php }else if($row["orderHeaderOrderStatus"]=="RDPK"){ ?>
                                <h5><span class="fw-bold badge bg-primary text-white">Ready to pick up</span></h5>
                            <?php }else if($row["orderHeaderOrderStatus"]=="FNSH"){?>
                                <h5><span class="fw-bold badge bg-success text-white">Completed</span></h5>
                            <?php } ?>
                        </td>
                        <td><?php 
                        $order_time = (new Datetime($row["orderHeaderOrderTime"])) -> format("F j, Y H:i");
                        echo $order_time;
                        ?></td>
                        <td><?php echo $row["customerFirstName"]." ".$row["customerLastName"];?></td>
                        <td><?php echo $row["paymentAmount"]." Rs";?></td>
                        <td><a href="adminOrderDetail.php?orderHeaderId=<?php echo $row["orderHeaderId"]?>" class="btn btn-sm btn-primary">View</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        </div>
        <?php }else{ ?>
        <div class="row">
            <div class="col m-2 p-2 bg-danger text-white rounded text-start">
              <span class="ms-2 mt-2">No order found with this menu</span>
                <?php if(isset($_GET["search"])){ ?>
                <a href="adminCanteenOrder.php?canteenId=<?php echo $canteenId;?>" class="text-white">Clear Search Result</a>
                <?php } ?>
            </div>
        </div>
        <!-- END GRID canteen SELECTION -->
        <?php } ?>
    </div>
</body>

</html>