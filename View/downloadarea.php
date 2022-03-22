 <?php
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
 session_start();
  //  require('../Model/filedownload.php');


 if( isset($_SESSION["id"]) || isset($_COOKIE["checked"]) ) {

      require('../Model/PaymentValidator.php');

      require "../vendor/autoload.php";

       require_once("downloadview.html");
       
       //if user click download buuton
      if(isset($_POST["download"])){
                     filedownload::todownload();
        }
      if(isset($_POST["profile"])){
        header("Location:profilepage.php") ;
        }

               //if user click logout buuton

        if(isset($_POST["logout"])){
          filedownload::logout();
      }

 }else {
       header("Location:login.php",true,301);
     exit();
   //  echo "no cookies nor session";
 }
?>
