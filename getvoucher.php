<?php
require $_SERVER['DOCUMENT_ROOT'] . '/controllers/functions.php';
require $_SERVER['DOCUMENT_ROOT']. '/settings.php';

$voucherCode = null;
$apiprocceed = false;
$complete = false;
$apiResponse = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["cnumber"])) {
    $complete = false;
  }  
  else{
    $complete = true;
  }
  if($complete){
    $apiResponse = authenticate();
    $apiprocceed = true;
  }
  else{
    $apiprocceed = false;
  }
}

// if(isset($apiResponse->ResultCode) && $apiResponse->ResultCode == "SST000" && $apiprocceed){
if(isset($apiResponse->ResultCode) && $apiprocceed){
$oldData = getdata($_POST['cnumber']);
if($oldData != 0){
    $expiration = strtotime($oldData["voucher_expiration"]);
    $presenttime = time();
    if($presenttime < $expiration){
    echo "<span class='text-center'>You have already claimed your voucher code please wait until " .date("F j, Y, g:i a",$expiration) . "</span>";
    echo "<br>";
    echo "<span class='text-center'>Your last voucher code is: <strong>".$oldData["voucher_code"] . "</strong></span>";
    }
    else{
    echo("<span class='text-center'>Getting your new voucher Code</span>");
    $tenant = getTenant();
    if($tenant->code == 0){
        $network = networkgrouplist($tenant);
        if($network->code == 0){
            $voucher_package = voucher_package($tenant,$network);
            echo json_encode($voucher_package);
            if($voucher_package->code == 0){
                $voucher = generate_voucher($tenant,$network,$voucher_package);
                // echo $voucher;
                if($voucher != null){
                    echo "<br>";
                    $sqlmes = savesql($_POST['cnumber'], $_POST['FirstName'], $_POST['LastName'], $_POST['MobileNumber'], $_POST['BirthDate'], $voucher);
                    if($sqlmes == 1){
                    echo "<span class='text-center'>Here is your voucher code: <strong>".$voucher . "</strong></span>";
                    }
                    else{
                    echo "<span class='text-center'>Something went wrong please try again. (Error-05)</span>";
                    }
                }
                else {
                    echo "<span class='text-center'>Something went wrong please try again. (Error-04)</span>";
                }
            }
            else {
                echo "<span class='text-center'>Something went wrong please try again. (Error-03)</span>";
            }
        }
        else {
            echo "<span class='text-center'>Something went wrong please try again. (Error-02).</span>";
        }
    }
    else {
        echo "<span class='text-center'>Something went wrong please try again. (Error-01)</span>";
    }
    }
}
else{
    echo("<span class='text-center'>Getting Voucher Code</span>");
    $tenant = getTenant();
    if($tenant->code == 0){
        $network = networkgrouplist($tenant);
        if($network->code == 0){
            $voucher_package = voucher_package($tenant,$network);
            if($voucher_package->code == 0){
                $voucher = generate_voucher($tenant,$network,$voucher_package);
                if($voucher != null){
                echo "<br>";
                $sqlmes = savesql($_POST['cnumber'], $_POST['FirstName'], $_POST['LastName'], $_POST['MobileNumber'], $_POST['BirthDate'], $voucher);
                if($sqlmes == 1){
                    echo "<span class='text-center'>Here is your voucher code: <strong>". $voucher ."</strong></span>";
                }
                else{
                    echo "<span class='text-center'>Something went wrong please try again</span>";
                }
                }
                else {
                    echo "<span class='text-center'>Something went wrong please try again.</span>";
                }
            }
            else {
                echo "<span class='text-center'>Something went wrong please try again.</span>";
            }
        }
        else {
            echo "<span class='text-center'>Something went wrong please try again.</span>";
        }
    }
    else {
        echo "<span class='text-center'>Something went wrong please try again.</span>";
    }
}          
}
else if(!$apiprocceed)
{
echo "<span class='text-center'>Please fill out all the required Information</span>";  
}
else{
if(isset($apiResponse->ResultMessage)){
    echo "<span class='text-center'>".$apiResponse->ResultMessage."</span>";
}
}
?>