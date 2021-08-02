<?php
require_once('app/vendor/autoload.php');
class Login extends Controller{
    public $loginError = array( 'e1'=>'Unknown  Email Address. Please Ensure you are registered', 'e2'=>'Unknown  username', 'e3'=>'Invalid Password or UserName', 'e4'=>'Email Not Verified', 'e5'=>'To Many Request');
    public $registerError = array( 'e1'=>'Unknown  Email Address. Please Ensure you are registered', 'e2'=>'Unknown  username', 'e3'=>'Invalid Password or UserName', 'e4'=>'Email Not Verified', 'e5'=>'To Many Request');
    function __construct()
    {
        $conString = 'mysql:host=' . SQL_HOST . ';dbname=' . SQL_DB;
        $this->dbConn = new PDO($conString, SQL_USER, SQL_PASS);
    }


    public function register(){
        $auth = new \Delight\Auth\Auth($this->dbConn);
        try {
            $userId = $auth->register($_POST['email'], $_POST['password']);
            $UserDb = $this->load_model('UserDb');
            $UserDb->registerUser($userId);
            $UserDb->insert_notification("Welcome to Budeshi Hub", "Welcome to Budeshi Hub, Please Ensure you complete your profile and activate your account by click the link that was sent to your email. Have a great time using Budeshi Hub", $userId, "");
            $auth->login($_POST['email'], $_POST['password']);
            $userId = $auth->getUserId();
            $mail = $this->load_helper("Sender");
            $result = $mail->welcome_message($userId,$_POST['email']);
            $data['status'] = "success";
            $data['message'] = "You have been successfully registered, Please Check your inbox or <b>Spam</b> to confirm your email";
            echo json_encode($data);
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            $error = urlencode("Invalid Email address");
            $data['status'] = "danger";
            $data['message'] = "Invalid Email address";
            echo json_encode($data);
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            
            $error = 'Invalid password';
            $data['status'] = "danger";
            $data['message'] = "Invalid Password";
            echo json_encode($data);
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $error = 'User already exists';
            $data['status'] = "danger";
            $data['message'] = "User already exists";
            echo json_encode($data);
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            $error = 'Too many requests';
            $data['status'] = "danger";
            $data['message'] = $error;
            echo json_encode($data);
        }
    }
    public function registeradmin(){
        
        $auth = new \Delight\Auth\Auth($this->dbConn);
        $UserDb = $this->load_model('UserDb');
        $user_data = [];
        $req_data = $this->input_post();

        $user_data["name"] = $req_data->name;
        $user_data["auth_id"] = $auth->register($req_data->email, $req_data->password);
        $UserDb->registerUser($user_data);
        try {
            $auth->admin()->addRoleForUserById($user_data["auth_id"], \Delight\Auth\Role::ADMIN);
            $this->sendJSON(Array(
                "status" => OKAY_RESPONSE,
                "message" => "succesfully created and admin user"
            ));
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            $this->sendJSON(Array(
                "status" => DANGER_RESONSE,
                "message" => "Could not create new user"
            ));
        }



    }
    public function transact($type = "login"){
        if ($type == "login") {
            if ($_SERVER["REQUEST_METHOD"] != "POST") {
                die("Unauthorized Access");
            }

            $auth = new \Delight\Auth\Auth($this->dbConn);
            // $remember_duration = null;
            // if(!empty($_POST['remember'])){
            //     $remember_duration = (int) (60*60*24*30);
            // }
            
            $data = $this->input_post();
            try {
                $auth->login($data->email, $data->password);
                $userId = $auth->getUserId();
                $this->sendJSON(Array(
                    "status" => OKAY_RESPONSE,
                    "data" => Array(
                        "userId" => $userId,
                        "email" => $data->email
                    )
                    ));
            }
            catch (\Delight\Auth\InvalidEmailException $e) {
                $e= "Unknown  Email Address. Please Ensure you are registered";
                //$e= str_replace(" ", " #", $e);
                $this->sendJSON(Array(
                    "status" => DANGER_RESPONSE,
                        "message" => "Unknown Email Address"
                    ));
                
            }
             catch (\Delight\Auth\UnknownUsernameException $e) {
                
                $e= "Unknown  username";
                $this->sendJSON(Array(
                    "status" => DANGER_RESPONSE,
                    "message" => $e
                    ));
            } catch (\Delight\Auth\InvalidPasswordException $e) {
                $e= "Invalid Password";
                $this->sendJSON(Array(
                    "status" => DANGER_RESPONSE,

                    "message" => $e
                    ));
            } catch (\Delight\Auth\EmailNotVerifiedException $e) {
                $e= "Email Not Verified";
                $this->sendJSON(Array(
                    "status" => DANGER_RESPONSE,
                    "message" => $e
                    ));
            } catch (\Delight\Auth\TooManyRequestsException $e) {
                $this->sendJSON(Array(
                    "status" => DANGER_RESPONSE,
                    "message" => $e
        
                    ));
               
            }
            catch( \Delight\Auth\AmbiguousUsernameException $e){
                $e = "Ambiguous Username";
                $this->sendJSON(Array(
                    "status" => DANGER_RESPONSE,
                    "data" => Array(
                        "message" => $e
                    )
                    ));
            }
        }
        if ($type == "logout") {
            $auth = new \Delight\Auth\Auth($this->dbConn);
            if (!$auth->isLoggedIn()) {
                echo 'No access not logged in';
                die();
            }
            $auth->logOut();
            $auth->destroySession();
            
        }
    }
    public function is_logged_in(){
        $auth = new \Delight\Auth\Auth($this->dbConn);
        $userId = $auth->getUserId();
        if($userId){
            $email = $auth->getEmail();

            $this->sendJSON(Array(
                "status" => OKAY_RESPONSE,
                "data" => Array(
                    "userId" => $userId,
                    "email" => $email
                )
                ));
        }
       

    }

