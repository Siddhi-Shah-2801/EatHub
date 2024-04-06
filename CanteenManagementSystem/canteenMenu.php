<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("connectionDB.php");
    include('head.php');
    if (!isset($_GET["canteenId"])) {
        header("location:restricted.php");
        exit(1);
    }
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/main.css" rel="stylesheet">
    <link href="./css/menu.css" rel="stylesheet">
    <title>canteen Menu | somaiya canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderCustomer.php') ?>

    <?php
    $canteenId = $_GET["canteenId"];
    $query = "SELECT canteenName,canteenLocation,canteenOpenHour,canteenCloseHour,canteenStatus,canteenPreOrderStatus,canteenContactNo,canteenPic
        FROM canteen WHERE canteenId = {$canteenId} LIMIT 0,1";
    $result = $mysqli->query($query);
    $canteenRow = $result->fetch_array();
    ?>

    <div class="container px-5 py-4" id="canteen-body">
        <a class="nav nav-item text-decoration-none text-muted mb-3" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>

        <?php
        if (isset($_GET["atc"])) {
            if ($_GET["atc"] == 1) {
        ?>
                <!-- START SUCCESSFULLY ADD TO CART -->
                <div class="row row-cols-1 notibar pb-3">
                    <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                        <span class="ms-2 mt-2">Add item to your cart successfully!</span>
                        <span class="me-2 float-end"><a class="text-decoration-none link-light" href="canteenMenu.php?canteenId=<?php echo $canteenId; ?>">X</a></span>
                    </div>
                </div>
                <!-- END SUCCESSFULLY ADD TO CART -->
            <?php } else { ?>
                <!-- START FAILED ADD TO CART -->
                <div class="row row-cols-1 notibar">
                    <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                        <span class="ms-2 mt-2">Failed to add item to your cart.</span>
                        <span class="me-2 float-end"><a class="text-decoration-none link-light" href="canteenMenu.php?canteenId=<?php echo $canteenId; ?>">X</a></span>
                    </div>
                </div>
                <!-- END FAILED ADD TO CART -->
        <?php }
        } ?>
        <div class="mb-3 text-wrap" id="canteenHeader">
            <div class="rounded-25 mb-4" id="canteenImage" style="
                    background: url(
                        <?php
                        if (is_null($canteenRow["canteenPic"])) {
                            echo "'./images/canteenLogo.png'";
                        } else {
                            echo "'./images/{$canteenRow['canteenPic']}'";
                        }
                        ?> 
                    ) center; height: 500px; width: 500px;
                    background-size: cover; background-repeat: no-repeat;
                    background-position: center;">
            </div>
            <h1 class="display-5 strong">
                <?php echo $canteenRow["canteenName"]; ?>
            </h1>
            <ul class="list-unstyled">
                <li class="my-2">
                    <?php
                    $now = date('y/m/d');
                    if ((($now < $canteenRow["canteenOpenHour"]) || ($now > $canteenRow["canteenCloseHour"])) || ($canteenRow["canteenStatus"] == 0)) {
                    ?>
                        <span class="badge rounded-pill bg-danger">Closed</span>
                    <?php } else { ?>
                        <span class="badge rounded-pill bg-success">Open</span>
                    <?php }
                    if ($canteenRow["canteenPreOrderStatus"] == 1) {
                    ?>
                        <span class="badge rounded-pill bg-success">Pre-order avaliable</span>
                    <?php } else { ?>
                        <span class="badge rounded-pill bg-danger">Pre-order Unavaliable</span>
                    <?php } ?>
                </li>
                <li class="">
                    <?php echo $canteenRow["canteenLocation"]; ?>
                </li>
                <li class="">Open hours:
                    <?php
                    $open = explode(":", $canteenRow["canteenOpenHour"]);
                    $close = explode(":", $canteenRow["canteenCloseHour"]);
                    echo $open[0] . ":" . $open[1] . " - " . $close[0] . ":" . $close[1];
                    ?>
                </li>
                <li class="">Mobile number: <?php echo "(+91) " . $canteenRow["canteenContactNo"]; ?></li>
            </ul>
        </div>

        <!-- GRID MENU SELECTION -->
        <h3 class="border-top py-3 mt-2">Menu</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 align-items-stretch mb-1">

            <?php
            $result->free_result();
            $query = "SELECT * FROM food WHERE canteenId = {$canteenId} AND NOT(foodTodayAvailable  = 0 AND foodPreOrderAvailable = 0)";
            $result = $mysqli->query($query);

            if ($result->num_rows > 0) {
                while ($foodRow = $result->fetch_array()) {
            ?>
                    <!-- GRID EACH MENU -->
                    <div class="col">
                        <div class="card rounded-25 mb-4">
                            <a href="./foodItem.php?<?php echo "canteenId=" . $foodRow["canteenId"] . "&foodId=" . $foodRow["foodId"] ?>" class="text-decoration-none text-dark">
                                <div class="card-img-top">
                                    <img <?php
                                            if (is_null($foodRow["foodPic"])) {
                                                echo "src='./images/canteenLogo.png'";
                                            } else {
                                                echo "src=\"./images/{$foodRow['foodPic']}\"";
                                            }
                                            ?> style="width:100%; height:125px; object-fit:cover;" class="img-fluid" alt="<?php echo $foodRow["foodName"] ?>">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title fs-5">
                                        <?php echo $foodRow["foodName"] ?>
                                    </h5>
                                    <p class="card-text"><?php echo $foodRow["foodPrice"] ?> Rs</p>
                                    <a href="./foodItem.php?<?php echo "canteenId=" . $foodRow["canteenId"] . "&foodId=" . $foodRow["foodId"] ?>" class="btn btn-sm mt-3 btn-outline-secondary">
                                        Add to cart
                                    </a>
                                </div>
                            </a>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
            <!-- END GRID EACH canteen-->

        </div>
        <!-- END GRID canteen SELECTION -->

    </div>
</body>

</html>