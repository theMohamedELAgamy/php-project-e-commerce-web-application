<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once ("../vendor/autoload.php");

$valid_login = false ;

if(Token::is_token_exists() != 0)
{
    header("Location:downloadarea.php");
}

else
 if(isset($_SESSION["id"]))
{
    header("Location:downloadarea.php");
}
else if (isset($_POST["paymentPage"])) {
    header("Location:paymentIndex.php");
}
else 
if(isset($_POST["username"]))
{
    
    $visitor = new Visitor();
    $valid_login = $visitor->login($_POST["username"],$_POST["password"],$_POST["remember_me"]);

}


?>

<html>   
    <head>  
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <title> Login Page </title>  
    <link rel="stylesheet" href="./CSS/login.css">
    </head> 
        <body> 
        <div class="new-user"> 
            <div class="login-cont"><h1>Login</h1> </div>   
              
            <form  method="POST" >
                
                    <label>Useremail : </label>   
                    <input type="text" placeholder="Enter Useremail" name="username" >  
                    <label>Password : </label>   
                    <input type="password" placeholder="Enter Password" name="password"  >  
                    <label><input class="check-dis" type="checkbox" checked="checked" name = "remember_me"> Remember me </label>
                    

                    <input type="submit" value="Login" name="submit" > 
                        <input type="submit" value="Payment Page" name="paymentPage" > 
                   
                   <h5 style="  font-size: 20px;
                        color: red;"><?php 
                        // if (isset($_SESSION["wrong pass"])&& $_SESSION["wrong pass"]== TRUE )
                        if ($valid_login)
                        echo "Wrong user name and password";
                        else echo ""
                        ?></h5>
            </form> 
            </div>    
        </body>    
    </html>  
    <!-- pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" -->