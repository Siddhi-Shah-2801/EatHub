<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("../connectionDB.php");
    include("../head.php"); ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/customerLogin.css" rel="stylesheet">

    <title>Canteen LOG IN | SOMAIYA Canteen </title>
</head>

<body class="d-flex flex-column h-100">
    <header class="navbar navbar-expand-md navbar-light fixed-top bg-light shadow-sm mb-auto">
        <div class="container-fluid mx-4">
            <a href="../viewPage.php">
                <img src="../images/canteenLogo.png" width="125" height="60" class="me-2" alt="SOMAIYA CANTEEN LOGO">
            </a>
        </div>
    </header>

    <div class="container form-signin">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <form method="POST" action="checkCanteenLogin.php" class="form-floating">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-door-open me-2"></i>Canteen Log In</h2>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username">
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="pwd">
                <label for="floatingPassword">Password</label>
            </div>
            <button class="w-100 btn btn-success mb-3" type="submit">Log In</button>
        </form>
    </div>
</body>

</html>