<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include('../connectionDB.php');
    include('../head.php');
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/customerLogin.css" rel="stylesheet">

    <title>Failed to place an order | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('../navHeaderCustomer.php') ?>
    <div class="mt-5"></div>
    <div class="container form-signin text-center reg-fail mt-auto">
        <?php
        if (isset($_GET["error"])) {
            $errorCode = $_GET["error"];
            $errorType = 1;
            $displayMessage =  "erroror Code: {$errorCode}";
        } else if (isset($_GET["paymentError"])) {
            $errorType = 2;
            $displayMessage = "Message: " . ucfirst($_GET["paymentError"]);
        }
        ?>
        <i class="mt-4 bi bi-exclamation-circle text-danger h1 display-2"></i>
        <h3 class="mt-2 mb-3 fw-normal text-bold">Unable to place your order</h3>
        <p class="mb-3 fw-normal text-bold">
            <?php
            switch ($errorType) {
                case 1:
                    echo "Sorry, the system has encountered with this error";
                    break;
                case 2:
                    echo "There is a problem with your payment";
                    break;
                default:
                    echo "There is an error in our system.";
            }
            ?>
            <br />
            <code><?php echo $displayMessage; ?></code>
        </p>
        <a class="btn btn-danger btn-sm w-50" href="../viewPage.php">Return to Home</a>
    </div>
</body>

</html>