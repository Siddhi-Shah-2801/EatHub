<?php
include('../connectionDB.php');

$username = $_POST["username"];
$password = $_POST["password"];

$query = "SELECT customerId,customerUserName,customerFirstName,customerLastname FROM customer WHERE
    customerUserName = '$username' AND customerPassword = '$password' AND customerType = 'admin' LIMIT 0,1";

$result = $mysqli->query($query);
if ($result->num_rows == 1) {
    //customer login
    $row = $result->fetch_array();
    session_start();
    $_SESSION["adminId"] = $row["customerId"];
    $_SESSION["firstName"] = $row["customerFirstName"];
    $_SESSION["lastName"] = $row["customerLastName"];
    $_SESSION["utype"] = "admin";

    header("location: adminHome.php");
    exit(1);
} else {
    ?>
    <script>
        alert("You entered wrong username and/or password!");
        history.back();
    </script>
    <?php
}
?>