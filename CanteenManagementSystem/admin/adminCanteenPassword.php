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
        if(isset($_POST["resetConfirm"])){
            $canteenId = $_POST["canteenId"];
            $newPassword = $_POST["newPassword"];
            $newConfirmPassword= $_POST["newConfirmPassword"];
            if($newPassword != $newConfirmPassword){
                ?>
                    <script>
                        alert('The new password is not match.\nPlease re-enter again.');
                        history.back();
                    </script>;
                <?php
                exit(1);
            }else{
                $query = "UPDATE canteen SETcanteenPassword = '{$newPassword}' WHERE canteenId = {$canteenId}";
                $result = $mysqli -> query($query);
                if($result){
                    header("location: adminCanteenDetail.php?canteenId={$canteenId}&updatePassword=1");
                }else{
                    header("location: adminCanteenDetail.php?canteenId={$canteenId}&updatePassword=0");
                }
            }
        }

        include('../head.php');
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/login.css" rel="stylesheet">
    <title>Update canteen password | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderAdmin.php')?>

    <div class="container form-signin mt-auto w-50">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <form method="POST" action="adminCanteenPassword.php" class="form-floating">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-key me-2"></i>Update canteen Password</h2>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="resetPassword" minlength="8" maxlength="45" placeholder="New Password" name="newPassword"
                    required>
                <label for="resetPassword">New Password</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="resetConfirmPassword" minlength="8" maxlength="45" placeholder="Confirm New Password"
                    name="newConfirmPassword" required>
                <label for="resetConfirmPassword">Confirm New Password</label>
                <div id="passwordHelpBlock" class="form-text smaller-font">
                    New password must be at least 8 characters long.
                </div>
            </div>
            <input type="hidden" name="canteenId" value="<?php echo $_GET["canteenId"]?>">
            <button class="w-100 btn btn-success my-3" name="resetConfirm" type="submit" onclick="return confirm('Do you want to update this canteen password?');" >Update Password</button>
        </form>
    </div>
</body>

</html>