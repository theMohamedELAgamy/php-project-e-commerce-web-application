<?php
   require('../Model/PaymentValidator.php');

   if(isset($_SESSION["id"]) || (Token::is_token_exists() != 0)) {
    header("Location:downloadarea.php") ;
    // var_dump($_SESSION["id"]) ;
    // var_dump(Token::is_token_exists());
    // echo "no session or token" ;
}
   
   use chillerlan\QRCode\QRCode;
   use chillerlan\QRCode\QROptions;
  

   $errors = []; // declaring errors[]

   // login
   if (isset($_POST['login'])) {
    header("Location:login.php") ;

   }
   // when submitting the form 
   if (isset($_POST['submit'])) {
    // validate entries
    $validation = new PaymentValidator($_POST);
    $errors = $validation->validateForm();

    // if errors is empty .. then send user's data to db
    if(empty($errors)){
      $validation->sendUserToDb();
      header("Location:login.php") ;

    }

    
}

  $options = new QROptions(
    [
      'eccLevel' => QRCode::ECC_L,
      'outputType' => QRCode::OUTPUT_MARKUP_SVG,
      'version' => 5,
    ]
  );

  $qrcode = (new QRCode($options))->render('http://localhost/project/Php_project/View/paymentindex.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" type="text/css" href="./CSS/payment.css">
</head>
<body>
<div class="new-user">
  <div class="pay-cont">    <h2>Payment Details</h2>
</div>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

      <label>Email: </label>
      <div class="error">
        <?php echo $errors['email'] ?? '' ?>
      </div>
      <input type="text" name="email" value="<?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email']) ?? '' ?>">
     
      <label>Password: </label>
      <div class="error">
        <?php echo $errors['password'] ?? '' ?>
      </div>
      <input type="password" name="password" value="<?php if(isset($_POST['password'])) echo htmlspecialchars($_POST['password']) ?? '' ?>">
     
      <label>Confirm Password: </label>
      <div class="error">
        <?php echo $errors['confirmPassword'] ?? '' ?>
      </div>
      <input type="password" name="confirmPassword" value="<?php if(isset($_POST['confirmPassword'])) echo htmlspecialchars($_POST['confirmPassword']) ?? '' ?>">

      <label>Credit Card Number: </label>
      <div class="error">
        <?php echo $errors['creditCardNumber'] ?? '' ?>
      </div>
      <input type="text" name="creditCardNumber" value="<?php if(isset($_POST['creditCardNumber'])) echo htmlspecialchars($_POST['creditCardNumber']) ?? '' ?>">

      <!-- 
            <div class="exp-wrapper">
        <input autocomplete="off" class="exp" id="month" maxlength="2" pattern="[0-9]*" inputmode="numerical" placeholder="MM" type="text" data-pattern-validate />
        <input autocomplete="off" class="exp" id="year" maxlength="2" pattern="[0-9]*" inputmode="numerical" placeholder="YY" type="text" data-pattern-validate />
      </div> -->


      <label>Credit Card Expiration Date: </label>
      <div class="error">
        <?php echo $errors['expirationDate'] ?? '' ?>
      </div>
      <div class="expiration-date" >
        <select name='expireMM' id='expireMM' value="">
          <option value=''>Month</option>
          <option value='01'>January</option>
          <option value='02'>February</option>
          <option value='03'>March</option>
          <option value='04'>April</option>
          <option value='05'>May</option>
          <option value='06'>June</option>
          <option value='07'>July</option>
          <option value='08'>August</option>
          <option value='09'>September</option>
          <option value='10'>October</option>
          <option value='11'>November</option>
          <option value='12'>December</option>
        </select> 
        <select name='expireYY' id='expireYY'>
            <option value=''>Year</option>
            <option value='2022'>2022</option>
            <option value='2023'>2023</option>
            <option value='2024'>2024</option>
            <option value='2025'>2025</option>
            <option value='2026'>2026</option>
        </select>

      </div>

      <div class="btn-container">
        <input type="submit" value="submit" name="submit" >
        <input type="submit" value="login" name="login" >

      </div>
      

    </form>
    <center>
      <img src='<?= $qrcode ?>' alt='QR Code' width='100' height='100'>
      <p class="qr">scan me to come here again</p>
    </center>
  </div>
    
    
</body>
</html>