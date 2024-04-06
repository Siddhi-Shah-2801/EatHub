<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("../connectionDB.php");
    if ($_SESSION["utype"] != "canteenOwner") {
        header("location: ../restricted.php");
        exit(1);
    }
    $canteenId = $_SESSION["canteenId"];
    if (isset($_POST["upd_confirm"])) {
        if (isset($_POST["canteenStatus"])) {
            $canteenStatus = 1;
        } else {
            $canteenStatus = 0;
        }
        if (isset($_POST["canteenPreOrderStatus"])) {
            $canteenPreOrderStatus = 1;
        } else {
            $canteenPreOrderStatus = 0;
        }
        $canteenName = $_POST["canteenName"];
        $canteenUserName = $_POST["canteenUserName"];
        $canteenLocation = $_POST["canteenLocation"];
        $canteenEmail = $_POST["canteenEmail"];
        $canteenContactNo = $_POST["canteenContactNo"];
        $canteenOpenHour = $_POST["canteenOpenHour"];
        $canteenCloseHour = $_POST["canteenCloseHour"];
        $updateQuery = "UPDATE canteen SET canteenUserName = '{$canteenUserName}', canteenName = '{$canteenName}', canteenLocation = '{$canteenLocation}', canteenOpenHour = '{$canteenOpenHour}', 
            canteenCloseHour = '{$canteenCloseHour}', canteenEmail = '{$canteenEmail}', canteenContactNo = '{$canteenContactNo}', canteenStatus = {$canteenStatus}, canteenPreOrderStatus ={$canteenPreOrderStatus}
            WHERE canteenId = {$canteenId};";
        $updateResult = $mysqli->query($updateQuery);
        if (!empty($_FILES["canteenPic"]["name"])) {
            //Image upload
            $targetDir = '../images/';
            $temp = explode(".", $_FILES["canteenPic"]["name"]);
            $targetNewFileName = "canteen" . $canteenId . "." . strtolower(end($temp));
            $targetFile = $targetDir . $targetNewFileName;
            if (move_uploaded_file($_FILES["canteenPic"]["tmp_name"], SITE_ROOT . $targetFile)) {
                $updateQuery = "UPDATE canteen SET canteenPic = '{$targetNewFileName}' WHERE canteenId = {$canteenId};";
                $updateResult = $mysqli->query($updateQuery);
            } else {
                $updateResult = false;
            }
        }
        if ($updateResult) {
            $_SESSION["canteenName"] = $canteenName;
            header("location: canteenProfile.php?up_prf=1");
        } else {
            header("location: canteenProfile.php?up_prf=0");
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
    <title>Update canteen profile | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderCanteen.php') ?>

    <div class="container form-signin mt-auto w-50">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <?php
        //Select customer record from database
        $query = "SELECT canteenUserName,canteenName,canteenLocation,canteenOpenHour,canteenCloseHour,canteenStatus,canteenPreOrderStatus,canteenEmail,canteenContactNo FROM canteen WHERE canteenId = {$canteenId} LIMIT 0,1";
        $result = $mysqli->query($query);
        $row = $result->fetch_array();
        ?>
        <form method="POST" action="canteenProfileEdit.php" class="form-floating" enctype="multipart/form-data">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-pencil-square me-2"></i>Update canteen Information</h2>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="canteenstatus" name="canteenStatus" <?php if ($row["canteenStatus"]) {
                                                                                                            echo "checked";
                                                                                                        } ?>>
                <label class="form-check-label" for="canteenStatus">Opening for today</label>
            </div>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="canteenPreOrderStatus" name="canteenPreOrderStatus" <?php if ($row["canteenPreOrderStatus"]) {
                                                                                                                            echo "checked";
                                                                                                                        } ?>>
                <label class="form-check-label" for="canteenPreOrderStatus">Accepting Pre-Order</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="canteenUserName" placeholder="Username" name="canteenUserName" value="<?php echo $row["canteenUserName"]; ?>" required>
                <label for="canteenUserName">Username</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="canteenName" placeholder="canteen Name" value="<?php echo $row["canteenName"]; ?>" name="canteenName" required>
                <label for="canteenName">canteen Name</label>
            </div>
            <div class="form-floating mb-2">
                <input type="email" class="form-control" id="email" placeholder="E-mail" name="canteenEmail" value="<?php echo $row["canteenEmail"]; ?>" required>
                <label for="email">E-mail</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="canteenLocation" placeholder="Location" value="<?php echo $row["canteenLocation"]; ?>" name="canteenLocation" required>
                <label for="canteenLocation">canteen Location</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="canteenContactNo" placeholder="Phone Number" value="<?php echo $row["canteenContactNo"]; ?>" name="canteenContactNo" required>
                <label for="canteenContactNo">Contact Number</label>
            </div>
            <div class="row row-cols-2 g-2 mb-2">
                <div class="col">
                    <div class="form-floating">
                        <input type="time" class="form-control" id="canteenOpenHour" placeholder="Open Hour" value="<?php echo $row["canteenOpenHour"]; ?>" name="canteenOpenHour" required>
                        <label for="canteenOpenHour">Open Hour</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <input type="time" class="form-control" id="canteenCloseHour" placeholder="Close Hour" value="<?php echo $row["canteenCloseHour"]; ?>" name="canteenCloseHour" required>
                        <label for="canteenCloseHour">Close Hour</label>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <label for="formFile" class="form-label">Upload canteen image</label>
                <input class="form-control" type="file" id="canteenPic" name="canteenPic" accept="image/*">
            </div>
            <button class="w-100 btn btn-success mb-3" name="upd_confirm" type="submit">Update canteen Profile</button>
        </form>
    </div>
</body>

</html>