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
    if (isset($_POST["updateItem"])) {
        //Update button pressed
        $targetCanteenId = $_POST["canteenId"];
        $targetCustomerId = $_SESSION["customerId"];
        $targetFoodId = $_POST["foodId"];
        $amount = $_POST["amount"];
        $request = $_POST["request"];
        $cartUpdateQuery = "UPDATE cart SET cartAmount = {$amount}, cartNote = '{$request}' 
        WHERE customerId = {$targetCustomerId} AND canteenId = {$targetCanteenId} AND foodId = {$targetFoodId}";
        $cartUpdateResult = $mysqli->query($cartUpdateQuery);
        if ($cartUpdateResult) {
            header("location: customerCart.php?updateCart=1");
        } else {
            header("location: customerCart.php?updateCart=0");
        }
        exit(1);
    }

    include('head.php');
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/menu.css" rel="stylesheet">
    <script type="text/javascript" src="js/inputNumber.js"></script>
    <title>Food Item | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php
    include('navheaderCustomer.php');
    $canteenId = $_GET["canteenId"];
    $foodId = $_GET["foodId"];
    $query = "SELECT * FROM food WHERE canteenId = {$canteenId} AND foodId = {$foodId} LIMIT 0,1";
    $result = $mysqli->query($query);
    $foodRow = $result->fetch_array();
    ?>

    <div class="container px-5 py-4" id="canteen-body">
        <div class="row my-4">
            <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
                <i class="bi bi-arrow-left-square me-2"></i>Go back
            </a>
        </div>
        <div class="row row-cols-1 row-cols-md-2 mb-5">
            <div class="col mb-3 mb-md-0">
                <img <?php
                        if (is_null($foodRow["foodPic"])) {
                            echo "src='./images/canteenLogo.png'";
                        } else {
                            echo "src=\"./images/{$foodRow['foodPic']}\"";
                        }
                        ?> class="img-fluid rounded-25 float-start" alt="<?php echo $foodRow["foodName"] ?>">
            </div>
            <div class="col text-wrap">
                <h1 class="fw-light"><?php echo $foodRow["foodName"] ?></h1>
                <h3 class="fw-light"><?php echo $foodRow["foodPrice"] ?> Rs</h3>
                <ul class="list-unstyled mb-3 mb-md-0">
                    <li class="my-2">
                        <?php if ($foodRow["foodTodayAvailable"] == 1) { ?>
                            <span class="badge rounded-pill bg-success">Avaliable</span>
                        <?php } else { ?>
                            <span class="badge rounded-pill bg-danger">Unavaliable</span>
                        <?php }
                        if ($foodRow["foodPreOrderAvailable"] == 1) { ?>
                            <span class="badge rounded-pill bg-success">Pre-order avaliable</span>
                        <?php } else { ?>
                            <span class="badge rounded-pill bg-danger">Pre-order Unavaliable</span>
                        <?php } ?>
                    </li>
                </ul>

                <?php
                $customerQuery = "SELECT cartAmount,cartNote FROM cart WHERE customerId = {$_SESSION['customerId']} AND foodId = {$foodId} AND canteenId = {$canteenId}";
                $cartInsertQuery = $mysqli->query($customerQuery)->fetch_array();
                ?>

                <div class="form-amount">
                    <form class="mt-3" method="POST" action="customerUpdateCart.php">
                        <div class="input-group mb-3">
                            <button id="submitButton" class="btn btn-outline-secondary" type="button" title="subtract amount" onclick="submitAmount('amount')">
                                <i class="bi bi-dash-lg"></i>
                            </button>
                            <input type="number" class="form-control text-center border-secondary" id="amount" name="amount" value="<?php echo $cartInsertQuery["cartAmount"] ?>" min="1" max="99">
                            <button id="addButton" class="btn btn-outline-secondary" type="button" title="add amount" onclick="addAmount('amount')">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        <input type="hidden" name="canteenId" value="<?php echo $canteenId ?>">
                        <input type="hidden" name="foodId" value="<?php echo $foodId ?>">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="addRequest" name="request" value="<?php echo $cartInsertQuery["cartNote"] ?>" placeholder=" ">
                            <label for="addRequest" class="d-inline-text">Additional Request (Optional)</label>
                            <div id="addRequestHelpText" class="form-text">
                                Such as no veggie.
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-block">
                            <button class="btn btn-success" type="submit" title="Update item" name="updateItem" value="upd">
                                Update item
                            </button>
                            <button class="btn btn-outline-danger" type="submit" formaction="removeCartItem.php?remove=1&canteenId=<?php echo $canteenId ?>&foodId=<?php echo $foodId ?>" value="remove" title="remove from cart" name="removeItem">
                                Remove item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>
</body>

</html>