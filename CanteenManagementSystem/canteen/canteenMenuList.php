<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    if ($_SESSION["utype"] != "canteenOwner") {
        header("location: ../restricted.php");
        exit(1);
    }
    include("../connectionDB.php");
    include('../head.php');
    $canteenId = $_SESSION["canteenId"];
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <title>Canteen Menu List | somaiya canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderCanteen.php'); ?>

    <div class="container px-5 pt-4" id="canteen-body">
        <div class="mt-4 border-bottom">
            <a class="nav nav-item text-decoration-none text-muted mb-2" href="#" onclick="history.back();">
                <i class="bi bi-arrow-left-square me-2"></i>Go back
            </a>

            <?php
            if (isset($_GET["dsb_fdt"])) {
                if ($_GET["dsb_fdt"] == 1) {
                    ?>
                    <!-- START SUCCESSFULLY DELETE MENU -->
                    <div class="row row-cols-1 notibar">
                        <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                            <span class="ms-2 mt-2">Successfully removed menu.</span>
                            <span class="me-2 float-end"><a class="text-decoration-none link-light"
                                    href="canteenMenuList.php">X</a></span>
                        </div>
                    </div>
                    <!-- END SUCCESSFULLY DELETE MENU -->
                <?php } else { ?>
                    <!-- START FAILED DELETE MENU -->
                    <div class="row row-cols-1 notibar">
                        <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                            <span class="ms-2 mt-2">Failed to remove menu.</span>
                            <span class="me-2 float-end"><a class="text-decoration-none link-light"
                                    href="canteenMenuList.php">X</a></span>
                        </div>
                    </div>
                    <!-- END FAILED DELETE MENU -->
                <?php }
            }
            if (isset($_GET["add_fdt"])) {
                if ($_GET["add_fdt"] == 1) {
                    ?>
                    <!-- START SUCCESSFULLY FOOD MENU -->
                    <div class="row row-cols-1 notibar">
                        <div class="col mt-2 ms-2 p-2 bg-success text-white rounded text-start">
                            <span class="ms-2 mt-2">Successfully add new menu.</span>
                            <span class="me-2 float-end"><a class="text-decoration-none link-light"
                                    href="canteenMenuList.php">X</a></span>
                        </div>
                    </div>
                    <!-- END SUCCESSFULLY FOOD MENU -->
                <?php } else { ?>
                    <!-- START FAILED FOOD MENU -->
                    <div class="row row-cols-1 notibar">
                        <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                            <span class="ms-2 mt-2">Failed to add new menu.</span>
                            <span class="me-2 float-end"><a class="text-decoration-none link-light"
                                    href="canteenMenuList.php">X</a></span>
                        </div>
                    </div>
                    <!-- END FAILED FOOD MENU -->
                <?php }
            }
            ?>

            <h2 class="pt-3 display-6">Menu List</h2>
            <form class="form-floating mb-3" method="GET" action="canteenMenuList.php">
                <div class="row g-2">
                    <div class="col">
                        <input type="text" class="form-control" id="foodName" name="foodName" placeholder="Food name"
                            <?php if (isset($_GET["search"])) { ?>value="<?php echo $_GET["foodName"]; ?>" <?php } ?>>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="search" class="btn btn-success">Search</button>
                        <button type="reset" class="btn btn-danger"
                            onclick="javascript: window.location='canteenMenuList.php'">Clear</button>
                        <a href="canteenMenuAdd.php" class="btn btn-primary">Add new menu</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="pt-2" id="cust-table">

            <?php
            if (!isset($_GET["page"])) {
                $page = 1;
                $_GET["page"] = 1;
            }
                if (!isset($_GET["search"])) {
                    $searchQuery = "SELECT * FROM food f WHERE canteenId = {$canteenId};";
                } else {
                    $searchFn = $_GET["foodName"];
                    $searchQuery = "SELECT * FROM food f WHERE canteenId = {$canteenId} AND foodName LIKE '%{$searchFn}%';";
                }
                $searchResult = $mysqli->query($searchQuery);
                $searchNumRow =
                 $searchResult->num_rows;
                $recPerPage = 3;
                $numberOfPages = ceil($searchNumRow / $recPerPage);
                echo "<BR> Total Pages: ", $numberOfPages;
                $limit_query = "select * from food limit " . ($_GET["page"] - 1) * $recPerPage . "," . $recPerPage;
                $res1 = $mysqli->query($limit_query);
                $res1numRows =
                 $res1->num_rows;
                if ($res1numRows== 0) {
                    ?>
                    <div class="row">
                        <div class="col mt-2 ms-2 p-2 bg-danger text-white rounded text-start">
                            <span class="ms-2 mt-2">No canteen found!</span>
                            <a href="canteenMenuList.php" class="text-white">Clear Search Result</a>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="table-responsive">
                        <table class="table rounded-5 table-light table-striped table-hover align-middle caption-top mb-5">
                            <caption>
                                <?php echo $searchNumRow; ?> menu(s) <?php if (isset($_GET["search"])) { ?><br /><a
                                        href="canteenMenuList.php" class="text-decoration-none text-danger">Clear Search
                                        Result</a>
                                <?php } ?>
                            </caption>
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Menu name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Menu Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                while ($row = $res1->fetch_array()) { ?>
                                    <tr>
                                        <th><?php echo $i++; ?></th>
                                        <td><img <?php
                                        if (is_null($row["foodPic"])) {
                                            echo "src='../images/canteenLogo.png'";
                                        } else {
                                            echo "src=\"../images/{$row['foodPic']}\"";
                                        }
                                        ?>    style="width:75%; height:125px; object-fit:cover;" class="img-fluid rounded"
                                                alt="<?php echo $foodRow["foodName"] ?>">
                                        </td>
                                        <td>
                                            <?php echo $row["foodName"]; ?>
                                        </td>
                                        <td><?php echo $row["foodPrice"] . " RS"; ?></td>
                                        <td>
                                            <?php
                                            if ($row["foodTodayAvailable"] == 1) {
                                                ?>
                                                <span class="badge rounded-pill bg-success">Avaliable</span>
                                            <?php } else { ?>
                                                <span class="badge rounded-pill bg-danger">Unavaliable</span>
                                            <?php }
                                            if ($row["foodPreOrderAvailable"] == 1) {
                                                ?>
                                                <span class="badge rounded-pill bg-success">Pre-order avaliable</span>
                                            <?php } else { ?>
                                                <span class="badge rounded-pill bg-danger">Pre-order Unavaliable</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="canteenMenuDetail.php?foodId=<?php echo $row["foodId"] ?>"
                                                class="btn btn-sm btn-primary">View</a>
                                            <a href="canteenMenuEdit.php?foodId=<?php echo $row["foodId"] ?>"
                                                class="btn btn-sm btn-outline-success">Edit</a>
                                            <a href="canteenMenuDelete.php?foodId=<?php echo $row["foodId"] ?>"
                                                class="btn btn-sm btn-outline-danger">Delete</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <?php
                    if ($_GET["page"] >= 2)
                        echo '<a href = "canteenMenuList.php?page=' . ($_GET["page"] - 1) . '">Previous</a>';
                    for ($i = 1; $i <= $numberOfPages; $i++)
                        echo '<a href="canteenMenuList.php?page=' . $i . '">' . $i . ',</a>';
                    if ($_GET["page"] < $numberOfPages)
                        echo '<a href="canteenMenuList.php?page=' . ($_GET["page"] + 1) . '">Next</a>';
                   

                }
                $searchResult->free_Result();
            ?>
        </div>
    </div>
</body>

</html>