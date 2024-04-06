<?php
session_start();
date_default_timezone_set('Asia/Bangkok');
include('connectionDB.php');
$pickupTime = $_POST["pickupTime"];
$payAmount = $_POST["payAmount"];
//Check which canteen customer selected
//and validate the selected pick-up time
$canteenQuery = "SELECT canteenId,canteenOpenHour,canteenCloseHour,canteenStatus,canteenPreOrderStatus FROM canteen
    WHERE canteenId = (SELECT canteenId FROM cart WHERE customerId = {$_SESSION['customerId']} GROUP BY customerId)";
$canteenArray = $mysqli->query($canteenQuery)->fetch_array();
$canteenId = $canteenArray["canteenId"];
$canteenOpenArray = explode(":", $canteenArray["canteenOpenHour"]);
$canteenCloseArray = explode(":", $canteenArray["canteenCloseHour"]);
$canteenOpen = $canteenOpenArray[0] . ":" . $canteenOpenArray[1];
$canteenClose = $canteenCloseArray[0] . ":" . $canteenCloseArray[1];
$canteenToday = $canteenArray["canteenStatus"];
$canteenPreOrder = $canteenArray["canteenPreOrderStatus"];
$pickupTimeArray = explode("T", $pickupTime);
$nowDate = date("Y-m-d");
$tommorrowDate = (new Datetime($nowDate))->add(new DateInterval("P1D"))->format('Y-m-d');
if (($canteenToday == 1 && $pickupTimeArray[0] == $nowDate && $pickupTimeArray[1] >= $canteenOpen && $pickupTimeArray[1] <= $canteenClose) ||
    ($canteenPreOrder == 1 && $pickupTimeArray[0] == $tommorrowDate && $pickupTimeArray[1] >= $canteenOpen && $pickupTimeArray[1] <= $canteenClose)
) {
    //Order accepted.
    //Omise Payment
    // require_once dirname(__FILE__) . '/omise-php/lib/omise.php';
    // define('OMISE_API_VERSION', '2019-05-29');
    // define('OMISE_PUBLIC_KEY', 'pkey_test_5pj8zasgcvaasrujrrs');
    // define('OMISE_SECRET_KEY', 'skey_test_5pj8zasgc2vv1yma57q');
    // $charge = omiseCharge::create(array(
    //     'amount' => $payAmount,
    //     'currency' => 'Rs',
    //     'card' => $_POST["omiseToken"]
    // ));
    $payStatus = $charge['status'];
    if ($payStatus == "successful") {
        $cardFinance = $charge['card']['financing'];
        $cardBrand = $charge['card']['brand'];
        $cardLastDigit = $charge['card']['lastDigits'];
        $paymentDetail = ucfirst($cardBrand) . " [*" . $cardLastDigit . "]";
        switch ($cardFinance) {
            case "credit":
                $paymentType = "CRDC";
                break;
            case "debit":
                $paymentType = "DBTC";
                break;
            case "prepaid":
                $paymentType = "PPDC";
                break;
            default:
                $paymentType = "UNKN";
        }
        $amount = $charge['amount'] / 100;
        $paymentQuery = "INSERT INTO payment (customerId,paymentType,paymentAmount,paymentDetail) VALUES ({$_SESSION['customerId']},'{$paymentType}',{$amount},'{$paymentDetail}');\n";
        $paymentResult = $mysqli->query($paymentQuery);
        $paymentId = $mysqli->insert_id;
        $orderHeaderQuery = "INSERT INTO orderheader (customerId,canteenId,paymentId,orderHeaderPickupTime,orderHeaderOrderStatus) VALUES ({$_SESSION['customerId']},{$canteenId},{$paymentId},'{$pickupTime}','ACPT');\n";
        $orderHeaderResult = $mysqli->query($orderHeaderQuery);
        $orderHeaderId = $mysqli->insert_id;
        //Generate Ref Code
        $orderHeaderDate = date("Ymd");
        //calculate leading zero
        $idLength = strlen((string)$orderHeaderId);
        $lead0 = 7 - $idLength;
        $lead0str = "";
        for ($i = 0; $i < $lead0; $i++) {
            $lead0str .= "0";
        }
        $orderHeaderReferenceCode = $orderHeaderDate . $lead0str . $orderHeaderId;
        $orderHeaderUpdate = "UPDATE orderheader SET orderHeaderReferenceCode = {$orderHeaderReferenceCode} WHERE orderHeaderId = {$orderHeaderId};";
        $orderHeaderUpdateReset = $mysqli->query($orderHeaderUpdate);
        //Prepare detail value
        $orderValue = "";
        $cartQuery = "SELECT ct.foodId,f.foodPrice,ct.cartAmount,ct.cartNote FROM cart ct INNER JOIN food f ON ct.foodId = f.foodId WHERE ct.customerId = {$_SESSION['customerId']} AND ct.canteenId = {$canteenId};\n";
        $cartResult = $mysqli->query($cartQuery);
        $cartRow = $cartResult->num_rows;
        $i = 0;
        while ($crt_arr = $cartResult->fetch_array()) {
            $i++;
            $orderValue .= "({$orderHeaderId},{$crt_arr['foodId']},{$crt_arr['cartAmount']},{$crt_arr['foodPrice']},'{$crt_arr['cartNote']}')";
            if ($i < $cartRow) {
                $orderValue .= ",";
            } else {
                $orderValue .= ";";
            }
        }
        $orderQuery = "INSERT INTO orderdetail (orderHeaderId,foodId,orderAmount,orderBuyPrice,orderNote) VALUES {$orderValue}\n";
        $orderResult = $mysqli->query($orderQuery);
        if ($orderResult) {
            $cartDeleteQuery = "DELETE FROM cart WHERE customerId = {$_SESSION['customerId']} AND canteenId = {$canteenId};\n";
            $cartDeleteResult = $mysqli->query($cartDeleteQuery);
            header("location: orderSuccess.php?orh={$orderHeaderId}");
        } else {
            header("location: orderFail.php?err={$mysqli->errno}");
        }
        exit(1);
    } else {
        $payerMessage = $charge['failureMessage'];
        header("location: orderFail.php?paymentError={$payerMessage}");
        exit(1);
    }
} else {
?>
    <script>
        alert("You enter the pick-up time incorrectly.\nPlease re-enter it again.");
        history.back();
    </script>
<?php
    exit(1);
}
?>