<?php

include_once '../../../configuration/settings-configuration.php';
include_once __DIR__.'/../../../database/dbconfig.php';
require_once '../authentication/user-class.php';

class alumniRegistration {
    private $user;
    private $main_url;
    private $smtp_email;
    private $smtp_password;
    private $system_name;

    public function __construct() {
        $this->alumni = new ALUMNI();
        $this->main_url = $this->alumni->mainUrl();
        $this->smtp_email = $this->alumni->smtpEmail();
        $this->smtp_password = $this->alumni->smtpPassword();
        $this->system_name = $this->alumni->systemName();
    }

    public function signUp($email, $upass, $confirm_password, $user_type) {
        $tokencode = md5(uniqid(rand()));

        if($upass != $confirm_password) {
            $this->redirectWithError("Passwords do not match. Please try again.");
        }

        $stmt = $this->alumni->runQuery("SELECT * FROM users WHERE email=:email");
        $stmt->execute(array(":email"=>$email));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt->rowCount() > 0) {
            $this->redirectWithError("Email already taken. Please try another one.");
        } else {
            if($this->alumni->register($email, $upass, $tokencode, $user_type)) {
                $id = $this->alumni->lasdID();  
                $key = base64_encode($id);
                $id = $key;

                $message = "     
                    Hello $email,
                    <br /><br />
                    Welcome to $this->system_name
                    <br /><br />
                    <a href='$this->main_url/verify-account?id=$id&code=$tokencode'>Click HERE to Verify your Account!</a>
                    <br /><br />
                    Thanks,";
                    
                $subject = "Verify Email";
                    
                $this->alumni->send_mail($email,$message,$subject,$this->smtp_email,$this->smtp_password,$this->system_name);
                $this->redirectWithSuccess("Please check the Email to verify the account.");
            } else {
                $error = $this->alumni->getLastError();
                $this->redirectWithError("Something went wrong, please try again! Error: " . $error);
            }
        }
    }

    private function redirectWithError($message) {
        $_SESSION['status_title'] = "Oops!";
        $_SESSION['status'] = $message;
        $_SESSION['status_code'] = "error";
        $_SESSION['status_timer'] = 100000;
        header('Location: ../../../');
        exit();
    }

    private function redirectWithSuccess($message) {
        $_SESSION['status_title'] = "Success!";
        $_SESSION['status'] = $message;
        $_SESSION['status_code'] = "success";
        $_SESSION['status_timer'] = 40000;
        header('Location: ../../../');
        exit();
    }

    public function addRegistration($user_id, $first_name, $middle_name, $last_name, $batch, $course_id){
            
        $stmt = $this->alumni->runQuery('UPDATE users SET first_name=:first_name, middle_name=:middle_name, last_name=:last_name, batch=:batch, course_id=:course_id WHERE id=:id');
        $exec = $stmt->execute(array(
            ':id'             => $user_id,
            ':first_name'     => $first_name,
            ':middle_name'    => $middle_name,
            ':last_name'      => $last_name,
            ':batch'          => $batch,
            ':course_id'      => $course_id,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Alumni information successfully added!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }
    
        header('Location: ../');
        exit();

    }


}

// Usage outside the class
if(isset($_POST['btn-signup'])) {
    $email = trim($_POST['email']);
    $upass = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $user_type = 3;

    $signup = new alumniRegistration();
    $signup->signUp($email, $upass, $confirm_password, $user_type);
}

//add other information to alumni
if(isset($_POST['btn-add-alumni-other'])) {
    $user_id            = $_GET["id"];
    $first_name         = trim($_POST['first_name']);
    $middle_name        = trim($_POST['middle_name']);
    $last_name          = trim($_POST['last_name']);
    $batch              = trim($_POST['batch']);
    $course_id          = trim($_POST['course']);

    $employerUpdate = new alumniRegistration();
    $employerUpdate->addRegistration($user_id, $first_name, $middle_name, $last_name, $batch, $course_id);
}