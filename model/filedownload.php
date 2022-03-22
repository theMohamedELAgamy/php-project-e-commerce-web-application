<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Illuminate\Database\Capsule\Manager as Capsule;
require "../vendor/autoload.php";


class filedownload{
    
   
    static function todownload(){
             filedownload::connect();
            if(self::getcounter()<7)
            {  session_start();
                    
                   $_SESSION["file"]=filedownload::getpath();
                     header("Location:downloadpage.php",true,301);
                                   exit();
            }else{echo "<center><h3>you have downloaded the file for 7 times</h3> </center>";}
    }
    //connection to database
    static function connect(){  
        $connectdb = new Dbconnection();


        
    }
    static function getid(){
        if(isset($_SESSION["id"])){
            return $_SESSION["id"];
        }else{
            return $_COOKIE["checked"];
        }
    }

    //get order counter
    static function getcounter(){
        $requrd= Capsule::table("orders")->where("user_id",(int)self::getid())->get();
           foreach ($requrd as $item) {
                $counter= $item->counter;
         }
         return $counter;
         
    }  
    //set order counter
    static function setcounter(){
        $newcounter=self::getcounter()+1;
        Capsule::table('orders')->where("user_id",(int)self::getid())->update(["counter" => $newcounter]);
    }
    //get recant path
    static function getpath(){
        $requrd= Capsule::table("products")->where("product_id",1)->get();
        
            foreach ($requrd as $item) {
                $path= $item->download_file_link;
         }
        
        return $path;
    }
    
    
    // change path
    static function changepath($filepath){
                 
                 $newname="text".rand().".txt";
                 rename($filepath,$newname) ;
                 Capsule::table('products')->where("product_id",1)->update(["download_file_link" => $newname]);
                
    }
    
       //edit profile
       static function editProfile($old_email,$new_email,$new_password){
        
        if((Capsule::table('users')->where("user_email",$old_email)->value("user_email"))==$old_email){
            Capsule::table('users')->where("user_email",$old_email)->update(["user_password" => $new_password,"user_email" => $new_email]);
            return true;
        }else return false;
       
    }
    //log out
   static  function logout(){
            session_unset();
            session_destroy();
            if(isset($_COOKIE["checked"])) {
                // delete token from db
                self::deleteTokenFromDb() ;
                // setcookie("checked", "", time()-(60*60*24*7));
                setcookie('checked', '', time() - 3600, '/') ;
                setcookie('token', '', time() - 3600, '/') ;
                unset($_COOKIE["checked"]);
                unset($_COOKIE["token"]);
            }
            setcookie("checked", "", time()-(60*60*24*7));
            setcookie("token", "", time()-(60*60*24*7));

            
            header("Location:../View/login.php",true,301) ;

            exit();
}

private function deleteTokenFromDb(){
    $user_id = $_COOKIE["checked"];
    $cookie_token = $_COOKIE["token"] ; 
    $connectdb = new Dbconnection();
    $table = $connectdb -> getTableName("tokens") ;
    $table->where("user_id","=", $user_id,"and")
    ->where("remember_me_token","like",$cookie_token,"and")
    -> delete() ;
  }
}
