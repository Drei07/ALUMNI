<?php
include_once '../../../configuration/settings-configuration.php';
require_once 'user-class.php';
//URL
$user = new ALUMNI();
$main_url = $user->mainUrl();
$smtp_email = $user->smtpEmail();
$smtp_password = $user->smtpPassword();
$system_name = $user->systemName();

if(isset($_POST['btn-forgot-password']))
{
 $email = $_POST['email'];
 
 $stmt = $user->runQuery("SELECT id, tokencode FROM users WHERE email=:email AND user_type = :user_type");
 $stmt->execute(array(":email"=>$email, ":user_type" => 3));
 $row = $stmt->fetch(PDO::FETCH_ASSOC); 
 if($stmt->rowCount() == 1)
 {
  $id = base64_encode($row['id']);
  $code = ($row['tokencode']);
  
  $message= "
       Hello , $email
       <br /><br />
       We got requested to reset your password, if you do this then just click the following link to reset your password, if not just ignore this email,
       <br /><br />
       Click Following Link To Reset Your Password 
       <br /><br />
       <a href='$main_url/dashboard/user/authentication/user-reset-password?id=$id&code=$code'>click here to reset your password</a>
       <br /><br />
       thank you :)
       ";
  $subject = "Password Reset";
  
  $user->send_mail($email,$message,$subject,$smtp_email,$smtp_password,$system_name);
  
  $_SESSION['status_title'] = "Success !";
  $_SESSION['status'] = "We've sent the password reset link to $email, kindly check your spam folder and 'Report not spam' to click the link.";
  $_SESSION['status_code'] = "success";
  header('Location: ../../../');
 }
 else
 {
    $_SESSION['status_title'] = "Oops !";
    $_SESSION['status'] = "Entered email not found";
    $_SESSION['status_code'] = "error";
    header('Location: ../../../forgot-password');
 }
}
?>