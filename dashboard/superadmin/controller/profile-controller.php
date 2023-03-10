<?php
session_start();
include_once __DIR__.'/../../../database/dbconfig.php';

class ProfileSettings {
    private $conn;

    public function __construct() {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }
    // update profile
    public function updateProfile($user_id, $first_name, $middle_name, $last_name, $email) 
    {
        $stmt = $this->runQuery('SELECT * FROM users WHERE id=:id');
        $stmt->execute(array(":id"=>$user_id));
        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        if($row['first_name'] == $first_name && $row['middle_name'] == $middle_name && $row['last_name'] == $last_name && $row['email'] == $email){
            $_SESSION['status_title'] = 'Warning!';
            $_SESSION['status'] = 'No changes have been made to your profile.';
            $_SESSION['status_code'] = 'warning';
            $_SESSION['status_timer'] = 40000;

            header('Location: ../profile');
            exit;
        }

        if (empty($middle_name)) 
        {
            $middle_name = null;
        }
        $stmt = $this->runQuery('UPDATE users SET first_name=:first_name, middle_name=:middle_name, last_name=:last_name, email=:email WHERE id=:id');
        $exec = $stmt->execute(array(
            "id"            => $user_id,
            ":first_name"   => $first_name,
            ":middle_name"  => $middle_name,
            ":last_name"    => $last_name,
            ":email"        => $email
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Profile successfully updated';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../profile');
    }
    //update user avatar
    public function updateAvatar($user_id, $avatar)
    {
        $folder = "../../../src/img/" . basename($avatar);
        chmod($folder, 0755);
        $stmt = $this->runQuery('UPDATE users SET profile=:profile WHERE id=:id');
        $exec = $stmt->execute(array(
            "id"        => $user_id,
            ":profile"  => $avatar,
        ));

        if ($exec && move_uploaded_file($_FILES['system_logo']['tmp_name'], $folder)) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Avatar successfully updated';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../profile');

    }
    //update user password
    public function updatePassword($user_id, $old_password, $new_password, $confirm_password)
    {
        $new = md5($new_password);
        $old = md5($old_password);

        // Check if old password is correct
        $stmt = $this->runQuery("SELECT * FROM users WHERE id=:id");
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_password = $user_data["password"];

        if($current_password == $old){

            // check if new password match with confirm pasword
            if($new_password == $confirm_password){
                $stmt = $this->runQuery('UPDATE users SET password=:password WHERE id=:id');
                $exec = $stmt->execute(array(
                    "id"         => $user_id,
                    ":password"  => $new,
                ));

                if ($exec) {
                    $_SESSION['status_title'] = 'Success!';
                    $_SESSION['status'] = 'Password successfully changed';
                    $_SESSION['status_code'] = 'success';
                    $_SESSION['status_timer'] = 40000;
                } else {
                    $_SESSION['status_title'] = 'Oops!';
                    $_SESSION['status'] = 'Something went wrong, please try again!';
                    $_SESSION['status_code'] = 'error';
                    $_SESSION['status_timer'] = 100000;
                }
                header('Location: ../profile');
            }else{
                $_SESSION['status_title'] = 'Sorry!';
                $_SESSION['status'] = 'New password and Confirm password did not match, Please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 1000000;
                header('Location: ../profile');
            }
        }else{
            $_SESSION['status_title'] = 'Sorry!';
            $_SESSION['status'] = 'Incorrect old password, Please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 1000000;
            header('Location: ../profile');
        }
    }

    //update avatar to default
    public function updateAvatarToDefault($user_id){
        $stmt = $this->runQuery('UPDATE users SET profile=:profile WHERE id=:id');
        $exec = $stmt->execute(array(
            ":profile"  => "profile.png",
            "id"        => $user_id
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Avatar successfully updated to default';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../profile');
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

// Check if the form has been submitted for updating the profile
if (isset($_POST['btn-update-profile'])) {
    $user_id        = $_GET["id"];
    $first_name     = trim($_POST['first_name']);
    $middle_name    = trim($_POST['middle_name']);
    $last_name      = trim($_POST['last_name']);
    $email          = trim($_POST['email']);

    $ProfileSettings = new ProfileSettings();
    $ProfileSettings->updateProfile($user_id, $first_name, $middle_name, $last_name, $email);
}

if (isset($_POST['btn-update-avatar'])) {
    $user_id    = $_GET["id"];
    $avatar     = $_FILES['avatar']['name'];

    $ProfileSettings = new ProfileSettings();
    $ProfileSettings->updateAvatar($user_id, $avatar);
}

if (isset($_POST['btn-update-password'])) {
    $user_id            = $_GET["id"];
    $old_password       = trim($_POST['old_password']);
    $new_password       = trim($_POST['new_password']);
    $confirm_password   = trim($_POST['confirm_password']);

    $ProfileSettings = new ProfileSettings();
    $ProfileSettings->updatePassword($user_id, $old_password, $new_password, $confirm_password);
}

if (isset($_GET['delete_avatar'])) {
    $user_id = $_GET["id"];

    $ProfileSettings = new ProfileSettings();
    $ProfileSettings->updateAvatarToDefault($user_id);
}

?>
