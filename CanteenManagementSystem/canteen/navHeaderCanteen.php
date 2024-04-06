<!--    NAV HEADER FOR Canteen OWNER SIDE PAGE   -->
<header class="navbar navbar-expand-md navbar-light fixed-top bg-light shadow-sm mb-auto">
    <div class="container-fluid mx-4">
        <a href="canteenHome.php">
            <img src='../images/canteenLogo.png' width="125" height="60" class="me-2" alt="somaiya canteen Logo">
        </a>
        <div class="navbar-collapse collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link px-2 text-dark" href="canteenHome.php">Home</a>
                </li>
                <li class="nav-item">
                    <a href="canteenOrderList.php" class="nav-link px-2 text-dark">Order</a>
                </li>
                <li class="nav-item">
                    <a href="canteenMenuList.php" class="nav-link px-2 text-dark">Menu</a>
                </li>
                <li class="nav-item">
                    <a href="canteenProfile.php" class="nav-link px-2 text-dark">Profile</a>
                </li>
                <li class="nav-item">
                    <a href="canteenReportSelect.php" class="nav-link px-2 text-dark">Revenue Report</a>
                </li>
            </ul>
            <div class="d-flex">
                <?php if (!isset($_SESSION['canteenId'])) { ?>
                    <a class="btn btn-outline-secondary me-2" href="../customer/custRegister.php">Sign Up</a>
                    <a class="btn btn-success" href="../customer/customerLogin.php">Log In</a>
                <?php } else { ?>
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a href="canteenProfile.php" class="nav-link px-2 text-dark">
                                Welcome, <?= $_SESSION['canteenName'] ?>
                                
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