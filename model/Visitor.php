<?php

/*******************dispalying errors*****************************/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once ("../vendor/autoload.php");

/**
 * Description of Visitor
 *
 * @author moustafa
 */
class Visitor {
    
    private $user_table;
    private $user;
    
                
    function __construct()
    {
       
        $this->database = new Dbconnection();  //creating object from database
        $this->user_table = $this->database->getTableName("users"); //using table users
        
        
    }

    public function login($user_name , $user_password , $remember_me) 
    {
        $hashed_pass = sha1($user_password) ;
        $this->user = $this->user_table->where('user_email','like',$user_name,"and")
                            ->where("user_password","like", $hashed_pass ,"and")
                            ->first();   //first not get --> to return non associatve array

                            if (!$this->user)
                            {
                                // $_SESSION["wrong pass"] = TRUE;
                                $flag = true ;
                            }

                            else if ($remember_me == "on")
                            {
                                $_SESSION["id"] = $this->user->user_id;
                                $cookie_name = "checked";
                                $cookie_value = $this->user->user_id;
                                $token = new Token($this->user->user_id);
                                $cookie_token= $token->get_cookie_token();
                                setcookie($cookie_name, $cookie_value, time() + (60*60), "/"); 
                                setcookie("token",$cookie_token,time()+60*60, "/");
                                // $_SESSION["wrong pass"] = FALSE;
                                $flag = false ;
                                header("Location:../View/downloadarea.php");


                            }

                            else
                            {
                                $_SESSION["id"] = $this->user->user_id;
                                // $_SESSION["wrong pass"] = FALSE;
                                $flag = false ;
                                var_dump($remember_me);
                                header("Location:../View/downloadarea.php");

                            }
                            return $flag ;

}
}




// echo "<pre>";
// print_r($user);
// echo "<pre>";  