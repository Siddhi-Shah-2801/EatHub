<!--    NAV HEADER FOR CUSTOMER SIDE PAGE
        EXCEPT LOGIN AND REGISTRATION PAGE  -->

<header class="navbar navbar-expand-md navbar-light fixed-top bg-light shadow-sm mb-auto">
    <div class="container-fluid mx-4">
        <a href='viewPage.php'>
            <img src='./images/canteenLogo.png' width="125" class="me-2" height="60" alt=" Somaiya Canteen Logo">
        </a>
        <div class="navbar-collapse collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link px-2 text-dark" href='viewPage.php'>Home</a>
                </li>
                <li class="nav-item">
                    <a href='canteenList.php' class="nav-link px-2 text-dark">Canteen List</a>
                </li>
                <?php if (isset($_SESSION['customerId'])) { ?>
                    <li class="nav-item">
                        <a href='customerOrderHistory.php' class="nav-link px-2 text-dark">Order History</a>
                    </li>
                <?php } ?>
            </ul>
            <div class="d-flex">
                <?php if (!isset($_SESSION['customerId'])) { ?>
                    <a class="btn btn-outline-secondary me-2" href='./customer/custRegister.php'>Sign Up</a>
                    <a class="btn btn-success" href='./customer/customerLogin.php'>Log In</a>
                <?php } else { ?>


                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a type="button" class="btn btn-light" href='customerCart.php'>
                                My Cart
                                <?php
                                $incartQuery = "SELECT SUM(cartAmount) AS incartAmount FROM cart WHERE customerId = {$_SESSION['customerId']}";
                                $incartResult = $mysqli->query($incartQuery)->fetch_array();
                                $incartAmount = $incartResult["incartAmount"];
                                if ($incartAmount > 0) {
                                    ?>
                                    <span class="ms-1 badge bg-success">
                                        <?php echo $incartAmount; ?>
                                    </span>
                                <?php } else { ?>
                                    <span class="ms-1 badge bg-secondary">0</span>
                                <?php } ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href='customerProfile.php' class="nav-link px-2 text-dark">
                                Welcome, <?= $_SESSION['firstName'] ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="mx-2 mt-1 mt-md-0 btn btn-outline-danger" href='logout.php'>Log
                                Out</a>
                        </li>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>
</header>