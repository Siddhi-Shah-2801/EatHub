<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include('connectionDB.php');
    include('head.php'); ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <style>
        html {
            height: 100%;
        }
    </style>
    <title>Welcome | somaiya canteen</title>
</head>

<body class="d-flex flex-column h-100">

    <?php include('navHeaderCustomer.php') ?>

    <div class="position-relative d-flex text-center text-white promo-banner-bg py-3">
        <div class="p-lg-2 mx-auto my-5">
            <h1 class="display-5 fw-normal" style="color:indianred;">Welcome to Somaiya canteen</h1>
            <p style="color:black;">Food ordering system of Somaiya Campus Canteen</p>
        </div>
    </div>

    <div class="container p-5" id="recommended-canteen">
        <h2 class="border-bottom pb-2"><i class="bi bi-canteen align-top"></i> Recommended For You</h2>

        <!-- GRID Canteen SELECTION -->
        <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-3">

            <?php
            $query = "SELECT canteenId,canteenOpenHour,canteenCloseHour,canteenStatus,canteenPreOrderStatus,canteenPic,canteenName FROM canteen
            WHERE (canteenPreOrderStatus = 1) OR (canteenPreOrderStatus = 0 AND (CURTIME() BETWEEN CanteenOpenHour AND CanteenCloseHour));";
            $result = $mysqli->query($query);
            if ($result !== false && $result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                    ?>
                    <!-- GRID EACH CANTEEN -->
                    <div class="col">
                        <a href="<?php echo "canteenMenu.php?canteenId=" . $row["canteenId"] ?>"
                            class="text-decoration-none text-dark">
                            <div class="card rounded-25">
                                <img <?php
                                if (is_null($row["canteenPic"])) {
                                    echo "src='images/canteenLogo.png'";
                                } else {
                                    echo "src=\"images/{$row['canteenPic']}\"";
                                }
                                ?>    style="width:100%; height:175px; object-fit:cover;"
                                    class="card-img-top rounded-25 img-fluid" alt="<?php echo $row["canteenPic"] ?>">
                                <div class="card-body">
                                    <h4 name="canteenName" class="card-title">
                                        <?php echo $row["canteenName"] ?>
                                    </h4>
                                    <p class="card-subtitle">
                                        <?php
                                        $now = date('dd:mm:yy');
                                        if ((($now < $row["canteenOpenHour"]) || ($now > $row["canteenCloseHour"])) || ($row["canteenStatus"] == 0)) {
                                            ?>
                                            <span class="badge rounded-pill bg-danger">Closed</span>
                                        <?php } else { ?>
                                            <span class="badge rounded-pill bg-success">Open</span>
                                        <?php }
                                        if ($row["canteenPreOrderStatus"] == 1) {
                                            ?>
                                            <span class="badge rounded-pill bg-success">Pre-order avaliable</span>
                                        <?php } else { ?>
                                            <span class="badge rounded-pill bg-danger">Pre-order Unavaliable</span>
                                        <?php } ?>
                                    </p>
                                    <?php
                                    $open = explode(":", $row["canteenOpenHour"]);
                                    $close = explode(":", $row["canteenCloseHour"]);
                                    ?>
                                    <p class="card-text my-2">Open hours:
                                        <?php echo $open[0] . ":" . $open[1] . " - " . $close[0] . ":" . $close[1]; ?></p>
                                    <div class="text-end">
                                        <a href="<?php echo "canteenMenu.php?canteenId=" . $row["canteenId"] ?>"
                                            class="btn btn-sm btn-outline-dark">Go to canteen</a>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- END GRID EACH Canteen -->
                <?php }
            } else {
                ?>
                <div class="row row-cols-1 w-100">
                    <div class="col mt-4 pt-3 px-3 bg-danger text-white rounded text-center">
                        <p class="ms-2 mt-2">No canteen currently avaliable to order.</p>
                    </div>
                </div>
                <?php
            }
            // $result->freeResult();
            ?>
        </div>
        <!-- END GRID Canteen SELECTION -->

    </div>
</body>

</html>