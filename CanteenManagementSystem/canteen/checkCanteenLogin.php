<?php
session_start();
include('../connectionDB.php');

$username = $_POST["username"];
$pwd = $_POST["pwd"];

$query = "SELECT canteenId,canteenUserName,canteenName FROM canteen WHERE
    canteenUserName = '$username' AND canteenPassword = '$pwd' LIMIT 0,1";

$result = $mysqli->query($query);
if ($result->num_rows == 1) {
    //customer login
    $row = $result->fetch_array();
    $_SESSION["canteenId"] = $row["canteenId"];
    $_SESSION["canteenUserName"] = $username;
    $_SESSION["canteenName"] = $row["canteenName"];
    $_SESSION["utype"] = "canteenOwner";
    header("location: canteenHome.php");
} else {
    ?>
    <script>
        alert("Wrong username and/or password!");
        history.back();
    </script>
    <?php
}
?>