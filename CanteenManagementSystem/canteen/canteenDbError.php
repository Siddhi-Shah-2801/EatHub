<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('../head.php'); ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/customerLogin.css" rel="stylesheet">

    <title>Database Error | SOMAIYA</title>
</head>

<body class="d-flex flex-column h-100">
    <header class="navbar navbar-expand-md navbar-light fixed-top bg-light shadow-sm mb-auto">
        <div class="container-fluid mx-4">
            <a href="../viewPage.php">
                <img src="../images/canteenLogo.png" width="125" class="me-2" alt="somaiya canteen Logo">
            </a>
            <div class="navbar-collapse collapse" id="navbarCollapse">
                <div class="d-flex text-end"></div>
            </div>
        </div>
    </header>
    <div class="mt-5"></div>
    <div class="container form-signin text-center restricted mt-auto">
        <i class="mt-4 bi bi-hdd-network-fill text-danger h1 display-2"></i>
        <h3 class="mt-2 mb-3 fw-normal text-bold">Connection Error</h3>
        <p class="mb-3 fw-normal text-bold text-wrap">Cannot connect to somaiya canteen database right now.</p>
        <a class="btn btn-danger btn-sm w-50" href="../viewPage.php">Try again.</a>
    </div>
</body>

</html>