    public function password_reset(){
        $this->load_view('backend/recover',[]);
    }
    public function forgetpassword(){
        $auth = new \Delight\Auth\Auth($this->dbConn);
        try {
                
                $auth->forgotPassword($_POST['email'], function ($selector, $token) {
                $url = ABS_PATH.'Login/reset/'.urlencode($selector) .'/' .urlencode($token);
                $mail = $this->load_helper('Sender');
                $response = $mail->reset_password($_POST['email'], $_POST['email'], $selector, $token);
                $out['status'] = 'success';
                $out['message'] = 'A password reset mail has been sent to '.$_POST['email'];
                echo json_encode($out);
            });   
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            $out['ajaxstatus'] = 'danger';
            $out['err'] = 'danger';
                $out['message'] = 'The email '.$_POST['email'].' you provided is invalid: Try Again';
                echo json_encode($out);
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            $out['err'] = 'danger';
                $out['message'] = 'The'.$_POST['email']. ' has not been verified: Please verify your email';
                echo json_encode($out);
        }
        catch (\Delight\Auth\ResetDisabledException $e) {
            $out['err'] = 'danger';
                $out['message'] = 'A password reset is not activated on this system';
                echo json_encode($out);
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            $out['err'] = 'danger';
                $out['message'] = 'Too many request';
                echo json_encode($out);
        }
    }
    public function reset($selector, $token){
        $auth = new \Delight\Auth\Auth($this->dbConn);
        if ($auth->canResetPassword($selector, $token)) {
            $out['token'] = $token;
            $out['selector'] = $selector;
            $this->load_view('backend/newpass', $out);
            
        }
        else{
            $out['err'] = 'danger';
            $out['message'] = 'Invalid Token set for this call';
            echo json_encode($out);
        }

    }
    public function newpass(){
        $auth = new \Delight\Auth\Auth($this->dbConn);
        $selector = $_POST['selector'];
        $token = $_POST['token'];
        $password = $_POST['password'];
        try {
            $auth->resetPassword($selector, $token, $password);
            $out['status'] = 'success';
            $out['message'] = 'Password was Successfully reset, Proceed to Login';
            echo json_encode($out);
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            $out['err'] = 'danger';
            $out['message'] = 'Invalid Token set for this call';
            echo json_encode($out);
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            $out['err'] = 'danger';
            $out['message'] = 'Token has expired try reseting your password again!';
            echo json_encode($out);
        }
        catch (\Delight\Auth\ResetDisabledException $e) {
            $out['err'] = 'danger';
            $out['message'] = 'The system does not support resetting of password';
            echo json_encode($out);
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            $out['err'] = 'danger';
            $out['message'] = 'The password you provided is invalid';
            echo json_encode($out);
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            $out['err'] = 'danger';
            $out['message'] = 'Too Many Request';
            echo json_encode($out);
        }

    }
}



?>