<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        session_start(); 
        include("../connectionDb.php"); 
        if($_SESSION["utype"]!="admin"){
            header("location: ../restricted.php");
            exit(1);
        }
        if(isset($_POST["updateConfirm"])){
            $customerId = $_POST["customerId"];
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $email = $_POST["email"];
            $gender = $_POST["gender"];
            $type = $_POST["type"];

            $query = "UPDATE customer SET customerFirstName = '{$firstname}', customerLastName = '{$lastname}', customerEmail = '{$email}', customerGender = '{$gender}', customerType = '{$type}' WHERE customerId = {customerId}";
            $result = $mysqli -> query($query);
            if($result){
                header("location: admin_customer_list.php?up_prf=1");
            }else{
                header("location: admin_customer_list.php?up_prf=0");
            }
            exit(1);
        }
        include('../head.php');
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/login.css" rel="stylesheet">
    <title>Update profile | Somaiya Canteen</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('navHeaderAdmin.php')?>

    <div class="container form-signin mt-auto w-50">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <?php 
            //Select customer record from database
            $customerId = $_GET["customerId"];
            $query = "SELECT customerFirstName,customerLastName,customerEmail,customerGender,customerType FROM customer WHERE customerId = {customerId} LIMIT 0,1";
            $result = $mysqli ->query($query);
            $row = $result -> fetch_array();
        ?>
        <form method="POST" action="adminCustomerEdit.php" class="form-floating">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-pencil-square me-2"></i>Update Profile</h2>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="firstname" placeholder="First Name" name="firstname"
                value="<?php echo $row["customerFirstName"];?>" required>
                <label for="firstname">First Name</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="lastname" placeholder="Last Name" value="<?php echo $row["customerLastName"];?>" name="lastname" required>
                <label for="lastname">Last Name</label>
            </div>
            <div class="form-floating mb-2">
                <input type="email" class="form-control" id="email" placeholder="E-mail" name="email" value="<?php echo $row["customerEmail"];?>" required>
                <label for="email">E-mail</label>
            </div>
            <div class="form-floating">
                <select class="form-select mb-2" id="gender" name="gender">
                    <option value="M" <?php if($row["customerGender"]=="M"){echo "selected";}?>>Male</option>
                    <option value="F" <?php if($row["customerGender"]=="F"){echo "selected";}?>>Female</option>
                    <option value="N" <?php if($row["customerGender"]=="N"){echo "selected";}?>>Non-binary</option>
                </select>
                <label for="gender">Your Gender</label>
            </div>
            <div class="form-floating">
                <select class="form-select mb-2" id="type" name="type">
                    <option value="cust" <?php if($row["customerType"]=="cust"){echo "selected";}?>>customer</option>
                    <option value="adm" <?php if($row["customerType"]=="adm"){echo "selected";}?>>Admin</option>
                </select>
                <label for="gender">Your role</label>
            </div>
            <input type="hidden" name="customerId" value="<?php echo customerId;?>">
            <button class="w-100 btn btn-success mb-3" name="updateConfirm" type="submit">Update Profile</button>
        </form>
    </div>
</body>

</html>