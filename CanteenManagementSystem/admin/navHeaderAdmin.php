<!--    NAV HEADER FOR ADMIN SIDE PAGE   -->

<header class="navbar navbar-expand-md navbar-light fixed-top bg-light shadow-sm mb-auto">
    <div class="container-fluid mx-4">
        <a href="adminHome.php">
            <img src="../images/canteenLogo.png" width="125" height="60" class="me-2" alt="Somaiya Canteen Logo">
        </a>
        <div class="navbar-collapse collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link px-2 text-dark" href="adminHome.php">Home</a>
                </li>
                <li class="nav-item">
                    <a href="adminCustomerList.php" class="nav-link px-2 text-dark">Customer</a>
                </li>
                <li class="nav-item">
                    <a href="adminCanteenList.php" class="nav-link px-2 text-dark">Canteen</a>
                </li>
                <li class="nav-item">
                    <a href="adminFoodList.php" class="nav-link px-2 text-dark">Menu</a>
                </li>
                <li class="nav-item">
                    <a href="adminOrderList.php" class="nav-link px-2 text-dark">Order</a>
                </li>
            </ul>
            <div class="d-flex">
                <?php if (!isset($_SESSION['adminId'])) { ?>
                    <a class="btn btn-outline-secondary me-2" href="./customer/custRegister.php">Sign Up</a>
                    <a class="btn btn-success" href="./customer/customerLogin.php">Log In</a>
                <?php } else { ?>
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a href="adminCustomerDetail.php?customerId=<?php echo $_SESSION["adminId"] ?>" class="nav-link px-2 text-dark">
                                Welcome, <?= $_SESSION['firstName'] ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="mx-2 mt-1 mt-md-0 btn btn-outline-danger" href="../logout.php">Log Out</a>
                        </li>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>
</header>