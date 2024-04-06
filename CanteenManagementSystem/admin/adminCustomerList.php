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
    <title>Customer List | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">

    <?php include('navHeaderAdmin.php')?>

    <div class="container p-2 pb-0" id="admin-dashboard">
        <div class="mt-4 border-bottom">
            <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
                <i class="bi bi-arrow-left-square me-2"></i>Go back
            </a>

            <?php
            if(isset($_GET["updateProfile"])){
                if($_GET["updateProfile"]==1){
                    ?>
            <!-- START SUCCESSFULLY UPDATE PROFILE -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                    <span class="ms-2 mt-2">Successfully updated customer profile.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminCustomerList.php">X</a></span>
                </div>
            </div>
            <!-- END SUCCESSFULLY UPDATE PROFILE -->
            <?php }else{ ?>
            <!-- START FAILED UPDATE PROFILE -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                   <span class="ms-2 mt-2">Failed to update customer profile.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminCustomerList.php">X</a></span>
                </div>
            </div>
            <!-- END FAILED UPDATE PROFILE -->
            <?php }
                }
            if(isset($_GET["deleteCustomer"])){
                if($_GET["deleteCustomer"]==1){
                    ?>
            <!-- START SUCCESSFULLY DELETE PROFILE -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                    <span class="ms-2 mt-2">Successfully deleted customer profile.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminCustomerList.php">X</a></span>
                </div>
            </div>
            <!-- END SUCCESSFULLY DELETE PROFILE -->
            <?php }else{ ?>
            <!-- START FAILED DELETE PROFILE -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                   <span class="ms-2 mt-2">Failed to delete customer profile.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminCustomerList.php">X</a></span>
                </div>
            </div>
            <!-- END FAILED DELETE PROFILE -->
            <?php }
                }
            if(isset($_GET["addCustomer"])){
                if($_GET["addCustomer"]==1){
                    ?>
            <!-- START SUCCESSFULLY ADD PROFILE -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                    <span class="ms-2 mt-2">Successfully add new customer profile.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminCustomerList.php">X</a></span>
                </div>
            </div>
            <!-- END SUCCESSFULLY ADD PROFILE -->
            <?php }else{ ?>
            <!-- START FAILED ADD PROFILE -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                   <span class="ms-2 mt-2">Failed to add new customer profile.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminCustomerList.php">X</a></span>
                </div>
            </div>
            <!-- END FAILED ADD PROFILE -->
            <?php }
                }
            ?>

            <h2 class="pt-3 display-6">Customer List</h2>
            <form class="form-floating mb-3" method="GET" action="adminCustomerList.php">
                <div class="row g-2">
                    <div class="col">
                        <input type="text" class="form-control" id="username" name="un" placeholder="Username"
                            <?php if(isset($_GET["search"])){?>value="<?php echo $_GET["un"];?>" <?php } ?>>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="firstname" name="fn" placeholder="First name"
                            <?php if(isset($_GET["search"])){?>value="<?php echo $_GET["fn"];?>" <?php } ?>>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="lastname" name="ln" placeholder="Last name"
                            <?php if(isset($_GET["search"])){?>value="<?php echo $_GET["ln"];?>" <?php } ?>>
                    </div>
                    <div class="col">
                        <select class="form-select" id="utype" name="ut">
                            <?php if(isset($_GET["search"])){?>
                            <option selected value="">Customer Type</option>
                            <option value="cust" <?php if($_GET["ut"]=="cust"){ echo "selected";}?>>customer</option>
                            <option value="adm" <?php if($_GET["ut"]=="adm"){ echo "selected";}?>>admin</option>
                            <?php }else{ ?>
                            <option selected value="">Customer Type</option>
                            <option value="adm">admin</option>
                            <option value="cust">customer</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="search" value="1" class="btn btn-success">Search</button>
                        <button type="reset" class="btn btn-danger"
                            onclick="javascript: window.location='adminCustomerList.php'">Clear</button>
                        <a href="adminCustomerAdd.php" class="btn btn-primary">Add new customer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="container pt-2" id="cust-table">

        <?php
            if(!isset($_GET["search"])){
                $searchQuery = "SELECT customerId,customerUserName,customerFirstName,customerLastName,customerType,customerEmail FROM customer;";
            }else{
                $searchUserName=$_GET["un"];
                $searchFirstName=$_GET["fn"];
                $searchLastName=$_GET["ln"];
                $searchUserType=$_GET["ut"];
                $searchQuery = "SELECT customerId,customerUserName,customerFirstName,customerLastName,customerType,customerEmail FROM customer
                WHERE customerUserName LIKE '%{$searchUserName}%' AND customerFirstName LIKE '%{$searchFirstName}%' AND customerLastName LIKE '%{$searchLastName}%' AND customerType LIKE '%{$searchUserType}%';";
            }
            $searchResult = $mysqli -> query($searchQuery);
            $searchNumRow = $searchResult -> num_rows;
            if($searchNumRow == 0){
        ?>
        <div class="row">
            <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                <span class="ms-2 mt-2">No customer found!</span>
                <a href="adminCustomerList.php" class="text-white">Clear Search Result</a>
            </div>
        </div>
        <?php } else{ ?>
        <div class="table-responsive">
        <table class="table rounded-5 table-light table-striped table-hover align-middle caption-top mb-5">
            <caption><?php echo $searchNumRow;?> customer(s) <?php if(isset($_GET["search"])){?><br /><a
                    href="adminCustomerList.php" class="text-decoration-none text-danger">Clear Search
                    Result</a><?php } ?></caption>
            <thead class="bg-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Username</th>
                    <th scope="col">First name</th>
                    <th scope="col">Last name</th>
                    <th scope="col">Type</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; while($row = $searchResult -> fetch_array()){ ?>
                <tr>
                    <th><?php echo $i++;?></th>
                    <td><?php echo $row["customerUserName"];?></td>
                    <td><?php echo $row["customerFirstName"];?></td>
                    <td><?php echo $row["customerLastName"];?></td>
                    <td><?php 
                        switch($row["customerType"]){
                            case "cust": echo "customer"; break;
                            case "adm": echo "admin"; break;
                            default: echo "customer";
                        }
                    ?>
                    </td>
                    <td><?php echo $row["customerEmail"];?></td>
                    <td>
                        <a href="adminCustomerDetail.php?customerId=<?php echo $row["customerId"]?>"
                            class="btn btn-sm btn-primary">View</a>
                        <a href="adminCustomerEdit.php?customerId=<?php echo $row["customerId"]?>"
                            class="btn btn-sm btn-outline-success">Edit</a>
                        <a href="adminCustomerDelete.php?customerId=<?php echo $row["customerId"]?>"
                            class="btn btn-sm btn-outline-danger">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
        <?php }
            $searchResult -> free_result();
        ?>
    </div>
</body>

</html>