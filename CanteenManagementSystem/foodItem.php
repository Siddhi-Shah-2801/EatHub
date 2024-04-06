<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        session_start();
        include("connectionDB.php");
        include('head.php');
        if(!(isset($_GET["canteenId"])||isset($_GET["foodId"]))){
            header("location: restricted.php");
            exit(1);
        }
        if(!isset($_SESSION["customerId"])){
            header("location: ./customer/customerLogin.php");
            exit(1);
        }
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/menu.css" rel="stylesheet">
    <script type="text/javascript" src="../CanteenManagementSystem/js/inputNumber.js"></script>
    <script type="text/javascript">
        function changeCanteenCf(){
            return window.confirm("Do you want to change the canteen?\nDon't worry we will do it for you automatically.");
        }
    </script>
    <title>Food Item | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php 
        include('navHeaderCustomer.php');
        $canteenId = $_GET["canteenId"];
        $foodId = $_GET["foodId"];
        $query = "SELECT f.*,c.canteenStatus,c.canteenPreOrderStatus FROM food f INNER JOIN canteen c ON f.canteenId = c.canteenId WHERE f.canteenId = {$canteenId} AND f.foodId = {$foodId} LIMIT 0,1";
        $result = $mysqli -> query($query);
        $foodRow = $result -> fetch_array();
    ?>
    <div class="container px-5 py-4" id="canteen-body">
        <div class="row my-4">
            <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
                <i class="bi bi-arrow-left-square me-2"></i>Go back
            </a>
        </div>
        <div class="row row-cols-1 row-cols-md-2 mb-5">
            <div class="col mb-3 mb-md-0">
                <img 
                    <?php
                        if(is_null($foodRow["foodPic"])){echo "src='./images/canteenLogo.png'";}
                        else{echo "src=\"./images/{$foodRow['foodPic']}\"";}
                    ?> 
                    class="img-fluid rounded-25 float-start" 
                    alt="<?php echo $foodRow["foodName"]?>">
            </div>
            <div class="col text-wrap">
                <h1 class="fw-light"><?php echo $foodRow["foodName"]?></h1>
                <h3 class="fw-light"><?php echo $foodRow["foodPrice"]?> Rs</h3>
                <ul class="list-unstyled mb-3 mb-md-0">
                    <li class="my-2">
                        <?php if($foodRow["foodTodayAvailable"]==1 && $foodRow["canteenStatus"]==1){ ?>
                        <span class="badge rounded-pill bg-success">Avaliable</span>
                        <?php }else{ ?>
                        <span class="badge rounded-pill bg-danger">Unavaliable</span>
                        <?php }
                            if($foodRow["foodPreOrderAvailable"]==1&&$foodRow["canteenPreOrderStatus"]==1){?>
                        <span class="badge rounded-pill bg-success">Pre-order avaliable</span>
                        <?php }else{ ?>
                        <span class="badge rounded-pill bg-danger">Pre-order Unavaliable</span>
                        <?php }?>
                    </li>
                </ul>
                <div class="form-amount">
                    <form class="mt-3" method="POST" action="./customer/addItem.php">
                        <div class="input-group mb-3">
                            <button id="sub_btn" class="btn btn-outline-secondary" type="button" title="subtract amount" onclick="submitAmount('amount')">
                                <i class="bi bi-dash-lg"></i>
                            </button>
                            <input type="number" class="form-control text-center border-secondary" id="amount"
                                name="amount" value="1" min="1" max="99">
                            <button id="add_btn" class="btn btn-outline-secondary" type="button" title="add amount" onclick="addAmount('amount')">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        <input type="hidden" name="canteenId" value="<?php echo $canteenId?>">
                        <input type="hidden" name="foodId" value="<?php echo $foodId?>">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="addrequest" name="request" placeholder=" ">
                            <label for="addrequest" class="d-inline-text">Additional Request (Optional)</label>
                            <div id="addrequest_helptext" class="form-text">
                                Such as no veggie.
                            </div>
                        </div>
                        <button class="btn btn-success w-100" type="submit" title="add to cart" name="addtocart"
                        <?php
                            $cartSearchQuery1 = "SELECT COUNT(*) AS count FROM cart WHERE customerId = {$_SESSION['customerId']}";
                            $cartsearchRow1 = $mysqli -> query($cartSearchQuery1) -> fetch_array();
                            if($cartsearchRow1["count"]>0){
                                $cartsearchQuery2 = $cartSearchQuery1." AND canteenId = {$canteenId}";
                                $cartsearchRow2 = $mysqli -> query($cartsearchQuery2) -> fetch_array();
                                if($cartsearchRow2["count"]==0){?>
                                    onclick="javascript: return changeCanteenCf();"<?php 
                                } 
                            }
                        ?>
                        >
                           Add to cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php $result -> free_result();?> 
    </div>
</body>

</html>