<?php
require_once 'superadmin-class.php';
$superadmin = new SUPERADMIN();

if(!$superadmin->isUserLoggedIn())
{
 $superadmin->redirect('../../../private/superadmin/');
}

if($superadmin->isUserLoggedIn()!="")
{
 $superadmin->logout();
 $superadmin->redirect('../../../private/superadmin/');
}
?>