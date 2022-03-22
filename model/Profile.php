<?php 
require "../vendor/autoload.php";
use Illuminate\Database\Capsule\Manager as Capsule;
class Profile {
    private $data ;
    private $errors = [];

    // Constructor
    public function __construct($post_data){
        $this->data = $post_data;
      }

       // Main Edit profile method
       public function editProfileHandler(){ 
        
        $this->validateEmail();
        $this->validatePassword();
        $this->validateConfirmPassword();
                
        return $this->errors;

      }

    // ************** validate each field ******************


      // Email
      private function validateEmail(){
        $val = trim($this->data['email']);
        $currentUserId = $_SESSION["id"];
        $this->connectDb();
        $currentUseEmail =  Capsule::table('users')->where('user_id', $currentUserId)->value("user_email");
        

        // // check if empty email
        // if(empty($val)){
        //   $this->addError('email', 'Email cannot be empty');
        // }  

        // check if valid email
        if(!filter_var($val, FILTER_VALIDATE_EMAIL) && !empty($val)){
            $this->addError('email', 'Email must be a valid email address');
        }

        // check if unique email
        $this->connectDb();
          if ((Capsule::table('users')->where('user_email', $val)->exists()) && ($val !=$currentUseEmail)) {
          $this->addError('email', 'This email already made a payment before');
          // echo ($currentUseEmail) ;
        }
        
        
      }

      // Password
      private function validatePassword(){

        $val = trim($this->data['password']);

        if(empty($val)){
            $this->addError('password', 'Password cannot be empty');
          } else {
            if(!preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}/', $val)){
              $this->addError('password','Password must be from 8 to 16 Alphanumeric characters with at least one digit and One Upper case letter');
            }
          }

      }

      // Confirm Password
      private function validateConfirmPassword(){

        $passwordVal = trim($this->data['password']);
        $confirmPasswordVal = trim($this->data['confirmPassword']);

        if(empty($confirmPasswordVal)){
            $this->addError('confirmPassword', 'Confirm password cannot be empty');
          } else {
            if (!($passwordVal === $confirmPasswordVal)) {
                $this->addError('confirmPassword','Password does not match');
             }
          }

      }

      // Add Error to errors[]
      private function addError($key, $val){
        $this->errors[$key] = $val;
      }

      public static function getUserEmail(){ 
        $current_id  ;

        if (isset($_SESSION["id"])) {
          $current_id = $_SESSION["id"]  ;
        } elseif (isset($_COOKIE["checked"])) {
          $current_id = $_COOKIE["checked"] ;
        }



      // $this->connectDb();
      
      $connectdb = new Dbconnection();
      return Capsule::table('users')->where('user_id',$current_id )->value("user_email");
      echo Capsule::table('users')->where('user_id',$current_id)->value("user_email");
     
      
      
      }

            // ************** Edit Profile Database Methods ******************

    // Send user's data to db
    public function sendProfileToDb(){ 
        $this->connectDb();
        $this->userProfileDataHandler();
     }

       // Connect to database method
       private function connectDb(){

        $connectdb = new Dbconnection();
       
      }

      // 
      private function userProfileDataHandler(){
        $newEmail = trim($this->data['email']);
        // echo  empty($newEmail);
        if (empty($newEmail)) {
          $newEmail = $this->getUserEmail();
        } ;
        $newPassword = trim($this->data['password']);

        // Hashing password
        $newHashedPassword = sha1($newPassword) ;

        $this->editProfile($newEmail ,$newHashedPassword);

       }

        //edit profile
        private function editProfile($new_email,$new_password){
            if(isset($_SESSION["id"])) {
                $updatedUser_Id = $_SESSION["id"] ;
            } else {
                $old_Id = $_COOKIE["checked"];
            }
          
        
            if((Capsule::table('users')->where("user_id",$updatedUser_Id)->value("user_id"))==$updatedUser_Id){
                Capsule::table('users')->where("user_id",$updatedUser_Id)->update(["user_password" => $new_password,"user_email" => $new_email]);
            //    echo "Done";
            }//else echo "There is a problem";
           
        }



}