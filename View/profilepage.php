<?php
   require('../Model/Profile.php');
   session_start();

   

if(!isset($_SESSION["id"]) && (Token::is_token_exists() == 0)) {
    header("Location:login.php") ;
    // var_dump($_SESSION["id"]) ;
    // var_dump(Token::is_token_exists());
    // echo "no session or token" ;
}

$userEmail = Profile::getUserEmail();


   $errors = []; // declaring errors[]

   // ToDo Check for sessions or cokes

    // back to download page
    if (isset($_POST['back'])) {
        header("Location:downloadarea.php") ;
    }

    // when submitting the profile data Form 
   if (isset($_POST['submit'])) {
    // validate entries
    $newProfile = new Profile($_POST);
    $errors = $newProfile->editProfileHandler();

    // if errors is empty .. then send user's data to db
    if(empty($errors)){
        $check =  $newProfile->sendProfileToDb();
        header("Location:downloadarea.php") ;
       
      }

    }

   ?>


<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" type="text/css" href="./CSS/profilepage.css">
</head>
<body>
<div class="new-user">
    <h2>Edit Profile Details</h2>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

      <label>New Email "optional": </label>
      <div class="error">
        <?php echo $errors['email'] ?? '' ?>
      </div>
      <input type="text" name="email" placeholder="<?php echo "$userEmail"?>"   value="<?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email']) ?? '' ?>">
     
      <label>New Password: </label>
      <div class="error">
        <?php echo $errors['password'] ?? '' ?>
      </div>
      <input type="text" name="password" value="<?php if(isset($_POST['password'])) echo htmlspecialchars($_POST['password']) ?? '' ?>">
     
      <label>Confirm New Password: </label>
      <div class="error">
        <?php echo $errors['confirmPassword'] ?? '' ?>
      </div>
      <input type="text" name="confirmPassword" value="<?php if(isset($_POST['confirmPassword'])) echo htmlspecialchars($_POST['confirmPassword']) ?? '' ?>">

      

      <input type="submit" value="submit" name="submit" >
      <input type="submit" value="back to download page" name="back" >

    </form>
    
  </div>
    
    
</body>
</html>

