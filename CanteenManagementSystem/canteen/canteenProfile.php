<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    if ($_SESSION["utype"] != "canteenOwner") {
        header("location: ../restricted.php");
        exit(1);
    }
    include("../connectionDB.php");
    include('../head.php');
    $canteenId = $_SESSION["canteenId"];
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <title>Canteen Profile | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderCanteen.php'); ?>

    <div class="container px-5 pt-4" id="profile-body">
        <div class="row my-4 pb-2 border-bottom">
            <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
                <i class="bi bi-arrow-left-square me-2"></i>Go back
            </a>

            <?php
            if (isset($_GET["up_pwd"])) {
                if ($_GET["up_pwd"] == 1) {
            ?>
                    <!-- START SUCCESSFULLY UPDATE PASSWORD -->
                    <div class="row row-cols-1 notibar">
                        <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                            <span class="ms-2 mt-2">Successfully updated the password!</span>
                            <span class="me-2 float-end"><a class="text-decoration-none link-light" href="canteenProfile.php">X</a></span>
                        </div>
                    </div>
                    <!-- END SUCCESSFULLY UPDATE PASSWORD -->
                <?php } else { ?>
                    <!-- START FAILED UPDATE PASSWORD -->
                    <div class="row row-cols-1 notibar">
                        <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                            <span class="ms-2 mt-2">Failed to update the password.</span>
                            <span class="me-2 float-end"><a class="text-decoration-none link-light" href="canteenProfile.php">X</a></span>
                        </div>
                    </div>
                    <!-- END FAILED UPDATE PASSWORD -->
                <?php }
            }
            if (isset($_GET["up_prf"])) {
                if ($_GET["up_prf"] == 1) {
                ?>
                    <!-- START SUCCESSFULLY UPDATE PASSWORD -->
                    <div class="row row-cols-1 notibar">
                        <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                            <span class="ms-2 mt-2">Successfully updated Canteen profile!</span>
                            <span class="me-2 float-end"><a class="text-decoration-none link-light" href="canteenProfile.php">X</a></span>
                        </div>
                    </div>
                    <!-- END SUCCESSFULLY UPDATE PASSWORD -->
                <?php } else { ?>
                    <!-- START FAILED UPDATE PASSWORD -->
                    <div class="row row-cols-1 notibar">
                        <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                            <span class="ms-2 mt-2">Failed to update Canteen profile.</span>
                            <span class="me-2 float-end"><a class="text-decoration-none link-light" href="canteenProfile.php">X</a></span>
                        </div>
                    </div>
                    <!-- END FAILED UPDATE PASSWORD -->
            <?php }
            }
            ?>

            <h2 class="pt-3 display-6">Canteen Profile</h2>
        </div>

        <a class="btn btn-sm btn-outline-secondary me-2" href="canteenUpdatePassword.php">
            Change password
        </a>
        <a class="btn btn-sm btn-primary mt-2 mt-md-0" href="canteenProfileEdit.php">
            Update Canteen profile
        </a>

        <!-- START CUSTOMER INFORMATION -->
        <?php
        //Select customer record from database
        $query = "SELECT canteenUserName,canteenName,canteenLocation,canteenOpenHour,canteenCloseHour,canteenStatus,canteenPreOrderStatus,canteenEmail,canteenContactNo,canteenPic FROM canteen WHERE canteenId = {$canteenId} LIMIT 0,1";
        $result = $mysqli->query($query);
        $row = $result->fetch_array();
        ?>
        <div class="row row-cols-1 mt-4">
            <div class="rounded-25 mb-4" id="canteenImage" style="
                    background: url(
                        <?php
                        if (is_null($row["canteenPic"])) {
                            echo "'../images/canteenLogo.png'";
                        } else {
                            echo "'../images/{$row['canteenPic']}'";
                        }
                        ?> 
                    ) center; height: 200px; width:500px;
                    background-size: cover; background-repeat: no-repeat;
                    background-position: center;">
            </div>
            <dl class="row">
                <dt class="col-sm-3">Username</dt>
                <dd class="col-sm-9"><?php echo $row["canteenUserName"]; ?></dd>
                <dt class="col-sm-3">Canteen Name</dt>
                <dd class="col-sm-9"><?php echo $row["canteenName"]; ?></dd>
                <dt class="col-sm-3">Canteen Location</dt>
                <dd class="col-sm-9"><?php echo $row["canteenLocation"]; ?></dd>
                <dt class="col-sm-3">canteen Opening Hours</dt>
                <dd class="col-sm-9">
                    <?php
                    $current_time = date('H:i:s');
                    if ($current_time >= $row["canteenOpenHour"] && $current_time <= $row["canteenCloseHour"]) {
                    ?><span class="badge fs-6 bg-success">Canteen-front Opening</span> <?php
                                                                                    } else {
                                                                                        ?><span class="badge fs-6 bg-danger">Canteen-front Closed</span> <?php
                                                                                                                                                        }
                                                                                                                                                        $open = explode(":", $row["canteenOpenHour"]);
                                                                                                                                                        $close = explode(":", $row["canteenCloseHour"]);
                                                                                                                                                        echo $open[0] . ":" . $open[1] . " - " . $close[0] . ":" . $close[1];
                                                                                                                                                            ?>
                </dd>
                <dt class="col-sm-3">Canteen Operation Status</dt>
                <dd class="col-sm-9">
                    <?php if ($row["canteenStatus"] == 1) { ?>
                        <span class="badge fs-6 bg-success">Avaliable for Store-Front</span>
                    <?php } else { ?>
                        <span class="badge fs-6 bg-danger">Unavaliable for Store-Front</span>
                    <?php }
                    if ($row["canteenPreOrderStatus"] == 1) { ?>
                        <span class="badge fs-6 bg-success">Avaliable for Pre-Order</span>
                    <?php } else { ?>
                        <span class="badge fs-6 bg-danger">Unavaliable for Pre-Order</span>
                    <?php } ?>
                </dd>
                <dt class="col-sm-3">E-mail</dt>
                <dd class="col-sm-9"><?php echo $row["canteenEmail"]; ?></dd>
                <dt class="col-sm-3">Contact Number</dt>
                <dd class="col-sm-9"><?php echo "(+91) " . $row["canteenContactNo"]; ?></dd>
            </dl>
        </div>
        <!-- END CUSTOMER INFORMATION -->
    </div>
</body>

</html>