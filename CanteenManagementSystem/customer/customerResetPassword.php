<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include('../connectionDB.php');
    include('../head.php');
    if (isset($_POST["resetConfirm"])) {
        $customerId = $_POST['customerId'];
        $newPassword = $_POST['newPassword'];
        $newCfPassword = $_POST['newCfPassword'];
        if ($newPassword == $newCfPassword) {
            $query = "UPDATE customer SET customerPassword = '$newPassword' WHERE customerId=$customerId";
            $result = $mysqli->query($query);
            if ($result) {
                header("location:customerResetSuccess.php");
            } else {
                header("location:customerResetFail.php?err={$mysqli->errno}");
            }
            exit();
        } else {
            ?>
            <script>
                alert("Your new password does not match. \nPlease enter it again.");
                history.back();
            </script>
            <?php
        }
    } else {
        $customerUserName = $_POST["fpUserName"];
        $customerEmail = $_POST["fpEmail"];
        $query = "SELECT customerFirstName,customerLastName,customerId FROM customer WHERE customerUserName = '$customerUserName' AND customerEmail = '$customerEmail' LIMIT 0,1";
        $result = $mysqli->query($query);
        if ($result->num_rows == 0) {
            ?>
            <script>
                alert("There is no account associated with this username and password");
                history.back();
            </script>
            <?php
            exit(1);
        } else {
            $row = $result->fetch_array();
            $customerId = $row["customerId"];
            $customerFirstName = $row["customerFirstName"];
            $customerLastName = $row["customerLastName"];
        }
    }
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/customerLogin.css" rel="stylesheet">
    <title>Reset Password | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <header class="navbar navbar-light fixed-top bg-light shadow-sm mb-auto">
        <div class="container-fluid mx-4">
            <a href="../viewPage.php">
                <img src='../images/canteenLogo.png' width="125" class="me-2" alt="Somaiya Canteen Logo">
            </a>
        </div>
    </header>

    <div class="container form-signin mt-auto">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <form method="POST" action='customerResetPassword.php' class="form-floating">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-key me-2"></i>Reset Password</h2>
            <p class="mt-4 fw-normal">Enter your information below.<br />
                This is an account of <?php echo $customerFirstName . " " . $customerLastName; ?></p>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="resetPassword" minlength="8" maxlength="45"
                    placeholder="New Password" name="newPassword" required>
                <label for="fpUserName">New Password</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="resetPassword" minlength="8" maxlength="45"
                    placeholder="Confirm New Password" name="newCfPassword" required>
                <label for="fpEmail">Confirm New Password</label>
            </div>
            <input type="hidden" name="customerId" value="<?= $customerId ?>">
            <button class="w-100 btn btn-success mb-3" name="resetConfirm" type="submit">Reset Password</button>
        </form>
    </div>
</body>

</html>