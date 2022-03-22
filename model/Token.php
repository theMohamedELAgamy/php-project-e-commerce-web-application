<?php

class Token
{
    private $token;

    public function __construct($user_id)
    {
        $this->token = sha1(rand(1000,2000));
        var_dump($this->token);
        $db = new Dbconnection();
        $db->getTableName("tokens")->insert(["user_id"=>$user_id,"remember_me_token"=>$this->token]);
        
    }

    public function get_cookie_token()
    {
        return $this->token;
    }

    public static function is_token_exists ()
    {
        

        if(!isset($_COOKIE["checked"]) || !isset($_COOKIE["token"]))
        {
            return 0 ; 
        }
        
        $user_id = ($_COOKIE["checked"])  ;
        $cookie_token = ($_COOKIE["token"]) ;

        $db = new Dbconnection();
        $flag = $db->getTableName("tokens")->where("user_id","=",$user_id,"and")
                                        ->where("remember_me_token","like",$cookie_token,"and")
                                        ->exists();
        if($flag) {
            return $user_id;
        }

        return 0 ; 
                                
    }
}