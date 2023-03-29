<?php
require_once 'employer-class.php';
$user = new EMPLOYER();
$config = new SystemConfig();
$main_url = new MainUrl();
if(empty($_GET['id']) && empty($_GET['code']))
{
 $user->redirect('../../../');
}

if(isset($_GET['id']) && isset($_GET['code']))
{
 $id = base64_decode($_GET['id']);
 $code = $_GET['code'];
 
 $stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid AND tokencode=:token");
 $stmt->execute(array(":uid"=>$id,":token"=>$code));
 $rows = $stmt->fetch(PDO::FETCH_ASSOC);
 
 if($stmt->rowCount() == 1)
 {
  if(isset($_POST['btn-update-password']))
  {
   	$new_password 		= trim($_POST['password']);
	$confirm_password   = trim($_POST['confirm_password']);
    $code 				= md5(uniqid(rand()));
    $new_hash_password 	= md5($new_password);

	if($new_password != $confirm_password) {
        $_SESSION['status_title'] = "Oops!";
        $_SESSION['status'] = "Passwords do not match. Please try again.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_timer'] = 100000;
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; // Get the current URL
		header("Location: $url"); // Add this line to redirect back to the same page
        exit();
    }

    $stmt = $user->runQuery("UPDATE users SET password=:password, tokencode=:token WHERE id=:uid");
    $stmt->execute(array(":token"=>$code,":password"=>$new_hash_password,":uid"=>$rows['id']));
    
    $_SESSION['status_title'] = "Success !";
    $_SESSION['status'] = "Password is updated. Redirecting to Sign in.";
    $_SESSION['status_code'] = "success";
    header("refresh:4;../../../private/employer/");
   
  } 
 }
 else
 {
    $_SESSION['status_title'] = "Oops !";
    $_SESSION['status'] = "Your token is expired.";
    $_SESSION['status_code'] = "error";
 }
 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="../../../src/img/<?php echo $config->getSystemLogo() ?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $config->getSKey() ?>"></script>
    <link rel="stylesheet" href="../../../src/css/signin.css?v=<?php echo time(); ?>">
    <title>Track Me | Reset Password?</title>
</head>
<body>
	<div class="container" id="container">
			<div class="form-container sign-in-container">
				<form action="" method="POST">
					<h1>Reset Password?</h1>
					<input type="password" name="password" placeholder="Password" required autofocus/>
					<input type="password" name="confirm_password" placeholder="Confirm Password" required autofocus/>
					<a href="../../../">Back to signin</a>
					<button type="submit"  id="submit" name="btn-update-password">Reset</button>
				</form>
			</div>
			<div class="overlay-container">
				<div class="overlay">
					<div class="overlay-panel overlay-right">
						<img src="../../../src/img/sign_in_logo.png" width="60%" alt="logo">
						<p>Stay on track with Track Me - your ultimate employment and document solution!</p>
					</div>
				</div>
			</div>
		</div>
		<footer>&copy; <?php echo $config->getSystemCopyright() ?></footer>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="../../../src/node_modules/sweetalert/dist/sweetalert.min.js"></script>
	<script src="../../../src/node_modules/jquery/dist/jquery.min.js"></script>
	<script src="../../../src/js/signin.js"></script>
	<script>

		// CAPTCHA
			grecaptcha.ready(function() {
			grecaptcha.execute('<?php echo $config->getSKey() ?>', {action: 'submit'}).then(function(token) {
				document.getElementById("g-token").value = token;
			});
			});

	</script>

	<!-- SWEET ALERT -->
	<?php

		if(isset($_SESSION['status']) && $_SESSION['status'] !='')
		{
			?>
			<script>
				swal({
				title: "<?php echo $_SESSION['status_title']; ?>",
				text: "<?php echo $_SESSION['status']; ?>",
				icon: "<?php echo $_SESSION['status_code']; ?>",
				button: false,
				timer: <?php echo $_SESSION['status_timer']; ?>,
				});
			</script>
			<?php
			unset($_SESSION['status']);
		}
	?>
</body>
</html>