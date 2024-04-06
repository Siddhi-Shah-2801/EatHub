<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("../connectionDB.php");
    if ($_SESSION["utype"] != "admin") {
        header("location: ../restricted.php");
        exit(1);
    }
    if (isset($_POST["addConfirm"])) {
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
        $canteenContactNO = $_POST["canteenContactNo"];
        $canteenOpenHour = $_POST["canteenOpenHour"];
        $canteenCloseHour = $_POST["canteenCloseHour"];
        $insertQuery = "INSERT INTO canteen (canteenUserName,canteenName,canteenLocation,canteenOpenHour,canteenCloseHour,canteenEmail,canteenContactNO,canteenStatus,canteenPreOrderStatus) 
            VALUES ('{$canteenUserName}','{$}','{$canteenLocation}','{$canteenOpenHour}','{$canteenCloseHour}','{$canteenEmail}','{$canteenContactNO}',{$canteenStatus},{$canteenPreOrderStatus});";
        $insertResult = $mysqli->query($insertQuery);
        if (!empty($_FILES["canteenPic"]["name"]) && $insertResult) {
            //Image upload
            $canteenId = $mysqli->insert_id;
            $targetDirectory = '../images/';
            $temp = explode(".", $_FILES["canteenPic"]["name"]);
            $targetNewFileName = "canteen" . $canteenId . "." . strtolower(end($temp));
            $targetFile = $targetDirectory . $targetNewFileName;
            if (move_uploaded_file($_FILES["canteenPic"]["tmp_name"], SITE_ROOT . $targetFile)) {
                $insertQuery = "UPDATE canteen SET canteenPic = '{$targetNewFileName}' WHERE canteenId = {$canteenId};";
                $insertResult = $mysqli->query($insertQuery);
            } else {
                $insertResult = false;
            }
        }
        if ($insertResult) {
            header("location: adminCanteenList.php?addCanteen=1");
        } else {
            header("location: adminCanteenList.php?addCanteen=0");
        }
        exit(1);
    }
    include('../head.php');
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/customerLogin.css" rel="stylesheet">
    <title>Add new canteen | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderAdmin.php') ?>

    <div class="container form-signin mt-auto w-50">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <form method="POST" action="adminCanteenAdd.php" class="form-floating" enctype="multipart/form-data">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-pencil-square me-2"></i>Add New Canteen</h2>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="canteenStatus" name="canteenStatus" checked>
                <label class="form-check-label" for="canteenStatus">Opening for today</label>
            </div>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="canteenPreOrderStatus" name="canteenPreOrderStatus" checked>
                <label class="form-check-label" for="canteenPreOrderStatus">Accepting Pre-Order</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="canteenUserName" placeholder="Username" name="canteenUserName" required>
                <label for="canteenName">Username</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="canteenName" placeholder="Canteen Name" name="canteenName" required>
                <label for="canteenName">Canteen Name</label>
            </div>
            <div class="form-floating mb-2">
                <input type="email" class="form-control" id="canteenEmail" placeholder="E-mail" name="canteenEmail" required>
                <label for="canteenEmail">E-mail</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="canteenLocation" placeholder="Location" name="canteenLocation" required>
                <label for="canteenLocation">Canteen Location</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="canteenContactNo" placeholder="Contact Number" name="canteenContactNo" required>
                <label for="canteenContactNo">Contact Number</label>
            </div>
            <div class="row row-cols-2 g-2 mb-2">
                <div class="col">
                    <div class="form-floating">
                        <input type="time" class="form-control" id="canteenOpenHour" placeholder="Open Hour" name="canteenOpenHour" required>
                        <label for="canteenOpenHour">Open Hour</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <input type="time" class="form-control" id="canteenCloseHour" placeholder="Close Hour" name="canteenCloseHour" required>
                        <label for="canteenOpenHour">Close Hour</label>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <label for="formFile" class="form-label">Upload canteen image</label>
                <input class="form-control" type="file" id="canteenPic" name="canteenPic" accept="image/*">
            </div>
            <button class="w-100 btn btn-success mb-3" name="addConfirm" type="submit">Add new Canteen</button>
        </form>
    </div>
</body>

</html>