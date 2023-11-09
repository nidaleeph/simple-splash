<?php
require $_SERVER['DOCUMENT_ROOT']. '/vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT']. '/settings.php';
use Firebase\JWT\JWT;
date_default_timezone_set('Asia/Manila');
//function here
header("Access-Control-Allow-Origin: *");
function authenticate(){  
  $url = $GLOBALS['authenticate_api'];
  $cnumber = $_POST['cnumber'];
  $MyObject = array(
      "CardNumber" => $cnumber
  );
  $content = json_encode($MyObject);  
  $jwtToken = getToken();
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_HTTPHEADER,
  array("Content-type: application/json",
  "X-API-KEY: ".$GLOBALS['x_api_key'],
  "Authorization: JWT ".$jwtToken));
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
  
  $json_response = curl_exec($curl);
  
  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      
  $responseCode = 0;

  if (curl_errno($curl)) {
    die(curl_error($curl));
  } else {
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  }

  curl_close($curl);
  

  $response = json_decode($json_response);

  return $response;
}

function getToken(){
  $key = $GLOBALS['jwt_secretkey'];
  $datestamp = time() + 300;
  $payload = array(
      "exp" => $datestamp,
      "iss" => $GLOBALS['iss'],
      "sub_jwk" => $GLOBALS['sub_jwk']
  );
  $jwt = JWT::encode($payload, $key);
  $decoded = JWT::decode($jwt, $key, array('HS256'));

  return $jwt;
}

function networkgrouplist($tenant){
  $url = "https://cloud-as.ruijienetworks.com/service/api/maint/network/list?page=1&per_page=20&access_token=".$tenant->access_token;

  $MyObject = array(
      "groupId" => $tenant->groupId
  );
  $content = json_encode($MyObject);

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_HTTPHEADER,
  array("Content-type: application/json"));
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
  
  $json_response = curl_exec($curl);
  
  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      
  $responseCode = 0;

  if (curl_errno($curl)) {
    die(curl_error($curl));
  } else {
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  }

  curl_close($curl);
  
  $response = json_decode($json_response);

  return $response;
}

function generate_voucher($tenant,$network,$voucher_package){
  $profile = null;
  foreach($voucher_package->voucherData->list as $data){
      if($data->name == $GLOBALS['voucherPackage']){
          $profile = $data;
          break;
      }
  }
  $buildingId = 0;
  foreach($network->dataList as $data){
      if($data->name == $GLOBALS['networkName']){
            $buildingId = $data->buildingId;
            break;
      }
  }
  // echo json_encode($profile) . "<br>";
  $url = "https://cloud-as.ruijienetworks.com/service/api/intlSamVoucher/create/".$tenant->tenantName."/".$GLOBALS['username']."/".$buildingId."?access_token=".$tenant->access_token;
  
  $MyObject = array(
      "groupId" => $buildingId,
      "quantity" => 1,
      "profile" => $profile->uuid
  );
  $content = json_encode($MyObject);

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_HTTPHEADER,
  array("Content-type: application/json"));
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
  
  $json_response = curl_exec($curl);
  
  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      
  $responseCode = 0;

  if (curl_errno($curl)) {
    die(curl_error($curl));
  } else {
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  }

  curl_close($curl);
  
  $response = json_decode($json_response);
  $code = null;
  
  if($response->code == 0){
    if($response->voucherData->code == 0){
      foreach($response->voucherData->list as $list){
          $code = $list->codeNo;
          return $code;
      }
    }
  } 
}

function getTenant(){  
  $url = "https://cloud-as.ruijienetworks.com/service/api/login?appid=".$GLOBALS['appId']."&secret=".$GLOBALS['secret']."&account=".$GLOBALS['username']."&password=".$GLOBALS['password'];

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_HTTPHEADER,
  array("Content-type: application/json"));
  
  $json_response = curl_exec($curl);
  
  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      
  $responseCode = 0;

  if (curl_errno($curl)) {
    die(curl_error($curl));
  } else {
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  }

  curl_close($curl);
  
  $response = json_decode($json_response);

  return $response;
}


function voucher_package($tenant,$network){  
  $buildingId = 0;
  foreach($network->dataList as $data){
      if($data->name == $GLOBALS['networkName']){
            $buildingId = $data->buildingId;
            break;
      }
  }
  $url = "https://cloud-as.ruijienetworks.com/service/api/intlSamProfile/getList/".$tenant->tenantName."/".$buildingId."?access_token=".$tenant->access_token."&tenantId=".$tenant->tenantId."&start=0&pageSize=3";
  // echo $url;
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_HTTPHEADER,
  array("Content-type: application/json"));
  
  $json_response = curl_exec($curl);
  
  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      
  $responseCode = 0;

  if (curl_errno($curl)) {
    die(curl_error($curl));
  } else {
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  }

  curl_close($curl);
  
  $response = json_decode($json_response);
  return $response;
}   

function savesql($card, $name, $surname, $mnumber, $bday, $voucher){
  try {
    $conn = new mysqli($GLOBALS['db_hostname'], $GLOBALS['db_username'], $GLOBALS['db_password'], $GLOBALS['db_name'], $GLOBALS['db_port']);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $voucher_expiration = date("Y-m-d", strtotime('tomorrow')) . "T00:00:00";
    // set the PDO error mode to exception
    $sql = "INSERT INTO client_logs (cardnumber, firstname, lastname, mobilenumber, birthday, is_successful, voucher_code, voucher_expiration)
    VALUES ('$card', '$name', '$surname', '$mnumber', '$bday', 1, '$voucher', '$voucher_expiration')";
    // use exec() because no results are returned
    if ($conn->query($sql) === TRUE) {
      return 1;
    } else {
      return 0;
    }
    $conn->close();
  } catch(Exception $e) {
  echo $sql . "<br>" . $e->getMessage();
  }
}

function getdata($card){
  try {
    $conn = new mysqli($GLOBALS['db_hostname'], $GLOBALS['db_username'], $GLOBALS['db_password'], $GLOBALS['db_name'], $GLOBALS['db_port']);
    
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    // set the PDO error mode to exception
    $sql = "SELECT * FROM client_logs where cardnumber = $card and is_deleted = 0 order by created_at desc limit 1";
    $result = $conn->query($sql);
    $conn->close();
    if ($result->num_rows > 0) {
      // output data of each row
      return $row = $result->fetch_assoc();
    }
    return 0;     
  } catch(Exception $e) {
  echo $sql . "<br>" . $e->getMessage();
  }
}
?>