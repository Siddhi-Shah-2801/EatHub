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
    if (isset($_POST["addConfirm"])) {
        if (isset($_POST["foodTodayAvailable"])) {
            $foodTodayAvailable = 1;
        } else {
            $foodTodayAvailable = 0;
        }
        if (isset($_POST["foodPreOrderAvailable"])) {
            $foodPreOrderAvailable = 1;
        } else {
            $foodPreOrderAvailable = 0;
        }
        $foodName = $_POST["foodName"];
        $foodPrice = $_POST["foodPrice"];
        $insertQuery = "INSERT INTO food (foodName,foodPrice,canteenId,foodTodayAvailable,foodPreOrderAvailable) 
            VALUES ('{$foodName}',{$foodPrice},{$canteenId},{$foodTodayAvailable},{$foodPreOrderAvailable});";
        $insertResult = $mysqli->query($insertQuery);
        if (!empty($_FILES["foodPic"]["name"]) && $insertResult) {
            //Image upload
            $foodId = $mysqli->insert_id;
            $targetDir = '../images/';
            $temp = explode(".", $_FILES["foodPic"]["name"]);
            $targetNewFileName = $foodId . "_" . $canteenId . "." . strtolower(end($temp));
            $targetFile = $targetDir . $targetNewFileName;
            if (move_Uploaded_File($_FILES["foodPic"]["tmp_name"], SITE_ROOT . $targetFile)) {
                $insertQuery = "UPDATE food SET foodPic = '{$targetNewFileName}' WHERE foodId = {$foodId};";
                $insertResult = $mysqli->query($insertQuery);
            } else {
                $insertResult = false;
            }
        }
        if ($insertResult) {
            header("location: canteenMenuList.php?addfdt=1");
        } else {
            header("location: canteenMenuList.php?addfdt=0");
        }
        exit(1);
    }
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/login.css" rel="stylesheet">
    <title>Canteen Add Menu | somaiya canteen</title>
</head>


<body class="d-flex flex-column h-100">
    <?php include('navHeaderCanteen.php') ?>

    <div class="container form-signin mt-auto w-50">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <form method="POST" action="canteenMenuAdd.php" class="form-floating" enctype="multipart/form-data">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-pencil-square me-2"></i>Add New Menu</h2>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="foodTodayAvailable" name="foodTodayAvailable" checked>
                <label class="form-check-label" for="foodTodayAvailable">Menu avaliable for today</label>
            </div>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="foodPreOrderAvailable" name="foodPreOrderAvailable" checked>
                <label class="form-check-label" for="foodPreOrderAvailable">Accepting Pre-order for this menu</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="foodName" placeholder="foodName" name="foodName" required>
                <label for="foodName">Menu Name</label>
            </div>
            <div class="form-floating mb-2">
                <input type="number" step=".25" min="0.00" max="999.75" class="form-control" id="foodPrice" placeholder="Price (Rs)" name="foodPrice" required>
                <label for="foodPrice">Price (RS)</label>
            </div>
            <div class="mb-2">
                <label for="formFile" class="form-label">Upload food image</label>
                <input class="form-control" type="file" id="foodPic" name="foodPic" accept="image/*">
            </div>
            <button class="w-100 btn btn-success mb-3" name="addConfirm" type="submit">Add New Menu</button>
        </form>
    </div>
</body>

</html>