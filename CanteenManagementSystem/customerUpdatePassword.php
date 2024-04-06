<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("connectionDB.php");
    if (!isset($_SESSION["customerId"])) {
        header("location: restricted.php");
        exit(1);
    }
    if (isset($_POST["resetconfirm"])) {
        $oldpwd = $_POST["oldpwd"];
        $newpwd = $_POST["newpwd"];
        $newcfpwd = $_POST["newcfPwd"];
        if ($newpwd != $newcfpwd) {
             $query = "SELECT customerPassword FROM customer WHERE customerId = {$_SESSION['customerId']} LIMIT 0,1";
            $result = $mysqli->query($query);
            $row = $result->fetch_array();
            if ($oldpwd == $row["customerPassword"]) {
                $query = "UPDATE customer SET customerpassword = '{$newpwd}' WHERE customerId = {$_SESSION['customerId']}";
                $result = $mysqli->query($query);
                if ($result) {
                    header("location: customerProfile.php?updatePassword=1");
                } else {
                    header("location: customerProfile.php?updatePassword=0");
                }
             }
            
        } else {
            ?>
            <script>
                alert('Your new password does not match.\nPlease re-enter again.');
                history.back();
            </script>
            <?php
            exit(1);
           
            //  else {
                 ?>
            <!-- //     <script>
            //         alert('Your old password is not match.\nPlease re-enter again.');
            //         history.back();
            //     </script> -->
             <?php
            //     exit(1);
            // }
        }
    }

    include('head.php');
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/customerLogin.css" rel="stylesheet">
    <title>Update password | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderCustomer.php') ?>

    <div class="container form-signin mt-auto w-50">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <form method="POST" action="customerUpdatePassword.php" class="form-floating">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-key me-2"></i>Update Password</h2>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="oldpwd" minlength="8" maxlength="45"
                    placeholder="Old Password" name="oldpwd" required>
                <label for="oldpwd">Old Password</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="rstpwd" minlength="8" maxlength="45"
                    placeholder="New Password" name="newpwd" required>
                <label for="rstpwd">New Password</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="resetcfpwd" minlength="8" maxlength="45"
                    placeholder="Confirm New Password" name="newcfpwd" required>
                <label for="resetcfpwd">Confirm New Password</label>
                <div id="passwordHelpBlock" class="form-text smaller-font">
                    Your password must be at least 8 characters long.
                </div>
            </div>
            <button class="w-100 btn btn-success my-3" name="resetconfirm" type="submit"
                onclick="return confirm('Do you want to update your password?');">Update Password</button>
        </form>
    </div>
</body>

</html>