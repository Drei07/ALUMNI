<?php
include_once __DIR__. '/src/api/api.php';
include_once 'dashboard/user/authentication/user-signin.php';
include_once 'configuration/settings-configuration.php';

$config = new SystemConfig();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="src/img/<?php echo $config->getSystemLogo() ?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $config->getSKey() ?>"></script>
    <link rel="stylesheet" href="src/css/signin.css?v=<?php echo time(); ?>">
    <title>Track Me | Sign In</title>
</head>
<body >
	<div class="container" id="container">
		<div class="form-container sign-up-container">
			<form action="dashboard/user/controller/user-controller.php" method="POST">
				<h1>Create Account</h1>
				<input type="email" name="email" placeholder="Email" required autofocus />
				<input type="password" name="password" placeholder="Password" required autofocus/>
				<input type="password" name="confirm_password" placeholder="Confirm Password" required autofocus/>
				<p class="terms">By clicking sign up you are agreeing to the terms and conditions</p>
				<button type="submit"  id="submit" name="btn-signup">Sign Up</button>
			</form>
		</div>
		<div class="form-container sign-in-container">
			<form action="dashboard/user/authentication/user-signin.php" method="POST">
				<h1>Sign in</h1>
				<input type="hidden" id="g-token" name="g-token">
				<input type="email" name="email" placeholder="Email" required autofocus>
				<input type="password" name="password" placeholder="Password" required autofocus >
				<a href="forgot-password">Forgot your password?</a>
				<button type="submit"  id="submit" name="btn-signin">Sign In</button>
			</form>
		</div>
		<div class="overlay-container">
			<div class="overlay">
				<div class="overlay-panel overlay-left">
					<h1>Welcome Back!</h1>
					<p>To keep connected with us please login with your personal info</p>
					<button class="ghost" id="signIn">Sign In</button>
				</div>
				<div class="overlay-panel overlay-right">
					<img src="src/img/sign_in_logo.png" width="60%" alt="logo">
					<p>Stay on track with Track Me - your ultimate employment and document solution!</p>
					<button class="ghost" id="signUp">Sign Up</button>
				</div>
			</div>
		</div>
	</div>
	<footer>&copy; <?php echo $config->getSystemCopyright() ?></footer>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="src/node_modules/sweetalert/dist/sweetalert.min.js"></script>
	<script src="src/node_modules/jquery/dist/jquery.min.js"></script>
	<script src="src/js/signin.js"></script>
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