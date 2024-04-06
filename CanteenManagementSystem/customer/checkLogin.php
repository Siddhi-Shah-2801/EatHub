<?php
include('../connectionDB.php');

$username = $_POST["username"];
$pwd = $_POST["pwd"];
$query = "SELECT customerId,customerUserName,customerFirstName,customerLastName FROM customer where customerUserName = '$username' AND customerPassword = '$pwd' LIMIT 0,1";

$result = $mysqli->query($query);
if ($result->num_rows == 1) {
    //customer login
    ?>
    <script>
         alert("Logged in");
    </script>
    <?php
    $row = $result->fetch_array();
    session_start();
    $_SESSION["customerId"] = $row["customerId"];
    $_SESSION["firstName"] = $row["customerFirstName"];
    $_SESSION["lastName"] = $row["customerLastName"];
    $_SESSION["utype"] = "customer";

    header("location: ../viewPage.php");
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