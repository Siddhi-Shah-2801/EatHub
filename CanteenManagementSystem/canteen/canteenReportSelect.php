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
    include("rangeFunction.php");
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/customerLogin.css" rel="stylesheet">
    <script type="text/javascript" src="../js/revenueDateSelection.js"></script>
    <title>Revenue Report | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderCanteen.php'); ?>

    <div class="container form-signin">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <form method="GET" action="canteenReportSummary.php" class="form-floating">
            <h2 class="mt-4 mb-3">Revenue Report</h2>
            <p>Please select the option below to see your sales and revenue report.</p>
            <!-- 1 Today / 2 Yesterday / 3 This Week / 4 Monthly / 5 Specific Period -->
            <div class="form-check">
                <input class="form-check-input" type="radio" name="revenueMode" id="revenueMode1" value="1" checked onclick="switchDisable(0)">
                <label class="form-check-label" for="revenueMode1">
                    Today<br />(<?php echo date('F j, Y'); ?>)
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="revenueMode" id="revenueMode2" value="2" onclick="switchDisable(0)">
                <label class="form-check-label" for="revenueMode2">
                    Yesterday<br />(<?php echo (new Datetime())->sub(new DateInterval("P1D"))->format('F j, Y'); ?>)
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="revenueMode" id="revenueMode3" value="3" onclick="switchDisable(0)">
                <label class="form-check-label" for="revenueMode3">
                    This Week<br /> (<?php
                                        $weekRange = rangeWeek(date('Y-n-j'));
                                        $weekStart = (new Datetime($weekRange["start"]))->format('F j, Y');
                                        $weekEnd = (new Datetime($weekRange["end"]))->format('F j, Y');
                                        echo "{$weekStart} - {$weekEnd}";
                                        ?>)
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="revenueMode" id="revenueMode4" value="4" onclick="switchDisable(0)">
                <label class="form-check-label" for="revenueMode4">
                    This Month<br /> (<?php
                                        $monthrange = rangeMonth(date('Y-n-j'));
                                        // n - month number , j - date , y - year
                                        $month_start = (new Datetime($monthrange["start"]))->format('F j, Y');
                                        $month_end = (new Datetime($monthrange["end"]))->format('F j, Y');
                                        echo "{$month_start} - {$month_end}";
                                        ?>)
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="revenueMode" id="revenueMode5" value="5" onclick="switchDisable(1)">
                <label class="form-check-label" for="revenueMode5">
                    Specific Date<br />
                </label>
                <div class="row row-cols-2 g-0 mt-1 mb-2">
                    <div class="col">
                        <div class="form-floating">
                        <input type="date" class="form-control" id="startDate" placeholder="Starting Date"
                                value="<?php echo date('Y-m-d');?>"  max="<?php echo date('Y-m-d');?>" name="startDate"
                                oninput="updateMinRange()" disabled>
                            <label for="startDate">Starting Date</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="endDate" placeholder="Ending Date" value="<?php echo date('Y-m-d'); ?>"   max="<?php echo date('Y-m-d'); ?>" name="endDate" disabled>
                            <label for="endDate">Ending Date</label>
                        </div>
                    </div>
                </div>
            </div>
            <button class="w-100 btn btn-outline-success my-3" type="submit">Generate Report</button>
        </form>
    </div>
</body>

</html>