<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <?php 
        session_start(); 
        include("../connectionDB.php"); 
        include('../head.php');
        if($_SESSION["utype"]!="admin"){
            header("location: ../restricted.php");
            exit(1);
        }
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../images/icon.png" rel="icon">
    <link href="../css/main.css" rel="stylesheet">
    <title>Admin Dashboard | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column">

    <?php include('navHeaderAdmin.php')?>

    <div class="d-flex text-center text-white promo-banner-bg py-3">
        <div class="p-lg-2 mx-auto my-3">
            <h1 class="display-5 fw-normal">ADMIN DASHBOARD</h1>
            <p class="lead fw-normal">Somaiya College Campus Canteen</p>
        </div>
    </div>

    <div class="container p-5" id="admin-dashboard">
        <h2 class="border-bottom pb-2"><i class="bi bi-graph-up"></i> System Status</h2>

        <!-- ADMIN GRID DASHBOARD -->
        <div class="row row-cols-1 row-cols-lg-2 align-items-stretch g-4 py-3">

            <!-- GRID OF CUSTOMER -->
            <div class="col">
                <a href="adminCustomerList.php" class="text-decoration-none text-dark">
                    <div class="card rounded-5 border-danger p-2">
                        <div class="card-body">
                            <h4 class="card-title">
                                Customer</h4>
                            <p class="card-text my-2">
                                <span class="h5">
                                    <?php
                                    $custQuery = "SELECT COUNT(*) AS count FROM customer;";
                                    $customerArr = $mysqli -> query($custQuery) -> fetch_array();
                                    echo $customerArr["count"];
                                ?>
                                </span>
                                customer(s) in the system
                            </p>
                            <div class="text-end">
                                <a href="adminCustomerList.php" class="btn btn-sm btn-outline-dark">Go to Customer List</a>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- END GRID OF CUSTOMER -->

            <!-- GRID OF canteen -->
            <div class="col">
                <a href="adminCanteenList.php" class="text-decoration-none text-dark">
                    <div class="card rounded-5 border-success p-2">
                        <div class="card-body">
                            <h4 class="card-title">
                                Canteen</h4>
                            <p class="card-text my-2">
                                <span class="h5">
                                    <?php
                                    $custQuery = "SELECT COUNT(*) AS count FROM canteen;";
                                    $custArr = $mysqli -> query($custQuery) -> fetch_array();
                                    echo $custArr["count"];
                                ?>
                                </span>
                                Canteen in the system
                            </p>
                            <div class="text-end">
                                <a href="adminCanteenList.php" class="btn btn-sm btn-outline-dark">Go to Canteen Food List</a>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- END GRID OF canteen -->

            <!-- GRID OF FOOD -->
            <div class="col">
                <a href="adminFoodList.php" class="text-decoration-none text-dark">
                    <div class="card rounded-5 border-primary p-2">
                        <div class="card-body">
                            <h4 class="card-title">
                                Menu</h4>
                            <p class="card-text my-2">
                                <span class="h5">
                                    <?php
                                    $custQuery = "SELECT COUNT(*) AS count FROM food;";
                                    $custArr = $mysqli -> query($custQuery) -> fetch_array();
                                    echo $custArr["count"];
                                ?>
                                </span>
                                menu(s) in the system
                            </p>
                            <div class="text-end">
                                <a href="adminFoodList.php" class="btn btn-sm btn-outline-dark">Go to Menu List</a>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- END GRID OF FOOD -->

            <!-- GRID OF ORDER -->
            <div class="col">
                <a href="admin_order_list.php" class="text-decoration-none text-dark">
                    <div class="card rounded-5 border-warning p-2">
                        <div class="card-body">
                            <h4 class="card-title">
                                Order</h4>
                            <p class="card-text my-2">
                                <span class="h5">
                                    <?php
                                    $custQuery = "SELECT COUNT(*) AS count FROM orderheader;";
                                    $custArr = $mysqli -> query($custQuery) -> fetch_array();
                                    echo $custArr["count"];
                                ?>
                                </span>
                                order(s) in the system
                            </p>
                            <div class="text-end">
                                <a href="adminOrderList.php" class="btn btn-sm btn-outline-dark">Go to Order List</a>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- END GRID OF ORDER -->


        </div>
        <!-- END ADMIN GRID DASHBOARD -->

    </div>
</body>

</html>