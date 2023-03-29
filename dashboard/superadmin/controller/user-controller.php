<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__.'/../../../database/dbconfig.php';
require_once '../../employer/authentication/employer-class.php';
require_once '../../user/authentication/user-class.php';


//EMPLOYER CONTROLLER

class employerRegistration {
    private $employer;
    private $main_url;
    private $smtp_email;
    private $smtp_password;
    private $system_name;

    public function __construct() {
        $this->employer = new EMPLOYER();
        $this->main_url = $this->employer->mainUrl();
        $this->smtp_email = $this->employer->smtpEmail();
        $this->smtp_password = $this->employer->smtpPassword();
        $this->system_name = $this->employer->systemName();


    }

    // ADD EMPLOYER
    public function registerEmployer($first_name, $middle_name, $last_name, $phone_number, $position, $email, $hash_password, $tokencode, $user_type)
    {
        $stmt = $this->employer->runQuery("SELECT * FROM users WHERE email=:email");
        $stmt->execute(array(":email"=>$email));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            $_SESSION['status_title'] = "Oops!";
            $_SESSION['status'] = "Email already taken. Please try another one.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 100000;
            header('Location: ../employer');
            exit();
        } else {
            if ($this->employer->register($first_name, $middle_name, $last_name, $phone_number, $position, $email, $hash_password, $tokencode, $user_type)) {
                $id = $this->employer->lasdID();
                $key = base64_encode($id);
                $id = $key;

                $message = "
                    Hello Employer: $email,
                    <br /><br />
                    Welcome to $this->system_name
                    <br /><br />
                    Email:<br />$email <br />
                    Password:<br />$hash_password
                    <br /><br />
                    <a href='$this->main_url/private/employer/verify-account?id=$id&code=$tokencode'>Click HERE to Verify your Account!</a>
                    <br /><br />
                    Thanks,
                ";

                $subject = "Verify Email";

                $this->employer->send_mail($email, $message, $subject, $this->smtp_email, $this->smtp_password, $this->system_name);
                $_SESSION['status_title'] = "Success!";
                $_SESSION['status'] = "Please check the Email to verify the account.";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_timer'] = 40000;
                header('Location: ../employer');
                exit();
            } else {
                // Error reporting
                $error = $employer->getLastError();
                echo "Error: ".$error;

                $_SESSION['status_title'] = "Sorry !";
                $_SESSION['status'] = "Something went wrong, please try again!";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_timer'] = 10000000;
                header('Location: ../employer');
                exit();
            }
        }
    }

    //edit employer
    public function editEmployer($user_id, $first_name, $middle_name, $last_name, $phone_number, $position) {

        $stmt = $this->employer->runQuery('SELECT first_name, middle_name, last_name, phone_number, position FROM users WHERE id=:id');
        $stmt->execute(array(':id' => $user_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result['first_name'] === $first_name &&
            $result['middle_name'] === $middle_name &&
            $result['last_name'] === $last_name &&
            $result['phone_number'] === $phone_number &&
            $result['position'] === $position) {
    
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'No changes were made.';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 40000;
    
            header('Location: ../employer');
            exit();
        }
    
        $stmt = $this->employer->runQuery('UPDATE users SET first_name=:first_name, middle_name=:middle_name, last_name=:last_name, phone_number=:phone_number, position=:position WHERE id=:id');
        $exec = $stmt->execute(array(
            ':id'             => $user_id,
            ':first_name'     => $first_name,
            ':middle_name'    => $middle_name,
            ':last_name'      => $last_name,
            ':phone_number'   => $phone_number,
            ':position'       => $position,
        ));
    
        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Employer information successfully updated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }
    
        header('Location: ../employer');
        exit();
    }    

    //disabled employer
    public function disabledEmployer($employer_id){

        $disabled = "disabled";
        $stmt = $this->employer->runQuery('UPDATE users SET account_status=:account_status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"               => $employer_id,
            ":account_status"   => $disabled,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Employer successfully disabled!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../employer');
        exit();

    }

    public function activateEmployer($employer_id){
    
        $active = "active";
        $stmt = $this->employer->runQuery('UPDATE users SET account_status=:account_status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"                => $employer_id,
            ":account_status"   => $active,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Employer successfully activated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../employer');
        exit();

    }

}

//add employer
if(isset($_POST['btn-add-employer'])) {

    $first_name         = trim($_POST['first_name']);
    $middle_name        = trim($_POST['middle_name']);
    $last_name          = trim($_POST['last_name']);
    $phone_number       = trim($_POST['phone_number']);
    $position           = trim($_POST['position']);
    $email              = trim($_POST['email']);
    $user_type          = 2;
    $tokencode          = md5(uniqid(rand()));


    // Generate Password
    $varchar            = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $shuffle            = str_shuffle($varchar);
    $hash_password      = substr($shuffle,0,8);

    $employerRegistration = new employerRegistration();
    $employerRegistration->registerEmployer($first_name, $middle_name, $last_name, $phone_number, $position, $email, $hash_password, $tokencode, $user_type);
}

//edit employer
if(isset($_POST['btn-edit-employer'])) {
    $user_id            = $_GET["id"];
    $first_name         = trim($_POST['first_name']);
    $middle_name        = trim($_POST['middle_name']);
    $last_name          = trim($_POST['last_name']);
    $phone_number       = trim($_POST['phone_number']);
    $position           = trim($_POST['position']);

    $employerUpdate = new employerRegistration();
    $employerUpdate->editEmployer($user_id, $first_name, $middle_name, $last_name, $phone_number, $position);
}

//disabled employer
if (isset($_GET['disabled_employer'])) {
    $employer_id = $_GET["id"];

    $disabled_employer = new employerRegistration();
    $disabled_employer->disabledEmployer($employer_id);
}

//activate employer
if (isset($_GET['activate_employer'])) {
    $employer_id = $_GET["id"];

    $activate_employer = new employerRegistration();
    $activate_employer->activateEmployer($employer_id);
}



//ALUMNI CONTROLLER

class alumniRegistration {
    private $alumni;
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

    //disabled alumni
    public function disabledAlumni($alumni_id){

        $disabled = "disabled";
        $stmt = $this->alumni->runQuery('UPDATE users SET account_status=:account_status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"               => $alumni_id,
            ":account_status"   => $disabled,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Employer successfully disabled!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {

            // Error reporting
            $error = $alumni->getLastError();
            echo "Error: ".$error;
           
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../alumni');
        exit();

    }

    public function activateAlumni($alumni_id){
    
        $active = "active";
        $stmt = $this->alumni->runQuery('UPDATE users SET account_status=:account_status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"                => $alumni_id,
            ":account_status"   => $active,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Employer successfully activated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            // Error reporting
            $error = $alumni->getLastError();
            echo "Error: ".$error;

            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../alumni');
        exit();

    }
}

//disabled alumni
if (isset($_GET['disabled_alumni'])) {
    $alumni_id = $_GET["id"];

    $disabled_alumni = new alumniRegistration();
    $disabled_alumni->disabledAlumni($alumni_id);
}

//activate alumni
if (isset($_GET['activate_alumni'])) {
    $alumni_id = $_GET["id"];

    $activate_alumni = new alumniRegistration();
    $activate_alumni->activateAlumni($alumni_id);
}
?>
