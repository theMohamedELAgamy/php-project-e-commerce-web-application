<?php 

require "../vendor/autoload.php";
use Illuminate\Database\Capsule\Manager as Capsule;

 class PaymentValidator { 


    private $data ;
    private $errors = [];
    // private static $fields = ['email', 'password' , 'confirmPassword','creditCardNumber','expirationDate'];
   

    // Constructor
    public function __construct($post_data){
        $this->data = $post_data;
      }

      // Main validation method
      public function validateForm(){ 
        
        $this->validateEmail();
        $this->validatePassword();
        $this->validateConfirmPassword();
        $this->validateCreditCardNumber();
        $this->validateExpirationDate();
        
        return $this->errors;

      }


      // ************** validate each field ******************
      

      // Email
      private function validateEmail(){
        $val = trim($this->data['email']);

        // check if empty email
        if(empty($val)){
          $this->addError('email', 'Email cannot be empty');
        }  

        // check if valid email
        if(!filter_var($val, FILTER_VALIDATE_EMAIL) && !empty($val)){
            $this->addError('email', 'Email must be a valid email address');
        }

        // check if unique email
        $this->connectDb();
          if (Capsule::table('users')->where('user_email', $val)->exists()) {
          $this->addError('email', 'This email already made a payment before');
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

      // Card number
      private function validateCreditCardNumber(){
        $val = trim($this->data['creditCardNumber']);
        if(empty($val)){
          $this->addError('creditCardNumber', 'Credit Card Number Cannot Be Empty');
        } else {
          if(!ctype_digit($val) || strlen($val) != 16){
            $this->addError('creditCardNumber','Credit Card Number Must Be 16 Positive Numbers');
          }
        }

      }


      // Expiration date
      private function validateExpirationDate(){
        $monthVal = trim($this->data['expireMM']);
        $yearVal = trim($this->data['expireYY']);
        $expirationDate = "$yearVal/$monthVal/01";
        $todayDate = date("Y/m/d");
                if(empty($monthVal) ||empty($yearVal) ){
          $this->addError('expirationDate', 'Expiration Date Cannot Be Empty');
         } else {
          if ((($expirationDate) < ($todayDate))) {
            // $this->addError('expirationDate', 'Expiration Date Must Be A Valid Date');
            $this->addError('expirationDate', 'Expiration date must be a valid dDate');
          }
        }

      }


      // Add Error to errors[]
      private function addError($key, $val){
        $this->errors[$key] = $val;
      }

      // ************** Payment Database Methods ******************

      // Send user's data to db
      public function sendUserToDb(){ 
        $this->connectDb();
        $this->userDataHandler();
     }


      private function userDataHandler(){
        $email = trim($this->data['email']);
        $password = trim($this->data['password']);

        // Hashing password
        $hashedPassword = sha1($password) ;

        // insert into users table
        $userId = Capsule::table('users')->insertGetId([
          'user_email' => $email,
          'user_password' => $hashedPassword
      ]);

      // Insert into orders table
      $produceId = 1;
      $affected = Capsule::table('orders')->insert([
        'user_id' => $userId,
        'product_id' => "$produceId",

        ]);

 }


      // Connect to database method
      private function connectDb(){

        $connectdb = new Dbconnection();
       
      }



  }



















?>