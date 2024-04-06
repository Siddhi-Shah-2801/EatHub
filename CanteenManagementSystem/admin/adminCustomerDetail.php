<!DOCTYPE html>
<html lang="en">

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
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/menu.css" rel="stylesheet">
    <title>Customer Profile | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderAdmin.php')?>

    <div class="container px-5 py-4" id="cart-body">
        <div class="row my-4 pb-2 border-bottom">
            <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
                <i class="bi bi-arrow-left-square me-2"></i>Go back
            </a>

            <?php 
            if(isset($_GET["updatePassword"])){
                if($_GET["updatePassword"]==1){
                    ?>
            <!-- START SUCCESSFULLY UPDATE PASSWORD -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                    <span class="ms-2 mt-2">Successfully updated customer password!</span>
                </div>
            </div>
            <!-- END SUCCESSFULLY UPDATE PASSWORD -->
            <?php }else{ ?>
            <!-- START FAILED UPDATE PASSWORD -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                    <span class="ms-2 mt-2">Failed to update customer password.</span>
                </div>
            </div>
            <!-- END FAILED UPDATE PASSWORD -->
            <?php }
                }
            ?>

            <h2 class="pt-3 display-6"> My Profile</h2>
        </div>

        <a class="btn btn-sm btn-outline-secondary" href="adminCustomerPassword.php?customerId=<?php echo $_GET["customerId"]?>">
            Change password
        </a>
        <a class="btn btn-sm btn-primary mt-2 mt-md-0" href="adminCustomerEdit.php?customerId=<?php echo $_GET["customerId"]?>">
            Update profile
        </a>
        <a class="btn btn-sm btn-danger mt-2 mt-md-0" href="adminCustomerDelete.php?customerId=<?php echo $_GET["customerId"]?>">
            Delete this profile
        </a>

        <!-- START CUSTOMER INFORMATION -->
        <?php
            //Select customer record from database
            $customerId = $_GET["customerId"];
            $query = "SELECT customerUserName,customerFirstName,customerLastName,customerEmail,customerGender,customerType FROM customer WHERE customerId = {$customerId} LIMIT 0,1";
            $result = $mysqli ->query($query);
            $row = $result -> fetch_array();
        ?>
        <div class="row row-cols-1 mt-4">
            <dl class="row">
                <dt class="col-sm-3">Username</dt>
                <dd class="col-sm-9"><?php echo $row["customerUserName"];?></dd>

                <dt class="col-sm-3">Name</dt>
                <dd class="col-sm-9"><?php echo $row["customerFirstName"]." ".$row["customerLastName"];?></dd>

                <dt class="col-sm-3">Gender</dt>
                <dd class="col-sm-9"><?php 
                if($row["customerGender"]=="M"){echo "Male";}
                else if($row["customerGender"]=="F"){echo "Female";}
                else if($row["customerGender"]=="N"){echo "Non-binary";}?>
                </dd>

                <dt class="col-sm-3">Account Type</dt>
                <dd class="col-sm-9"><?php 
                if($row["customerType"]=="customer"){echo "customer";}
                else if($row["customerType"]=="admin"){echo "admin";}
                ?>
                </dd>
                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9"><?php echo $row["customerEmail"];?></dd>
            </dl>
        </div>
        <!-- END CUSTOMER INFORMATION -->
    </div>
</body>

</html>