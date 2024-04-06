<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        session_start(); 
        include("../connectionDB.php"); 
        if($_SESSION["utype"]!="admin"){
            header("location: ../restricted.php");
            exit(1);
        }
        if(isset($_POST["updateConfirm"])){
            $canteenId = $_POST["canteenId"];
            $foodId = $_POST["foodId"];
            if(isset($_POST["foodTodayAvailable"])){$foodTodayAvailable = 1;}else{$foodTodayAvailable = 0;}
            if(isset($_POST["foodPreOrderAvailable"])){$foodPreOrderAvailable = 1;}else{$foodPreOrderAvailable = 0;}
            $foodName = $_POST["foodName"];
            $foodPrice = $_POST["foodPrice"];
            $updateQuery = "UPDATE food SET foodName = '{$foodName}', foodPrice = '{$foodPrice}', foodTodayAvailable = '{$foodTodayAvailable}',
            foodPreOrderAvailable = '{$foodPreOrderAvailable}' WHERE foodId = {$foodId};";
            $updateResult = $mysqli -> query($updateQuery);
            if(!empty($_FILES["foodPic"]["name"])){
                //Image upload
                $targetDirectory = '/images/';
                $temp = explode(".",$_FILES["foodPic"]["name"]);
                $targetNewFileName = $foodId."_".$canteenId.".".strtolower(end($temp));
                $targetFile = $targetDirectory.$targetNewFileName;
                if(moveUploadedFile($_FILES["foodPic"]["tempName"],SITE_ROOT.$targetFile)){
                    $updateQuery = "UPDATE food SET foodPic = '{$targetNewFileName}' WHERE foodId = {$foodId};";
                    $updateResult = $mysqli -> query($updateQuery);
                }else{
                    $updateResult = false;
                }
            }
            if($updateResult){header("location: adminFoodDetail.php?foodId={$foodId}&updateFoodItem=1");}
            else{header("location: adminFoodDetail.php?foodId={$foodId}&updateFoodItem=0");}
            exit(1);
        }
        include('../head.php');
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/login.css" rel="stylesheet">
    <title>Update menu detail | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderAdmin.php')?>

    <div class="container form-signin mt-auto w-50">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <?php 
            //Select customer record from database
            $canteenId = $_GET["canteenId"];
            $foodId = $_GET["foodId"];
            $query = "SELECT * FROM food WHERE foodId = {$foodId} LIMIT 0,1";
            $result = $mysqli ->query($query);
            $row = $result -> fetch_array();
        ?>
        <form method="POST" action="adminFoodEdit.php" class="form-floating" enctype="multipart/form-data">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-pencil-square me-2"></i>Update Menu Detail</h2>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="foodTodayAvailable"
                name="foodTodayAvailable" <?php if($row["foodTodayAvailable"]){echo "checked";} ?>>
                <label class="form-check-label" for="foodTodayAvailable">Menu avaliable for today</label>
            </div>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="foodPreOrderAvailable" name="foodPreOrderAvailable" <?php if($row["foodPreOrderAvailable"]){echo "checked";} ?>>
                <label class="form-check-label" for="foodPreOrderAvailable">Accepting Pre-order for this menu</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="foodName" placeholder="foodName" name="foodName"
                value="<?php echo $row["foodName"];?>" required>
                <label for="foodName">Menu Name</label>
            </div>
            <div class="form-floating mb-2">
                <input type="number" step=".25" min="0.00" max="999.75" class="form-control" id="foodPrice" placeholder="Price (Rs)" value="<?php echo $row["foodPrice"];?>" name="foodPrice" required>
                <label for="foodPrice">Price (Rs)</label>
            </div>
            <div class="mb-2">
                <label for="formFile" class="form-label">Upload food image</label>
                <input class="form-control" type="file" id="foodPic" name="foodPic" accept="image/*">
            </div>
            <input type="hidden" name="canteenId" value="<?php echo $canteenId;?>">
            <input type="hidden" name="foodId" value="<?php echo $foodId;?>">
            <button class="w-100 btn btn-success mb-3" name="updateConfirm" type="submit">Update Menu Detail</button>
        </form>
    </div>
</body>

</html>