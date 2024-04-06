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
    <title>Canteen List | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">

    <?php include('navHeaderAdmin.php')?>

    <div class="container p-2 pb-0" id="admin-dashboard">
        <div class="mt-4 border-bottom">
            <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
                <i class="bi bi-arrow-left-square me-2"></i>Go back
            </a>

            <?php
            if(isset($_GET["updateCanteen"])){
                if($_GET["updateCanteen"]==1){
                    ?>
            <!-- START SUCCESSFULLY UPDATE PROFILE -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-success text-white rouserNameded text-start">
                    <span class="ms-2 mt-2">Successfully updated Canteen profile.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminCanteenList.php">X</a></span>
                </div>
            </div>
            <!-- END SUCCESSFULLY UPDATE PROFILE -->
            <?php }else{ ?>
            <!-- START FAILED UPDATE PROFILE -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-danger text-white rouserNameded text-start">
                   <span class="ms-2 mt-2">Failed to update canteen profile.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminCanteenList.php">X</a></span>

                </div>
            </div>
            <!-- END FAILED UPDATE PROFILE -->
            <?php }
                }
            if(isset($_GET["deleteCanteebn"])){
                if($_GET["deleteCanteebn"]==1){
                    ?>
            <!-- START SUCCESSFULLY DELETE PROFILE -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-success text-white rouserNameded text-start">
                    <span class="ms-2 mt-2">Successfully deleted Canteen profile.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminCanteenList.php">X</a></span>
                </div>
            </div>
            <!-- END SUCCESSFULLY DELETE PROFILE -->
            <?php }else{ ?>
            <!-- START FAILED DELETE PROFILE -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-danger text-white rouserNameded text-start">
                   <span class="ms-2 mt-2">Failed to delete canteen profile.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminCanteenList.php">X</a></span>
                </div>
            </div>
            <!-- END FAILED DELETE PROFILE -->
            <?php }
                }
            if(isset($_GET["addCanteen"])){
                if($_GET["addCanteen"]==1){
                    ?>
            <!-- START SUCCESSFULLY ADD PROFILE -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-success text-white rouserNameded text-start">
                    <span class="ms-2 mt-2">Successfully add new canteen.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminCanteenList.php">X</a></span>
                </div>
            </div>
            <!-- END SUCCESSFULLY ADD PROFILE -->
            <?php }else{ ?>
            <!-- START FAILED ADD PROFILE -->
            <div class="row row-cols-1 notibar">
                <div class="col mt-2 ms-2 p-2 bg-danger text-white rouserNameded text-start">
                    <span class="ms-2 mt-2">Failed to add new canteen.</span>
                    <span class="me-2 float-end"><a class="text-decoration-none link-light" href="adminCanteenList.php">X</a></span>
                </div>
            </div>
            <!-- END FAILED ADD PROFILE -->
            <?php }
                }
            ?>

            <h2 class="pt-3 display-6">canteen List</h2>
            <form class="form-floating mb-3" method="GET" action="adminCanteenList.php">
                <div class="row g-2">
                    <div class="col">
                        <input type="text" class="form-control" id="username" name="userName" placeholder="Username"
                            <?php if(isset($_GET["search"])){?>value="<?php echo $_GET["userName"];?>" <?php } ?>>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="canteenname" name="searchName" placeholder="canteen Name"
                            <?php if(isset($_GET["search"])){?>value="<?php echo $_GET["searchName"];?>" <?php } ?>>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="search" value="1" class="btn btn-success">Search</button>
                        <button type="reset" class="btn btn-danger"
                            onclick="javascript: window.location='adminCanteenList.php'">Clear</button>
                        <a href="adminCanteenAdd.php" class="btn btn-primary">Add new canteen</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="container pt-2" id="cust-table">

        <?php
            if(!isset($_GET["search"])){
                $searchQuery = "SELECT canteenId,canteenUserName,canteenName,canteenLocation,canteenOpenHour,canteenCloseHour,canteenStatus,canteenPreOrderStatus,canteenEmail,canteenContactNo FROM canteen;";
            }else{
                $searchUserName=$_GET["userName"];
                $searchCanteenName=$_GET["searchName"];
                $searchQuery = "SELECT canteenId,canteenUserName,canteenName,canteenLocation,canteenOpenHour,canteenCloseHour,canteenStatus,canteenPreOrderStatus,canteenEmail,canteenContactNo FROM canteen
                WHERE canteenUserName LIKE '%{$searchUserName}%' AND canteenName LIKE '%{$searchCanteenName}%';";
            }
            $searchResult = $mysqli -> query($searchQuery);
            $searchNumRows = $searchResult -> num_rows;
            if($searchNumRows == 0){
        ?>
        <div class="row">
            <div class="col mt-2 ms-2 p-2 bg-danger text-white rouserNameded text-start">
               <span class="ms-2 mt-2">No canteen fouser Named!</span>
                <a href="adminCanteenList.php" class="text-white">Clear Search Result</a>
            </div>
        </div>
        <?php } else{ ?>
        <div class="table-responsive">
        <table class="table rouserNameded-5 table-light table-striped table-hover align-middle caption-top mb-5">
            <caption><?php echo $searchNumRows;?> canteen(s) <?php if(isset($_GET["search"])){?><br /><a
                    href="adminCanteenList.php" class="text-decoration-none text-danger">Clear Search
                    Result</a><?php } ?></caption>
            <thead class="bg-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Username</th>
                    <th scope="col">canteen name</th>
                    <th scope="col">Location</th>
                    <th scope="col">Open Hour</th>
                    <th scope="col">canteen Status</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; while($row = $searchResult -> fetch_array()){ ?>
                <tr>
                    <th><?php echo $i++;?></th>
                    <td><?php echo $row["canteenUserName"];?></td>
                    <td><?php echo $row["canteenName"];?></td>
                    <td class="text-wrap"><?php echo $row["canteenLocation"];?></td>
                    <td>
                        <?php
                            $openHourArray = explode(":",$row["canteenOpenHour"]);
                            $closeHourArray = explode(":",$row["canteenCloseHour"]);
                            $openHourHM = $openHourArray[0].":".$openHourArray[1];
                            $closeHourHM = $closeHourArray[0].":".$closeHourArray[1];
                            echo $openHourHM."-".$closeHourHM;
                        ?>
                    </td>
                    <td>
                        <?php 
                            $now = date('H:i:s');
                            if((($now < $row["canteenOpenHour"])||($now > $row["canteenCloseHour"]))||($row["canteenStatus"]==0)){
                        ?>
                        <span class="badge rouserNameded-pill bg-danger">Closed</span>
                        <?php }else{ ?>
                        <span class="badge rouserNameded-pill bg-success">Open</span>
                        <?php }
                            if($row["canteenPreOrderStatus"]==1){
                        ?>
                        <span class="badge rouserNameded-pill bg-success">Pre-order avaliable</span>
                        <?php }else{ ?>
                        <span class="badge rouserNameded-pill bg-danger">Pre-order userNameavaliable</span>
                        <?php } ?>
                    </td>
                    
                    <td class="small"><?php echo $row["canteenEmail"];?><br/><?php echo "(+66) ".$row["canteenContactNo"];?></td>
                    <td>
                        <a href="adminCanteenDetail.php?canteenId=<?php echo $row["canteenId"]?>"
                            class="btn btn-sm btn-primary">View</a>
                        <a href="adminCanteenEdit.php?canteenId=<?php echo $row["canteenId"]?>"
                            class="btn btn-sm btn-outline-success">Edit</a>
                        <a href="adminCanteenDelete.php?canteenId=<?php echo $row["canteenId"]?>"
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