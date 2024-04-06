<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        session_start(); 
        include("../connectionDB.php"); 
        if($_SESSION["utype"]!="canteenOwner"){
            header("location: ../restricted.php");
            exit(1);
        }
        $canteenId = $_SESSION["canteenId"];
        if(isset($_POST["rst_confirm"])){
            $oldpwd = $_POST["old_pwd"];
            $newpwd = $_POST["new_pwd"];
            $newcfpwd = $_POST["new_cfpwd"];
            if($newpwd != $newcfpwd){
                ?>
                    <script>
                        alert('The new password is not match.\nPlease re-enter again.');
                        history.back();
                    </script>;
                <?php
                exit(1);
            }else{
                $query = "SELECT canteenPassword FROM canteen WHERE canteenId = {$canteenId} LIMIT 0,1;";
                $result = $mysqli -> query($query);
                $row = $result -> fetch_array();
                if($oldpwd == $row["canteenPassword"]){
                    $query = "UPDATE canteen SET canteenPassword = '{$newpwd}' WHERE canteenId = {$canteenId};";
                    $result = $mysqli -> query($query);
                    if($result){
                        header("location: canteenProfile.php?up_pwd=1");
                    }else{
                        header("location: canteenProfile.php?up_pwd=0");
                    }
                }else{
                    ?>
                    <script>
                        alert('Your old password is not match.\nPlease re-enter again.');
                        history.back();
                    </script>
                    <?php
                    exit(1);
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
    <?php include('navHeaderCanteen.php')?>

    <div class="container form-signin mt-auto w-50">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <form method="POST" action="canteenUpdatePassword.php" class="form-floating">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-key me-2"></i>Update Canteen Password</h2>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="old_pwd" minlength="8" maxlength="45" placeholder="Old Password" name="old_pwd"
                    required>
                <label for="old_pwd">Old Password</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="rst_pwd" minlength="8" maxlength="45" placeholder="New Password" name="new_pwd"
                    required>
                <label for="rst_pwd">New Password</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="rst_cfpwd" minlength="8" maxlength="45" placeholder="Confirm New Password"
                    name="new_cfpwd" required>
                <label for="rst_cfpwd">Confirm New Password</label>
                <div id="passwordHelpBlock" class="form-text smaller-font">
                    New password must be at least 8 characters long.
                </div>
            </div>
            <button class="w-100 btn btn-success my-3" name="rst_confirm" type="submit" onclick="return confirm('Do you want to update the canteen password?');" >Update Canteen Password</button>
        </form>
    </div>

   
</body>

</html>