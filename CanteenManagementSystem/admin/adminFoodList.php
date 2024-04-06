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
    <title>Menu List | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">

    <?php include('navHeaderAdmin.php')?>

    <div class="container p-2 pb-0" id="admin-dashboard">
        <div class="mt-4 border-bottom">
            <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
                <i class="bi bi-arrow-left-square me-2"></i>Go back
            </a>

            <?php
            if(isset($_GET["deleteFoodItem"])){
                if($_GET["deleteFoodItem"]==1){
                    ?>
            <!-- START SUCCESSFULLY DELETE MENU -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                    <span class="ms-2 mt-2">Successfully removed menu.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminFoodList.php">X</a></span>
                </div>
            </div>
            <!-- END SUCCESSFULLY DELETE MENU -->
            <?php }else{ ?>
            <!-- START FAILED DELETE MENU -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                 <span class="ms-2 mt-2">Failed to remove menu.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminFoodList.php">X</a></span>
                </div>
            </div>
            <!-- END FAILED DELETE MENU -->
            <?php }
                }
            if(isset($_GET["addFoodItem"])){
                if($_GET["addFoodItem"]==1){
                    ?>
            <!-- START SUCCESSFULLY FOOD MENU -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                    <span class="ms-2 mt-2">Successfully add new menu.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminFoodList.php">X</a></span>
                </div>
            </div>
            <!-- END SUCCESSFULLY FOOD MENU -->
            <?php }else{ ?>
            <!-- START FAILED FOOD MENU -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                    <span class="ms-2 mt-2">Failed to add new menu.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminFoodList.php">X</a></span>
                </div>
            </div>
            <!-- END FAILED FOOD MENU -->
            <?php }
                }
            ?>

            <h2 class="pt-3 display-6">Menu List</h2>
            <form class="form-floating mb-3" method="GET" action="adminFoodList.php">
                <div class="row g-2">
                    <div class="col">
                        <input type="text" class="form-control" id="foodName" name="foodName" placeholder="Food name"
                            <?php if(isset($_GET["search"])){?>value="<?php echo $_GET["foodName"];?>" <?php } ?>>
                    </div>
                    <div class="col">
                        <select class="form-select" id="canteenId" name="canteenId">
                            <option selected value="">Canteen Name</option>
                            <?php
                                $optionQuery = "SELECT canteenId,canteenName FROM canteen;";
                                $optionResult = $mysqli -> query($optionQuery);
                                $optionRow = $optionResult -> num_rows;
                                if($optionResult -> num_rows != 0){
                                    while($optionArray = $optionResult -> fetch_array()){
                            ?>
                            <option value="<?php echo $optionArray["canteenId"]?>"><?php echo $optionArray["canteenName"];?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="search" value="1" class="btn btn-success">Search</button>
                        <button type="reset" class="btn btn-danger"
                            onclick="javascript: window.location='adminFoodList.php'">Clear</button>
                        <a href="adminFoodAdd.php" class="btn btn-primary">Add new menu</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="container pt-2" id="cust-table">

        <?php
            if(!isset($_GET["search"])){
                $searchQuery = "SELECT f.foodId,c.canteenId,f.foodName,f.fooodPrice,f.foodTodayAvailable,f.foodPreOrderAvailable,c.canteenName FROM food f INNER JOIN canteen c ON f.canteenId = c.canteenId ORDER BY f.fooodPrice DESC,f.canteenId ASC;";
            }else{
                $searchCanteenId=$_GET["canteenId"];
                if($searchCanteenId!=""){$canteenIdClause = " AND f.canteenId = {$searchCanteenId} ";}else{$canteenIdClause = " ";}
                $searchFoodName=$_GET["foodName"];
                $searchQuery = "SELECT f.foodId,s.canteenId,f.foodName,f.fooodPrice,f.foodTodayAvailable,f.foodPreOrderAvailable,s.canteenName FROM food f INNER JOIN canteen c ON f.canteenId = s.canteenId
                WHERE foodName LIKE '%{$searchFoodName}%'".$canteenIdClause." ORDER BY f.fooodPrice DESC,f.canteenId ASC;";
            }
            $searchResult = $mysqli -> query($searchQuery);
            $searchNumRow = $searchResult -> num_rows;
            if($searchNumRow == 0){
        ?>
        <div class="row">
            <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
               <span class="ms-2 mt-2">No Canteen found!</span>
                <a href="adminFoodList.php" class="text-white">Clear Search Result</a>
            </div>
        </div>
        <?php } else{ ?>
        <div class="table-responsive">
        <table class="table rounded-5 table-light table-striped table-hover align-middle caption-top mb-5">
            <caption><?php echo $searchNumRow;?> menu(s) <?php if(isset($_GET["search"])){?><br /><a
                    href="adminFoodList.php" class="text-decoration-none text-danger">Clear Search
                    Result</a><?php } ?></caption>
            <thead class="bg-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Menu name</th>
                    <th scope="col">canteen name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Menu Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; while($row = $searchResult -> fetch_array()){ ?>
                <tr>
                    <th><?php echo $i++;?></th>
                    <td><?php echo $row["foodName"];?></td>
                    <td><?php echo $row["canteenName"];?></td>
                    <td><?php echo $row["fooodPrice"]." Rs";?></td>
                    <td>
                    <?php 
                        if($row["foodTodayAvailable"]==1){
                        ?>
                        <span class="badge rounded-pill bg-success">Avaliable</span>
                        <?php }else{ ?>
                        <span class="badge rounded-pill bg-danger">Unavaliable</span>
                        <?php }
                            if($row["foodPreOrderAvailable"]==1){
                        ?>
                        <span class="badge rounded-pill bg-success">Pre-order avaliable</span>
                        <?php }else{ ?>
                        <span class="badge rounded-pill bg-danger">Pre-order Unavaliable</span>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="adminFoodDetail.php?foodId=<?php echo $row["foodId"]?>"
                            class="btn btn-sm btn-primary">View</a>
                        <a href="adminFoodEdit.php?canteenId=<?php echo $row["canteenId"];?>&foodId=<?php echo $row["foodId"]?>"
                            class="btn btn-sm btn-outline-success">Edit</a>
                        <a href="adminFoodDelete.php?foodId=<?php echo $row["foodId"]?>"
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