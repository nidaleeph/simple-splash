
<!DOCTYPE HTML>  
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
<style>

  .error {color: #FF0000;}
  #cover {
  background-size: cover;
  height: 100%;
  text-align: left;
  display: flex;  
  align-items: center;
  position: relative;
  }

  #cover-caption {
    width: 100%;
    position: relative;
    z-index: 1;
  }
  /* input {
    float: right;
    clear: both;
  } */

  /* only used for background overlay not needed for centering */


.overlay{
    display: none;
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 999;
    max-height: 100%;
    max-width: 100%;
    padding: 0;
    margin: 0 auto;
    background: rgba(255,255,255,0.8) url("assets/img/giphy.gif") center no-repeat;
}
/* Turn off scrollbar when body element has the loading class */
body.loading{
    overflow: hidden;   
}
/* Make spinner image visible when body element has the loading class */
body.loading .overlay{
    display: block;
}

img {
    padding: 0;
    display: block;
    margin: 0 auto;
    max-height: 100%;
    max-width: 100%;
}

</style>
<script src="js/jquery-3.5.1.min.js" type="text/javascript"></script>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<script src="bootstrap/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<link rel="icon" type="image/png" href="assets/img/favicon.ico">

<script>

$(document).ready(function(e) {
    $("form").submit(function(e){
        e.preventDefault();
        let formdata = $("form").serialize();
        $.ajax({
        type: "POST",
        url: "getvoucher.php",
        data: formdata,
        // dataType: "json",
        success: function(response) {
            $("#response").html(response); 
        },
        error: function(xhr, status, error){
         var errorMessage = xhr.status + ': ' + xhr.statusText
         alert('Error - ' + errorMessage);
        }
        }); 
    });
});

// Add remove loading class on body element based on Ajax request status
$(document).on({
    ajaxStart: function(){
        $("body").addClass("loading"); 
    },
    ajaxStop: function(){ 
        $("body").removeClass("loading"); 
    }    
});

function onlyNumberKey(evt) {  
  var ASCIICode = (evt.which) ? evt.which : evt.keyCode
  if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
      return false;
  return true;
}


$(document).ready(function(){
    $("#MobileNumber").keyup(function(){
      if (
        ($(this).val().length > 0) && ($(this).val().substr(0,4) != '+639')
        || ($(this).val() == '')
        ){
        $(this).val('+639');    
      }
    });
});

function onlyAlphabets(e, t) {
  try {
      if (window.event) {
          var charCode = window.event.keyCode;
      }
      else if (e) {
          var charCode = e.which;
      }
      else { return true; }
      if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 8 || charCode == 32)
          return true;
      else
          return false;
  }
  catch (err) {
      alert(err.Description);
  }
}

function onlyNumberKey(evt) {
         
  // Only ASCII character in that range allowed
  var ASCIICode = (evt.which) ? evt.which : evt.keyCode
  if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
      return false;
  return true;
}

</script>
</head>
<?php
require __DIR__ . '/vendor/autoload.php';
require 'settings.php';
require __DIR__ . '/controllers/functions.php';

// define variables and set to empty values
$cnumber = null;
$FirstName = null;
$LastName = null;
$BirthDate = null;
$MobileNumber = null;
?>
<body>
<section id="cover" class="min-vh-100">
  <div id="cover-caption" >
    <div class="container">
      <div class="row">
        <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10 mx-auto text-left form p-4">
        <!-- <h1>Cebuana Lhuiler</h2> -->
        <img src="assets/img/cebuana_logo.png" alt="" height="180">
        <div class="row">
          
          <form id="formdata" name="formdata" method="post">  
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="CardNumber" name="cnumber" placeholder="12345689" value="<?php echo $cnumber;?>" onkeydown="return onlyNumberKey(event)" pattern = "[0-9]+" oninvalid="this.setCustomValidity('Please enter a valid card number. Must contain only numbers')" oninput="this.setCustomValidity('')" required>
              <label for="CardNumber">Card Number
              <span class="error">*</span>
              </label>
              <!-- <input id="i-cnumber" type="text" name="cnumber" value="<?php echo $cnumber;?>" required> -->
            </div>
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="firstname" value="<?php echo $FirstName;?>" maxlength="20" onkeydown="return onlyAlphabets(event,this);" pattern="[A-Za-z\s]+" oninvalid="this.setCustomValidity('Please enter a valid firstname. Must contain only letters')" oninput="this.setCustomValidity('')">
              <label for="firstname">First Name
              <!-- <span class="error">*</span> -->
              </label>
              <!-- <input id="i-firstname" type="text" name="FirstName" value="<?php echo $FirstName;?>" required> -->
            </div>

            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="LastName" name="LastName" placeholder="lastname" value="<?php echo $LastName;?>" maxlength="20" onkeydown="return onlyAlphabets(event,this);" pattern="[A-Za-z\s]+" oninvalid="this.setCustomValidity('Please enter a valid lastname. Must contain only letters')" oninput="this.setCustomValidity('')">
              <label for="LastName">Last Name
              <!-- <span class="error">*</span> -->
              </label>
              <!-- <input type="text" name="LastName" value="<?php echo $LastName;?>" required> -->
            </div>

            <div class="form-floating mb-3">
              <input type="date" class="form-control" id="BirthDate" name="BirthDate" placeholder="birthday" min="1880-01-01" max="<?php echo date("Y-m-d", strtotime("now"))?>" value="<?php echo $BirthDate;?>">
              <label for="BirthDate">Birthday
              <!-- <span class="error">*</span> -->
              </label>
              <!-- <input id="i-birthday" type="date" name="BirthDate" value="<?php echo $BirthDate;?>" required> -->
            </div>

            <div class="form-floating mb-3">
              <input type="tel" class="form-control" id="MobileNumber" name="MobileNumber" placeholder="mobilenumber" value="+639<?php echo $MobileNumber;?>" onkeydown="return onlyNumberKey(event)" maxlength="13" oninvalid="this.setCustomValidity('Please enter a valid mobile number. Must contain only numbers')" oninput="this.setCustomValidity('')" required>
              <label for="MobileNumber">Mobile Number (+639)
              <!-- <span class="error">*</span> -->
              </label>
              <!-- <input type="text" name="MobileNumber" value="<?php echo $MobileNumber;?>" required> -->
            </div>
            <p class="text-left"><span class="error">* required field</span></p>

              <!-- <input type="submit" name="submit" value="Get Voucher"> -->
              <div class="d-grid gap-2">
                <button type="submit" name="submit" class="btn btn-primary">Get Voucher</button>
              </div>
              <div class="overlay"></div>
              <br>
          </form>
            <div id="response" class='text-center'></div>
        </div>
      </div>
    </div>
    </div>
  </div>
</section>

<script src="bootstrap/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>