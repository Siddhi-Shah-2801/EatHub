<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    if (!isset($_SESSION["customerId"])) {
        header("location: restricted.php");
        exit(1);
    }
    include('connectionDB.php');
    include('head.php');
    $noOrder = false;
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/menu.css" rel="stylesheet">
    <title>My Cart | SOMAIYA CANTEEN</title>
</head>

<body class="d-flex flex-column h-100">

    <?php include('navHeaderCustomer.php') ?>

    <div class="container px-5 py-4" id="cart-body">
        <div class="row my-4">
            <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
                <i class="bi bi-arrow-left-square me-2"></i>Go back
            </a>

            <?php
            if (isset($_GET["upCart"])) {
                if ($_GET["upCart"] == 1) {
            ?>
                    <!-- START SUCCESSFULLY UPDATE CART -->
                    <div class="row row-cols-1 notibar">
                        <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                            <span class="ms-2 mt-2">Successfully updated your item!</span>
                            <span class="me-2 float-end"><a class="text-decoration-none link-light" href="customerCart.php">X</a></span>
                        </div>
                    </div>
                    <!-- END SUCCESSFULLY UPDATE CART -->
                <?php } else { ?>
                    <!-- START FAILED UPDATE CART -->
                    <div class="row row-cols-1 notibar">
                        <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                            <span class="ms-2 mt-2">Failed to update your item.</span>
                            <span class="me-2 float-end"><a class="text-decoration-none link-light" href="customerCart.php">X</a></span>
                        </div>
                    </div>
                    <!-- END FAILED UPDATE CART -->
                <?php }
            }
            if (isset($_GET["removeCart"])) {
                if ($_GET["removeCart"] == 1) {
                ?>
                    <!-- START SUCCESSFULLY DELETE ITEM FROM CART -->
                    <div class="row row-cols-1 notibar">
                        <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">

                            <span class="ms-2 mt-2">Successfully remove your item!</span>
                            <span class="me-2 float-end"><a class="text-decoration-none link-light" href="customerCart.php">X</a></span>
                        </div>
                    </div>
                    <!-- END SUCCESSFULLY DELETE ITEM FROM CART -->
                <?php } else { ?>
                    <!-- START FAILED DELETE ITEM FROM CART -->
                    <div class="row row-cols-1 notibar">
                        <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                            <span class="ms-2 mt-2">Failed to remove your item.</span>
                            <span class="me-2 float-end"><a class="text-decoration-none link-light" href="customerCart.php">X</a></span>
                        </div>
                    </div>
                    <!-- END FAILED DELETE ITEM FROM CART -->
            <?php }
            }  ?>

            <h2 class="py-3 display-6 border-bottom">
                My Cart
            </h2>
        </div>

        <?php
        $cartQuery = "SELECT * FROM cart WHERE customerId = {$_SESSION['customerId']}";
        $cartNumRow = $mysqli->query($cartQuery)->num_rows;
        if ($cartNumRow > 0) {
        ?>
            <!-- CASE: HAVE ITEM(S) IN THE CART -->
            <div class="row row-cols-1 row-cols-md-2 mb-5">
                <div class="col">
                    <div class="row row-cols-1">
                        <div class="col">
                            <h5 class="fw-light">My Order</h5>
                            <p class="fw-light">From
                                <?php
                                $cartQuery = "SELECT canteenId,canteenUserName,canteenName,canteenOpenHour,canteenCloseHour,canteenStatus, canteenPreOrderStatus FROM canteen c WHERE canteenId = (SELECT canteenId FROM cart WHERE customerId = {$_SESSION['customerId']} LIMIT 0,1)";
                                $cartResult = $mysqli->query($cartQuery)->fetch_array();
                                echo $cartResult["canteenName"];
                                $canteenOpen = $cartResult["canteenOpenHour"];
                                $canteenClose = $cartResult["canteenCloseHour"];
                                if ($cartResult["canteenStatus"] == 0 && $cartResult["canteenPreOrderStatus"] == 0) {
                                    $disableCanteen = true;
                                } else {
                                    $disableCanteen = false;
                                    if ($cartResult["canteenStatus"] == 0) {
                                        $disableToday = true;
                                    } else {
                                        $disableToday = false;
                                    }
                                    if ($cartResult["canteenPreOrderStatus"] == 0) {
                                        $disablePreOrder = true;
                                    } else {
                                        $disablePreOrder = false;
                                    }
                                }
                                ?>
                            </p>
                        </div>

                        <?php
                        //calculate min max of datetime input
                        $nowTime = date("H:i");
                        $nowDate = date("Y-m-d");
                        $nowDateTime = $nowDate . "T" . $nowTime;
                        $tommorowDate = (new Datetime($nowDate))->add(new DateInterval("P1D"))->format('Y-m-d');
                        $canteenOpenArray = explode(":", $canteenOpen);
                        $canteenOpen = $canteenOpenArray[0] . ":" . $canteenOpenArray[1];
                        $canteenCloseArray = explode(":", $canteenClose);
                        $canteenClose = $canteenCloseArray[0] . ":" . $canteenCloseArray[1];

                        if ($nowTime >= $canteenOpen && $nowTime < $canteenClose) {
                            $canteenOpening = true;
                        } else {
                            $canteenOpening = false;
                        }

                        if ($noOrder) {
                            $minDate = $nowDate;
                            $maxDate = $nowDate;
                            $minTime = $nowTime;
                            $minTime = $nowTime;
                        } else {
                            if ($disablePreOrder) {
                                $minDate = $nowDate;
                                $maxDate = $nowDate;
                                $minTime = $nowTime;
                                $maxTime = $canteenClose;
                            } else if ($disableToday) {
                                $minDate = $tommorowDate;
                                $maxDate = $tommorowDate;
                                $minTime = $canteenOpen;
                                $maxTime = $canteenClose;
                            } else {
                                $maxDate = $tommorowDate;
                                $maxTime = $canteenClose;
                                if ($nowTime < $canteenOpen) {
                                    $minDate = $nowDate;
                                    $minTime = $canteenOpen;
                                } else if ($nowTime > $canteenClose) {
                                    $minDate = $tommorowDate;
                                    $minTime = $canteenOpen;
                                } else {
                                    $minDate = $nowDate;
                                    $minTime = $nowTime;
                                }
                            }
                        }

                        $minDatetime = $minDate . "T" . $minTime;
                        $maxDateTime = $maxDate . "T" . $maxTime;
                        ?>

                        <div class="col">
                            <ul class="list-group">
                                <!-- START CART ITEM -->
                                <?php
                                $cartDetailQuery = "SELECT ct.cartAmount,ct.foodId,foodPic,f.foodName,f.foodPrice,ct.cartNote,foodTodayAvailable,foodPreOrderAvailable
                                FROM cart ct INNER JOIN food f ON ct.foodId = f.foodId WHERE ct.customerId = {$_SESSION['customerId']}";
                                $cartDetailResult = $mysqli->query($cartDetailQuery);
                                while ($row = $cartDetailResult->fetch_array()) {
                                ?>
                                    <li class="list-group-item d-flex border-0 pb-3 border-bottom w-100 justify-content-between align-items-center">
                                        <div class="image-parent">
                                            <img <?php
                                                    if (is_null($row["foodPic"])) {
                                                        echo "src='./images/canteenLogo.png'";
                                                    } else {
                                                        echo "src=\"./images/{$row['foodPic']}\"";
                                                    }
                                                    ?> class="img-fluid rounded" style="width: 100px; height:100px; object-fit:cover;" alt="food">
                                        </div>
                                        <div class="ms-3 mt-3 me-auto">
                                            <div class="fw-normal"><span class="h5"><?php echo $row["cartAmount"] ?>x</span>
                                                <?php echo $row["foodName"] ?>
                                                <p><?php printf("%.2f Rs <small class='text-muted'>(%.2f Rs each)</small>", $row["foodPrice"] * $row["cartAmount"], $row["foodPrice"]) ?><br />
                                                    <span class="text-muted small"> <?php echo $row["cartNote"] ?></span>
                                                <ul class="list-unstyled list-inline">
                                                    <li>
                                                        <?php
                                                        $remove = false;
                                                        $removeLink = false;
                                                        if ($disableCanteen || ($disablePreOrder && !$canteenOpening)) {
                                                            $remove = true;
                                                        } else {
                                                            if ($row["foodTodayAvailable"] == 0 && $row["foodPreOrderAvailable"] == 0) {
                                                                $remove = true;
                                                        ?>
                                                                <span class="badge rounded-pill bg-danger">Out of stock</span>
                                                            <?php } else if ($row["foodTodayAvailable"] == 0) { ?>
                                                                <span class="badge rounded-pill bg-danger">Today Unavaliable</span>
                                                                <?php
                                                                if ($disablePreOrder) {
                                                                    $remove = true;
                                                                }
                                                            } else if ($row["foodPreOrderAvailable"] == 0) { ?>
                                                                <span class="badge rounded-pill bg-danger">Pre-order Unavaliable</span>
                                                        <?php
                                                                if (!$canteenOpening || $disableToday) {
                                                                    $remove = true;
                                                                }
                                                            }
                                                        }
                                                        if ($remove) {
                                                            $noOrder = true;
                                                            $removeLink = true;
                                                        }
                                                        ?>
                                                    </li>
                                                </ul>
                                                </p>
                                                <?php if ($removeLink) { ?>
                                                    <a href="removeCartItem.php?remove=1&canteenId=<?php echo $cartResult["canteenId"]; ?>&foodId=<?php echo $row["foodId"]; ?>" class="text-decoration-none text-danger nav nav-item small">Remove item</a>
                                                <?php } else { ?>
                                                    <a href="customerUpdateCart.php?canteenId=<?php echo $cartResult["canteenId"]; ?>&foodId=<?php echo $row["foodId"]; ?>" class="text-decoration-none text-success nav nav-item small">Edit item</a>
                                                <?php } ?>
                                            </div>
                                    </li>
                                    <!-- END CART ITEM -->
                                <?php } ?>
                            </ul>
                            <div class="col my-3">
                                <ul class="list-inline justify-content-between">
                                    <li class="list-item mb-2">
                                        <a href="removeCartAll.php?remove=1&canteenId=<?php echo $cartResult["canteenId"]; ?>" class="nav nav-item text-danger text-decoration-none small" name="removeAll" id="removeAll">
                                            Remove all item in cart
                                        </a>
                                    </li>
                                    <li class="list-inline-item fw-light me-5">Grand Total</li>
                                    <li class="list-inline-item fw-bold h4">
                                        <?php
                                        $grandTotalQuery = "SELECT SUM(ct.cartAmount*f.foodPrice) AS grandtotal FROM cart ct INNER JOIN food f 
                                        ON ct.foodId = f.foodId WHERE ct.customerId = {$_SESSION['customerId']} GROUP BY ct.customerId";
                                        $grandTotalArray = $mysqli->query($grandTotalQuery)->fetch_array();
                                        $orderCost = $grandTotalArray["grandtotal"];
                                        printf("%.2f Rs", $orderCost);
                                        if ($orderCost < 20) {
                                            $minCost = false;
                                            $noOrder = true;
                                        } else {
                                            $minCost = true;
                                        }
                                        ?>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col mt-3 mt-md-0">
                    <div class="row row-cols-1">
                        <div class="col mb-3">
                            <div class="card p-2 p-md-4 border-0 border-bottom">
                                <h5 class="card-title fw-light">My Information</h5>
                                <ul class="card-text list-unstyled m-0 p-0 small">
                                    <?php
                                    $customerQuery = "SELECT customerEmail FROM customer WHERE customerId = {$_SESSION['customerId']} LIMIT 0,1";
                                    $customerArray = $mysqli->query($customerQuery)->fetch_array();
                                    ?>
                                    <li>Name: <?php echo $_SESSION["firstName"] . " " . $_SESSION["lastName"]; ?></li>
                                    <li>Email: <?php echo $customerArray["customerEmail"] ?> </li>
                                </ul>
                            </div>
                        </div>
                        <form method="POST" action="addOrder.php">
                            <div class="col mb-1">
                                <div class="card px-2 px-md-4 pb-1 pb-md-2 border-0">
                                    <h5 class="card-title fw-light">Pick-Up Detail</h5>
                                    <label for="pickupTime" class="form-label small">Pick-Up Date and Time</label>
                                    <input type="datetime-local" class="form-control" name="pickupTime" id="pickupTime" min="<?php echo $minDatetime ?>" max="<?php echo $maxDateTime ?>" value="<?php echo $minDatetime ?>" <?php if ($noOrder) {
                                                                                                                                                                                                                                    echo "disabled";
                                                                                                                                                                                                                                } ?>>
                                    <input type="hidden" name="payamount" value="<?php echo $orderCost * 100; ?>">
                                    <div id="passwordHelpBlock" class="form-text smaller-font pt-2">
                                        <!-- SUBJECTED TO CHANGE LATER -->
                                        <ul class="list-unstyled">
                                            <?php
                                            $canteenTimeRange = $canteenOpen . " to " . $canteenClose;
                                            if ($disableCanteen || ($disablePreOrder && !$canteenOpening)) {
                                                //Case 1: canteen Closed OR (Disabled Pre-order and already close for the day)
                                            ?>
                                                <li class="list-item text-danger fw-bold">This canteen is not accepting any order.</li>
                                                <?php } else {
                                                if ($disableToday) {
                                                    //Case 2: canteen close today but accept pre-order
                                                ?>
                                                    <li class="list-item text-danger fw-bold">This canteen is not accepting order for today.</li>
                                                    <li class="list-item">But, you can pick-up order tomorrow from <?php echo $canteenTimeRange; ?></li>
                                                <?php } else if ($disablePreOrder) {
                                                    //Case 3: canteen open today but NOT accepting pre-order
                                                ?>
                                                    <li class="list-item">You can order from this canteen until<?php echo $canteenClose ?></li>
                                                    <li class="list-item text-danger fw-bold">But, this canteen is not accepting any pre-order.</li>
                                                    <?php } else {
                                                    //Case 4:canteen open and accept pre-order
                                                    if ($canteenOpening) {
                                                        //Case 4.1: canteen window is open    
                                                    ?>
                                                        <li class="list-item">You can order from this canteen until <?php echo $canteenClose ?></li>
                                                        <li class="list-item">Also, you can pick-up order tomorrow from <?php echo $canteenTimeRange; ?></li>
                                                    <?php       } else if ($nowTime < $canteenOpen) {
                                                        //Case 4.2: canteen window is not open yet 
                                                    ?>
                                                        <li class="list-item">This canteen will open today from <?php echo $canteenTimeRange; ?>.</li>
                                                        <li class="list-item">You can also pick-up order tomorrow from <?php echo $canteenTimeRange; ?></li>
                                                    <?php       } else { //Case 4.2: canteen window is already close for today 
                                                    ?>
                                                        <li class="list-item text-danger fw-bold">This canteen is already closed for today.</li>
                                                        <li class="list-item">But, you can pick-up order tomorrow from <?php echo $canteenTimeRange; ?></li>
                                            <?php }
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <?php if ($noOrder) { ?>
                                    <button type="submit" class="w-100 btn btn-danger disabled" name="placeOrder" id="placeOrder" disabled>
                                        <?php
                                        if (!$minCost) {
                                            echo "Your order is less than minimum amount.";
                                        } else {
                                            echo "Cannot proceed with payment";
                                        }
                                        ?>
                                    </button>
                                <?php } else { ?>
                                    <script type="text/javascript" src="https://cdn.omise.co/omise.js" data-key="pkey_test_5pj8zasgcvaasrujrrs" data-image="../images/icon.png" data-frame-label="Somaiya Canteen | Somaiya University" data-button-label="Proceed with payment" data-submit-label="Submit" data-locale="en" data-location="no" data-amount="<?php echo $orderCost * 100; ?>" data-currency="Rs">
                                    </script>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END CASE: HAVE ITEM(S) IN THE CART -->
        <?php } else { ?>
            <!-- CASE: NO ITEM IN THE CART -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                    <span class="ms-2 mt-2">You have no item in the cart</span>
                </div>
            </div>
            <!-- END CASE: NO ITEM IN THE CART -->
        <?php } ?>

    </div>

</body>

<!-- Apply class to omise payment button -->
<script type="text/javascript">
    var pay_btn = document.getElementsByClassName("omise-checkout-button");
    pay_btn[0].classList.add("w-100", "btn", "btn-primary");
</script>

</html>