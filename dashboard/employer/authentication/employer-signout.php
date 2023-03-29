<?php
require_once 'employer-class.php';
$user = new EMPLOYER();

if(!$user->isUserLoggedIn())
{
 $user->redirect('../../../private/employer/');
}

if($user->isUserLoggedIn()!="")
{
 $user->logout();
 $user->redirect('../../../private/employer/');
}
?>