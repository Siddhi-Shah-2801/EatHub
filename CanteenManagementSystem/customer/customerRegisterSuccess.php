<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include('../connectionDB.php');
    include('../head.php'); ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/customerLogin.css" rel="stylesheet">

    <title>Successfully Registered | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <header class="navbar navbar-light fixed-top bg-light shadow-sm mb-auto">
        <div class="container-fluid mx-4">
            <a href="../viewPage.php">
                <img src='../images/canteenLogo.png' width="125" class="me-2" alt="Somaiya Canteen Logo">
            </a>
        </div>
    </header>
    <div class="mt-5"></div>
    <div class="container form-signin text-center reg-success mt-auto">
        <i class="mt-4 bi bi-check-circle text-success h1 display-2"></i>
        <h3 class="mt-2 mb-3 fw-normal text-bold">Your account is ready!</h3>
        <p class="mb-3 fw-normal text-bold">Welcome and enjoy your food with Somaiyans</p>
        <a class="btn btn-success btn-sm w-50" href="../viewPage.php">Return to Home</a>
    </div>
</body>