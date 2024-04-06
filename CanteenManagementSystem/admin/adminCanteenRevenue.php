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
                    <li class="">Telephone number: <?php echo "(+66) ".$canteenRow["canteenContactNo"];?></li>
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
            <a class="text-decoration-none link-secondary" href="admin_canteen_detail.php?canteenId=<?php echo $canteenId?>">Menus</a>
            <span class="text-secondary">/</span> 
            <a class="nav-item text-decoration-none link-secondary" href="adminCanteenOrder.php?canteenId=<?php echo $canteenId?>">Orders</a></span>
            <span class="text-secondary">/</span> 
            <a class="nav-item text-decoration-none link-success" href="adminCanteenRevenue.php?canteenId=<?php echo $canteenId?>">Revenue</a></span>
        </h3>
        </div>
        <div class="container form-signin">
        <form method="GET" action="adminCanteenReport.php" class="form-floating">
            <input type="hidden" name="canteenId" value="<?php echo $canteenId;?>">
            <p>Please select the option below to see sales and revenue report of this canteen.</p>
            <!-- 1 Today / 2 Yesterday / 3 This Week / 4 Monthly / 5 Specific Period -->
            <div class="form-check">
                <input class="form-check-input" type="radio" name="revenueMode" id="revenueMode1" value="1" checked onclick="switch_disable(0)">
                <label class="form-check-label" for="revenueMode1">
                    Today<br />(<?php echo date('F j, Y');?>)
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="revenueMode" id="revenueMode2" value="2" onclick="switch_disable(0)">
                <label class="form-check-label" for="revenueMode2">
                    Yesterday<br />(<?php echo (new Datetime()) -> sub(new DateInterval("P1D")) -> format('F j, Y');?>)
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="revenueMode" id="revenueMode3" value="3" onclick="switch_disable(0)">
                <label class="form-check-label" for="revenueMode3">
                    This Week<br /> (<?php 
                    $weekRange = rangeWeek(date('Y-n-j'));
                    $weekStart = (new Datetime($weekRange["start"])) -> format('F j, Y');
                    $weekEnd = (new Datetime($weekRange["end"])) -> format('F j, Y');
                    echo "{$weekStart} - {$weekEnd}";
                    ?>)
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="revenueMode" id="revenueMode4" value="4" onclick="switch_disable(0)">
                <label class="form-check-label" for="revenueMode4">
                    This Month<br /> (<?php 
                    $monthrange = rangeMonth(date('Y-n-j'));
                    $month_start = (new Datetime($monthrange["start"])) -> format('F j, Y');
                    $month_end = (new Datetime($monthrange["end"])) -> format('F j, Y');
                    echo "{$month_start} - {$month_end}";
                    ?>)
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="revenueMode" id="revenueMode5" value="5" onclick="switch_disable(1)">
                <label class="form-check-label" for="revenueMode5">
                    Specific Date<br />
                </label>
                <div class="row row-cols-2 g-0 mt-1 mb-2">
                    <div class="col">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="startDate" placeholder="Starting Date"
                                value="<?php echo date('Y-m-d');?>" max="<?php echo date('Y-m-d');?>" name="startDate"
                                oninput="update_minrange()" disabled>
                            <label for="startDate">Starting Date</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="endDate" placeholder="Ending Date"
                                value="<?php echo date('Y-m-d');?>" max="<?php echo date('Y-m-d');?>" name="endDate" disabled>
                            <label for="endDate">Ending Date</label>
                        </div>
                    </div>
                </div>
            </div>
            <button class="w-100 btn btn-outline-success my-3" type="submit">Generate Report</button>
        </form>
    </div>
    </div>
</body>

</html>