<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("../connectionDB.php");
    include('../head.php'); ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="../css/customerLogin.css" rel="stylesheet">
    <link href='../images/icon.png' rel="icon">
    <title>Log in | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <header class="navbar navbar-light fixed-top bg-light shadow-sm mb-auto">
        <div class="container-fluid mx-4">
            <a href='../viewPage.php'>
                <img src='../images/icon.png' width="125" class="me-2" alt="Somaiya Canteen Logo">
            </a>
        </div>
    </header>

    <div class="container form-signin mt-auto">
        <form method="POST" action="checkAdminLogin.php" class="form-floating">
            <h2 class="mt-5 mb-3 fw-normal text-bold"><i class="bi bi-door-open me-2"></i>Admin Log In</h2>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username"
                    required>
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password"
                    required>
                <label for="floatingPassword">Password</label>
            </div>
            <button class="w-100 btn btn-success mb-3" type="submit">Log In</button>
        </form>
</body>

</html>