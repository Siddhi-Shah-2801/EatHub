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
            if(isset($_POST["canteenStatus"])){$canteenStatus = 1;}else{$canteenStatus = 0;}
            if(isset($_POST["canteenPreOrderStatus"])){$canteenPreOrderStatus = 1;}else{$canteenPreOrderStatus = 0;}
            $canteenName = $_POST["canteenName"];
            $canteenUserName = $_POST["canteenUserName"];
            $canteenLocation = $_POST["canteenLocation"];
            $canteenEmail = $_POST["canteenEmail"];
            $canteenContactNo = $_POST["canteenContactNo"];
            $canteenOpenHour = $_POST["canteenOpenHour"];
            $canteenCloseHour = $_POST["canteenCloseHour"];
            $updateQuery = "UPDATE canteen SET canteenUserName = '{$canteenUserName}', canteenName = '{$canteenName}', canteenLocation = '{$canteenLocation}', canteenOpenHour = '{$canteenOpenHour}', 
            canteenCloseHour = '{$canteenCloseHour}', canteenEmail = '{$canteenEmail}', canteenContactNo = '{$canteenContactNo}', canteenStatus = {$canteenStatus}, canteenPreOrderStatus = {$canteenPreOrderStatus}
            WHERE canteenId = {$canteenId};";
            $updateResult = $mysqli -> query($updateQuery);
            if(!empty($_FILES["canteenPic"]["name"])){
                //Image upload
                $targetDirectory = '/images/';
                $temp = explode(".",$_FILES["canteenPic"]["name"]);
                $targetNewFileName = "canteen".$canteenId.".".strtolower(end($temp));
                $targetFile = $targetDirectory.$targetNewFileName;
                if(moveUploadedFile($_FILES["canteenPic"]["tempName"],SITE_ROOT.$targetFile)){
                    $updateQuery = "UPDATE canteen SET canteenPic = '{$targetNewFileName}' WHERE canteenId = {$canteenId};";
                    $updateResult = $mysqli -> query($updateQuery);
                }else{
                    $updateResult = false;
                }
            }
            if($updateResult){header("location: adminCanteenList.php?updateCanteenFood=1");}
            else{header("location: adminCanteenList.php?updateCanteenFood=0");}
            exit(1);
        }
        include('../head.php');
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/login.css" rel="stylesheet">
    <title>Update canteen information | EATERIO</title>
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
            $query = "SELECT canteenUserName,canteenName,canteenLocation,canteenOpenHour,canteenCloseHour,canteenStatus,canteenPreOrderStatus,canteenEmail,canteenContactNo FROMcanteen WHERE canteenId = {$canteenId} LIMIT 0,1";
            $result = $mysqli ->query($query);
            $row = $result -> fetch_array();
        ?>
        <form method="POST" action="adminCanteenEdit.php" class="form-floating" enctype="multipart/form-data">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-pencil-square me-2"></i>Updatecanteen Information</h2>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="canteenstatus"
                name="canteenStatus" <?php if($row["canteenStatus"]){echo "checked";} ?>>
                <label class="form-check-label" for="canteenstatus">Opening for today</label>
            </div>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="canteenpreorderstatus" name="canteenPreOrderStatus" <?php if($row["canteenPreOrderStatus"]){echo "checked";} ?>>
                <label class="form-check-label" for="canteenpreorderstatus">Accepting Pre-Order</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="canteenusername" placeholder="Username" name="canteenUserName"
                value="<?php echo $row["canteenUserName"];?>" required>
                <label for="canteenname">Username</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="canteenname" placeholder="canteen Name" value="<?php echo $row["canteenName"];?>" name="canteenName" required>
                <label for="canteenname">canteen Name</label>
            </div>
            <div class="form-floating mb-2">
                <input type="email" class="form-control" id="email" placeholder="E-mail" name="canteenEmail" value="<?php echo $row["canteenEmail"];?>" required>
                <label for="email">E-mail</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="canteenlocation" placeholder="Location" value="<?php echo $row["canteenLocation"];?>" name="canteenLocation" required>
                <label for="canteenlocation">canteen Location</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="canteenphoneno" placeholder="Phone Number" value="<?php echo $row["canteenContactNo"];?>" name="canteenContactNo" required>
                <label for="canteenphoneno">Phone Number</label>
            </div>
            <div class="row row-cols-2 g-2 mb-2">
                <div class="col">
                    <div class="form-floating">
                        <input type="time" class="form-control" id="canteenopenhour" placeholder="Open Hour" value="<?php echo $row["canteenOpenHour"];?>" name="canteenOpenHour" required>
                        <label for="canteenopenhour">Open Hour</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <input type="time" class="form-control" id="canteenclosehour" placeholder="Close Hour" value="<?php echo $row["canteenCloseHour"];?>" name="canteenCloseHour" required>
                        <label for="canteenopenhour">Close Hour</label>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <label for="formFile" class="form-label">Uploadcanteen image</label>
                <input class="form-control" type="file" id="canteenPic" name="canteenPic" accept="image/*">
            </div>
            <input type="hidden" name="canteenId" value="<?php echo $canteenId;?>">
            <button class="w-100 btn btn-success mb-3" name="updateConfirm" type="submit">Updatecanteen</button>
        </form>
    </div>
</body>

</html>