<?php
require_once 'employer-class.php';
$user = new EMPLOYER();

if(empty($_GET['id']) && empty($_GET['code']))
{
 $user->redirect('');
}

if(isset($_GET['id']) && isset($_GET['code']))
{
 $id = base64_decode($_GET['id']);
 $code = $_GET['code'];
 
 $status_Y = "Y";
 $status_N = "N";
 
 $stmt = $user->runQuery("SELECT id,status FROM users WHERE id=:id AND tokencode=:code LIMIT 1");
 $stmt->execute(array(":id"=>$id,":code"=>$code));
 $row=$stmt->fetch(PDO::FETCH_ASSOC);
 if($stmt->rowCount() > 0)
 {
  if($row['status']==$status_N)
  {
   $stmt = $user->runQuery("UPDATE users SET status=:status WHERE id=:id");
   $stmt->bindparam(":status",$status_Y);
   $stmt->bindparam(":id",$id);
   $stmt->execute(); 
   
   $msg = "
        <div class='alert alert-error' style='font-size:20px; font-weight:300; color:#000;'>
        <strong>Welcome !</strong> Your account is now activated.
        </div>
        <a href='../../private/employer' style='text-decoration:none; display: flex; justify-content: right; font-size: 1.2rem; color:#E8772E; font-weight:600;''>Signin here  <img src='../../src/img/caret-right-fill.svg' style='margin-top: .5rem; margin-left: 5px;' width='15' height='15' alt='Arrow right'></a>";
  }
  else
  {
   $msg = "
        <div class='alert alert-error' style='font-size:20px; font-weight:300; color:#000;'>
        <strong>Hey !</strong>  Your account is all ready activated.
        </div>
        <a href='../../private/employer' style='text-decoration:none; display: flex; justify-content: right; font-size: 1.2rem; color:#E8772E; font-weight:600;'>Signin here  <img src='../../src/img/caret-right-fill.svg' style='margin-top: .5rem; margin-left: 5px;' width='15' height='15' alt='Arrow right'></a>";
  }
 }
 else
 {
  $msg = "
        <div class='alert alert-error' style='font-size:20px; font-weight:300; color:#000;'>
        <strong>Warning !</strong> No account Found.
        </div>";
 } 
}
