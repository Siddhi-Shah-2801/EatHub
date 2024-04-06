<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("../connectionDB.php");
    include("../head.php");
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="../css/customerLogin.css" rel="stylesheet">
    <title>
        LOG IN | SOMAIYA CANTEEN
    </title>
</head>

<body class="d-flex flex-column h-100">
    <header class="navbar navbar-light fixed-top bg-light shadow-sm mb-auto">
        <div class="container-fluid mx-4">
            <a href="../viewPage.php">
                <img src="../images/canteenLogo.png" width=100 class="me-2" alt="Somaiya Canteen Logo">
            </a>
        </div>
    </header>
    <div class="container form-signin mt-auto">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();"><i
                class="bi bi-arrow-left-square me-2"></i> GO back
        </a>
        <form method="POST" action="checkLogin.php" class="form-floating">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-door-open me-2"></i>LOG IN </h2>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="floatingInput" name="username" placeholder="Username" required><label
                    for="floatingInput">Username</label>
            </div>
            <div class="form-floating mb-2"> 
                <input type="password" class="form-control" id="floatingPassword" placeholder="password" name="pwd"
                    required>
                <label for="floatingPassword">password</label>
            </div>
            <button class="w-100 btn btn-sucess mb-3" type="submit">LOG IN</button><br><br>
            <a class="nav nav-item text-decoration-none text-muted mb-2 small" href="../canteen/canteenLogin.php">
                <i class="bi bi-canteen me-2"></i>Log In To Your Canteen Account
            </a><br><br>
            <a class="nav nav-item text-decoration-none text-muted mb-2 small" href="../admin/adminLogin.php">
                <i class="bi bi-canteen me-2"></i>Log In To Your Admin Account
            </a><br><br>
            <a class="nav nav-item text-decoration-none text-muted mb-2 small" href="customerForgotPassword.php">
                <i class="bi bi-key me-2"></i>Forgot Your Password?
            </a><br><br>
            <a class="nav nav-item text-decoration-none text-muted mb-2 small" href="custRegister.php">
                <i class="bi-bi-person-plus me-2"></i> Create Your New Account
            </a><br><br>
        </form>
    </div>
</body>
</html>